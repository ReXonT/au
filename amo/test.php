<?php
$subdomain = 'lpwebinar'; #Наш аккаунт - поддомен

#Формируем ссылку для запроса
$link = 'https://' . $subdomain . '.amocrm.ru/api/v2/account?with=custom_fields';

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


$res = json_decode($out,true);
foreach ($res['_embedded']['custom_fields']['contacts'] as $value) {
		if($value['name'] == 'Телефон')
			echo $value['id'];
}
echo '<pre>';
echo $next;
print_r($res['_embedded']['custom_fields']['contacts']);
echo '</pre>';

?>