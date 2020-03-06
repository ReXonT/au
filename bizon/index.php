<?php

require('bizon.class.php');
require('functions.php');

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
                'type' => 12
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
                'desc' => 'Пример: 20578:subarenda <br><br>
                Советую заполнить все поля ниже. Поиск будет идти до первого нахождения по любому из полей',    // описание поля, можно пару строк
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
                'desc' => 'Найдет сходство телефонов вида +79991112233, 79991112233, 89991112233, 9991112233',    // описание поля, можно пару строк
            ],
            'viewers_email' => [
                'title' => 'Email',   // заголовок поля
                'default' => '',
                'show' => [
                    'option' => [1],
                    'viewers_extype' => [1,2]
                ],
                'desc' => 'Поиск на точное соответствие',    // описание поля, можно пару строк
            ],
            'viewers_username' => [
                'title' => 'Имя зрителя',   // заголовок поля
                'default' => '',
                'show' => [
                    'option' => [1],
                    'viewers_extype' => [1,2]
                ],
                'desc' => 'Регистр не важен. Совпадения по этому полю минимальные, потому что человек может указать что угодно.',    // описание поля, можно пару строк
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
    
    $ps = $_REQUEST['paysys']['ps'];            // Сюда придут настройки выбранной системы

    $session_id = $ps['id'];       // id сессии

    $admin = [
    	'username' => $ps['options']['account'],
    	'password' => $ps['options']['secret']
	];

    $cookie = 'cookies/'.$session_id.'cookie.txt';  // адрес создания куки файла

    /* Авторизация */
    $bizon = new Bizon($cookie);
    $auth = $bizon->auth($admin);

    preg_match('/Успешная авторизация/', $auth['message'], $match);
    
    
    if(empty($match))
    {
        $log .= $auth['message'].' Количество попыток осталось: '.$auth['availAttempts'].'<br>';
        closeScript($log);
        exit();
    }
    else
    {
        $log .= 'Успешная авторизация'.'<br>';
        /* Инициализация переменных */
        $option = $options['option']; // с чем работаем
        $write_type = $options['write_type']; // перевод в текст
        $result = "";  // строка для вывода результата работы

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
                $result = 'Ошибка выбора типа действия';
                break;
        }

        /* + Инициализация переменных, учитывая то, с кем работаем */
        $extype = $options[$option_name.'_extype'];   // задача запроса
        $get_type = $options[$option_name.'_get_type']; // тип получения информации
        $last_num = $options[$option_name.'_last_num']; // номер веба с конца
        $search_type = $options[$option_name.'_search_type']; // тип поиска по вебинарам (автовеб, веб или везде)
        
        /* Инициализируем данные по зрителю */ 
        $viewer = [
            'username' => $options[$option_name.'_username'],
            'phone' => $options[$option_name.'_phone'],
            'email' => $options[$option_name.'_email'],
            'referer' => $options[$option_name.'_referer']  // источник
        ];

        $web = [
            'date' => $options[$option_name.'_date'],     // дата вебинара
            'time' => $options[$option_name.'_time'],     // время вебинара
            'room' => $options[$option_name.'_room_id']       // ID комнаты вебинара
        ];

        /* Указываем какие данные из карточки зрителя нам нужны */
        $return_keys = [
            'username', // имя (+фамилия) пользователя
            'phone',    // телефон
            'email',    // email
            'clickBanner', // был ли клик по баннеру
            'clickFile',    // был ли клик по кнопке
            'chatUserId',    // айди юзера в чате
            'referer'   // источник
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

                if( isset($params['webinarId']) )
                {
                    $log .= 'Найден вебинар с webId: '.$params['webinarId'].'<br>';
                    
                    $web_info = $bizon->call($method, $params);

                    /* Получаем инфо по нужному вебинару */
                    if( !isset($web_info['message']) )
                    {

                        /* Работаем со зрителями */
                        if($option_name == 'viewers')
                        {
                            // проверяем: есть ли хоть 1 поле для поиска
                            foreach ($viewer as $key => $value) 
                            {
                                if( !empty($value) )
                                {
                                    $viewer_to_find[$key] = $value;
                                }    
                            }

                            if( isset($viewer_to_find) )
                            {
                                // получаем массив данных о зрителях выбранного веба
                                $users_info = $bizon->call( $bizon_methods['getviewers'], $params );

                                if( !isset($users_info['message']) )
                                {
                                    /* Получаем массив юзеров на вебинаре */
                                    $users = getUsersFromInfo($users_info, $return_keys);

                                    /* Ищем зрителя на вебинаре */
                                    if($viewers_method == 2)
                                    {

                                        $s = 0; // стоп-переменная
                                        
                                        // Перебираем данные из зрителей (email, username, phone)
                                        foreach ($users as $value) //  === 1 ===
                                        {
                                            foreach ($value as $k => $v) 
                                            {
                                                // Если есть такое поле в исходном поиске
                                                if( isset($viewer_to_find[$k]) )
                                                {
                                                    // приводим к нижнему регистру оба текста
                                                    $v = mb_strtolower($v);
                                                    $viewer_to_find[$k] = mb_strtolower($viewer_to_find[$k]);

                                                    if($k == 'phone')
                                                    {
                                                        /* Приводим оба числа в формат (начинаем с 9) */ 
                                                        if($v[0] == '+' || $v[0] == 8 || $v[0] == 7)
                                                        {
                                                            $v = mb_substr($v, 1);
                                                            if($v[0] == 7)
                                                                $v = mb_substr($v, 1);
                                                        }

                                                        if($viewer_to_find[$k][0] == '+' || $viewer_to_find[$k][0] == 8 || $viewer_to_find[$k][0] == 7)
                                                        {
                                                            $viewer_to_find[$k] = mb_substr($viewer_to_find[$k], 1);
                                                            if($viewer_to_find[$k][0] == 7)
                                                                $viewer_to_find[$k] = mb_substr($viewer_to_find[$k], 1);
                                                        }                                                       
                                                    }

                                                    preg_match( '/'.$viewer_to_find[$k].'/', $v, $match );
                                                    if( !empty($match) && isset($match) )
                                                    {
                                                        $user = $value;
                                                        $log .= 'Нашли по '.$k.'<br>';
                                                        $s = 1;
                                                        break;
                                                    }
                                                } // end if
                                            }

                                            if($s) break;
                                        } //  end foreach 1

                                    } // end метода поиска зрителя на вебинаре 
                                }
                                else 
                                {
                                    $log .= 'Ошибка запроса к Бизон365: '.$users_info['message'].'<br>';
                                    closeScript($log);
                                    exit();
                                }                     
                            } // end if isset
                            else
                            {
                                $log .= 'Не указаны поля для поиска <br>';
                                closeScript($log);
                                exit();
                            }
                            
                            if(!$s) $result = 'Не найден такой зритель';

                        } //  end if ($option_name == 'viewers')
                    }   // end if isset web_info
                    else 
                    {
                        $log .= 'Ошибка запроса к Бизон365: '.$web_info['message'].'<br>';
                        closeScript($log);
                        exit();
                    }
                } // end if ( isset $params['webinarId'] )
                else $log .= 'Такой вебинар не найден <br>';
                break;

            /* Получаем вебинар по конкретным датам */
            case 2:
                // перевод в нужный формат даты
                $tmp = explode('.', $web['date']);
                $web['date'] = implode('-', $tmp);

                $webinar_id = $web['room'].'*'.$web['date'].'T'.$web['time'];

                $log .= 'Установлен webId: '.$webinar_id.'<br>';

                if($_REQUEST['test'])
                {
                    $webinar_id = '20578:prav_rody*2020-02-21T17:02:42';
                }

                $params['webinarId'] = $webinar_id;

                /* Получаем инфо по нужному вебинару */
                try
                {
                    $web_info = $bizon->call($method, $params);
                }
                catch (Exception $e)
                {
                    $log .= $e->getMessage().'<br>';
                }

                if( isset($web_info) )
                {
                    $log .= 'Получены данные по webId <br>';
                } 
                else
                {
                    $log .= 'Ошибка запроса по webId <br>';
                    closeScript($log);
                    exit();
                }

                break;

            /* Получаем данные по списку последних вебинаров. 
            Учитывая поиск по автовебам/живым */
            case 3:

                $web_list = $bizon->call($bizon_methods['getlist'], $params);


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
        } // end switch($extype)


        if($_REQUEST['debug'])
        {
            echo '<pre>';
            print_r($web_list);
            echo '</pre>';
        }

        switch ($option) {
            // зрители
            case 1:
                if( $write_type )
                {
                    $result .= '<br>';
                    foreach ($user as $key => $value) 
                    {
                        $russian_key = russianName($key);
                        $result .= $russian_key.': '.$value.'<br>';
                    }
                }
                break;
            // вебинары
            case 2:
                /* Создаем строку для вывода данных для метода getviwers */
                if( $write_type && $method == $bizon_methods['getviewers'] )
                {     
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
                                        $result .= $russian_key.": ".$v.'<br>';
                                    }
                                }
                            }
                        }
                        $result .= '<br><br>';
                    }
                    $result .= "Общее число зрителей: ".$web_info['total'];
                }
                /* Создаем строку для вывода данных для метода get */
                else if( $write_type && $method == $bizon_methods['get'] )
                {
                    foreach ($web_info['report'] as $key => $value) 
                    {
                        // Пропускаем большие массивы данных
                        if($key == 'report' || $key == 'messages' || $key == 'messagesTS')
                            continue;

                        $result .= $key.": ".$value.'<br>';
                    }
                }
                else // иначе в результат отправляем весь массив данных
                {
                    $result = $web_info;
                }
                break;
        }
    } // end else успешной авторизации

    $out = 1;

    unlink($cookie);           // удаляем файл куки

    $responce = [
        'out' => $out,         // Обязательно должен быть номер выхода out, отличный от нуля!
        
        'value' => [           // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через $bN_value.ваши_ключи_массива
            'result' => $result,     // где N - порядковый номер блока в схеме
            'user' => $user,
            'log' => $log,
            'web_info' => $web_info,
        ]
    ];

} elseif($act == '') {
    // Действие не задано, и что же нам сделать? Станцевать вальс, попрыгать, пойти в гости к Кролику?

} elseif($act == 'man') {
    $responce = [
        'html' =>'###Доступные переменные:

        **{b.{bid}.value.result}** - результат выполнения
        **{b.{bid}.value.log}** - строка логирования ошибок
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

