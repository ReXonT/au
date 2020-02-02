<?php

	class Amo 
	{

		/* Поддомен */
		protected $subdomain;

		/* Логин юзера */
		protected $user_login;

		/* Api key (hash) юзера */
		protected $user_hash;

	    public function __construct($user_login, $user_hash, $subdomain)
	    {
	        $this->subdomain = $subdomain;
	        $this->user_login = $user_login;
	        $this->user_hash = $user_hash;
	    }

		public function auth($session_id)
		{
			// Массив с параметрами, которые нужно передать методом POST к API системы
			$user = array(
		        'USER_LOGIN' =>  $this->user_login, // Ваш логин (электронная почта)
		        'USER_HASH' =>  $this->user_hash, // Хэш для доступа к API (смотрите в профиле пользователя)
		    );

		    $link = 'https://' . $this->subdomain . '.amocrm.ru/private/api/auth.php?type=json';

		    $curl = curl_init(); // Сохраняем дескриптор сеанса cURL
		    // Устанавливаем необходимые опции для сеанса cURL
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
		    curl_setopt($curl, CURLOPT_URL, $link);
		    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($user));
		    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		    curl_setopt($curl, CURLOPT_HEADER, false);
		    curl_setopt($curl, CURLOPT_COOKIEFILE, $session_id.'cookie.txt'); 
		    curl_setopt($curl, CURLOPT_COOKIEJAR, $session_id.'cookie.txt'); 
		    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		    
		    $out = curl_exec($curl); // Инициируем запрос к API и сохраняем ответ в переменную
		    
		    curl_close($curl); // Завершаем сеанс cURL
		    
		    return $out;
		}

		public function request($data, $session_id, $type_name)
		{
		    $link = 'https://' . $this->subdomain . '.amocrm.ru/api/v2/'.$type_name;

		    $headers[] = "Accept: application/json";

		     //Curl options
		    $curl = curl_init();
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
		    curl_setopt($curl, CURLOPT_USERAGENT, "amoCRM-API-client-
		    undefined/2.0");
		    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
		    curl_setopt($curl, CURLOPT_URL, $link);
		    curl_setopt($curl, CURLOPT_HEADER,false);
		    curl_setopt($curl,CURLOPT_COOKIEFILE, $session_id."cookie.txt");
		    curl_setopt($curl,CURLOPT_COOKIEJAR, $session_id."cookie.txt");
		    $out = curl_exec($curl);
		    curl_close($curl);
		    $result = json_decode($out,TRUE);

		    unlink($session_id.'cookie.txt'); // удаляем файл куки

		    return $result;
		}

		public function get_custom_fields($session_id)
		{
			$link = 'https://' .$this->subdomain. '.amocrm.ru/api/v2/account?with=custom_fields';

		    $curl = curl_init(); 

		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
		    curl_setopt($curl, CURLOPT_URL, $link);
		    curl_setopt($curl, CURLOPT_HEADER, false);
		    curl_setopt($curl, CURLOPT_COOKIEFILE, $session_id.'cookie.txt'); 
		    curl_setopt($curl, CURLOPT_COOKIEJAR, $session_id.'cookie.txt'); 
		    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		    $out1 = curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
		    curl_close($curl);


		    $res = json_decode($out1,true);
		    return $res;
		}

		public function get_accounts($session_id)
		{
			$link = 'https://' .$this->subdomain. '.amocrm.ru/api/v2/contacts';

		    $curl = curl_init(); 

		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
		    curl_setopt($curl, CURLOPT_URL, $link);
		    curl_setopt($curl, CURLOPT_HEADER, false);
		    curl_setopt($curl, CURLOPT_COOKIEFILE, $session_id.'cookie.txt'); 
		    curl_setopt($curl, CURLOPT_COOKIEJAR, $session_id.'cookie.txt'); 
		    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		    $out1 = curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
		    curl_close($curl);


		    $res = json_decode($out1,true);
		    return $res;
		}
	}