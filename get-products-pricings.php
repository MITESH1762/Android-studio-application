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

            $sql = 'SELECT 
                        t1.product_supplier_mapping_id, t1.product_price, t1.approx_delivery_duration, t1.product_view_link,
                        t2.product_id, t2.product_name, t2.product_description, t2.product_photo, 
                        t3.supplier_id, t3.supplier_name, t3.supplier_logo
                    FROM 
                        product_supplier_mapping t1 
                        left join products_master t2 on t1.product_id = t2.product_id 
                        left join suppliers_master t3 on t1.supplier_id = t3.supplier_id;';

            $stmt = $db->prepare($sql);
            // $stmt->bindParam(1, $img_status);
            // $stmt->bindParam(2, $_img_display_date);
            $stmt->execute();
            $response['products_pricing_master'] = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $post_data = array(
                    "product_supplier_mapping_id" =>  $row['product_supplier_mapping_id'],
                    "product_price"             =>  $row['product_price'],
                    "approx_delivery_duration"  =>  $row['approx_delivery_duration'],
                    "product_view_link"         =>  $row['product_view_link'],
                    "product_id"                =>  $row['product_id'],
                    "product_name"              =>  $row['product_name'],
                    "product_description"       =>  $row['product_description'],
                    "product_photo"             =>  $row['product_photo'],
                    "supplier_id"               =>  $row['supplier_id'],
                    "supplier_name"             =>  $row['supplier_name'],
                    "supplier_logo"             =>  $row['supplier_logo']
                );
                array_push($response['products_pricing_master'], $post_data);
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