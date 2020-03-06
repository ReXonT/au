<?php

function wordToUniversalFormat ($word)
{
    $word = trim($word);
    $word = mb_strtolower($word);
    return $word;
}

/* Перевод ключей в понятные названия */
function russianName($key)
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
            'chatUserId' => 'ID в чате'
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