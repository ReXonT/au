<?php 

class Bizon 
{
    protected $cookie_file;
    protected $url = 'https://online.bizon365.ru/api/v1/';
    protected $useragent = 'activeusers';
    protected $params;

    public function __construct($cookie)
    {
        $this->cookie_file = $cookie;
    }

    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
    }

    public function getViewers($webinar_id)
    {
        $this->setParam('webinarId', $webinar_id);
        return $this->call('getviewers', $this->params);
    }

    public function get($webinar_id)
    {
        $this->setParam('webinarId', $webinar_id);
        return $this->call('get', $this->params);
    }

    public function getList($room_id = '')
    {
        return $this->call('getlist', $this->params);
    }

    private function call($method, $params)
    {
        $link = $this->url . 'webinars/reports/'. $method . '?' . http_build_query($params);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, $this->useragent);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookie_file); 
        curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookie_file);
        curl_setopt($curl, CURLOPT_URL, $link);
        $resp = curl_exec($curl); // Инициируем запрос к API и сохраняем ответ в переменную
        curl_close($curl); // Завершаем сеанс cURL

        $response = json_decode($resp, 1);

        return $response;
    }

    public function auth($user)
    {
        $link = $this->url . 'auth/login';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, $this->useragent);
        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($user));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded'));
        curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookie_file); 
        curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookie_file);
        $resp = curl_exec($curl); // Инициируем запрос к API и сохраняем ответ в переменную
        curl_close($curl); // Завершаем сеанс cURL

        $response = json_decode($resp, 1);

        return $response;
    }

}