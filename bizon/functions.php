<?php

// Получает webinarId нужного вебинара (позиция вебинара указывается с конца списка)
function getLastWebId($list_of_webinars, $pos, $room_id = '')
{
    if ($room_id)
    {
        $i = 0;
        foreach ($list_of_webinars['list'] as $value)
        {
            // Если нашли нужную комнату
            if($value['name'] == $room_id)
            {
                // Перебираем позиции до нужной (с конца)
                if($i == $pos)
                    return $value['webinarId'];
                else $i++;
            }
        }
        return 'Не найдено';
    }

    return $list_of_webinars['list'][$pos]['webinarId'];
}

// Создает webinarId из введенных данных и даты проведения
function createWebIdByDate($room_id, $web_date, $web_time)
{
    $tmp = explode('.', $web_date);
    $web_date = implode('-', $tmp);

    return $room_id . '*' . $web_date . 'T' . $web_time;
}

// Получает список вебинаров конкретной комнаты
function getListFromRoom($list_of_webinars, $room_id)
{
    foreach ($list_of_webinars as $webinar)
    {
        if($webinar['name'] == $room_id)
        {
            $web_info[] = $webinar;
        }
    }

    return $web_info;
}

function wordToUniversalFormat ($word)
{
    $word = trim($word);
    $word = mb_strtolower($word);
    return $word;
}

function phoneFormat ($phone)
{
    if($phone[0] == '+' || $phone[0] == 8 || $phone[0] == 7)
    {
        $phone = mb_substr($phone, 1);
        if($phone[0] == 7)
            $phone = mb_substr($phone, 1);
    }
    return $phone;
}

/* Перевод ключей в понятные названия */
function russianName ($key)
{
    $name = [
            'ip' => 'IP',
            'city' => 'Город',
            'country' => 'Страна', 
            'email' => 'Email',
            'username' => 'Имя',
            'phone' =>  'Телефон',
            'finished' =>  'Дошел до конца',
            'view' =>  'Время входа UNIX',
            'viewTill' =>  'Время выхода UNIX',
            'page' => 'Страница регистрации',
            'partner' =>  'Refid партнера',
            'ban' =>  'Забанен?',
            'ignore' => 'В игнор?',
            'referer' =>  'Источник',
            'mob' => 'С мобильного?',
            'clickBanner' =>  'Клик по баннеру',
            'clickFile' => ' Клик по кнопке',
            'vizitForm' =>  'Открыта форма заказа',
            'newOrder' =>   'Номер оформленного заказа',
            'orderDetails' => 'Название товара в оформленном заказе', 
            'utm_source' => 'utm_source', 
            'utm_medium' => 'utm_medium', 
            'utm_campaign' => 'utm_campaign', 
            'utm_term' => 'utm_term',
            'utm_content' =>  'utm_content',
            'uid' => 'Идентификатор подписчика',
            'playVideo' => 'Запустил просмотр',
            'total' => 'Общее число зрителей',
            'viewers' => 'Зрители',
            'chatUserId' => 'ID в чате',
            'c1' => 'Доп. поле (vk id)',
            'cu1' => 'Свой url (vk id)'
    ];
    return $name[$key];

}

/* Получаем массив с данными зрителей по нужным значениям */
function getUsersFromInfo ($users_info, $return_keys)
{
    
    $i = 0;
    foreach ($users_info['viewers'] as $value) 
    {
        foreach ($value as $k => $v) 
        {
            // Если есть значение в поле
            if($v != "")
            {
                // Ищем только те, что нам нужны
                foreach ($return_keys as $r_k) 
                {
                    if($k == $r_k)
                    {
                        $users[$i][$k] = $v;
                    }
                }
            }
        }
        $i++;
    }

    return $users;
}

function closeScript ($log)
{
    $responce = [
        'out' => 2,         // Обязательно должен быть номер выхода out, отличный от нуля!
        
        'value' => [           // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через $bN_value.ваши_ключи_массива
            'log' => $log
        ]
    ];

    // Отдать JSON, не кодируя кириллические символы в кракозябры
    echo json_encode($responce, JSON_UNESCAPED_UNICODE);
}