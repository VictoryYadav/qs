<?php                                                                                   

defined('BASEPATH') OR exit('No direct script access allowed');

//require APPPATH. 'core/Admin_controller.php';

/**
 * @description : Library to access MyOperator Public API
 */
Class MY_Operator {

    protected $developers_url = 'https://krutiinstitute.in/webapp/WebApi/getColleges';
    protected $token = '4545454';

    function __construct() {

    }

    public function run() {
        # request for Logs
        $url = $this->developers_url;
        $fields = array("token" => $this->token);
        $result = $this->_post_api($fields, $url);

        $this->log("result");
        $this->log($result);
    }

    private function _post_api(Array $fields, $url) {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            $result = curl_exec($ch);
        } catch (Exception $e) {
            return false;
        }
        $this->log("url");
        $this->log($url);
        $this->log("fields");
        $this->log($fields);
        curl_close($ch);
        if ($result)
            return $result;
        else
            return false;
    }

    private function log($message) {
        print_r($message);
        echo "\n";
    }
}