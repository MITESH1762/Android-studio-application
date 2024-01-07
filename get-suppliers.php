<?php
    // <!-- Get Todays Meme of the Day -->
    // <!-- Get 5 memes in every category from the selections provided by user -->

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
        #END Parameters in POST

        try{

            $sql = 'SELECT * from suppliers_master ORDER BY supplier_id DESC';
            $stmt = $db->prepare($sql);
            // $stmt->bindParam(1, $img_status);
            // $stmt->bindParam(2, $_img_display_date);
            $stmt->execute();
            $response['suppliers_master'] = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $post_data = array(
                    "supplier_id"               =>  $row['supplier_id'],
                    "supplier_name"             =>  $row['supplier_name'],
                    "supplier_logo"             =>  $row['supplier_logo']
                );
                array_push($response['suppliers_master'], $post_data);
            }

            $json_response = $printJson->convertToJson(200, 0, Strings::$no_error, 1, $response);
            echo $json_response;
            exit;

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