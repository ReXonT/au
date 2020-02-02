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
                'title' => 'AmoCRM',
                'type' => 8
            ]
        ],
        'vars' => [                     // переменные, которые можно будет настроить в блоке
            'method_type' => [
                'title' => 'С чем работаем?',   // заголовок поля
                'values' => [
                    1 => 'Сделки',
                    2 => 'Контакты'
                ],
                'desc' => '',    // описание поля, можно пару строк
            ],
            'exec_type' => [
                'title' => 'Выбор действия',   // заголовок поля
                'values' => [
                    1 => 'Добавить',
                    2 => 'Обновить'
                ],
                'desc' => '',    // описание поля, можно пару строк
            ],

            'name' => [
                'title' => 'Имя',
                'desc' => '',
                'show' => [
                    'method_type' => [1,2],
                    'exec_type' => [1,2]
                ]
            ],

            // сделки
            'id' => [
                'title' => 'ID',
                'desc' => 'Обязательно',
                'show' => [
                    'method_type' => [1],
                    'exec_type' => [2]
                ]
            ],
            'status_id' => [
                'title' => 'ID статуса сделки',
                'desc' => 'Обязательно (1 - новая)',
                'default' => '1',
                'show' => [
                    'method_type' => [1],
                    'exec_type' => [2]
                ]
            ],
            
            'sale' => [
                'title' => 'Бюджет',
                'desc' => '',
                'show' => [
                    'method_type' => [1],
                    'exec_type' => [1,2]
                ]
            ],
            'responsible_user_id' => [
                'title' => 'ID ответственного',
                'desc' => '',
                'show' => [
                    'method_type' => [1],
                    'exec_type' => [1,2]
                ]
            ],
            'tags' => [
                'title' => 'Теги',
                'desc' => 'Не обязательно',
                'show' => [
                    'method_type' => [1],
                    'exec_type' => [1,2]
                ]
            ],


            // контакты
            'phone' => [
                'title' => 'Телефон',
                'desc' => '',
                'show' => [
                    'method_type' => [2],
                    'exec_type' => [1,2]
                ]
            ],
            'email' => [
                'title' => 'Email',
                'desc' => '',
                'show' => [
                    'method_type' => [2],
                    'exec_type' => [1,2]
                ]
            ],

        ],
        'out' => [                      // Это блоки выходов, мы задаём им номера и подписи (будут видны на схеме)
            1 => [                      // Номер 0 означает красный выход блока ВРМ, зарезервированный для случаев сбоя
                'title' => 'Результат',    // название выхода 1
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
    $session_id = $ums['id'];


    $user_login = 'lpwebinar@yandex.ru';
    $user_hash = '630dcb876794a2db5732262dc2240c8b2a2f4d49';
    $subdomain = 'lpwebinar'; // Наш аккаунт - поддомен

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

    


    $method_type = $options['method_type']; // с чем работаем (сделки, контакты ...)

    switch ($method_type) {
        case 1:
            // Сделки...
            $type_name = 'leads';
            break;

        case 2:
            // Сделки...
            $type_name = 'contacts';
            break;

    }

    
    $cab_custom_fields = $amo->get_custom_fields($session_id);

    // находим id поля vk_uid
    foreach ($cab_custom_fields['_embedded']['custom_fields'][$type_name] as $value) {
            if($value['name'] == 'vk_uid')
                ${'field_'.$type_name.'_vk_uid_id'} = $value['id'];
            
            if($value['name'] == 'Телефон')
                ${'field_'.$type_name.'_phone'} = $value['id'];

            if($value['name'] == 'Email')
                ${'field_'.$type_name.'_email'} = $value['id'];
    }

    $exec_type = $options['exec_type'];

    $field_names = [
        'name',
        'tags',
        'sale',
        'responsible_user_id',
        'id',
        'status_id',
        'phone',
        'email'
    ];

    /* Инициализация переменных из исходника
        пример:
        $leads_name
        $leads_id
        ....
    */
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
            $exec_name = 'update';
            break;
        
        default:
            // code...
        $exec_name = 'add';
            break;
    }

    // формируем массив на создание/изменение сущности
    /*
        пример:
        $leads['add'][0]['name'] = $leads_name;
        $leads['add'][0]['id'] = $leads_id;
        ....
    */
    foreach ($field_names as $field_name) 
    {
        if(!empty(${$type_name.'_'.$field_name}))
        {
            ${$type_name}[$exec_name][0][$field_name] = ${$type_name.'_'.$field_name};
        }
    }

    // добавляем дату изменения
    ${$type_name}[$exec_name][0]['updated_at'] = time();


    // ставим vk_uid
    ${$type_name}[$exec_name][0]['custom_fields'] = [
        [
            'id' => ${'field_'.$type_name.'_vk_uid_id'},
            'values' => [
                [
                'value' => 'au'.$target,
                ],
            ],
        ],
    ];


    switch ($exec_type) 
    {
        case 1:
            // Добавить
            if($method_type == 2)   // если контакт
            {
                // Проставляем телефон
                $a = 
                [
                    'id' => ${'field_'.$type_name.'_phone'},
                    'values' => [
                        [
                            'value' => ${$type_name.'_phone'},
                            'enum' => 'MOB'
                        ],
                    ],
                
                ];

                array_push(${$type_name}[$exec_name][0]['custom_fields'], $a);

                // Проставляем email
                $a = 
                [
                    'id' => ${'field_'.$type_name.'_email'},
                    'values' => [
                        [
                            'value' => ${$type_name.'_email'},
                            'enum' => 'WORK'
                        ],
                    ],
                ];

                array_push(${$type_name}[$exec_name][0]['custom_fields'], $a);
            }

            $result = $amo->request(${$type_name}, $session_id, $type_name);
            $new_id = $result['_embedded']['items'][0]['id'];
            break;

        case 2:
            // Удалить
            $result = $amo->request(${$type_name}, $session_id, $type_name);
            break;
        
        default:
            // code...
            break;
    }
    
    var_dump($result);
    
    
    $out = 1;
    

    $responce = [
        'out' => $out,         // Обязательно должен быть номер выхода out, отличный от нуля!
        
        'value' => [           // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через $bN_value.ваши_ключи_массива
            'id' => $new_id
        ]
    ];

} elseif($act == '') {
    // Действие не задано, и что же нам сделать? Станцевать вальс, попрыгать, пойти в гости к Кролику?
}

// Отдать JSON, не кодируя кириллические символы в кракозябры
echo json_encode($responce, JSON_UNESCAPED_UNICODE);