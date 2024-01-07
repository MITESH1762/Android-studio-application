<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    date_default_timezone_set('Asia/Kolkata');

    include_once '../functions/print-json.php';
    include_once '../functions/strings.php';
    include_once '../error-handeling/error-handler.php';

    class Database {
        private $host = "localhost";
        private $database_name = "foodstrike_db";
        private $username = "root";
        private $password = "";

        public $conn;

        //JWT
        private $secret_key;
        private $issuer_claim;
        private $audience_claim;
        private $issuedat_claim;
        private $notbefore_claim;
        private $expire_claim;

        private $load_more_count;
 
        function __construct(){
            $this->secret_key = "bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=";
            $this->issuer_claim = "https://www.example.com/";
            $this->audience_claim = "OV7fmuObZV";
            $this->issuedat_claim = time();
            $this->notbefore_claim = $this->issuedat_claim + 0; //not before in seconds
            $this->expire_claim = $this->issuedat_claim + 60 * 60 * 24;//24 hrs // expire time in seconds // jwt valid for 60 days (60 seconds * 60 minutes * 24 hours * 60 days)
            $this->load_more_count = 2;
        }

        public function getConnection(){
            $this->conn = null;
            $this->secret_key = null;
            $this->issuer_claim = null;
            $this->audience_claim = null;
            $this->issuedat_claim = null;
            $this->notbefore_claim = null;
            $this->expire_claim = null;

            try{

                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);
                $this->conn->exec("set names utf8");
                
            }catch(PDOException $exception){
                
                $current_date = date("Y-m-d");
                $errorHandler = new ErrorHandler();
                $errorHandler->logError(__FILE__."\t".$exception->getMessage(), "../error-handeling/logs/errors-$current_date.log");

                $printJson = new PrintJson();
                $json_response = $printJson->convertToJson(400, 1001, Strings::$er_database_connection, 0, "");
                echo $json_response;
                exit;
            }
            return $this->conn;
        }

        public function getSecretKey(){
            return $this->secret_key;
        }

        public function getIssuerClaim(){
            return $this->issuer_claim;
        }

        public function getAudienceClaim(){
            return $this->audience_claim;
        }

        public function getIssuedDateClaim(){
            return $this->issuedat_claim;
        }

        public function getBeforeClaim(){
            return $this->notbefore_claim;
        }

        public function getExpireClaim(){
            return $this->expire_claim;
        }

        public function getLoadMoreCount(){
            return $this->load_more_count;
        }

        public function GUID(){

            if (function_exists('com_create_guid') === true){
                return trim(com_create_guid(), '{}');
            }
        
            return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
        }

    }  
?>