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

function findViewer(array $viewers, array $options)
{
    $options['cu1'] = trim($options['cu1']);
    if($options['viewers_add_fields'] == 'add') // Если также выбран поиск по доп. полям
    {
        $options_main = [
            'username' => Word::toUniversalFormat($options['username']),
            'c1' => trim($options['c1']),
            'phone' => phoneFormat($options['phone']),
            'email' => Word::toUniversalFormat($options['email']),
            'referer' => Word::toUniversalFormat($options['referer'])
        ];

        $options = array_merge($options, $options_main);
    }

    foreach ($viewers as $viewer)
    {
        $viewer['cu1'] = trim($viewer['cu1']);
        if($options['cu1'] && $options['cu1'] === $viewer['cu1'])
            return $viewer;

        if($options['viewers_add_fields'] == 'add') // Если также выбран поиск по доп. полям
        {
            $viewer_main = [
                'username' => Word::toUniversalFormat($viewer['username']),
                'c1' => trim($viewer['c1']),
                'phone' => phoneFormat($viewer['phone']),
                'email' => Word::toUniversalFormat($viewer['email']),
                'referer' => Word::toUniversalFormat($viewer['referer'])
            ];

            foreach ($viewer_main as $key)
                if($options[$key] && $options[$key] === $viewer[$key])
                    return $viewer;
        }
    }

    return false;
}

function findViewerByChatId(array $viewers, $chat_id, $kind_info = '')
{
    foreach ($viewers as $viewer)
    {
        if($viewer['chatUserId'] == $chat_id)
        {
            if(!$kind_info)
                return $viewer;
            return changeKindInfo($viewer, $kind_info);
        }
    }
}

function changeKindInfo($viewer, $kind_info)
{
    switch ($kind_info) // Выбираем тип получения информации
    {
        case 'main': // Получить основную информацию
            return $result = [
                'chat_id' => $viewer['chatUserId'],
                'username' => $viewer['username'],
                'vk_uid' => $viewer['cu1'],
                'add_field' => $viewer['c1'],
                'phone' => $viewer['phone'],
                'email' => $viewer['email']
            ];
            break;

        case 'cu1': // Получить свой URL параметр
            return $result = $viewer['cu1'];
            break;

        case 'c1': // Получить своё поле
            return $result = $viewer['c1'];
            break;
    }
}

function dataToText($data)
{
    $text = "";
    if(!is_array($data))
        $text = $data;
    else
    {
        foreach ($data as $item_key => $item)
        {
            if(!is_array($item))
                $text .= $item . ',';
            else
                foreach ($item as $key_value => $value)
                    if($value)
                        $text .= russianName($key_value) . ": " . $value . "\n";
                $text .= "\n";
        }
    }

    return trim($text,",\n");
}

function phoneFormat($phone)
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
        'chatUserId' => 'ID в чате', 'chat_id' => 'ID в чате',
        'c1' => 'Доп. поле (vk id)', 'add_field' => 'Доп. поле (vk id)',
        'cu1' => 'Свой url (vk id)', 'vk_uid' => 'Свой url (vk id)',
        '_id' => 'ID вебинара',
        'group' => 'Группа',
        'roomid' => 'ID комнаты',
        'webinarId' => 'ID вебинара',
        'ver' => 'Версия',
        'created' => 'Создан',
        'room_title' => 'Заголовок комнаты'
    ];
    return $name[$key];

}