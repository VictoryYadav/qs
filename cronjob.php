<?php
// Initialize curl
$ch = curl_init();

// Set the URL
curl_setopt($ch, CURLOPT_URL, 'https://eo.vtrend.org/cronjob/everyMorning4am');

// Set other curl options if needed
// For example:
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the curl command
$response = curl_exec($ch);

// Check for errors
if(curl_errno($ch)){
    echo 'Curl error: ' . curl_error($ch);
}

// Close curl
curl_close($ch);
?>