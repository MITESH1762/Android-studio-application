<?php

    include_once '../config/database.php';
    include_once '../functions/functions.php';
    include_once '../error-handeling/error-handler.php';
    require "../vendor/autoload.php";
    use \Firebase\JWT\JWT;

    class JWTToken{
        
        public $secret_key;
        public $issuer_claim;
        public $audience_claim;
        public $issuedat_claim;
        public $notbefore_claim;
        public $expire_claim;

        function __construct($secret_key, $issuer_claim, $audience_claim, $issuedat_claim, $notbefore_claim, $expire_claim){
            $this->secret_key = $secret_key;
            $this->issuer_claim = $issuer_claim;
            $this->audience_claim = $audience_claim;
            $this->issuedat_claim = $issuedat_claim;
            $this->notbefore_claim = $notbefore_claim;
            $this->expire_claim = $expire_claim;
        }

        public function generateJWT($data){
            
            $printJson = new PrintJson();
            $errorHandler = new ErrorHandler();
            $current_date = date("Y-m-d");
            $current_date_time = date("Y-m-d H:i:s");
            
            try{
                //PARAMS
                $u_email = $data["u_email"];
                $u_id = $data["u_id"];
                $ul_id = $data["ul_id"];
                //#PARAMS
                 
                $token = array(
                    "iss" => $this->issuer_claim,
                    "aud" => $this->audience_claim,
                    "iat" => $this->issuedat_claim,
                    "nbf" => $this->notbefore_claim,
                    "exp" => $this->expire_claim,
                    "data" => array(
                        "identity" => $u_id,
                        "email_address" => $u_email,
                        "session" => $ul_id
                ));

                $jwt = JWT::encode($token, $this->secret_key);
                // $jwt = JWT::encode($token, base64_encode($this->secret_key));

                return array(
                        "jwt" => $jwt,
                        "expireAt" => $this->expire_claim
                    );
                    
            }catch(Throwable $exception){
                
                $errorHandler->logError(__FILE__."-3000\t".$exception->getMessage(), "../error-handeling/logs/errors-$current_date.log");

                $json_response = $printJson->convertToJson(200, 3000, Strings::$er_general, 0, "");
                echo $json_response;
                exit;
            }
        }

        public function verifyJWT($data){
            $printJson = new PrintJson();
            $errorHandler = new ErrorHandler();
            $current_date = date("Y-m-d");
            $current_date_time = date("Y-m-d H:i:s");
            
            try{
                //PARAMS
                $token = $data["token"];
                $u_email = $data["u_email"];
                $u_id = $data["u_id"];
                $ul_id = $data["ul_id"];
                //#PARAMS
                 
                $authHeader = $token;
                $arr = explode(" ", $authHeader);
                $jwt = $arr[1];

                if($jwt){
                    try {
                        $decoded = JWT::decode($jwt, $this->secret_key, array('HS256'));
 
                        $identity = $decoded->data->identity;
                        $email_address = $decoded->data->email_address;
                        $session = $decoded->data->session;

                        if(($u_email == $email_address) && ((int)$u_id === (int)$identity) && ((int)$ul_id === (int)$session)){
                            // Access is granted. Add code of the operation here 
                            return true;
                            // echo json_encode(array(
                            //     "decoded" => $decoded,
                            //     "message" => "Access granted",
                            //     "error" => ""
                            // ));
                            exit;
                        }else{
                            $errorHandler->logError(__FILE__."-3003\tData Mismatch\t", "../error-handeling/logs/errors-$current_date.log");

                            $json_response = $printJson->convertToJson(401, 3003, Strings::$er_authentication_failed, 0, "");
                            echo $json_response;
                            exit;
                        }
                    }catch (Throwable $exception){
                        $errorHandler->logError(__FILE__."-3002\tAccess denied\t".$exception->getMessage(), "../error-handeling/logs/errors-$current_date.log");

                        $json_response = $printJson->convertToJson(401, 3002, Strings::$er_authentication_failed, 0, "");
                        echo $json_response;
                        exit;
                    }
                }

            }catch(Throwable $exception){
                
                $errorHandler->logError(__FILE__."-3001\t".$exception->getMessage(), "../error-handeling/logs/errors-$current_date.log");

                $json_response = $printJson->convertToJson(401, 3001, Strings::$er_authentication_failed, 0, "");
                echo $json_response;
                exit;
            }
        }

    }

?>