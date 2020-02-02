<?php
/*
 * Внешний расчётный модуль (ВРМ) - принимает, обрабатывает и отдаёт обратно блоксхеме JSON данные, в случаях, когда необходимы
 * сложные операции обработки, поиск, сравнение или просто когда одним этим блоком можно заменить сразу несколько других.
 * Ссылка: http://activeusers.ru/vrm/searchandshow.php
 */
//ini_set('display_errors', 1);

require_once __DIR__ . '/autoload.php';

set_time_limit(0); // Будем работать сколько надо

/*
 * Текущий режим:
 * options - загрузка настроек
 * run - обработка данных
 * */

$act = $_REQUEST['act'];

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Режим OPTIONS - в котором ВРМ создаёт в схеме блок управления  *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

if($act == 'options') {
    $responce = [
        'title' => 'ВРМ GetCourse',      // Это заголовок блока, который будет виден на схеме
        'paysys' => [
            'ps' => [
                'title' => 'Сервис GetCourse',
                'type' => 4
            ]
        ],
        'vars' => [                     // переменные, которые можно будет настроить в блоке
            'exec_type' => [
                'title' => 'Что добавляем?',
                'desc' => 'Выберите вариант',
                'values' => [
                    1 => 'Пользователь',
                    2 => 'Сделка'
                ]
                'default' => ''
            ],
            'first_name' => [
                'title' => 'Имя',   // заголовок поля
                'desc' => '',    // описание поля, можно пару строк
                'default' => '{user.{uid}.first_name}',
                'show' => [
                    'exec_type' => [1,2]
                ]
            ],
            'last_name' => [
                'title' => 'Фамилия',   // заголовок поля
                'desc' => '',    // описание поля, можно пару строк
                'default' => '{user.{uid}.last_name}',
                'show' => [
                    'exec_type' => [1,2]
                ]
            ],
            'product_title' => [
                'title' => 'Название продукта',   // заголовок поля
                'desc' => '',    // описание поля, можно пару строк
                'default' => '',
                'show' => [
                    'exec_type' => 2
                ]
            ],
            'deal_number' => [
                'title' => 'Номер заказа',   // заголовок поля
                'desc' => '',    // описание поля, можно пару строк
                'default' => '',
                'show' => [
                    'exec_type' => 2
                ]
            ],
            'deal_cost' => [
                'title' => 'Сумма сделки',   // заголовок поля
                'desc' => '',    // описание поля, можно пару строк
                'default' => '',
                'show' => [
                    'exec_type' => 2
                ]
            ],
            'groups' => [
                'title' => 'Группы',   // заголовок поля
                'desc' => 'Через запятую',    // описание поля, можно пару строк
                'default' => ''          // значение по умолчанию
            ],
            'email' => [
                'title' => 'Е-майл пользователя',   // заголовок поля
                'desc' => '',    // описание поля, можно пару строк
                'default' => '',
                'show' => [
                    'exec_type' => [1,2]
                ]
            ],
        ],
        'out' => [                      // Это блоки выходов, мы задаём им номера и подписи (будут видны на схеме)
            1 => [                      // Номер 0 означает красный выход блока ВРМ, зарезервированный для случаев сбоя
                'title' => 'Добавлен',    // название выхода 1
            ]
        ]
    ];

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Режим RUN - в котором ВРМ получает, обрабатывает и возвращает  *
     * полученные от схемы данные                                   *
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
} elseif($act == 'run') {              // Схема прислала данные, обрабатываем

    $target = $_REQUEST['target'];  // Пользователь, от имени которого выполняется блок
    $ums = $_REQUEST['ums'];     // Данные об активности пользователя, массив в котором есть
    $options = $_REQUEST['options'];
    $ps = $_REQUEST['paysys']['ps'];
    $pso = $ps['options'];

    // id - номер элемента (комментария, поста, смотря о чём речь в активности)
    // from_id - UID пользователя
    // date - дата в формате timestamp
    // text - текст комментария, сообщения и т.д.
    // Можно передавать несколько хранилищ, но не увлекайтесь с объёмом
    $out = 1;                    // Номер выхода по умолчанию. Если дальнейший код не назначит другой выход - значит что-то не так
    $user_id = 0;


    /* Теперь, начинаем работать с тем, что прислала активность */

    try {

        $user = new \GetCourse\User();

        $user::setAccountName($pso['account']);
        $user::setAccessToken($pso['secret']);

        $user
            ->setEmail($options['email'])
            ->setFirstName($options['first_name'])
            ->setLastName($options['last_name'])
            ->setUserAddField('vk_uid', $target)
            //->setGroup('шахматисты')
            //->setGroup('дилетанты')
            ->setOverwrite()
            ->setSessionReferer('http://activeusers.ru');

        if (!empty($options['groups'])) {
            $tmp = explode(',', $options['groups']);
            foreach ($tmp as $g) {
                $g = trim($g);
                if (!empty($g)) {
                    $user->setGroup($g);
                }
            }
        }

        $result = $user->apiCall($action = 'add');

        if(!empty($_GET['debug'])) {
            echo '<pre>';
            print_r($options);
            print_r($result);
            echo '</pre>';
        }

        $user_id = $result->result->user_id;

        if($result->result->success != 1) {
            $error = $result->result->error_message;
            $out = 0;
        }


    } catch (Exception $e) {
        $error = $e->getMessage();
        $out = 0;
    }

    if (!empty($url)) {
        $out = 1;
    }

    // Сформировать массив данных на отдачу

    $responce = [
        'out' => $out,         // Обязательно должен быть номер выхода out, отличный от нуля!
        'value' => [           // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через $bN_value.ваши_ключи_массива
            'user_id' => $user_id,
            'error' => $error,
        ]
    ];


} elseif($act == '') {
    // Действие не задано, и что же нам сделать? Станцевать вальс, попрыгать, пойти в гости к Кролику?

}

// Отдать JSON, не кодируя кириллические символы в кракозябры
echo json_encode($responce, JSON_UNESCAPED_UNICODE);


