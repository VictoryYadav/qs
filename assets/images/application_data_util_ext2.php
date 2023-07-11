<?php
//include "../assets/utils/fwdbutil.php";

//session_start();
//$dbh = setupPDO();

// v3 code starts
// change during RCA-18, API CR
include_once "../assets/utils/fwdateutil.php";
include_once "../assets/utils/fwglobalutil.php";
// rca-282, files in s3
require "../vendor/autoload.php";
use Aws\S3\S3Client;
// rca-305, additional docs
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// new function start here

/************** CTRL Visa code end **************************************/
?>
