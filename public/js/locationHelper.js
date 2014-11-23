var countryDropdown = $("#country");
var stateDropdown   = $("#state");
var cityDropdown    = $("#city");
countryDropdown.select2({
    placeholder: "Select your country",
    allowClear: true
});
(function ($)
{
    $.post(basePath+"/home/location-by-ip", function(response) {
        var location = $.parseJSON(response.location);
        $("#phone").intlTelInput({
            defaultCountry: location.country_iso_code.toLowerCase(),
            responsiveDropdown: true
        });

        $("#location_id").val(location.id);
        // load the state for that location
        populateStates(location.country_id, location);
        // load cities for that location
        populateCities(location.subdivision_iso_code, location.country_id, location, "Select...");
    }, "json");

})(jQuery);

countryDropdown.on("change", function (e) {
    populateStates(e.val, null);
    stateDropdown.select2('val', null);
    cityDropdown.select2({
        data: {},
        placeholder: "Select a state first"
    });
    cityDropdown.select2("enable", false);
});

stateDropdown.on("change", function (e) {
    populateCities(e.val, $("#country").val(), null, "Select...");
    cityDropdown.select2("enable", true);
    cityDropdown.select2('val', null);
});

cityDropdown.on("change", function (e) {
    $("#location_id").val(e.val);
});

function populateStates(country_id, location)
{
    return $.post(basePath + '/home/states-list', {country_id: country_id}).done(function (data) {
        var states = [];
        $.each(data, function(text, id) {
            if (text === "") {
                return; // same as 'continue'
            }
            states.push({id:id, text:text});
        });

        // init select2 states and set current location state as default
        stateDropdown.select2({
            placeholder: "Select...",
            data: states
        });
        if (location !== null) {
            stateDropdown.select2("val", location.subdivision_iso_code);
        }
    }, 'json'); // loaded states
}

function populateCities(state_iso_code, country_id, location, placeholder)
{
    $.post(basePath + '/home/cities-list', {state: state_iso_code, country_id: country_id}).done(function (data) {
        var cities = [];
        $.each(data, function(id, object) {
            if (object.city_name === "") {
                return; // same as 'continue'
            }
            cities.push({id:object.id, text:object.city_name});
        });

        // init select2 for cities list and set current location id as default city
        cityDropdown.select2({
            placeholder: placeholder,
            data: cities
        });
        if (location !== null) {
            cityDropdown.select2("val", location.id);
        }
    }, 'json');
}