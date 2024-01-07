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
        // $product_supplier_mapping_id            = trim(htmlspecialchars(strip_tags($request_obj['product_supplier_mapping_id'])));
        $supplier_id                            = trim(htmlspecialchars(strip_tags($request_obj['supplier_id'])));
        $product_id                             = trim(htmlspecialchars(strip_tags($request_obj['product_id'])));
        $product_price                          = trim(htmlspecialchars(strip_tags($request_obj['product_price'])));
        $approx_delivery_duration               = trim(htmlspecialchars(strip_tags($request_obj['approx_delivery_duration'])));
        $product_view_link                      = trim(htmlspecialchars(strip_tags($request_obj['product_view_link'])));
        #END Parameters in POST

        if(isset($supplier_id) && isset($product_id)){
            
            try{

                $response = array();

                $sql = 'SELECT * from  product_supplier_mapping WHERE product_id = ? and supplier_id = ? LIMIT 1';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(1, $product_id);
                $stmt->bindParam(2, $supplier_id);
                $stmt->execute();
                
                $num = $stmt->rowCount();

                if($num <= 0){
                    // New
                    $sql="INSERT into product_supplier_mapping
                        (product_id, supplier_id,product_price,approx_delivery_duration,product_view_link,last_modified)
                        values(:product_id, :supplier_id, :product_price, :approx_delivery_duration, :product_view_link, :last_modified)";
                        
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':supplier_id', $supplier_id);
                    $stmt->bindParam(':product_price', $product_price);
                    $stmt->bindParam(':approx_delivery_duration', $approx_delivery_duration);
                    $stmt->bindParam(':product_view_link', $product_view_link);
                    $stmt->bindParam(':last_modified', $current_date_time);

                    if($stmt->execute()){
                        $product_supplier_mapping_id = $db->lastInsertId();

                        $response['message']     = "Pricing saved successfully!";
                        $json_response = $printJson->convertToJson(200, 0, Strings::$no_error, 1, $response);
                        echo $json_response;
                        exit;
                    }
                    else{
                        $errorHandler->logError(__FILE__."-2001\t".$stmt->errorInfo(), "../error-handeling/logs/errors-$current_date.log");

                        $json_response = $printJson->convertToJson(200, 2001, Strings::$er_general, 0, "");
                        echo $json_response;
                        exit;
                    }
                }else{
                    // Update
                    try{
                        $sql = "UPDATE
                                    product_supplier_mapping
                                SET
                                    product_price = :product_price,
                                    approx_delivery_duration = :approx_delivery_duration,
                                    product_view_link = :product_view_link
                                WHERE
                                    product_id = :product_id and 
                                    supplier_id = :supplier_id";

                        $stmt = $db->prepare($sql);
                        $stmt->bindParam(':product_price', $product_price);
                        $stmt->bindParam(':approx_delivery_duration', $approx_delivery_duration);
                        $stmt->bindParam(':product_view_link', $product_view_link);
                        $stmt->bindParam(':supplier_id', $supplier_id);
                        $stmt->bindParam(':product_id', $product_id);
                        
                        if($stmt->execute()){
                            $response['message']     = "Pricing updated successfully!";
                            $json_response = $printJson->convertToJson(200, 0, Strings::$no_error, 1, $response);
                            echo $json_response;
                            exit;
                        }
                        else{
                            $errorHandler->logError(__FILE__."-2002\t".$stmt->errorInfo(), "../error-handeling/logs/errors-$current_date.log");
    
                            $json_response = $printJson->convertToJson(200, 2002, Strings::$er_general, 0, "");
                            echo $json_response;
                            exit;
                        }

                    }catch (Throwable $exception){

                    }
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