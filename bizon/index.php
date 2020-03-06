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
                    2 => 'Проверить присутствие на вебинаре',
                    3 => 'Найти ключевое слово в сообщении у зрителя',
                    4 => 'Найти всех, кто написал ключевое слово'
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
                    'viewers_extype' => [1]
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
                    'option' => [1]
                ],
                'desc' => 'Например, укажите здесь id пользователя, которого ищем. Если вы давали ему ссылку с его id (по инструкции)',    // описание поля, можно пару строк
            ],
            'viewers_phone' => [
                'title' => 'Телефон',   // заголовок поля
                'default' => '',
                'show' => [
                    'option' => [1]
                ],
                'desc' => 'Найдет сходство телефонов вида +79991112233, 79991112233, 89991112233, 9991112233',    // описание поля, можно пару строк
            ],
            'viewers_email' => [
                'title' => 'Email',   // заголовок поля
                'default' => '',
                'show' => [
                    'option' => [1]
                ],
                'desc' => 'Поиск на точное соответствие',    // описание поля, можно пару строк
            ],
            'viewers_username' => [
                'title' => 'Имя зрителя',   // заголовок поля
                'default' => '',
                'show' => [
                    'option' => [1]
                ],
                'desc' => 'Регистр не важен. Совпадения по этому полю минимальные, потому что человек может указать что угодно.',    // описание поля, можно пару строк
            ],
            'viewers_keyword_stype' => [
                'title' => 'Тип поиска',   // заголовок поля
                'values' => [
                    1 => 'Есть хотя бы одно',
                    2 => 'Есть все слова',
                    3 => 'Точное соответствие'
                ],
                'default' => 1,
                'show' => [
                    'option' => [1],
                    'viewers_method' => [3,4]
                ],
                'desc' => '',    // описание поля, можно пару строк
            ],
            'viewers_keyword' => [
                'title' => 'Ключевое слово',   // заголовок поля
                'default' => '',
                'show' => [
                    'option' => [1],
                    'viewers_method' => [3,4]
                ],
                'desc' => 'Какое слово(слова) ищем. Через запятую',    // описание поля, можно пару строк
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
            ],
            2 => [
                'title' => 'Не найдено'
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
                $log .= 'Ошибка выбора типа действия';
                closeScript($log);
                exit();
                break;
        }

        /* + Инициализация переменных, учитывая то, с кем работаем */
        $extype = $options[$option_name.'_extype'];   // задача запроса
        $get_type = $options[$option_name.'_get_type']; // тип получения информации
        $last_num = $options[$option_name.'_last_num']; // номер веба с конца
        $search_type = $options[$option_name.'_search_type']; // тип поиска по вебинарам (автовеб, веб или везде)
        $keyword = $options[$option_name.'_keyword']; // ключевое слово на поиск
        $keyword_stype = $options[$option_name.'_keyword_stype']; // тип поиска ключа
        
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

        Результат выполнения switch: установленный $params['webinarId'], кроме case 3: там чистое выполнение
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
        

        /* Если работа с вебинарами */
        if($option_name == 'webinar')
        {
            /* Получаем инфо по нужному вебинару */
            $web_info = $bizon->call($method, $params);

            /* Если ошибка */
            if( isset($web_info['message']) )
            {
                $log .= 'Ошибка запроса к Бизон365: '.$web_info['message'].'<br>';
                closeScript($log);
                exit();
            }
        }

        /* Работаем со зрителями */
        if($option_name == 'viewers')
        {
            /* Проверяем: есть ли хоть 1 поле для поиска (указано ли в ВРМ) */
            foreach ($viewer as $key => $value) 
            {
                if( !empty($value) )
                {
                    $viewer_to_find[$key] = $value;
                }    
            }

            if( isset($viewer_to_find) )
            {
                /* Получаем массив данных о зрителях выбранного веба */
                $web_info = $bizon->call($bizon_methods['getviewers'], $params);
                
                if( !isset($web_info['message']) )
                {
                    /* Получаем массив юзеров на вебинаре */
                    $users = getUsersFromInfo($web_info, $return_keys);

                    $s = 0; // стоп-переменная

                    // Перебираем данные из зрителей
                    foreach ($users as $value) //  === 1 ===
                    {
                        foreach ($value as $k => $v) 
                        {     
                            // Если есть такое поле в исходном поиске
                            if( isset($viewer_to_find[$k]) )
                            {
                                // приводим к нижнему регистру оба текста
                                $v = wordToUniversalFormat($v);
                                $viewer_to_find[$k] = wordToUniversalFormat($viewer_to_find[$k]);

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

                                // Ищем вхождения
                                preg_match( '/'.$viewer_to_find[$k].'/', $v, $match );

                                if( !empty($match) && isset($match) )
                                {
                                    $user = $value;
                                    $result = 'Зритель найден';
                                    $log .= 'Нашли по '.$k.'<br>';
                                    $s = 1;
                                    break;
                                }
                            } // end if
                        }

                        if($s) break;
                    } //  end foreach 1 (перебор данных зрителей - поиск)

                    if(!$s) $result = 'Не найден такой зритель';

                    /* Если нам нужно найти сообщения */
                    if($viewers_method == 1 || $viewers_method == 3 || $viewers_method == 4)
                    {
                        /* Получаем массив данных о сообщениях выбранного веба */
                        $web_info = $bizon->call($bizon_methods['get'], $params);
                        $messages_json = $web_info['report']['messages'];
                        $messages_php = json_decode($messages_json, 1);
                        $messages = $messages_php[$user['chatUserId']];
                        if(!empty($messages))
                        {
                            $result = 'Сообщения зрителя найдены';
                        }
                        else
                        {
                            $log .= 'Не найдено сообщений зрителя <br>';
                            closeScript($log);
                            exit();
                        }

                        /* Если ищем ключевое слово в сообщении || Людей с ключевыми */
                        if($viewers_method == 3 || $viewers_method == 4)
                        {
                            // Если несколько слов указали в ВРМ
                            $much = 0;
                            if(strpos($keyword, ','))
                            {
                                $keyword = explode(',',$keyword);
                                $much = 1;
                            }

                            $k = 0; // стоп-переменная

                            // Если хотя бы 1 или все слова
                            if($keyword_stype == 1 || $keyword_stype == 2)
                            {
                                // Если ищем все слова
                                if($keyword_stype == 2 )
                                {
                                    // Считаем сколько слов
                                    $count = count($keyword);
                                    // Счетчик для вхождений
                                    $counter = 0;
                                }

                                // Цикл как обертка для 3 метода и ключевой для 4
                                foreach ($messages_php as $key => $messages) 
                                {
                                    // Если метод на поиск сообщений - ставим сразу
                                    if($viewers_method == 3)
                                    {
                                        $messages = $messages_php[$user['chatUserId']];
                                    }

                                    foreach ($messages as $value) 
                                    {
                                        // Перевод в универсальный формат (trim и mb_strtolower)
                                        $value = wordToUniversalFormat($value);
                                        // если много слов
                                        if($much)
                                        {
                                           foreach ($keyword as $v) 
                                           {
                                                $v = wordToUniversalFormat($v);

                                                preg_match('/'.$v.'/', $value, $match);

                                                if(!empty($match))
                                                {
                                                    // Если хотя бы 1
                                                    if($keyword_stype == 1)
                                                    {
                                                        if($viewers_method == 4)
                                                        {
                                                            $users_chat_ids[] = $key;
                                                        }
                                                        $result = 'Найдено';
                                                        $k = 1;
                                                        break;
                                                    }
                                                    elseif($keyword_stype == 2)
                                                    {
                                                        $counter++;
                                                    }
                                                    
                                                }
                                                if($keyword_stype == 2 && $counter == $count)
                                                {
                                                    if($viewers_method == 4)
                                                    {
                                                        $users_chat_ids[] = $key;
                                                    }
                                                    $result = 'Найдено';
                                                    $k = 1;
                                                    break;
                                                }
                                            } // end foreach $keyword
                                            if($k) break; 
                                        }
                                        else
                                        {
                                            $keyword = wordToUniversalFormat($keyword);
                                            preg_match('/'.$keyword.'/', $value, $match);
                                            if(!empty($match))
                                            {
                                                if($viewers_method == 4)
                                                {
                                                    $users_chat_ids[] = $key;
                                                }
                                                $result = 'Найдено';
                                                $k = 1;
                                                break;
                                            }
                                        } // end else
                                    } // end foreach $messages
                                    if($viewers_method == 3) break; // если метод поиска сообщения - не перебираем дальше
                                } // end foreach messages_php
                            }
                            else if($keyword_stype == 3)
                            {
                                foreach ($messages as $value) 
                                {
                                    $keyword = wordToUniversalFormat($keyword);
                                    $value = wordToUniversalFormat($value);
                                    if($value == $keyword)
                                    {
                                        $result = 'Найдено';
                                        $k = 1;
                                        break;
                                    }   
                                }
                            }

                            if(!$k)
                            {
                                $log .= 'Не нашли таких сообщений';
                                closeScript($log);
                                exit();
                            }
                        }
                    } // end if $viewers_method == 1,3,4
                }
                else 
                {
                    $log .= 'Ошибка запроса к Бизон365: '.$web_info['message'].'<br>';
                    closeScript($log);
                    exit();
                }                     
            } // end if isset ['message']
            else
            {
                $log .= 'Не указаны поля для поиска <br>';
                closeScript($log);
                exit();
            }
        } //  end if ($option_name == 'viewers')


        /* Если стоит переключатель на "Выводить текстом" */
        switch ($option) {
            // зрители
            case 1:

                /* Если искали сообщения */
                if( $write_type && $viewers_method == 1)
                {
                    $result = "Сообщения от ".$user['username']." с вебинара ID: ".$params['webinarId'].'<br>';
                    foreach ($messages as $value) 
                    {
                        $result .= '«'.$value.'»;';
                    }
                }

                /* Если искали человека */
                if( $write_type && $viewers_method == 2)
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
            'log' => $log,
            'messages' => $messages,
            'users_chat_ids' => $users_chat_ids
        ]
    ];

} elseif($act == '') {
    // Действие не задано, и что же нам сделать? Станцевать вальс, попрыгать, пойти в гости к Кролику?

} elseif($act == 'man') {
    $responce = [
        'html' =>
        '##Описание
        Данная ВРМ работает с аккаунтом Бизон365, который Вы указали в интеграции. Подробная инструкция тут - 

        ###Доступные переменные:

        **{b.{bid}.value.result}** - результат выполнения в текстовом виде
        **{b.{bid}.value.messages}** - массив с сообщениями зрителя

        ####Отладка
        **{b.{bid}.value.log}** - строка логирования действий/ошибок (для выхода "Не найдено")
        '
    ];
}

// Отдать JSON, не кодируя кириллические символы в кракозябры
echo json_encode($responce, JSON_UNESCAPED_UNICODE);