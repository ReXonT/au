<?php

require_once('amo_class.php');

 $user_login = 'lpwebinar@yandex.ru';
    $user_hash = '630dcb876794a2db5732262dc2240c8b2a2f4d49';
    $subdomain = 'lpwebinar'; // Наш аккаунт - поддомен

    $amo = new Amo($user_login, $user_hash, $subdomain);
    
    // Авторизация
    $response = $amo->auth($session_id);

$subdomain = 'lpwebinar'; #Наш аккаунт - поддомен

#Формируем ссылку для запроса
$link = 'https://' . $subdomain . '.amocrm.ru/api/v2/account?with=custom_fields,note_types';

/*
Нам необходимо инициировать запрос к серверу. Воспользуемся библиотекой cURL (поставляется в составе PHP).
Вы также можете использовать и кроссплатформенную программу cURL, если вы не программируете на PHP.
*/

$curl = curl_init(); #Сохраняем дескриптор сеанса cURL
#Устанавливаем необходимые опции для сеанса cURL
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
curl_setopt($curl, CURLOPT_URL, $link);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
$out = curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

$c = json_decode($out,1);
echo '<pre>';
print_r($c);
echo '</pre>';