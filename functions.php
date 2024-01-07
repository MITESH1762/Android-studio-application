<?php

    class Functions {

        public function generateReferralCode($length_of_string){
            $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            return substr(str_shuffle($str_result), 0, $length_of_string);
        }

        public function generateMedicalCode($length_of_string){
            $str_result = '123456789123456789123456789123456789';
            return substr(str_shuffle($str_result), 0, $length_of_string);
        }

        public function getInitials($name){
            $words = explode(" ", $name);
            $acronym = "";
            foreach ($words as $w) {
                $acronym .= $w[0];
            }
            return $acronym;
        }

        function formatDate($getdate, $format){
            $datetime = new DateTime($getdate); 
            return $datetime->format($format); 
            // Assuming today is March 10th, 2001, 5:16:18 pm, and that we are in the
            // Mountain Standard Time (MST) Time Zone
            // $today = date("j-F-Y");                          // 10-March-2001
            // $today = date("F j, Y, g:i a");                 // March 10, 2001, 5:16 pm
            // $today = date("m.d.y");                         // 03.10.01
            // $today = date("j, n, Y");                       // 10, 3, 2001
            // $today = date("Ymd");                           // 20010310
            // $today = date('h-i-s, j-m-y, it is w Day');     // 05-16-18, 10-03-01, 1631 1618 6 Satpm01
            // $today = date('\i\t \i\s \t\h\e jS \d\a\y.');   // it is the 10th day.
            // $today = date("D M j G:i:s T Y");               // Sat Mar 10 17:16:18 MST 2001
            // $today = date('H:m:s \m \i\s\ \m\o\n\t\h');     // 17:03:18 m is month
            // $today = date("H:i:s");                         // 17:16:18
        }

        function formatNumebertoDigits($number, $digits){
            return sprintf('%0'.$digits.'d', $number);
        }
    
        function ellipsis($string, $max_length) {
            return (strlen($string) > $max_length) ? substr($string,0,strrpos(substr($string, 0, $max_length), ' '))."…" : $string;
        }

    }

?>