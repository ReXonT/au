<?php

require_once ('Bizon.php');
require_once ('functions.php');

$act = $_REQUEST['act'];

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Режим OPTIONS - в котором ВРМ создаёт в схеме блок управления  *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

if($act == 'options') {
    $responce = [
        'title' => 'Интеграция Бизон365',       // Это заголовок блока, который будет виден на схеме
        'paysys' => [                           // Группа полей, отвечающая за интеграцию с платёжными системами и внешними сервисами.
            'ps' => [                           // ВРМ получит доступ к ID аккаунта, секретному ключу и другим атрибутам выбранной системы
                'title' => 'Интеграция',
                'type' => 12
            ]
        ],
        'vars' => [                             // переменные, которые можно будет настроить в блоке
            'option' => [
                'title' => 'С чем работаем',   // заголовок поля
                'values' => [
                    'viewers' => 'Зрители',
                    'webinar' => 'Вебинары',
                ],
                'desc' => '',    // описание поля, можно пару строк
            ],
            'exec_type' => [
                'title' => 'Что искать',   // заголовок поля
                'values' => [
                    'get_last' => 'Недавний вебинар',
                    'get_date' => 'Вебинаре по дате',
                ],
                'show' => [
                    'option' => ['viewers', 'webinar']
                ],
                'desc' => '',    // описание поля, можно пару строк
            ],
            'get_type' => [
                'title' => 'Что получить',
                'values' => [
                    'get_all' => 'Полный отчет',
                    'get_viewers' => 'Список зрителей'
                ],
                'show' => [
                    'option' => ['webinar']
                ],
            ],
            'kind_info' => [
                'title' => 'Какую информацию получить',   // заголовок поля
                'values' => [
                    'main' => 'Основную (имя, email, телефон, доп. поля)',
                    'cu1' => 'Свой URL параметр',
                    'c1' => 'Своё поле'
                ],
                'show' => [
                    'option' => ['webinar'],
                    'get_type' => ['get_viewers']
                ],
                'desc' => '',    // описание поля, можно пару строк
            ],
            'web_pos' => [
                'title' => 'Какой вебинар получить',   // заголовок поля
                'values' => [
                    0 => '1 с конца (самый недавний)',
                    1 => '2 с конца',
                    2 => '3 с конца',
                ],
                'show' => [
                    'option' => ['webinar'],
                    'exec_type' => ['get_last']
                ],
                'desc' => '',    // описание поля, можно пару строк
            ],
            'search_type' => [
                'title' => 'Как искать',
                'values' => [
                    'all' => 'Все вебинары',
                    'live' => 'Только живые',
                    'auto' => 'Только автовебинары'
                ],
                'show' => [
                    'option' => ['webinar'],
                    'exec_type' => ['get_last']
                ],
            ],
            'web_date' => [
                'title' => 'Дата вебинара',   // заголовок поля
                'default' => '2020-02-03',
                'show' => [
                    'option' => ['webinar'],
                    'exec_type' => ['get_date']
                ],
                'desc' => 'Формат: год.месяц.день',
            ],
            'web_time' => [
                'title' => 'Время начала вебинара',   // заголовок поля
                'default' => '20:00:00',
                'show' => [
                    'option' => ['webinar'],
                    'exec_type' => ['get_date']
                ],
                'desc' => 'Формат: час:минута:секунда',    // описание поля, можно пару строк
            ],
            'room_id' => [
                'title' => 'ID комнаты',   // заголовок поля
                'default' => '',
                'desc' => 'В какой комнате проводить поиск. <br> Пример: 20578:subarenda',    // описание поля, можно пару строк
            ],
        ],
        'out' => [                              // Это блоки выходов, мы задаём им номера и подписи (будут видны на схеме)
            1 => [                              // Номер 0 означает красный выход блока ВРМ, зарезервированный для случаев сбоя
                'title' => 'Найдено',           // название выхода 1
            ]
        ]
    ];

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Режим RUN - в котором ВРМ получает, обрабатывает и возвращает  *
     * полученные от схемы данные                                   *
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
} elseif($act == 'run') {            // Схема прислала данные, обрабатываем

    $target  = $_REQUEST['target'];  // Пользователь, от имени которого выполняется блок
    $ums     = $_REQUEST['ums'];     // Данные об активности пользователя, массив в котором есть
    $out     = 0;                    // Номер выхода по умолчанию. Если дальнейший код не назначит другой выход - значит что-то не так
    $options = $_REQUEST['options'];
    $ps      = $_REQUEST['paysys']['ps'];            // Сюда придут настройки выбранной системы
    $cookie  = 'cookies/' . $ps['id'] . 'cookie.txt';  // адрес создания куки файла

    // Авторизация
    $bizon = new Bizon($cookie);
    $bizon->auth([
        'username' => $ps['options']['account'],
        'password' => $ps['options']['secret']
    ]);

    // Тип поиска. По умолчанию Auto и Live = 1
    switch ($options['search_type'])
    {
        // Только живые
        case 'live':
            $bizon->setParam('AutoWebinars', 0);
            break;

        // Только авто
        case 'auto':
            $bizon->setParam('LiveWebinars', 0);
            break;
    }

    // Получаем webinarId
    switch ($options['exec_type'])
    {
        // Получаем данные по недавним вебинарам
        case 'get_last':
            $list_of_webinars = $bizon->getList();
            $webinar_id = getLastWebId($list_of_webinars, $options['web_pos'], $options['room_id']);
            break;

        // Получаем вебинар по конкретным датам
        case 'get_by_date':
            // перевод в нужный формат даты
            $webinar_id = createWebIdByDate($options['room_id'], $options['web_date'], $options['web_time']);

            if($_REQUEST['test'])
                $webinar_id = '20578:prav_rody*2020-02-21T17:02:42';
            break;
    }

    // Получаем информацию по нужному вебинару
    $webinar_info = $bizon->get($webinar_id);

    $result = array(); // Здесь будет результат для юзера

    switch ($options['get_type'])
    {
        // Получить полную информацию
        case 'get_all':
            $result = $webinar_info;
            break;

        // Получить список зрителей
        case 'get_viewers':
            $report = json_decode($webinar_info['report']['report'], 1);
            $viewers = $report['usersMeta'];

            foreach ($viewers as $viewer)
            {
                switch ($options['kind_info']) // Выбираем тип получения информации
                {
                    // Получить основную информацию
                    case 'main':
                        $result[] = [
                            'name' => $viewer['username'],
                            'vk_uid' => $viewer['cu1'],
                            'add_field' => $viewer['c1'],
                            'phone' => $viewer['phone'],
                            'email' => $viewer['email']
                        ];
                        break;

                    // Получить свой URL параметр
                    case 'cu1':
                        $result[] = $viewer['cu1'];
                        break;

                    // Получить своё поле
                    case 'c1':
                        $result[] = $viewer['c1'];
                        break;
                }
            }
            break;
    }

    $out = 1;
    unlink($cookie);           // удаляем файл куки

    $responce = [
        'out' => $out,         // Обязательно должен быть номер выхода out, отличный от нуля!

        'value' => [           // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через $bN_value.ваши_ключи_массива
            'result' => $result,     // где N - порядковый номер блока в схеме
        ]
    ];

}
elseif($act == 'man')
{
    $responce = [
        'html' =>
            '##Описание
            Данная ВРМ работает с аккаунтом Бизон365, который Вы указали в интеграции. Подробная инструкция тут - https://vk.com/@rexont-activeusers-integraciya-s-bizon365
        
            ###Доступные переменные:
        
            **{b.{bid}.value.result}** - результат выполнения
            **{b.{bid}.value.text}** - результат выполнения в текстовом виде
            '
    ];
}

// Отдать JSON, не кодируя кириллические символы в кракозябры
echo json_encode($responce, JSON_UNESCAPED_UNICODE);