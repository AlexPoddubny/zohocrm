<?php
    
    namespace App\Zoho;
    
    class GetUsers{
        
        public function execute(){
            $curl_pointer = curl_init();
            
            $curl_options = array();
            $url = "https://www.zohoapis.com/crm/v2/users?";
            $parameters = array();
            $parameters["type"]="AllUsers";
            $parameters["page"]="1";
            $parameters["per_page"]="2";
            foreach ($parameters as $key=>$value){
                $url =$url.$key."=".$value."&";
            }
            $curl_options[CURLOPT_URL] = $url;
            $curl_options[CURLOPT_RETURNTRANSFER] = true;
            $curl_options[CURLOPT_HEADER] = 1;
            $curl_options[CURLOPT_CUSTOMREQUEST] = "GET";
            $headersArray = array();
            
            $headersArray[] = "Authorization". ":" . "Zoho-oauthtoken " . "1000.bce20953bee75ee69b54642ef519b9c8.e1be16ad09cfedc86704a84520651234";
            $headersArray[] = "If-Modified-Since".":"."2020-05-15T12:00:00+05:30";
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
