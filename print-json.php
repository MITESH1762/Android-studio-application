<?php

    include_once '../functions/strings.php';

    class PrintJson {

        public function convertToJson($response_code, $error_code, $error_msg, $success_code, $data){
            try{
                $response_data = array();

                http_response_code($response_code);

                if($error_code != 0){
                    $response_data = array(
                        "error_code" =>  $error_code,
                        "error_msg" => $error_msg,
                        "success_code" => $success_code
                    );
                }else{
                    $response_data = array(
                        "error_code" =>  $error_code,
                        "error_msg" => $error_msg,
                        "success_code" => $success_code,
                        "data" => $data
                    );
                }

                return json_encode($response_data);
                exit;

            }catch(Exception $exception){

                http_response_code(400);

                $response_data = array(
                    "error_code" =>  1,
                    "error_msg" => Strings::$er_database_connection,
                    "success_code" => 0
                );

                return json_encode($response_data);
                exit;
            }
        }

    }

?>