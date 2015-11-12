/**
 * Created by Beto on 11/12/2015.
 */
(function($){


	$.fn.bdmap = function(options){
		initialize(this, options);
	};


    var props = {
        marks : [],
        directionDisplay : undefined,
        directionService : undefined,
        map :undefined
    };

	function initialize(obj, options)
	{
		var opts = $.extend({}, $.fn.bdmap.defaults, options);


		//Busca todos los campos de tipo direcciÃ³n y les empieza a agregar los enventos
		var addressFields = $(opts.elAddresses);
		addressFields.each(function(index, element){

            var $el = $(this);

            //Busca el campo dirección
            var txtAddress = $el.first('input[type="text"]');
            addEventsAddress(txtAddress, index);



		});

        props.directionService = new google.maps.DirectionsService();
        props.directionDisplay = new google.maps.DirectionsRenderer();


        loadCurrentPosition(opts);

	}

    /***
     * Agrega lso envetos necesarios para las direcciones
     * @param obj
     * @param index
     */
    function addEventsAddress(obj, index)
    {
        obj.on('change', function(target){ findAddressByText(target, index); } );
    }

    function findAddressByText(obj, index)
    {
        var addressText = $(obj.target).val();

        if(addressText == '')
            return alert('No hay seleccionado ningun texto');

        $.ajax({
            method:'GET',
            url : '/wp-admin/admin-ajax.php?action=getLocation&address'+addressText,
            data : { address : addressText }
        })
        .success(function(resp){
            markMap(resp.latitude, resp.longitude, obj, index);
        })
        .error(function(resp){

        });
    }

    /****
     * Marca la posición en el mapa enviada, adicionalmente valida que parte del trayecto es para pintar o no el recorrido
     * @param lat latitud que se desea ubicar
     * @param lon longitud de la dirección
     * @param obj caja de texto que dispara el evento
     * @param index numero de la caja de texto
     */
    function markMap(lat, lon, obj, index) {

        var googlePosition = new google.maps.LatLng(lat, lon);


        //Si la posicion en el mapa ya existe la elimina
        /*if(props.marks.length >= index+1)
        {
            props.marks[index].setMap(null);
        }*/

        //Si la marca no existe la agrega
        if (props.marks.length <= index) {
            var marker = new google.maps.Marker({
                position: googlePosition,
                map: props.map
            });
            props.marks.push(marker);
        }
        else {
            //Sino acutaliza el valor de la lista
            var marker = props.marks[index];
            //marker.setMap(null);
            marker.setPosition(googlePosition);
        }

        //Recorre todos los puntos y genera la trayectoria
        if(props.marks.length > 1)
        {
            /*props.marks.forEach(function(point, iPoint){

            });*/

            var directionsRequest = {
                origin : props.marks[0].getPosition(),
                destination : props.marks[1].getPosition(),
                travelMode: google.maps.TravelMode.DRIVING
            };
            props.directionService.route(directionsRequest, function(response, status){
                if (status == google.maps.DirectionsStatus.OK) {
                    props.directionDisplay.setMap(props.map);
                    props.directionDisplay.setDirections(response);
                }
            });

        }


        props.map.setCenter(googlePosition);
    }

    /****
     * Carga el mapa y lo muestra centrado en una posición especifica
     * @param latitude
     * @param longitude
     */
    function loadMapOnPosition(latitude, longitude)
    {
        var latlng = new google.maps.LatLng(latitude, longitude);
        var myOptions = {
            zoom: 15,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        if(!props.map)
            props.map = new google.maps.Map($("#map_location")[0], myOptions);
        else
            props.map.setCenter(new google.maps.LatLng(latitude, longitude));

    }


    var locationAutoloaded = false;
    function loadCurrentPosition(options) {
        var lat = options.defaultLatitude;
        var lon = options.defaultLongitude;

        if (navigator.geolocation) {
            var that = this;
            navigator.geolocation.getCurrentPosition(function (position) {
                lat = position.coords.latitude;
                lon = position.coords.longitude;
                locationAutoloaded = true;
                loadMapOnPosition(lat, lon);
            });
            /**Si el usuario no selecciona la autoubicación se toma una por defecto**/
            setTimeout(function(){
                if(!locationAutoloaded)
                    loadMapOnPosition(lat, lon);
            }, 5000);
        }
        else {
            //Si no tiene geolocalización lo ubica en la posición por defecto
            loadMapOnPosition(lat, lon);
        }
    }


	
    $.fn.bdmap.defaults = {
    	defaultLatitude : 4.57262365310281,
        defaultLongitude : -74.0970325469971,
    	elMap : '#map_location',
    	elAddresses : '.addressField',
        map : undefined
    };

})(jQuery);