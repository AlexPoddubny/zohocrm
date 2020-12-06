<?php
    
    
    namespace App\Zoho;


class GetRecords{
    
    public function execute($token){
        
        $curl_pointer = curl_init();
        
        $curl_options = array();
//        $url = "https://www.zohoapis.com/crm/v2/Deals?"; //4726647000000333001?";
        $url = "https://www.zohoapis.com/crm/v2/Tasks?"; //4726647000000333001?";
        $parameters = array();
        $parameters["page"]="1";
//        $parameters["per_page"]="2";
        $parameters["sort_by"]="id";
        $parameters["sort_order"]="desc";
        $parameters["include_child"]="false";
//        $parameters["id"] = '4726647000000333001';
        
        
        foreach ($parameters as $key=>$value){
            $url =$url.$key."=".$value."&";
        }
        $curl_options[CURLOPT_URL] = $url;
        $curl_options[CURLOPT_RETURNTRANSFER] = true;
        $curl_options[CURLOPT_HEADER] = 1;
        $curl_options[CURLOPT_CUSTOMREQUEST] = "GET";
        $headersArray = array();
        $headersArray[] = "Authorization". ":" . "Zoho-oauthtoken " . $token->access_token;
        $headersArray[] = "If-Modified-Since".":"."2020-10-12T17:59:50+05:30";
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
