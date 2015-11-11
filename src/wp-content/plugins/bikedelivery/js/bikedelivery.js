/**
 * Created by Beto on 11/8/2015.
 */
$ = jQuery;
$(document).on('ready', function(){
    loadDatePicker();
    loadTimePicker();
    getCurrentLocation();

    function loadDatePicker()
    {
        $('#order_day').datepicker({
            minDate :0
        });
    }

    function loadTimePicker()
    {
        $('#order_time').timepicker();
    }





    function placeMarker(location)
    {
        // first remove all markers if there are any
        //this.deleteOverlays();

        var marker = new google.maps.Marker({
            position: location,
            map: this.map
        });

        // add marker in markers array
        //this.markersArray.push(marker);
    }

    /*function deleteOverlays()
    {
        if (this.markersArray) {
            for (i in this.markersArray) {
                this.markersArray[i].setMap(null);
            }
            this.markersArray.length = 0;
        }
    }*/


});

var map = undefined;
var lat = 4.57262365310281;
var lon = -74.0970325469971;

function loadMap()
{
    var latlng = new google.maps.LatLng(lat, lon);
    var myOptions = {
        zoom: 15,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    if(!map)
        map = new google.maps.Map($("#map_location")[0], myOptions);
    else
        map.setCenter(new google.maps.LatLng(lat, lon));
}


var locationAutoloaded = false;
function getCurrentLocation() {
    if (navigator.geolocation) {
        var that = this;
        navigator.geolocation.getCurrentPosition(function (position) {
            lat = position.coords.latitude;
            lon = position.coords.longitude;
            locationAutoloaded = true;
            loadMap();
        });
        /**Si el usuario no selecciona la autoubicación se toma una por defecto**/
        setTimeout(function(){
            if(!locationAutoloaded)
                loadMap();
        }, 5000);
    }
    else {
        //Si no tiene geolocalización lo ubica en la posición por defecto
        loadMap();
    }
}


function updateLocation()
{
    /*var latlng = new google.maps.LatLng(lat, lon);
     var myOptions = {
     zoom: 15,
     center: latlng,
     mapTypeId: google.maps.MapTypeId.ROADMAP
     };
     this.map = new google.maps.Map(this.$el[0], myOptions);*/

    var that = this;
    // add a click event handler to the map object
    google.maps.event.addListener(map, "click", function (event) {
        // place a marker
        //that.placeMarker(event.latLng);

        // display the lat/lng in your form's lat/lng fields
        lat = event.latLng.lat();
        lon = event.latLng.lng();
        //that.trigger('set-position', { lat: that.lat, lon: that.lon });

        //that.setAddress(event.latLng);
    });
}

