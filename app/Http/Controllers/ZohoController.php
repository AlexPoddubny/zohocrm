<?php

namespace App\Http\Controllers;

use App\Zoho\AddUser;
use App\Zoho\GetListofModules;
use App\Zoho\GetRecords;
use App\Zoho\GetUsers;
use App\Zoho\Initialize;
use App\Zoho\InsertRecords;
use App\Zoho\ModuleMetadata;
use App\Zoho\UpdateRelatedRecords;
use Asciisd\Zoho\Facades\ZohoManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use zcrmsdk\crm\setup\restclient\ZCRMRestClient;


class ZohoController extends Controller
{
    
    private $code = [
        "access_token" =>"1000.b36ab0498dbfe6842a4e3a38a14f4c62.0c77a3602e9f87daf552c869a5e3f2a8",
        "refresh_token" => "1000.fcd7a20af3e388fbb9253cf8b3ea8341.6e826b61d6fcd98f6b0f62c118517e7a",
        "api_domain" => "https://www.zohoapis.com",
        "token_type" => "Bearer",
        "expires_in" => 3600
    ];
    
    private $token;
    private $file = 'token.json';
    
    public function __construct()
    {
//        $configuration = [
//            'client_id' => env('ZOHO_CLIENT_ID'),
//            'client_secret' => env('ZOHO_CLIENT_SECRET'),
//            'redirect_uri' =>env('ZOHO_REDIRECT_URI'),
//            'currentUserEmail'=>env('ZOHO_REDIRECT_URI')
//        ];
//        ZCRMRestClient::initialize($configuration);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        (new GetListofModules())->execute($this->code['access_token']);
//        (new InsertRecords())->execute($this->code['access_token']);  //"id" => "4726647000000333001"
//        (new UpdateRelatedRecords())->execute($this->code['access_token']);
//        $this->refreshToken();
//        $this->saveToken();
        $this->checkAccessToken();
        (new GetRecords())->execute($this->token);
    }
    
    public function getAccessToken()
    {
        // initial access token request
        $url = 'https://accounts.zoho.com/oauth/v2/token';

        //The data you want to send via POST
        $fields = [
            'grant_type'      => 'authorization_code',
            'client_id' => '1000.725Y8HYJ6S99OJGJ57HCZLW5M06QDR',
            'client_secret'         => '6527e08c7a375bc778e28f9dcad9f4e522c60cd0e3',
            'redirect_uri' => 'http://zoho.loc/zoho/oauth2callback',
            'code' => '1000.ddec7761e173cea8fa477f804d5dd2e5.d6acf779d49cce67cb4891090cc2cfca'
        ];

        //url-ify the data for the POST
        $fields_string = http_build_query($fields);

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        //execute post
        $result = curl_exec($ch);
        echo $result;
    }
    
    public function refreshToken()
    {
        echo 'Refreshing token:<br>';
        $url = 'https://accounts.zoho.com/oauth/v2/token?refresh_token='
            . $this->token->refresh_token//'1000.fcd7a20af3e388fbb9253cf8b3ea8341.6e826b61d6fcd98f6b0f62c118517e7a'
            . '&client_id=' . $this->token->client_id//'1000.725Y8HYJ6S99OJGJ57HCZLW5M06QDR'
            . '&client_secret=' . $this->token->client_secret//'6527e08c7a375bc778e28f9dcad9f4e522c60cd0e3'
            . '&grant_type=refresh_token';
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        dump($result = json_decode(curl_exec($ch)));
        $this->token->access_token = $result->access_token;
        $this->saveToken();
    }
    
    private function loadToken()
    {
        echo 'Loading token.<br>';
        $this->token = json_decode(Storage::get($this->file));
        echo "Access token is:<br>";
        dump($this->token);
    }
    
    private function saveToken()
    {
        /*
        $this->token = [
            "access_token" =>"1000.b36ab0498dbfe6842a4e3a38a14f4c62.0c77a3602e9f87daf552c869a5e3f2a8",
            "refresh_token" => "1000.fcd7a20af3e388fbb9253cf8b3ea8341.6e826b61d6fcd98f6b0f62c118517e7a",
            "client_id" => "1000.725Y8HYJ6S99OJGJ57HCZLW5M06QDR",
            "client_secret" => "6527e08c7a375bc778e28f9dcad9f4e522c60cd0e3"
        ];
        */
        echo 'Saving new token.<br>';
        Storage::put($this->file, json_encode($this->token));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    private function checkAccessToken()
    {
        echo 'Checking the access.<br>';
        $this->loadToken();
        
        $response = (new GetListofModules())->execute($this->token);
//        dump($response);
        if (isset($response['status']) && $response['status'] == 'error'){
            echo 'Access token is outdated.<br>';
            $this->refreshToken();
        }
        echo 'Access is granted. Getting the data:';
    }
    
    private function createDeal()
    {
        $curl_pointer = curl_init();
    
        $curl_options = array();
        $url = "https://www.zohoapis.com/crm/v2/Deals";
//        $url = "https://www.zohoapis.com/crm/v2/Tasks";
    
        $curl_options[CURLOPT_URL] = $url;
        $curl_options[CURLOPT_RETURNTRANSFER] = true;
        $curl_options[CURLOPT_HEADER] = 1;
        $curl_options[CURLOPT_CUSTOMREQUEST] = "POST";
        $requestBody = array();
        $recordArray = array();
        $recordObject = array();
//            $recordObject["Company"] = "FieldAPIValue";
//            $recordObject["Deal_Name"] = "347706107420006";
        $recordObject["Subject"] = "My Task";
//            $recordObject["First_Name"] = "34770617420006";
//            $recordObject["State"] = "FieldAPIValue";
    }
    
}
