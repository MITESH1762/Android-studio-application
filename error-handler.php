<?php

    $current_date = date("Y-m-d");
    $current_date_time = date("Y-m-d H:i:s");

    class ErrorHandler{

        public function logError($_error_message, $_log_file){
            
            $current_date_time = date("Y-m-d H:i:s");

            return error_log($current_date_time."\t".$_error_message."\r\n", 3, $_log_file);
        }

    }

    // Use the line below to report error to file
    // $errorHandler->logError("Dummey error", "../error-handeling/logs/errors-$current_date.log");
    // $errorHandler->logError($exception->getMessage(), "../error-handeling/logs/errors-$current_date.log");
?>
