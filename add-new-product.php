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

    define('UPLOAD_PATH', $_SERVER["DOCUMENT_ROOT"].'/foodstrike-apis/api/uploads/');
    $upload_path		= UPLOAD_PATH;

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
        $product_name                           = trim(htmlspecialchars(strip_tags($request_obj['product_name'])));
        $product_description                    = trim(htmlspecialchars(strip_tags($request_obj['product_description'])));
        $product_photo                          = trim(htmlspecialchars(strip_tags($request_obj['product_photo'])));
        #END Parameters in POST

        if(isset($product_name) && isset($product_description)){
            
            try{

                $sql="INSERT into  products_master 
                    (product_name, product_description,product_photo)
                    values(:product_name, :product_description, :product_photo)";
                    
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':product_name', $product_name);
                $stmt->bindParam(':product_description', $product_description);
                $stmt->bindParam(':product_photo', $product_photo);

                if($stmt->execute()){
                    $product_id = $db->lastInsertId();

                    // Save Product Pricings
                    $sql_1 = 'SELECT * from suppliers_master ORDER BY supplier_id ASC';
                    $stmt_1 = $db->prepare($sql_1);
                    $stmt_1->execute();
                    while($row = $stmt_1->fetch(PDO::FETCH_ASSOC)){
                        $supplier_id = $row['supplier_id'];

                        $sql_2="INSERT into product_supplier_mapping 
                            (product_id, supplier_id, product_price, approx_delivery_duration, product_view_link)
                            values(:product_id, :supplier_id, :product_price, :approx_delivery_duration, :product_view_link)";
                        $stmt_2 = $db->prepare($sql_2);
                        $stmt_2->bindParam(':product_id', $product_id);
                        $stmt_2->bindParam(':supplier_id', $supplier_id);
                        $stmt_2->bindParam(':product_price', $empty_string);
                        $stmt_2->bindParam(':approx_delivery_duration', $empty_string);
                        $stmt_2->bindParam(':product_view_link', $empty_string);
                        $stmt_2->execute();
                    }

                    $response['message']     = "Product saved successfully!";
                    $json_response = $printJson->convertToJson(200, 0, Strings::$no_error, 1, $response);
                    echo $json_response;
                    exit;
                }

                // if(isset($_FILES['product_photo'])){

                //     $file_arry   = explode('.',$_FILES['product_photo']['name']);
                //     $file_name   = $database->GUID().".".end($file_arry);
            
                //     echo $upload_path.$file_name;

                //     if(move_uploaded_file($_FILES['product_photo']['tmp_name'], $upload_path.$file_name)){
                //         $sql="INSERT into  products_master 
                //             (product_name, product_description,product_photo)
                //             values(:product_name, :product_description, :product_photo)";
                            
                //         $stmt = $db->prepare($sql);
                //         $stmt->bindParam(':product_name', $product_name);
                //         $stmt->bindParam(':product_description', $product_description);
                //         $stmt->bindParam(':product_photo', $upload_path.$file_name);

                //         if($stmt->execute()){
                //             $product_id = $db->lastInsertId();

                //             $response['message']     = "Product saved successfully!";
                //             $json_response = $printJson->convertToJson(200, 0, Strings::$no_error, 1, $response);
                //             echo $json_response;
                //             exit;
                //         }
                //     }
            
                // }else{

                //     $sql="INSERT into  products_master 
                //         (product_name, product_description,product_photo)
                //         values(:product_name, :product_description, :product_photo)";
                        
                //     $stmt = $db->prepare($sql);
                //     $stmt->bindParam(':product_name', $product_name);
                //     $stmt->bindParam(':product_description', $product_description);
                //     $stmt->bindParam(':product_photo', $empty_string);

                //     if($stmt->execute()){
                //         $product_id = $db->lastInsertId();

                //         $response['message']     = "Product saved successfully!";
                //         $json_response = $printJson->convertToJson(200, 0, Strings::$no_error, 1, $response);
                //         echo $json_response;
                //         exit;
                //     }

                // }
                
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