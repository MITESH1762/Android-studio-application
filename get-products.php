<?php

    include_once '../config/headers.php';   
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Access-Control-Max-Age: 3600");

    include_once '../config/database.php';
    include_once '../functions/functions.php';
    include_once '../error-handeling/error-handler.php';
    include_once '../class/JWT.php';

    $empty_string = "";
    $current_date = date("Y-m-d");
    $current_date_time = date("Y-m-d H:i:s");
    $printJson = new PrintJson();
    $database = new Database();
    $function = new Functions();
    $errorHandler = new ErrorHandler();
    $jwt = new JWTToken($database->getSecretKey(), $database->getIssuerClaim(), $database->getAudienceClaim(), $database->getIssuedDateClaim(), $database->getBeforeClaim(), $database->getExpireClaim());
    $db = $database->getConnection();
    $load_more = $database->getLoadMoreCount();

    // $data = json_decode(file_get_contents("php://input"));
    $request_obj = $_POST;

    // if(!isset($_POST) || (is_array($_POST) && count($_POST) < 1)){
    //     $json_response = $printJson->convertToJson(200, 2000, Strings::$er_paramaters_missing, 0, "");
    //     echo $json_response;
    //     exit;
    // }

    try{ 

        //Parameters in POST
        // $product_name                           = trim(htmlspecialchars(strip_tags($request_obj['product_name'])));
        #END Parameters in POST

        try{

            $sql = 'SELECT * from products_master ORDER BY product_id DESC';
            $stmt = $db->prepare($sql);
            // $stmt->bindParam(1, $img_status);
            // $stmt->bindParam(2, $_img_display_date);
            $stmt->execute();
            $response['products_master'] = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $post_data = array(
                    "product_id"               =>  $row['product_id'],
                    "product_name"             =>  $row['product_name'],
                    "product_description"      =>  $row['product_description'],
                    "product_photo"            =>  $row['product_photo']
                );
                array_push($response['products_master'], $post_data);
            }

            $json_response = $printJson->convertToJson(200, 0, Strings::$no_error, 1, $response);
            echo $json_response;
            exit;

            // $sql = 'SELECT * from products_master WHERE product_name LIKE :product_name';
            // $stmt = $db->prepare($sql);
            // $stmt->bindParam(':product_name', '%'.$product_name.'%');
            // $stmt->execute();
            // $response['products_master'] = array();
            // while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            //     $post_data = array(
            //         "product_id"               =>  $row['product_id'],
            //         "product_name"             =>  $row['product_name'],
            //         "product_description"      =>  $row['product_description'],
            //         "product_photo"            =>  $row['product_photo']
            //     );
            //     array_push($response['products_master'], $post_data);
            // }

            // $json_response = $printJson->convertToJson(200, 0, Strings::$no_error, 1, $response);
            // echo $json_response;
            // exit;

        }catch(Throwable $exception){
            $errorHandler->logError(__FILE__."-2003\t".$exception->getMessage(), "../error-handeling/logs/errors-$current_date.log");

            $json_response = $printJson->convertToJson(200, 2003, Strings::$er_fatel, 0, "");
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

//LOGIC

?>