<?php 



namespace App\Helpers;

use Exception;
use GuzzleHttp\Client;

class ExternalApiCall 
{
   
    function __construct(Client $client)
    {
        $this->httpClient = $client;
    }

    public function call($endpoint, $method = 'get', $headers = null , $body = null , $body_key = 'json' , $outputDebug = false)
    {

        try{
            if($body){

                $main_data = [
                    'headers' => $headers,
                    $body_key => $body,
                ];
            }else{
                $main_data = [
                    'headers' => $headers,
                ];
            }

            $response = $this->httpClient->$method($endpoint , $main_data);

            $responseHeaders = $response->getHeaders();
            $responseBody = $response->getBody();   
            $responseStatusCode = $response->getStatusCode();
        
            return collect(json_decode($responseBody->getContents(), true));

        } catch (\Throwable $e)
        {
         
            throw new Exception($e->getMessage());

        }

    }
}

