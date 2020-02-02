<?php

require_once('amo_class.php');

$act = $_REQUEST['act'];

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Режим OPTIONS - в котором ВРМ создаёт в схеме блок управления  *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

if($act == 'options') {
    $responce = [
        'title' => 'ВРМ AmoCRM',      // Это заголовок блока, который будет виден на схеме
        'paysys' => [                   // Группа полей, отвечающая за интеграцию с платёжными системами и внешними сервисами.
            'ps' => [                   // ВРМ получит доступ к ID аккаунта, секретному ключу и другим атрибутам выбранной системы
                'title' => 'Bitrix24',
                'type' => 8
            ]
        ],
        'vars' => [                     // переменные, которые можно будет настроить в блоке
            'exec_type' => [
                'title' => 'Выбор действия',   // заголовок поля
                'values' => [
                	1 => 'Добавить сделку',
                	2 => 'Удалить сделку'
                ],
                'desc' => '',    // описание поля, можно пару строк
            ],
            'name' => [
                'title' => 'Имя сделки',
                'desc' => 'Обязательно'
            ],
            'sale' => [
                'title' => 'Бюджет',
                'desc' => ''
            ],
            'responsible_user_id' => [
                'title' => 'ID ответственного',
                'desc' => ''
            ],
            'tags' => [
                'title' => 'Теги',
                'desc' => 'Не обязательно'
            ],
        ],
        'out' => [                      // Это блоки выходов, мы задаём им номера и подписи (будут видны на схеме)
            1 => [                      // Номер 0 означает красный выход блока ВРМ, зарезервированный для случаев сбоя
                'title' => 'Найдено',    // название выхода 1
            ]        
        ]
    ];

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Режим RUN - в котором ВРМ получает, обрабатывает и возвращает  *
 * полученные от схемы данные                                   *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
} elseif($act == 'run') {              // Схема прислала данные, обрабатываем

    $target = $_REQUEST['target'];  // Пользователь, от имени которого выполняется блок
    $ums    = $_REQUEST['ums'];     // Данные об активности пользователя, массив в котором есть
                                    // id - номер элемента (комментария, поста, смотря о чём речь в активности)
                                    // from_id - UID пользователя
                                    // date - дата в формате timestamp
                                    // text - текст комментария, сообщения и т.д.
    $out    = 0;                    // Номер выхода по умолчанию. Если дальнейший код не назначит другой выход - значит что-то не так
    $options = $_REQUEST['options'];
    $session_id = $_REQUEST['uid'];


    $user_login = 'lpwebinar@yandex.ru';
    $user_hash = '630dcb876794a2db5732262dc2240c8b2a2f4d49';
    $subdomain = 'lpwebinar'; #Наш аккаунт - поддомен

    $amo = new Amo($user_login, $user_hash, $subdomain);
    
    // Авторизация
    $response = $amo->auth($session_id);
    $response = json_decode($response, true);
    $response = $response['response'];  
    if (isset($response['auth'])) // Флаг авторизации доступен в свойстве "auth"
    {
        echo '<pre>';
        print_r($response);
        echo '</pre>';
        echo 'Авторизация прошла успешно';
    }else echo 'Авторизация не удалась';
    //

    $type_name = 'deal';

    $exec_type = $options['exec_type'];

    $field_names = [
        'name',
        'tags',
        'sale',
        'responsible_user_id'
    ];

    foreach ($field_names as $value) {
        ${$type_name.'_'.$value} = $options[$value];
    }

    switch ($exec_type) {
        case 1:
            // Добавить сделку
            $exec_name = 'add';
            break;

        case 2:
            // Удалить сделку
            $exec_name = 'del';
            break;
        
        default:
            // code...
        $exec_name = 'add';
            break;
    }


    foreach ($field_names as $field_name) 
    {
        if(!empty(${$type_name.'_'.$field_name}))
        {
            ${$type_name}[$exec_name][0][$field_name] = ${$type_name.'_'.$field_name};
        }
    }

    switch ($exec_type) {
        case 1:
            // Добавить сделку
            $result = $amo->addDeal($deal, $session_id);
            break;

        case 2:
            // Удалить сделку
            $exec_name = 'del';
            break;
        
        default:
            // code...
            break;
    }
    
    var_dump($result);
    
    $new_deal_id = $result['_embedded']['items'][0]['id'];


    $responce = [
        'out' => $out,         // Обязательно должен быть номер выхода out, отличный от нуля!
        
        'value' => [           // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через $bN_value.ваши_ключи_массива
            'result' => $result,     // где N - порядковый номер блока в схеме
            'id' => $new_deal_id
        ]
    ];

} elseif($act == '') {
    // Действие не задано, и что же нам сделать? Станцевать вальс, попрыгать, пойти в гости к Кролику?

}

// Отдать JSON, не кодируя кириллические символы в кракозябры
echo json_encode($responce, JSON_UNESCAPED_UNICODE);