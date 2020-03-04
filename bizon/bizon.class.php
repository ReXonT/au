<?php 

class Bizon 
{
    private $cookie_file = 'cookie.txt';
    private $url = 'https://online.bizon365.ru/api/v1/';
    private $useragent = 'activeusers';

    function call ($method, $params) 
    {
        $params = http_build_query($params);
        $link = $this->url.'webinars/reports/'.$method.'?'.$params;

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, $useragent);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookie_file); 
        curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookie_file);
        curl_setopt($curl, CURLOPT_URL, $link);

        $resp = curl_exec($curl); // Инициируем запрос к API и сохраняем ответ в переменную
        
        curl_close($curl); // Завершаем сеанс cURL

        $arr = json_decode($resp, 1);

        $arr['link'] = $link;

        return $arr;
    }

    function auth ($user)
    {

        $curl = curl_init(); 

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, $useragent);
        curl_setopt($curl, CURLOPT_URL, $link = $this->url.'/auth/login');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($user));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded'));
        curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookie_file); 
        curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookie_file); 

        $out = curl_exec($curl); // Инициируем запрос к API и сохраняем ответ в переменную

        curl_close($curl); // Завершаем сеанс cURL
    }

}