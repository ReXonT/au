<?php
class Senler
{
    
    /* Callback key Senler */
    protected $callback_key;

    /* ID группы ВКонтакте */
    protected $vk_group_id;

    public function __construct($callback, $group_id)
    {
        $this->callback_key = $callback;
        $this->vk_group_id = $group_id;
    }


    /* Формирование подписи. Взято из документации Senler */
    public function getHash($params, $secret)           
    { 
        $values = "";  
        foreach ($params as $value) {  
            $values .= (is_array($value) ? implode("", $value) : $value);  
        } 
        return md5($values . $secret); 
    }



    /* РАБОТА С ПОДПИСКАМИ SUBSCRIBERS */

    // добавление человека из группы подписок
    public function addSubscriber($senler_group_id, $user_id, $senler_utm_id)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id,  
                'subscription_id' => $senler_group_id,  
                'vk_user_id' => $user_id
            ];

        if(isset($senler_utm_id))
        {
            $params['utm_id'] = $senler_utm_id;
        }

        return $this->request('subscribers/add', $params);
    }

    // удаление подписчика из группы подписок
    public function deleteSubscriber($senler_group_id, $user_id)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id,  
                'subscription_id' => $senler_group_id,  
                'vk_user_id' => $user_id
            ];
        return $this->request('subscribers/del', $params);
    }

    // получение показателей подписок/отписок
    public function getStatSubscribe($date_from, $date_to, $subscription_id)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id,  
                'date_from' => $date_from,  
                'date_to' => $date_to,
                'subscription_id' => $subscription_id
            ];
        return $this->request('subscribers/StatSubscribe', $params);
    }

    // получение числа подписчиков группы
    public function getSubscribersFromGroup($subscription_id)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id, 
                'subscription_id' => $subscription_id
            ];
        return $this->request('subscribers/get', $params);
    }


    /* РАБОТА С БОТОМ BOTS */


    // добавить подписчика в бота
    public function addToBot($user_id, $bot_id)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id, 
                'vk_user_id' => $user_id,
                'bot_id' => $bot_id
            ];
        return $this->request('bots/AddSubscriber', $params);
    }

    // удалить подписчика из бота
    public function delFromBot($user_id, $bot_id)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id, 
                'vk_user_id' => $user_id,
                'bot_id' => $bot_id
            ];
        return $this->request('bots/DelSubscriber', $params);
    }


    /* РАБОТА С ПЕРЕМЕННЫМИ VARS */

    // установить переменную
    public function setVar($user_id, $var_name, $var_value)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id,
                'vk_user_id' => $user_id,
                'name' => $var_name,
                'value' => $var_value
            ];
        return $this->request('vars/set', $params);
    }

    // получить переменные
    public function getVar($user_id, $var_name)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id,
                'vk_user_id' => $user_id
            ];
        // если передано имя - ищем по имени
        if(isset($var_name))
        {
            $params['name'] = $var_name;
        }
        return $this->request('vars/get', $params);
    }

    // удалить переменные
    public function delVar($user_id, $var_name)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id,
                'vk_user_id' => $user_id,
                'name' => $var_name
            ];
        return $this->request('vars/del', $params);
    }


    /* РАБОТА С МЕТКАМИ UTM */

    // добавить метку
    public function addUtm($utm_name)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id,
                'name' => $utm_name
            ];
        return $this->request('utms/Add', $params);
    }

    // редактировать метку
    public function editUtm($utm_id, $utm_name)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id,
                'utm_id' => $utm_id,
                'name' => $utm_name
            ];
        return $this->request('utms/Edit', $params);
    }

    // удалить метку
    public function delUtm($utm_id)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id,
                'utm_id' => $utm_id
            ];
        return $this->request('utms/Del', $params);
    }

    // получить метки
    public function getUtm()
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id
            ];
        return $this->request('utms/Get', $params);
    }

    // получить ссылку для метки
    public function getUtmLink($utm_id, $subscription_id, $force)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id,
                'utm_id' => $utm_id,
                'subscription_id' => $subscription_id,
                'force' => $force
            ];
        return $this->request('utms/GetLink', $params);
    }

    // получить статистику меток
    public function getUtmStat($date_from, $date_to, $utm_id)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id,
                'date_from' => $date_from,
                'date_to' => $date_to,
                'utm_id' => $utm_id
            ];
        return $this->request('utms/StatCount', $params);
    }




    /* ДОП. ФУНКЦИИ */


    public function getErrorMessage($error_code)
    {
        $error_name = [
            0 => "Неизвестная ошибка",
            1 => "Один из обязательных параметров запроса отсутствуют",
            2 => "Hash отсутствует",
            3 => "Неправильный hash",
            4 => "Пользователь не разрешил отправку сообщений",
            5 => "Переданный пользователь не найден",
            6 => "Переданный идентификатор сообщества не найден",
            7 => "Переданный идентификатор группы не найден",
            8 => "Переданный идентификатор метки не найден",
            9 => "Слишком много обращений в секунду (максимум 20)",
            10 => "Переданный идентификатор бота не найден",
            11 => "Переменная должна содержать только латинские буквы или цифры",
        ];

        return $error_name[$error_code];
    }

    /* ОТПРАВКА + ПОЛУЧЕНИЕ ЗАПРОСА НА SENLER */

    public function request($request_name, array $params)
    {
        //добавляем hash к params
        $params['hash'] = $this->getHash($params, $this->callback_key);
        $myCurl = curl_init(); 
        curl_setopt_array($myCurl, [ 
            CURLOPT_URL => 'https://senler.ru/api/'.$request_name, 
            CURLOPT_RETURNTRANSFER => true, 
            CURLOPT_POST => true, 
            CURLOPT_POSTFIELDS => http_build_query($params) 
        ]); 
        $response = curl_exec($myCurl); 
        curl_close($myCurl);
        $answer = json_decode($response, true);
        return $answer;
    }
}