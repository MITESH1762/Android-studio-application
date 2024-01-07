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
        $admin_email                                = trim(htmlspecialchars(strip_tags($request_obj['admin_email'])));
        $admin_password                             = trim(htmlspecialchars(strip_tags($request_obj['admin_password'])));
        #END Parameters in POST

        if(isset($admin_email) && isset($admin_password)){
            
            try{

                $response = array();

                $sql = 'SELECT * from admin_master WHERE admin_email = ? LIMIT 1';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(1, $admin_email);
                $stmt->execute();
                
                $num = $stmt->rowCount();

                if($num > 0){

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $hash = $row['admin_password'];

                    $verify = password_verify($admin_password, $hash);

                    if ($verify) {
                        $response['user_details'] = array(
                            "admin_id"                      =>  $row['admin_id'],
                            "admin_name"                    =>  $row['admin_name'],
                            "admin_email"                   =>  $row['admin_email'],
                            "admin_mobile"                  =>  $row['admin_mobile']
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
        $errorHandler->logError(__FILE__."-2002\t".$exception->getMessage(), "../error-handeling/logs/errors-$current_date.log");

        $json_response = $printJson->convertToJson(200, 2002, Strings::$er_paramaters_missing, 0, "");
        echo $json_response;
        exit;
    }
?>