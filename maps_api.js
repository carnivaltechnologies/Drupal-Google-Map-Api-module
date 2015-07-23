function initialize() {
    var mapOptions = {
            zoom: 4,
            center: new google.maps.LatLng(-25.363882, 131.044922),
            styles: [{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"on"},{"saturation":-100},{"lightness":20}]},{"featureType":"road","elementType":"all","stylers":[{"visibility":"on"},{"saturation":-100},{"lightness":40}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"on"},{"saturation":-10},{"lightness":30}]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"visibility":"simplified"},{"saturation":-60},{"lightness":10}]},{"featureType":"landscape.natural","elementType":"all","stylers":[{"visibility":"simplified"},{"saturation":-60},{"lightness":60}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"},{"saturation":-100},{"lightness":60}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"},{"saturation":-100},{"lightness":60}]}]
    };

    var map = new google.maps.Map(document.getElementById('map-canvas'),
            mapOptions);
    map.setZoom(15);
    var image =  "car.png";

    var marker = new google.maps.Marker({
        position: map.getCenter(),
        map: map,
        title: 'Your Position',
        icon: image
    });

    var enable = false;
    if(navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
                function(position) {
                    var pos = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
                    map.setCenter(pos);
                    marker.position = pos;
                    enable = true;

                }, 
                function() {
                    handleNoGeolocation(true);
                }
        );
    } else {
        handleNoGeolocation(false);
    }
    var counter = 0;
    window.setInterval(function(){
        if(enable == true) {
            position ='';
            navigator.geolocation.getCurrentPosition(function(position){
                var pos = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
                map.setCenter(pos);
                marker.setPosition(pos);
                counter++;
                document.getElementById("counter").innerHTML="Counted - "+counter+"<br>Latitude - "+position.coords.latitude+"<br>Longitude - "+position.coords.longitude;
            });
        }
    }, 1000);

    google.maps.event.addListener(marker, 'click', function() {
        map.setZoom(12);
        map.setCenter(marker.getPosition());
    });
}

function calcRoute() {
    var start = 'Esplanade, Kolkata';
    var end = document.getElementById('address').value;
    var request = {
            origin:start,
            destination:end,
            travelMode: google.maps.TravelMode.DRIVING
    };
    directionsService.route(request, function(response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(response);
        }
    });
}

google.maps.event.addDomListener(window, 'load', initialize);
