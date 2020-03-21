<?php

ini_set('display_errors', 1);

require_once 'src/justclick.php';
require_once 'src/models/order.php';

$act = $_REQUEST['act'];

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Режим OPTIONS - в котором ВРМ создаёт в схеме блок управления  *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

if($act == 'options') {
     $responce = [
        'title' => 'Создаем в JC заказ',      // Это заголовок блока, который будет виден на схеме
         'paysys' => [
             'ps' => [
                 'title' => 'Интеграция',
                 'type' => 4
             ]
         ],
        'vars' => [                     // переменные, которые можно будет настроить в блоке
            'good_name' => [
                'title' => 'Название продукта',   // заголовок поля
                'desc' => '',    // описание поля, можно пару строк
                'default' => ''          // значение по умолчанию
            ],
            'good_sum' => [
                'title' => 'Стоимость продукта',   // заголовок поля
                'desc' => '',    // описание поля, можно пару строк
                'default' => ''          // значение по умолчанию
            ],
            'bill_comment' => [
                'title' => 'ID пользователя',   // заголовок поля
                'desc' => ''    // описание поля, можно пару строк
            ],
            'bill_first_name' => [
                'title' => 'Имя',   // заголовок поля
                'desc' => ''    // описание поля, можно пару строк
            ],
            'bill_surname' => [
                'title' => 'Фамилия',   // заголовок поля
                'desc' => ''    // описание поля, можно пару строк
            ],
            'bill_email' => [
                'title' => 'Email',   // заголовок поля
                'desc' => ''    // описание поля, можно пару строк
            ],
            'bill_phone' => [
                'title' => 'Телефон',   // заголовок поля
                'desc' => ''    // описание поля, можно пару строк
            ],
            'bill_kupon' => [
                'title' => 'Купон скидки',   // заголовок поля
                'desc' => ''    // описание поля, можно пару строк
            ],

            // UTM данные
            'utm_exec' => [
                'title' => 'Добавить utm',
                'values' => [
                    0 => 'Нет',
                    1 => 'Да'
                ],
                'default' => 0
            ],
            'utm_medium' => [
                'title' => 'utm_medium',   // заголовок поля
                'desc' => 'UTM-параметр канал',
                'show' => [
                    'utm_exec' => [1]
                ]
            ],
            'utm_source' => [
                'title' => 'utm_source',
                'desc' => 'UTM-параметр источник',
                'show' => [
                    'utm_exec' => [1]
                ]
            ],
            'utm_campaign' => [
                'title' => 'utm_campaign',
                'desc' => 'UTM-параметр кампания',
                'show' => [
                    'utm_exec' => [1]
                ]
            ],
            'utm_content' => [
                'title' => 'utm_content',
                'desc' => 'UTM-параметр объявление',
                'show' => [
                    'utm_exec' => [1]
                ]
            ],
            'utm_term' => [
                'title' => 'utm_term',
                'desc' => 'UTM-параметр ключ',
                'show' => [
                    'utm_exec' => [1]
                ]
            ],

            // Партнерские данные
            'aff_exec' => [
                'title' => 'Добавить парнерские данные',
                'values' => [
                    0 => 'Нет',
                    1 => 'Да'
                ],
                'default' => 0
            ],
            'aff_medium' => [
                'title' => 'aff_medium',   // заголовок поля
                'desc' => 'Партнерский-параметр канал',
                'show' => [
                    'aff_exec' => [1]
                ]
            ],
            'aff_source' => [
                'title' => 'aff_source',
                'desc' => 'Партнерский-параметр источник',
                'show' => [
                    'aff_exec' => [1]
                ]
            ],
            'aff_campaign' => [
                'title' => 'aff_campaign',
                'desc' => 'Партнерский-параметр кампания',
                'show' => [
                    'aff_exec' => [1]
                ]
            ],
            'aff_content' => [
                'title' => 'aff_content',
                'desc' => 'Партнерский-параметр объявление',
                'show' => [
                    'aff_exec' => [1]
                ]
            ],
            'aff_term' => [
                'title' => 'aff_term',
                'desc' => 'Партнерский-параметр ключ',
                'show' => [
                    'aff_exec' => [1]
                ]
            ],

            // Доп поля
            'bill_domain' => [
                'title' => 'Домен принятого заказа',
                'default' => 'activeusers.ru',
                'desc' => 'Не обязательно',
                'more' => 1
            ],
        ],
        'out' => [                      // Это блоки выходов, мы задаём им номера и подписи (будут видны на схеме)
            1 => [                      // Номер 0 означает красный выход блока ВРМ, зарезервированный для случаев сбоя
                'title' => 'Новый заказ',    // название выхода 1
            ],
            2 => [                      
                'title' => 'Уже создан',    // название выхода 2
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
                                    // id - номер элемента (комментария, поста, смотря о чём речь в активности)
                                    // from_id - UID пользователя
                                    // date - дата в формате timestamp
                                    // text - текст комментария, сообщения и т.д.
    $out = 0;  // Номер выхода по умолчанию. Если дальнейший код не назначит другой выход - значит что-то не так
    $options = $_REQUEST['options'];
    $ps = $_REQUEST['paysys']['ps'];
    $pso = $ps['options'];

    $jc = new JustClick($pso['account'], $pso['secret']);
    
    // Формируем массив купленных товаров
    $order = new Order();

    $order->addProduct($options['good_name'], $options['good_sum']);

    // Информация о клиенте
    $order->setNameFirst($options['bill_first_name']);
    $order->setNameLast($options['bill_last_name']);
    $order->setOtchestvo($options['bill_otchestvo']);
    $order->setEmail($options['bill_email']);
    $order->setPhoneNumber($options['bill_phone']);

    // Скидочный купон
    $order->setCoupon($options['bill_coupon']);

    // Текстовые заметки
    $order->setComment($options['bill_comment']);
    $order->setTag($options['bill_tag']);

    // Время
    $order->setTimerKill(true); // есть ли ограничение на время оплаты заказа, где:
        // false или 0 - счет автоматически не отменяется;
        // true или 1 - автоматическая отмена счета согласно настройкам в продукте;
        // при передаче времени в unixtime - автоотмена счета выставляется по этому времени.
    $order->setDateCreated(time());

    // Доп. информация
    $order->setDomainName($options['bill_domain']);

    // UTM метки
    $order->setUtm([
            'utm_source' => $options['utm_source'],
            'utm_medium' => $options['utm_medium'],
            'utm_campaign' => $options['utm_campaign'],
            'utm_content' => $options['utm_content'],
            'utm_term' => $options['utm_term'],
    ]);

    // Партнерские метки
    $order->setUtmAff([
        'aff_source' => $options['aff_source'],
        'aff_medium' => $options['aff_medium'],
        'aff_campaign' => $options['aff_campaign'],
        'aff_content' => $options['aff_content'],
        'aff_term' => $options['aff_term'],
    ]);

    // Вызываем функцию создания нового заказа и декодируем полученные данные
    $result = $jc->errorCodeToRussian(
        $jc->createOrder($order)['error_code']
    );

    logToFile($result);

    $out = 1;

    $responce = [
        'out' => $out,         // Обязательно должен быть номер выхода out, отличный от нуля!
        
        'value' => [           // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через $bN_value.ваши_ключи_массива
            'result' => $result,     // где N - порядковый номер блока в схеме
        ]
    ];

} elseif($act == '') {
    // Действие не задано, и что же нам сделать? Станцевать вальс, попрыгать, пойти в гости к Кролику?

}

// Отдать JSON, не кодируя кириллические символы в кракозябры
echo json_encode($responce, JSON_UNESCAPED_UNICODE);


function logToFile($data)
{
    $json = json_encode($data);
    $dir_home = __DIR__;
    $res = file($dir_home . '/log.txt');
    $res[] = $json . " \n";
    $str = implode ("", $res);
    file_put_contents($dir_home . '/log.txt', $str);
}