<?php

ini_set('display_errors' , 1);

require_once ('functions.php');
require_once ('Bizon.php');
require_once ('Word.php');

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
            'webinar_method' => [
                'title' => 'Выбор действия',
                'values' => [
                    'get_info' => 'Получить полный отчет',
                ],
                'show' => [
                    'option' => ['webinar']
                ],
            ],
            'viewer_method' => [
                'title' => 'Выбор действия',   // заголовок поля
                'values' => [
                    'get_all' => 'Получить полный список зрителей',
                    'get_messages' => 'Получить сообщения зрителя с вебинара',
                    'is_viewer' => 'Проверить присутствие на вебинаре',
                    'has_keyword' => 'Найти ключевое слово в сообщении у зрителя',
                    'have_keyword' => 'Найти всех, кто написал ключевое слово'
                ],
                'show' => [
                    'option' => ['viewers']
                ],
                'desc' => '',    // описание поля, можно пару строк
            ],
            'exec_type' => [
                'title' => 'В чем искать',   // заголовок поля
                'values' => [
                    'get_last' => 'Недавний вебинар',
                    'get_date' => 'Вебинаре по дате',
                ],
                'show' => [
                    'option' => ['viewers', 'webinar']
                ],
                'desc' => '',    // описание поля, можно пару строк
            ],


            'kind_info' => [                    // Вид получаемой информации (для зрителей)
                'title' => 'Какую информацию получить',
                'values' => [
                    'main' => 'Всю основную (телефон, email, имя, доп.поля)',
                    'cu1' => 'Получить свой URL параметр',
                    'c1' => 'Получить своё поле',
                ],
                'show' => [
                    'option' => ['viewers'],
                    'viewer_method' => ['get_all','have_keyword']
                ],
                'desc' => '',    // описание поля, можно пару строк
            ],

            /*
             * Поля поиска по недавним вебинарам
             */
            'web_pos' => [
                'title' => 'Номер вебинара',   // заголовок поля
                'values' => [
                    0 => 'Первый с конца (самый недавний)',
                    1 => 'Второй с конца',
                    2 => 'Третий с конца',
                ],
                'show' => [
                    'option' => ['webinar','viewers'],
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
                    'option' => ['webinar','viewers'],
                    'exec_type' => ['get_last']
                ],
            ],

            /*
             * Поля поиска вебинара по дате
             */
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

            'cu1' => [
                'title' => 'Данные в доп. url',   // заголовок поля
                'default' => '',
                'show' => [
                    'option' => ['viewers'],
                    'viewer_method' => ['get_messages','is_viewer','has_keyword']

                ],
                'desc' => 'Например, укажите здесь id пользователя, которого ищем. Если вы давали ему ссылку с его id (по инструкции)',    // описание поля, можно пару строк
            ],

            /*
             * Тип поиска ключевых слов
             */
            'keyword_search_type' => [
                'title' => 'Тип поиска',   // заголовок поля
                'values' => [
                    'at_least_one' => 'Есть хотя бы одно',
                    'all_words' => 'Есть все слова',
                    'exact_match' => 'Точное соответствие'
                ],
                'default' => 'at_least_one',
                'show' => [
                    'option' => ['viewers'],
                    'viewer_method' => ['has_keyword', 'have_keyword']
                ],
                'desc' => '',    // описание поля, можно пару строк
            ],
            'keywords' => [
                'title' => 'Ключевое слово',   // заголовок поля
                'default' => '',
                'show' => [
                    'option' => ['viewers'],
                    'viewer_method' => ['has_keyword', 'have_keyword']
                ],
                'desc' => 'Какое слово (слова) ищем. Через запятую',    // описание поля, можно пару строк
            ],

            /*
             * Добавить поля поиска
             */
            'viewers_add_fields' => [
                'title' => 'Дополнительные поля поиска',   // заголовок поля
                'values' => [
                    'add' => 'Показать ещё поля для поиска',
                    'none' => 'Не показывать'
                ],
                'default' => 'none',
                'show' => [
                    'option' => ['viewers'],
                    'viewer_method' => ['get_messages','is_viewer','has_keyword']
                ],
                'desc' => 'На случай, если не было установлено своё url',    // описание поля, можно пару строк
            ],
            'c1' => [
                'title' => 'Данные в доп.поле',   // заголовок поля
                'default' => '',
                'show' => [
                    'option' => ['viewers'],
                    'viewers_add_fields' => ['add']
                ],
                'desc' => 'Например, укажите здесь id пользователя, которого ищем. Если вы давали ему ссылку с его id (по инструкции)',    // описание поля, можно пару строк
            ],
            'referer' => [
                'title' => 'Данные в источнике',   // заголовок поля
                'default' => '',
                'show' => [
                    'option' => ['viewers'],
                    'viewers_add_fields' => ['add']
                ],
                'desc' => 'Значение в ссылке-источнике, с которой зритель пришел на вебинар',    // описание поля, можно пару строк
            ],
            'phone' => [
                'title' => 'Телефон',   // заголовок поля
                'default' => '',
                'show' => [
                    'option' => ['viewers'],
                    'viewers_add_fields' => ['add']
                ],
                'desc' => 'Найдет сходство телефонов вида +79991112233, 79991112233, 89991112233, 9991112233',    // описание поля, можно пару строк
            ],
            'email' => [
                'title' => 'Email',   // заголовок поля
                'default' => '',
                'show' => [
                    'option' => ['viewers'],
                    'viewers_add_fields' => ['add']
                ],
                'desc' => 'Поиск на точное соответствие',    // описание поля, можно пару строк
            ],
            'username' => [
                'title' => 'Имя зрителя',   // заголовок поля
                'default' => '',
                'show' => [
                    'option' => ['viewers'],
                    'viewers_add_fields' => ['add']
                ],
                'desc' => 'Регистр не важен. Совпадения по этому полю минимальные, потому что человек может указать что угодно.',    // описание поля, можно пару строк
            ],

            'room_id' => [
                'title' => 'ID комнаты',   // заголовок поля
                'default' => '',
                'show' => [
                    'option' => ['webinar', 'viewers']
                ],
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

    $bizon = new Bizon($cookie);
    $bizon->auth([
        'username' => $ps['options']['account'],
        'password' => $ps['options']['secret']
    ]); // Авторизация

    switch ($options['search_type']) // Тип поиска. По умолчанию Auto и Live = 1
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

    /*
     * Получаем webinarId
     */
    switch ($options['exec_type']) // По каким вебинарам ищем
    {
        case 'get_last': // Получаем данные по недавним вебинарам
            $list_of_webinars = $bizon->getList();
            $webinar_id = getLastWebId($list_of_webinars, $options['web_pos'], $options['room_id']);
            break;

        case 'get_by_date': // Получаем вебинар по конкретным датам
            $webinar_id = createWebIdByDate($options['room_id'], $options['web_date'], $options['web_time']); // перевод в нужный формат даты

            if($_REQUEST['test'])
                $webinar_id = '20578:prav_rody*2020-02-21T17:02:42';
            break;
    }

    $webinar_info = $bizon->get($webinar_id); // Получаем информацию по нужному вебинару

    $result = array(); // Здесь будет результат для юзера

    switch ($options['option'])
    {
        case 'webinar': // Работа с вебинарами
            switch ($options['webinar_method']) // Что будем получать
            {
                case 'get_info': // Получить полную информацию
                    $result = $webinar_info;
                    break;
            }
            break;

        case 'viewers': // Работа со зрителями
            $viewers = json_decode($webinar_info['report']['report'], 1)['usersMeta'];

            switch ($options['viewer_method']) // Тип выполнения
            {
                case 'get_all': // Получить весь список зрителей
                    foreach ($viewers as $viewer)
                    {
                        // Получаем данные зрителя, учитывая выбранный вид получения информации
                        $result[] = changeKindInfo($viewer, $options['kind_info']);
                    }
                    break;

                case 'get_messages': // Получить сообщения зрителя
                    if(!$viewer = findViewer($viewers, $options))       // Находим нужного зрителя
                    {
                        $result = 'Не найден зритель';
                        break;
                    }

                    $found_chat_user_id = $viewer['chatUserId'];        // и получаем его chatId

                    $viewers_messages = json_decode($webinar_info['report']['messages'], 1); // Получаем все сообщения с вебинара
                    foreach ($viewers_messages as $viewer_chat_id => $messages)
                    {
                        if($viewer_chat_id == $found_chat_user_id)      // Находим сообщения нужного зрителя
                        {
                            $result = $messages;
                            break;
                        }
                    }
                    break;

                case 'is_viewer': // Был ли такой человек на вебинаре
                    $viewers = json_decode($webinar_info['report']['report'], 1)['usersMeta'];

                    if(!findViewer($viewers, $options)['chatUserId'])
                    {
                        $result = 'Нет';
                        break;
                    }
                    $result = 'Да';
                    break;

                case 'has_keyword': // Есть ли у этого зрителя ключевое слово на вебинаре
                    if(!$viewer = findViewer($viewers, $options))       // Находим нужного зрителя
                    {
                        $result = 'Не найден зритель';
                        break;
                    }

                    $found_chat_user_id = $viewer['chatUserId'];        // и получаем его chatId

                    $viewers_messages = json_decode($webinar_info['report']['messages'], 1);
                    foreach ($viewers_messages as $viewer_chat_id => $messages)
                    {
                        if($viewer_chat_id == $found_chat_user_id)
                        {
                            switch ($options['keyword_search_type'])
                            {
                                case 'at_least_one':
                                    $result = Word::findAtLeastOne($messages, $options['keywords']);
                                    break;

                                case 'all_words':
                                    $result = Word::findAll($messages, $options['keywords']);
                                    break;

                                case 'exact_match':
                                    $result = Word::findExactMatch($messages, $options['keywords']);
                                    break;
                            }
                        }
                    }
                    break;

                case 'have_keyword': // Найти всех зрителей с ключевым словом
                    $found_viewers_chat_ids = array();

                    $viewers_messages = json_decode($webinar_info['report']['messages'], 1);
                    foreach ($viewers_messages as $viewer_chat_id => $messages)
                    {
                        $found = false; // Флаг успешно найденного соответствия в слове

                        switch ($options['keyword_search_type'])
                        {
                            case 'at_least_one':
                                $found = Word::findAtLeastOne($messages, $options['keywords']);
                                break;

                            case 'all_words':
                                $found = Word::findAll($messages, $options['keywords']);
                                break;

                            case 'exact_match':
                                $found = Word::findExactMatch($messages, $options['keywords']);
                                break;
                        }

                        if($found)                                          // Если успешно нашли нужное слово у зрителя
                            $found_viewers_chat_ids[] = $viewer_chat_id;    // Добавляем его chatId в общий список
                    }

                    foreach ($found_viewers_chat_ids as $found_chat_id)     // Получаем нужную информацию по найденным зрителям
                        $result[] = findViewerByChatId($viewers, $found_chat_id, $options['kind_info']);
                    break;
            }
            break;
    }


    $out = 1;
    unlink($cookie);           // удаляем файл куки

    $responce = [
        'out' => $out,         // Обязательно должен быть номер выхода out, отличный от нуля!

        'value' => [           // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через $bN_value.ваши_ключи_массива
            'result' => $result,    // результат выполнения
            //'text' => $text         // результат выполнения в текстовом виде
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