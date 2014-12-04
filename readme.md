## CoinBack.IO

**The biggest problem in the Bitcoin ecosystem is that not enough people have bitcoins.**

We need an easy and a simple way and preferably from a friendly trusted face for people to get bitcoins without wiring money to a remote nation, entering online credit card details and waiting a few days. Our experience has shown us that people are willing to pay a big premium to buy bitcoin in person, especially the first time. Merchants are always hungry for new profitable revenue streams as well. Coinback.io is a win for all parties and has evolved from nine months of working with retail merchants on Bitcoin adoption.

With coinback.io you can easily go to your favourite merchant and buy a small amount of bitcoins instantly. Merchants can acquire bitcoins by selling his products/services for bitcoin. Merchants bitcoins are not tied to the current bitcoin exchange rate, but have the average of the price he has acquired them at. So when a merchant sells his bitcoins he sees what is his average price and what is the current exchange price. Moreover he can decide premium on bitcoins he is selling. A merchant can send Bitcoin to a customer who has no wallet and thanks to the blockchain.info api we create him a new wallet on the fly.

**The merchant has made profit, a customer has just acquired bitcoin from a trusted local source, the ecosystem has grown and a new bitcoiner.**


## Installation
### Quick notes about bitcoin wallet/APIs used
+ For merchant wallet creation is used [blockchain.info Create Wallets API](https://blockchain.info/api/create_wallet) and sending out payments
 [blockchain.info Wallet API](https://blockchain.info/api/blockchain_wallet_api)
+ For notification about receiving payments is used [Chain.com Notifications API](https://chain.com/docs#notifications-overview)

Thus you have to make sure that you are registered at both of these API providers and have your API keys ready (more about it below).  
Blockchain.info requires manual approval from their side
### Tech stack and required libraries/dependencies
+ PHP version 5.4+
+ Laravel framework (comes with coinback.io project)
+ [Composer](https://getcomposer.org/) to pull all dependencies for Laravel framework
+ Apache/Nginx server - must do URL rewriting by Laravel convention - [http://laravel.com/docs/4.2/installation](http://laravel.com/docs/4.2/installation#pretty-urls)
+ PostgreSQL (or any relational database)
+ SendGrid or other SMTP mail provider to send emails
+ Plivo API for sending out SMS
+ *Optional* IP4R module for Postgres for faster IP to location lookup

**Before continuing, get yourself familiar with Laravel**

### Installations
1. Clone this project
2. By Laravel convention in you have to create `.env.*php` files in your local/staging/production environments. More details about it [here](http://laravel.com/docs/4.2/configuration#protecting-sensitive-configuration)
3. Create in root folder of project `.env.local.php` and `.env.testing.php` (for unit testing) in your local machine and `.env.php` in your production website.  
We will fill their contents in a moment
4. In `bootstrap/start.php` around line 27 inside `$app->detectEnvironment` fill your machines correct host name for local and production, also for staging if you plan to have it  
Quick ghetto way to know your host name is with following PHP code `echo gethostname();`
5. Copy the below content in all your `.env.*.php` files and replace values to yours

```php
<?php
return array(
'APP_DEBUG'         => true, // always FALSE for production. It shows the debugbar in browser and full stacktrace on screen if errors happen

'ENCRYPTION_KEY' => 'xxxxxx5@xxxx%kxxhO^-xxxE1', // random 32 characters encryption key used to encrypt passwords and other sensitive data

'DATABASE_DRIVER'   => 'pgsql', // for postgres its 'pgsql' - check in Laravel documentation
'DATABASE_NAME'     => 'coinback',
'DATABASE_USER'     => 'user',
'DATABASE_PASSWORD' => 'pass',

/* keeping locations and blocks table in separate database. you can have it same as first database, just fill same values as your first database */
'DATABASE_2'        => 'pgsql2',
'DATABASE_2_NAME'   => 'easybitz',
'DATABASE_2_USER'   => 'user',
'DATABASE_2_PASSWORD'=> 'pass',

/* Only used to validate bitcoin address correctness. values either mainnet or testnet.
 * Here can be only mainnet because blockchain.info doesnt have testnet */
'BITCOIN_VERSION' => 'mainnet',

/*  your blockchain.info API key */
'BCINFO_KEY'        => 'xxxxxx-aaaaa-ffff-oooo-xxxxxxx',

/* your chain.com API credentials */
'CHAIN_COM_ID'      => 'xxxxxxxxxxx',
'CHAIN_COM_SECRET'  => 'xxxxxxxxxxx',
'CHAIN_COM_API_URL' => 'https://api.chain.com/v2',
/** callback URL to where chain.com shoots of incoming transaction. Here it's in @ControlController#postReceive*/
'CHAIN_COM_NOTIFICATION_URL' => 'http://example.com/process/receive',

/* here using SendGrid as SMTP service to send emails. You can replace SendGrid with any other SMTP provider
* Values from here are set in app/config/mail.php */
'MAIL_ADMIN'        => 'your_email@example.com', // admin email to where error logs are sent
'MAIL_FROM'         => 'your_email@example.com',
'MAIL_FROM_NAME'    => 'Coinback',
'MAIL_HOST'         => 'smtp.sendgrid.net',
'MAIL_PORT'         => 587,
'MAIL_USER'         => 'your_sendgrid_user_here', // sendgrid user
'MAIL_PASS'         => 'your_sendgrid_password', // sendgrid password

/* Where to send error messages. start with country code (no + sign in front), phones are comma separated */
'ADMIN_PHONES' => '',

/* Plivo credentials */
'PLIVO_ID'          => 'xxxxxxxx',
'PLIVO_TOKEN'       => 'xxxxxxxxxxxxxxxxx',
'PLIVO_NUMBER'      => 'xxxxxx', // your registered plivo number
);
```

#### Next steps are important that are actually seeding db and importing dependencies  
Now after you have filled your environment files, it's time to fetch all Laravel dependencies

6. In project root folder run `composer update` or `sudo composer update` which will fetch all Laravel dependencies
7. Now again in root folder run `php artisan migrate` which creates required tables for the project. This is schema builder script which files are located in `app/database/migrations`
8. Import downloaded `locations` and `block` tables from MaxMind either to separate database or same database. In `.env.*.php` you specified `DATABASE_2` value in which database is located locations data
9. Make sure to rename primary key column in `locations` table from `location_id` to `id` because by Laravel Eloquent convention the primary key for every table is just `id`.  
Blocks table must stay as it is, no need to rename column because we are not using Eloquent ORM for that.
10. Now is time for IP4R module in PostgreSQL.  
Run this SQL query to create a function

```
CREATE OR REPLACE FUNCTION inet_to_bigint(inet)
  RETURNS bigint AS
$BODY$
    SELECT $1 - inet '0.0.0.0'
$BODY$
  LANGUAGE sql VOLATILE
  COST 100;
ALTER FUNCTION inet_to_bigint(inet)
  OWNER TO postgres;
```

**If you don't have it or using something else than Postgres** then in `app/models/Location.php` in method `public static function getUserLocationByIp($ip)` remove the `else` part in code and `App::environment` checking.  
So it should be

```php
public static function getUserLocationByIp($ip)
{
    if (!starts_with($ip, '::ffff:')) {
        $ip = '::ffff:'.$ip;
    }

    // no ip4r module
    return Location::with('country')
                   ->whereRaw('locations.id = ( select geoname_id from blocks where net_ip >>= ? )',
                       array($ip))->first();
}
```

*With above method without ip4r querying for user location could take up to 5 seconds!*

**If you have IP4R module for postgres** leave `Location.php` as it is.

Now the preparation is done and website should be up and running and everything working!

*Bitcoin donations appreciated* **15i5suRE85XadSoQV2HSFo2jy2KfB64oUa**