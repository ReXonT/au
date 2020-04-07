<?php

class Bitrix24
{
    private $webhook_url;

    public function __construct($url)
    {
        $this->setWebhookUrl($url);
    }

    public function setWebhookUrl($url)
    {
        $temp = explode('/', $url);
        unset($temp[count($temp)-2]);
        $this->webhook_url = implode($temp, '/');
    }


    public function call($method, array $query_data)
    {
        $curl = curl_init();

        $url = $this->webhook_url . $method . '.json';

        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_POSTFIELDS => http_build_query($query_data),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($response, 1);
        $result['url'] = $url;

        return $result;
    }
}