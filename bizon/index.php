<?php

require('bizon.class.php');

$act = $_REQUEST['act'];

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Режим OPTIONS - в котором ВРМ создаёт в схеме блок управления  *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

if($act == 'options') {
    $responce = [
        'title' => 'ВРМ Бизон365',      // Это заголовок блока, который будет виден на схеме
        'vars' => [                     // переменные, которые можно будет настроить в блоке
            'paysys' => [                   // Группа полей, отвечающая за интеграцию с платёжными системами и внешними сервисами.
                'ps' => [                   // ВРМ получит доступ к ID аккаунта, секретному ключу и другим атрибутам выбранной системы
                    'title' => 'Интеграция',
                    'type' => 6
                ]
            ],
            'extype' => [
                'title' => 'Выбор действия',   // заголовок поля
                'values' => [
                    1 => 'Получить информацию о недавнем вебинаре',
                    2 => 'Получить информацию о вебинаре по дате',
                	3 => 'Получить информацию обо всех вебинарах',
                ],
                'desc' => '',    // описание поля, можно пару строк
            ],     
            'get_type' => [
                'title' => 'Тип получения',
                'values' => [
                    1 => 'Полный отчет',
                    2 => 'Список зрителей'
                ],
                'show' => [
                    'extype' => [1,2]
                ]
            ],
            'search_type' => [
                'title' => 'Как искать',
                'values' => [
                    1 => 'Все вебинары',
                    2 => 'Только живые',
                    3 => 'Только автовебинары'
                ],
                'show' => [
                    'extype' => [1,2,3]
                ] 
            ],
            'last_num' => [
                'title' => 'Какой вебинар получить',   // заголовок поля
                'values' => [
                    0 => '1 с конца (самый недавний)',
                    1 => '2 с конца',
                    2 => '3 с конца',
                ],
                'show' => [
                    'extype' => [1]
                ],
                'desc' => '',    // описание поля, можно пару строк
            ],
            'web_date' => [
                'title' => 'Дата вебинара',   // заголовок поля
                'default' => '2020.02.03',
                'show' => [
                    'extype' => [2]
                ],
                'desc' => 'Формат: год.месяц.день',    // описание поля, можно пару строк
            ],
            'web_time' => [
                'title' => 'Время начала вебинара',   // заголовок поля
                'default' => '20:00:00',
                'show' => [
                    'extype' => [2]
                ],
                'desc' => 'Формат: час:минута:секунда',    // описание поля, можно пару строк
            ],
            'room_id' => [
                'title' => 'ID комнаты',   // заголовок поля
                'default' => '',
                'show' => [
                    'extype' => [1,2,3]
                ],
                'desc' => 'Пример: 20578:subarenda',    // описание поля, можно пару строк
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
    $session_id = $ums['id'];       // id сессии

    $ps = $_REQUEST['paysys']['ps'];            // Сюда придут настройки выбранной системы


    $user = [
    	'username' => 'lpwebinar@yandex.ru',
    	'password' => 'BarkasPW303'
	];

    // авторизация
    $bizon = new Bizon('cookies/'.$session_id.'cookie.txt');
    $bizon->auth($user);

    $extype = $options['extype'];   // задача запроса
    $get_type = $options['get_type']; // тип получения информации
    $last_num = $options['last_num']; // номер веба с конца
    $search_type = $options['search_type']; // тип поиска по вебинарам (автовеб, веб или везде)

    $web = [
        'time' => $options['web_time'],     // время вебинара
        'room' => $options['room_id']       // ID комнаты вебинара
    ];

    // перевод в нужный формат даты
    $tmp = explode('.', $web['date']);
    $web['date'] = implode('-', $tmp);

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

    // массив с параметрами запроса
    $params = [
        'LiveWebinars' => $live_web,
        'AutoWebinars' => $auto_web
    ];

    switch ($get_type) {
        // полный отчет 
        case 1:
            $method = 'get';
            break;

        // список зрителей
        case 2:
            $method = 'getviewers';
            break;
        
        default:
            $method = 'get';
            break;
    }

    switch ($extype) {
        // получаем данные по недавним вебинарам
        case 1:

            /* Получаем данные по списку последних вебинаров. 
            Учитывая поиск по автовебам/живым */
            $web_list = $bizon->call('getlist', $params); 

            /* Получаем нужный веб айди по нужному вебинару */
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
            else $params['webinarId'] = $web_list['list'][$last_num]['webinarId'];

            /* Получаем инфо по нужному вебинару */
            $web_info = $bizon->call($method, $params);
            break;

        // получаем вебинар по конкретным датам
        case 2:
            $webinar_id = $web['room'].'*'.$web['date'].'T'.$web['time'];

            if($_REQUEST['test'])
            {
                $webinar_id = '20578:prav_rody*2020-02-21T17:02:42';
            }
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
            $web_list = $bizon->call('getlist', $params);

            $list = array();

            if( !empty($web['room']) && isset($web['room']) )
            {
                foreach ($web_list['list'] as $value) 
                {
                    if( $value['name'] == $web['room'] )
                    {
                        $list[] = $value;
                    }
                }
                $web_list = $list;
            }
            break;
    }


    if($_REQUEST['debug'])
    {
        echo '<pre>';
        print_r($web_list);
        echo '</pre>';
    }
    /*$res = "";

    foreach ($arr['viewers'] as $value) {
        $res .= "\n\nИмя: ".$value['username'];
        $res .= "\nТелефон: ".$value['phone'];
        $res .= "\nEmail: ".$value['email'];
        $res .= "\nИсточник: ".$value['referer'];
        $res .= "\nГород: ".$value['city'];
        $res .= "\nТелефон: ".$value['phone'];
        $res .= "\nНаписано сообщений: ".$value['messages_num'];
    }*/
    $out = 1;

    unlink('cookies/'.$session_id.'cookie.txt');                        // удаляем файл куки

    $responce = [
        'out' => $out,         // Обязательно должен быть номер выхода out, отличный от нуля!
        
        'value' => [           // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через $bN_value.ваши_ключи_массива
            'result' => $web_info,     // где N - порядковый номер блока в схеме
            'res' => $res,
            'web_list' => $web_list
        ]
    ];

} elseif($act == '') {
    // Действие не задано, и что же нам сделать? Станцевать вальс, попрыгать, пойти в гости к Кролику?

}

// Отдать JSON, не кодируя кириллические символы в кракозябры
echo json_encode($responce, JSON_UNESCAPED_UNICODE);