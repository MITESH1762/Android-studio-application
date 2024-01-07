<?php

    include_once '../config/headers.php';   
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Access-Control-Max-Age: 3600");

    include_once '../config/database.php';
    include_once '../functions/functions.php';
    include_once '../error-handeling/error-handler.php';

    $empty_string = "";
    $status_1 = 1;
    $status_0 = 0;
    $current_date = date("Y-m-d");
    $current_date_time = date("Y-m-d H:i:s");
    $printJson = new PrintJson();
    $database = new Database();
    $function = new Functions();
    $errorHandler = new ErrorHandler();
    $db = $database->getConnection();
    $load_more = $database->getLoadMoreCount();

    //$data = json_decode(file_get_contents("php://input"));
    $request_obj = $_POST;

    //if($data == null){
    if(!isset($_POST) || (is_array($_POST) && count($_POST) < 1)){
        $json_response = $printJson->convertToJson(200, 2000, Strings::$er_paramaters_missing, 0, "");
        echo $json_response;
        exit;
    }

    try{ 

        //Parameters in POST
        $u_email                                = trim(htmlspecialchars(strip_tags($request_obj['u_email'])));
        $u_password                             = trim(htmlspecialchars(strip_tags($request_obj['u_password'])));
        #END Parameters in POST

        if(isset($u_email) && isset($u_password)){
            
            try{

                $response = array();

                $sql = 'SELECT * from users_master WHERE u_email = ? LIMIT 1';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(1, $u_email);
                $stmt->execute();
                
                $num = $stmt->rowCount();

                if($num > 0){

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $hash = $row['u_password'];

                    $verify = password_verify($u_password, $hash);

                    if ($verify) {
                        $response['user_details'] = array(
                            "u_id"                      =>  $row['u_id'],
                            "u_name"                    =>  $row['u_name'],
                            "u_email"                   =>  $row['u_email'],
                            "u_mobile"                  =>  $row['u_mobile'],
                            "u_account_status"          =>  $row['u_account_status']
                        );
    
                        $json_response = $printJson->convertToJson(200, 0, Strings::$no_error, 1, $response);
                        echo $json_response;
                        exit;
                    } else {
                        $json_response = $printJson->convertToJson(200, 2005, Strings::$er_invalid_user, 0, "");
                        echo $json_response;
                        exit;
                    }
                }else{
                    $json_response = $printJson->convertToJson(200, 2002, Strings::$er_invalid_user, 0, "");
                    echo $json_response;
                    exit;
                }
            }catch(Throwable $exception){
                $errorHandler->logError(__FILE__."-2003\t".$exception->getMessage(), "../error-handeling/logs/errors-$current_date.log");

                $json_response = $printJson->convertToJson(200, 2003, Strings::$er_fatel, 0, "");
                echo $json_response;
                exit;
            }
        }else{
            $json_response = $printJson->convertToJson(200, 2004, Strings::$er_paramaters_missing, 0, "");
            echo $json_response;
            exit;
        }
    }
    catch(Throwable $exception){
        $errorHandler->logError(__FILE__."-2005\t".$exception->getMessage(), "../error-handeling/logs/errors-$current_date.log");

        $json_response = $printJson->convertToJson(200, 2005, Strings::$er_paramaters_missing, 0, "");
        echo $json_response;
        exit;
    }

?>