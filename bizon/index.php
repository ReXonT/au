<?php

require('bizon.class.php');

$act = $_REQUEST['act'];

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Режим OPTIONS - в котором ВРМ создаёт в схеме блок управления  *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

if($act == 'options') {
    $responce = [
        'title' => 'ВРМ Бизон365',      // Это заголовок блока, который будет виден на схеме
        'paysys' => [                   // Группа полей, отвечающая за интеграцию с платёжными системами и внешними сервисами.
            'ps' => [                   // ВРМ получит доступ к ID аккаунта, секретному ключу и другим атрибутам выбранной системы
                'title' => 'Интеграция',
                'type' => 6
            ]
        ],
        'vars' => [                     // переменные, которые можно будет настроить в блоке
            'option' => [
                'title' => 'С чем работаем',   // заголовок поля
                'values' => [
                    1 => 'Зрители',
                    2 => 'Вебинары',
                ],
                'desc' => '',    // описание поля, можно пару строк
            ],

            /* ==== Поля для зрителей ==== */    
            'viewers_method' => [
                'title' => 'Выбор действия',   // заголовок поля
                'values' => [
                    1 => 'Получить сообщения зрителя с вебинара',
                    2 => 'Проверить присутствие на вебинаре'
                ],
                'show' => [
                    'option' => [1]
                ],
                'desc' => '',    // описание поля, можно пару строк
            ],
            'viewers_extype' => [
                'title' => 'В каком вебинаре',   // заголовок поля
                'values' => [
                    1 => 'Поиск по недавнему вебинару',
                    2 => 'Поиск вебинара по дате'
                ],
                'show' => [
                    'option' => [1]
                ],
                'desc' => '',    // описание поля, можно пару строк
            ], 
            'viewers_search_type' => [
                'title' => 'Как искать',
                'values' => [
                    1 => 'Все вебинары',
                    2 => 'Только живые',
                    3 => 'Только автовебинары'
                ],
                'show' => [
                    'option' => [1],
                    'viewers_extype' => [1,2]
                ] 
            ],
            'viewers_last_num' => [
                'title' => 'Какой вебинар получить',   // заголовок поля
                'values' => [
                    0 => '1 с конца (самый недавний)',
                    1 => '2 с конца',
                    2 => '3 с конца',
                ],
                'show' => [
                    'option' => [1],
                    'viewers_extype' => [1]
                ],
                'desc' => '',    // описание поля, можно пару строк
            ],
            'viewers_date' => [
                'title' => 'Дата вебинара',   // заголовок поля
                'default' => '2020.02.03',
                'show' => [
                    'option' => [1],
                    'viewers_extype' => [2]
                ],
                'desc' => 'Формат: год.месяц.день',    // описание поля, можно пару строк
            ],
            'viewers_time' => [
                'title' => 'Время начала вебинара',   // заголовок поля
                'default' => '20:00:00',
                'show' => [
                    'option' => [1],
                    'viewers_extype' => [2]
                ],
                'desc' => 'Формат: час:минута:секунда',    // описание поля, можно пару строк
            ],
            'viewers_room_id' => [
                'title' => 'ID комнаты',   // заголовок поля
                'default' => '',
                'show' => [
                    'option' => [1],
                    'viewers_extype' => [1,2]
                ],
                'desc' => 'Пример: 20578:subarenda',    // описание поля, можно пару строк
            ],
            'viewers_referer' => [
                'title' => 'Данные в источнике',   // заголовок поля
                'default' => '',
                'show' => [
                    'option' => [1],
                    'viewers_extype' => [1,2]
                ],
                'desc' => 'Например, укажите здесь id пользователя, которого ищем. Если вы давали ему ссылку с его id (по инструкции)',    // описание поля, можно пару строк
            ],
            'viewers_phone' => [
                'title' => 'Телефон',   // заголовок поля
                'default' => '',
                'show' => [
                    'option' => [1],
                    'viewers_extype' => [1,2]
                ],
                'desc' => 'Советую заполнить все поля. Поиск будет идти до первого нахождения по любому из полей',    // описание поля, можно пару строк
            ],
            'viewers_email' => [
                'title' => 'Email',   // заголовок поля
                'default' => '',
                'show' => [
                    'option' => [1],
                    'viewers_extype' => [1,2]
                ],
                'desc' => '',    // описание поля, можно пару строк
            ],
            'viewers_username' => [
                'title' => 'Имя зрителя',   // заголовок поля
                'default' => '',
                'show' => [
                    'option' => [1],
                    'viewers_extype' => [1,2]
                ],
                'desc' => 'Совпадения по этому полю минимальные. 
                Так как человек может указать что угодно.',    // описание поля, можно пару строк
            ],


            /* ==== Вебинарные поля ==== */ 
            'webinar_extype' => [
                'title' => 'Выбор действия',   // заголовок поля
                'values' => [
                    1 => 'Получить информацию о недавнем вебинаре',
                    2 => 'Получить информацию о вебинаре по дате',
                	3 => 'Получить информацию обо всех вебинарах',
                ],
                'show' => [
                    'option' => [2]
                ],
                'desc' => '',    // описание поля, можно пару строк
            ],     
            'webinar_get_type' => [
                'title' => 'Тип получения',
                'values' => [
                    1 => 'Полный отчет',
                    2 => 'Список зрителей'
                ],
                'show' => [
                    'option' => [2],
                    'webinar_extype' => [1,2]
                ]
            ],
            'webinar_search_type' => [
                'title' => 'Как искать',
                'values' => [
                    1 => 'Все вебинары',
                    2 => 'Только живые',
                    3 => 'Только автовебинары'
                ],
                'show' => [
                    'option' => [2],
                    'webinar_extype' => [1,3]
                ] 
            ],
            'webinar_last_num' => [
                'title' => 'Какой вебинар получить',   // заголовок поля
                'values' => [
                    0 => '1 с конца (самый недавний)',
                    1 => '2 с конца',
                    2 => '3 с конца',
                ],
                'show' => [
                    'option' => [2],
                    'webinar_extype' => [1]
                ],
                'desc' => '',    // описание поля, можно пару строк
            ],
            'webinar_date' => [
                'title' => 'Дата вебинара',   // заголовок поля
                'default' => '2020.02.03',
                'show' => [
                    'option' => [2],
                    'webinar_extype' => [2]
                ],
                'desc' => 'Формат: год.месяц.день',    // описание поля, можно пару строк
            ],
            'webinar_time' => [
                'title' => 'Время начала вебинара',   // заголовок поля
                'default' => '20:00:00',
                'show' => [
                    'option' => [2],
                    'webinar_extype' => [2]
                ],
                'desc' => 'Формат: час:минута:секунда',    // описание поля, можно пару строк
            ],
            'webinar_room_id' => [
                'title' => 'ID комнаты',   // заголовок поля
                'default' => '',
                'show' => [
                    'option' => [2],
                    'webinar_extype' => [1,2,3]
                ],
                'desc' => 'Пример: 20578:subarenda',    // описание поля, можно пару строк
            ],

            /* ==== Дополнительные поля ==== */ 
            'write_type' => [
                'title' => 'Вывести отчет в текст',   // заголовок поля
                'format' => 'checkbox',
                'more' => 1,
                'default' => 0,
                'desc' => 'Попробовать вывести отчет в текстовую строку (не все поля)',    // описание поля, можно пару строк
            ]

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
    $session_id = $ums['id'];       // id сессии

    $ps = $_REQUEST['paysys']['ps'];            // Сюда придут настройки выбранной системы

    $admin = [
    	'username' => 'lpwebinar@yandex.ru',
    	'password' => 'BarkasPW303'
	];

    $cookie = 'cookies/'.$session_id.'cookie.txt';  // адрес создания куки файла

    /* Авторизация */
    $bizon = new Bizon($cookie);
    $auth = $bizon->auth($admin);

    /* Инициализация переменных */
    $option = $options['option']; // с чем работаем
    $write_type = $options['write_type']; // перевод в текст
    $str = "";  // строка для вывода текста

    /* + Выбираем с кем работаем */
    switch ($option) {
        case 1:
            $option_name = 'viewers';
            $viewers_method = $options['viewers_method'];
            break;

        case 2:
            $option_name = 'webinar';
            break;
        default:
            $str = 'Ошибка выбора типа действия';
            break;
    }

    /* + Инициализация переменных, учитывая то, с кем работаем */
    $extype = $options[$option_name.'_extype'];   // задача запроса
    $get_type = $options[$option_name.'_get_type']; // тип получения информации
    $last_num = $options[$option_name.'_last_num']; // номер веба с конца
    $search_type = $options[$option_name.'_search_type']; // тип поиска по вебинарам (автовеб, веб или везде)

    $web = [
        'date' => $options[$option_name.'_date'],     // дата вебинара
        'time' => $options[$option_name.'_time'],     // время вебинара
        'room' => $options[$option_name.'_room_id']       // ID комнаты вебинара
    ];

    /* Инициализация доступных методов по работе с Бизон365 */
    $bizon_methods = [
        'get' => 'get',
        'getviewers' => 'getviewers',
        'getlist' => 'getlist'
    ];


    /* Выбираем тип поиска по Живым/Автовебинарам */
    switch ($search_type) {
        // искать везде
        case 1:
            $live_web = 1;
            $auto_web = 1;
            break;

        // только живые
        case 2:
            $live_web = 1;
            $auto_web = 0;
            break;

        // только автовебинары
        case 3:
            $live_web = 0;
            $auto_web = 1;
            break;
        
        default:
            $live_web = 1;
            $auto_web = 1;
            break;
    }

    /* Инициализуем массив с параметрами запроса к АПИ Бизон365 */
    $params = [
        'LiveWebinars' => $live_web,
        'AutoWebinars' => $auto_web
    ];

    /* Задаем $method выбранного типа */
    switch ($get_type) 
    {
        // полный отчет 
        case 1:
            $method = $bizon_methods['get'];
            break;

        // список зрителей
        case 2:
            $method = $bizon_methods['getviewers'];
            break;
        
        default:
            $method = $bizon_methods['get'];
            break;
    }

    /* 
    Основное выполнение. Поиск по вебинарам 
    Принцип:
    апи имеет толко 3 метода работы: get (отчет по вебинару (основные параметры)), getviewers (отчет по участникам) и getlist (получение всех вебинаров)

    Всё остальное - надстройка кодом.
    */
    switch ($extype) 
    {
        /* Получаем данные по недавним вебинарам */
        case 1:

            /* Получаем данные по списку последних вебинаров. 
            Учитывая поиск по автовебам/живым */
            $web_list = $bizon->call($bizon_methods['getlist'], $params); 

            /* Получаем webinarId по нужному вебинару */
            if( !empty($web['room']) && isset($web['room']) )
            {
                $i = 0;
                foreach ($web_list['list'] as $value) 
                {
                    if( $value['name'] == $web['room'] )
                    {
                        if($i == $last_num)
                        {
                            $params['webinarId'] = $value['webinarId'];
                            break;
                        }
                        else $i++;
                    }
                }
            }
            /* Получаем webinarId, считая от новейшего. Если нет привязки к комнате */
            else $params['webinarId'] = $web_list['list'][$last_num]['webinarId'];

            /* Получаем инфо по нужному вебинару */
            $web_info = $bizon->call($method, $params);

            /* Работаем со зрителями */
            if($option_name == 'viewers')
            {

                // получаем массив данных о зрителях выбранного веба
                $users_info = $bizon->call( $bizon_methods['getviewers'], $params );

                $return_keys = [
                    'username', // имя (+фамилия) пользователя
                    'phone',    // телефон
                    'email',    // email
                    'referer'   // источник
                ];

                /* Получаем массив с данными зрителей */
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
            
            }
            break;

        /* Получаем вебинар по конкретным датам */
        case 2:
            // перевод в нужный формат даты
            $tmp = explode('.', $web['date']);
            $web['date'] = implode('-', $tmp);

            $webinar_id = $web['room'].'*'.$web['date'].'T'.$web['time'];

            if($_REQUEST['test'])
            {
                $webinar_id = '20578:prav_rody*2020-02-21T17:02:42';
            }

            $params['webinarId'] = $webinar_id;

            /* Получаем инфо по нужному вебинару */
            $web_info = $bizon->call($method, $params);

            break;

        // получаем список последних вебов
        case 3:
            /* Получаем данные по списку последних вебинаров. 
            Учитывая поиск по автовебам/живым 

            Получает массив вида:
            [skip] => 0
            [limit] => 20
            [rooms] => Array
                (
                )

            [count] => 106
            [list] => Array
                (
                    [0] => Array
                        (
                            [_id] => 5e5eb3f2bd211614f9b6a8e1
                            [name] => 20578:subarenda
                            [text] => Завершен автовебинар 20578/20578:subarenda, начало: 03.03.2020 20:00, длительность: 166 минут, участников: 0
                            [type] => AutoWebinars
                            [nerrors] => 0
                            [count1] => 0
                            [count2] => 166
                            [data] => {"minutes":166,"roomid":"20578:subarenda","group":20578,"start":1583254800000,"stat":0}
                            [webinarId] => 20578:subarenda*2020-03-03T20:00:00
                            [created] => 2020-03-03T19:45:54.955Z
                        )
            */

            $method = $bizon_methods['getlist']; 

            $web_list = $bizon->call($method, $params);


            if( !empty($web['room']) && isset($web['room']) )
            {
                foreach ($web_list['list'] as $value) 
                {
                    if( $value['name'] == $web['room'] )
                    {
                        $web_info[] = $value;
                    }
                }
            }
            break;
    }


    if($_REQUEST['debug'])
    {
        echo '<pre>';
        print_r($web_list);
        echo '</pre>';
    }

    /* Создаем строку для вывода данных для метода getviwers */
    if( $write_type && $method == $bizon_methods['getviewers'] )
    {
        $return_keys = [
            'username', // имя (+фамилия) пользователя
            'phone',    // телефон
            'email',    // email
            'clickBanner', // был ли клик по баннеру
            'clickFile',    // был ли клик по кнопке
            'chatUserId',    // айди юзера в чате
            'referer'   // источник
        ];
        
        foreach ($web_info['viewers'] as $value) 
        {
            foreach ($value as $k => $v) 
            {
                // Если есть значение в поле
                if($v != "")
                {
                    // Ищем только те, что нам нужны
                    foreach ($return_keys as $r_k) 
                    {
                        // Если нашли - добавляем в строку
                        if($k == $r_k)
                        {
                            $russian_key = russianName($k);
                            $str .= $russian_key.": ".$v.'<br>';
                        }
                    }
                }
            }
            $str .= '<br><br>';
        }
        $str .= "Общее число зрителей: ".$web_info['total'];
    }

    /* Создаем строку для вывода данных для метода get */
    if( $write_type && $method == $bizon_methods['get'] )
    {
        foreach ($web_info['report'] as $key => $value) 
        {
            // Пропускаем большие массивы данных
            if($key == 'report' || $key == 'messages' || $key == 'messagesTS')
                continue;

            $str .= $key.": ".$value.'<br>';
        }
    }



    $out = 1;

    unlink($cookie);           // удаляем файл куки

    $responce = [
        'out' => $out,         // Обязательно должен быть номер выхода out, отличный от нуля!
        
        'value' => [           // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через $bN_value.ваши_ключи_массива
            'web_info' => $web_info,     // где N - порядковый номер блока в схеме
            'str' => $str,
            'params' => $params,
            'webId' => $webinar_id,
            'users' => $users
        ]
    ];

} elseif($act == '') {
    // Действие не задано, и что же нам сделать? Станцевать вальс, попрыгать, пойти в гости к Кролику?

} elseif($act == 'man') {
    $responce = [
        'html' =>
        'Доступные переменные:

        {b.{bid}.value.web_info} - массив, в котором содержатся все поля, полученные от Бизон365. Чтобы посмотреть то, что пришло - включите режим отладки.
        {b.{bid}.value.str} - текстовая строка отчета (если указано)
        '
    ];
}

// Отдать JSON, не кодируя кириллические символы в кракозябры
echo json_encode($responce, JSON_UNESCAPED_UNICODE);

/* 
Содержание ответа getviewers
"errors": [],
"viewers": [
    {
        "playVideo": 1,
        "phone": "+79005978548",
        "username": "Любовь Зайцева",
        "url": "https://start.bizon365.ru/room/20578/prav_rody",
        "ip": "109.172.47.124",
        "mob": true,
        "useragent": "Android, Chrome 62.0.3202.84",
        "referer": "https://away.vk.com/",
        "cu1": "",
        "p1": "",
        "p2": "",
        "p3": "",
        "roomid": "20578:prav_rody",
        "chatUserId": "SBDGuRJNU",
        "city": "Липецк",
        "country": "RU",
        "region": "Липецкая область",
        "tz": "",
        "created": "2020-02-23T10:46:41.079Z",
        "webinarId": "20578:prav_rody*2020-02-23T14:01:24",
        "view": 1582455715349,
        "viewTill": 1582461041435,
        "messages_num": 10
    },
    ...
],
"total": 14,
"skip": 0,
"limit": 1000,
"loaded": 14

Содержание ответа getlist
Array
(
    [skip] => 0
    [limit] => 20
    [rooms] => Array
        (
        )

    [count] => 106
    [list] => Array
        (
            [0] => Array
                (
                    [_id] => 5e5eb3f2bd211614f9b6a8e1
                    [name] => 20578:subarenda
                    [text] => Завершен автовебинар 20578/20578:subarenda, начало: 03.03.2020 20:00, длительность: 166 минут, участников: 0
                    [type] => AutoWebinars
                    [nerrors] => 0
                    [count1] => 0
                    [count2] => 166
                    [data] => {"minutes":166,"roomid":"20578:subarenda","group":20578,"start":1583254800000,"stat":0}
                    [webinarId] => 20578:subarenda*2020-03-03T20:00:00
                    [created] => 2020-03-03T19:45:54.955Z
                )
            ...
        )

)

*/

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