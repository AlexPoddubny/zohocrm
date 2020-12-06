<?php
    
    
    namespace App\Zoho;


    class AddUser
    {
        public function execute(){
            $curl_pointer = curl_init();
        
            $curl_options = array();
            $curl_options[CURLOPT_URL] = "https://www.zohoapis.com/crm/v2/users";
            $curl_options[CURLOPT_RETURNTRANSFER] = true;
            $curl_options[CURLOPT_HEADER] = 1;
            $curl_options[CURLOPT_CUSTOMREQUEST] = "POST";
            $requestBody = array();
            $userArray = array();
            $userObject = array();
            $userObject["role"]="3524033000000026005";
            $userObject["first_name"]="Variaggble33";
            $userObject["email"] = "asda@asd.com";
            $userObject["profile"] = "3524033000000026011";
            $userObject["last_name"] = "sadasd";
            $userArray[] = $userObject;
            $requestBody["users"] =$userArray;
            $curl_options[CURLOPT_POSTFIELDS]= json_encode($requestBody);
            $headersArray = array();
        
            $headersArray[] = "Authorization". ":" . "Zoho-oauthtoken " . "1000.bce20953bee75ee69b54642ef519b9c8.e1be16ad09cfedc86704a84520651234";
        
            $curl_options[CURLOPT_HTTPHEADER]=$headersArray;
        
            curl_setopt_array($curl_pointer, $curl_options);
        
            $result = curl_exec($curl_pointer);
            $responseInfo = curl_getinfo($curl_pointer);
            curl_close($curl_pointer);
            list ($headers, $content) = explode("\r\n\r\n", $result, 2);
            if(strpos($headers," 100 Continue")!==false){
                list( $headers, $content) = explode( "\r\n\r\n", $content , 2);
            }
            $headerArray = (explode("\r\n", $headers, 50));
            $headerMap = array();
            foreach ($headerArray as $key) {
                if (strpos($key, ":") != false) {
                    $firstHalf = substr($key, 0, strpos($key, ":"));
                    $secondHalf = substr($key, strpos($key, ":") + 1);
                    $headerMap[$firstHalf] = trim($secondHalf);
                }
            }
            $jsonResponse = json_decode($content, true);
            if ($jsonResponse == null && $responseInfo['http_code'] != 204) {
                list ($headers, $content) = explode("\r\n\r\n", $content, 2);
                $jsonResponse = json_decode($content, true);
            }
            dump($headerMap);
            dump($jsonResponse);
            dump($responseInfo['http_code']);
        
        }
    
    }
