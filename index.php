<!DOCTYPE html>
<html>
<head>
<title>Simple click event</title>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
<meta charset="utf-8">
<style>
html,body,#map-canvas {
	height: 90%;
	margin: 0;
	padding: 0;
}
</style>
<script
	src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>
<script src="maps_api.js">
</script>
</head>
<body>
<form method="post"  onsubmit="calcRoute();">
<input type="text" name="address" id="address" >
<input type="submit" name="submit" value="Submit">
</form>
	<div id="map-canvas"></div>
	<div id="counter"></div>
	<?php
	define('DRIVE','driving');
	define('WALK','walking');
	define('BICYCLE','bicycling');
	define('TRANSIT','transit');
	$key = 'GOOGLE API KEY HERE';
	function google_map_api($address){
	    global $key;
	    $address = urlencode($address);
        $url = "https://maps.google.com/maps/api/geocode/json?address={$address}&key={$key}";
        $resp_json = file_get_contents($url);
        $resp = json_decode($resp_json, true);
        // response status will be 'OK', if able to geocode given address
//         print_r($resp);
        if ($resp['status'] = 'OK') {
            // get the important data
            $lati = $resp['results'][0]['geometry']['location']['lat'];
            $longi = $resp['results'][0]['geometry']['location']['lng'];
            $formatted_address = $resp['results'][0]['formatted_address'];
            
            // verify if data is complete
            if ($lati && $longi && $formatted_address) {
                $data_arr = array();
                array_push($data_arr, $lati, $longi, $formatted_address);
                return $data_arr;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    
    function google_map_distance_api($origins, $destinations,$mode = DRIVE){
        global $key;
        $origins = urlencode($origins);
        $destinations = urlencode($destinations);
        $url="https://maps.googleapis.com/maps/api/distancematrix/json?origins={$origins}&destinations={$destinations}C&mode={$mode}&key={$key}";
        echo $url;
        $resp_json = file_get_contents($url);
        $resp = json_decode($resp_json, true);
        // response status will be 'OK', if able to geocode given address
        if ($resp['status'] = 'OK') {
            print_r($resp);
        }
    }
    if($_POST){
        $data_arr = google_map_api($_POST['address']);
        if ($data_arr) {
            
            $latitude = $data_arr[0];
            $longitude = $data_arr[1];
            $formatted_address = $data_arr[2];
            echo distance(22.5879424, 88.3834002, $latitude, $longitude, "K") . " KM<br>";
            google_map_distance_api('Esplanade, Kolkata',$formatted_address);
        }
        
    }
    
	function distance($lat1, $lon1, $lat2, $lon2, $unit) {
	    $theta = $lon1 - $lon2;
	    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	    $dist = acos($dist);
	    $dist = rad2deg($dist);
	    $miles = $dist * 60 * 1.1515;
	    $unit = strtoupper($unit);
	
	    if ($unit == "K") {
	        return ($miles * 1.609344);
	    } else if ($unit == "N") {
	        return ($miles * 0.8684);
	    } else {
	        return $miles;
	    }
	}
	
// 	echo distance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";
// 	echo distance(32.9697, -96.80322, 29.46786, -98.53506, "K") . " Kilometers<br>";
// 	echo distance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>";
	?>
	
</body>
</html>
