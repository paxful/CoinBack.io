<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	public function setUp()
	{
		parent::setUp();

		$location = Location::find(5128581);

		Artisan::call('migrate');
		Mail::pretend(true);
		Schema::create('locations', function($table)
		{
			$table->integer('id');
			$table->string('continent_code');
			$table->string('continent_name');
			$table->string('country_iso_code');
			$table->string('country_name');
			$table->string('subdivision_iso_code');
			$table->string('subdivision_name');
			$table->string('city_name');
			$table->string('metro_code');
			$table->string('time_zone');
			$table->integer('country_id');
			$table->string('country_iso_code3');
			$table->decimal('lat')->nullable();
			$table->decimal('lon')->nullable();
		});
		DB::table('locations')->insert(
			array(
				'id' => 5128581,
				'continent_code' => 'NA',
				'continent_name' => 'North America',
				'country_iso_code' => 'US',
				'country_name' => 'United States',
				'subdivision_iso_code' => 'NY',
				'subdivision_name' => 'New York',
				'city_name' => 'New York',
				'metro_code' => '501',
				'time_zone' => 'America/New_York',
				'country_id' => '227',
				'country_iso_code3' => 'USA',
				'lat' => 40.7127837,
				'lon' => -74.0059413
			)
		);
	}

	public function tearDown()
	{
		parent::tearDown();
		Mockery::close();
	}

	/**
	 * Creates the application.
	 *
	 * @return \Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$unitTesting = true;

		$testEnvironment = 'testing';

		return require __DIR__.'/../../bootstrap/start.php';
	}

}
