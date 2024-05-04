<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {


    public function __construct()
    {
        parent::__construct();

        
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

    private function translatetext($text, $currentLng, $targetLng ){

        // curl_setopt($curlSession, CURLOPT_URL, 'https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=hi&dt=t&q='.urlencode($text));

        $curlSession = curl_init(); 
        curl_setopt($curlSession, CURLOPT_URL, "https://translate.googleapis.com/translate_a/single?client=gtx&sl=$currentLng&tl=$targetLng&dt=t&q=".urlencode($text));
        curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curlSession);
        $jsonData = json_decode($response);
        curl_close($curlSession);

        if(isset($jsonData[0][0][0])){
            return $jsonData[0][0][0];
        }else{
            return false;
        }
    }

    public function lng(){
        $text = 'What are you doing';
        $currentLng = 'en';
        $targetLng = 'hi';

        // $text = 'विजय कुमार';
        // $currentLng = 'hi';
        // $targetLng = 'en';

        


        $d =  $this->translatetext($text, $currentLng, $targetLng);
        echo "<pre>";
        print_r($d);
    }


    
}
