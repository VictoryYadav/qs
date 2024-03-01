<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {


    public function __construct()
    {
        parent::__construct();

        
    }

    function pay_success(){
        echo "<pre>";print_r($_SESSION);
        print_r($_POST);
    }

    public function dd(){
    	// Get the user's IP address
$userIP = $_SERVER['REMOTE_ADDR'];

// Make a request to a free IP geolocation service
$response = file_get_contents("http://ip-api.com/json/{$userIP}");

// Decode the JSON response
$ipInfo = json_decode($response);

echo "<pre>";
print_r($userIP);
print_r($ipInfo);
die;

// Check if the request was successful and country was detected
if ($ipInfo && $ipInfo->status === "success") {
    $countryCode = $ipInfo->countryCode;
    $countryName = $ipInfo->country;

    echo "Country Code: $countryCode<br>";
    echo "Country Name: $countryName";
} else {
    echo "Unable to detect country.";
}
die;
    }

    
}
