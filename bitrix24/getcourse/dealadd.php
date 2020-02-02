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
        'title' => 'GC Deal Add',      // Это заголовок блока, который будет виден на схеме
        'paysys' => [
            'ps' => [
                'title' => 'Сервис GetCourse',
            ]
        ],
        'vars' => [                     // переменные, которые можно будет настроить в блоке
            'first_name' => [
                'title' => 'Имя',   // заголовок поля
                'desc' => '',    // описание поля, можно пару строк
                'default' => '{user.{uid}.first_name}'
            ],
            'last_name' => [
                'title' => 'Фамилия',   // заголовок поля
                'desc' => '',    // описание поля, можно пару строк
                'default' => '{user.{uid}.last_name}'
            ],
            'product_title' => [
                'title' => 'Название продукта',   // заголовок поля
                'desc' => '',    // описание поля, можно пару строк
                'default' => ''
            ],
            'deal_number' => [
                'title' => 'Номер заказа',   // заголовок поля
                'desc' => '',    // описание поля, можно пару строк
                'default' => ''
            ],
            'deal_cost' => [
                'title' => 'Сумма сделки',   // заголовок поля
                'desc' => '',    // описание поля, можно пару строк
                'default' => ''
            ],
            'email' => [
                'title' => 'Е-майл пользователя',   // заголовок поля
                'desc' => '',    // описание поля, можно пару строк
                'default' => ''          // значение по умолчанию
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
    $ums    = $_REQUEST['ums'];     // Данные об активности пользователя, массив в котором есть
    $options = $_REQUEST['options'];
    $ps = $_REQUEST['paysys']['ps'];
    $pso = $ps['options'];

    // id - номер элемента (комментария, поста, смотря о чём речь в активности)
    // from_id - UID пользователя
    // date - дата в формате timestamp
    // text - текст комментария, сообщения и т.д.
    // Можно передавать несколько хранилищ, но не увлекайтесь с объёмом
    $out    = 1;                    // Номер выхода по умолчанию. Если дальнейший код не назначит другой выход - значит что-то не так
    $user_id = 0;


    /* Теперь, начинаем работать с тем, что прислала активность */

    try {

        $deal = new \GetCourse\Deal();

        $deal::setAccountName($pso['account']);
        $deal::setAccessToken($pso['secret']);

        $deal
            ->setEmail($options['email'])
            ->setFirstName($options['first_name'])
            ->setLastName($options['last_name'])
            ->setUserAddField('vk_uid', $target)
            ->setOverwrite()
            ->setSessionReferer('http://activeusers.ru')
            ->setProductTitle($options['product_title'])
            ->setDealNumber($options['deal_number'])
            ->setDealCost($options['deal_cost']);

        $result = $deal->apiCall($action = 'add');

        $user_id = $result->result->user_id;
        $deal_id = $result->result->deal_id;




    } catch(Exception $e) {
        $error = $e->getMessage();
        $out = 0;
    }

    if(!empty($url)) {
        $out = 1;
    }

    // Сформировать массив данных на отдачу

    $responce = [
        'out' => $out,         // Обязательно должен быть номер выхода out, отличный от нуля!
        'value' => [           // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через $bN_value.ваши_ключи_массива
            'user_id' => $user_id,
            'deal_id' => $deal_id,
            'error' => $error,
        ]
    ];

} elseif($act == '') {
    // Действие не задано, и что же нам сделать? Станцевать вальс, попрыгать, пойти в гости к Кролику?

}

// Отдать JSON, не кодируя кириллические символы в кракозябры
echo json_encode($responce, JSON_UNESCAPED_UNICODE);


