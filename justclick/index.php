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
        'title' => 'ВРМ JustClick',      // Это заголовок блока, который будет виден на схеме
         'paysys' => [
             'ps' => [
                 'title' => 'Интеграция',
                 'type' => 4
             ]
         ],
        'vars' => [                     // переменные, которые можно будет настроить в блоке
            'option' => [
                'title' => 'С чем работаем',
                'values' => [
                    1 => 'Счета',
                    2 => 'Контакты',
                    3 => 'Продукты'
                ],
                'default' => 1
            ],
            'bill_option' => [
                'title' => 'Что делаем',
                'values' => [
                    1 => 'Создать счет',
                    2 => 'Изменить статус',
                    3 => 'Удалить/скрыть счет',
                    4 => 'Получить счета клиента',
                    5 => 'Получить информацию по счету',
                    6 => 'Получить все счета за указанную дату'
                ],
                'default' => 1,
                'show' => [
                    'option' => [1]
                ]
            ],
            'lead_option' => [
                'title' => 'Что делаем',
                'values' => [
                    1 => 'Добавить контакта в группу',
                    2 => 'Изменить данные контакта',
                    3 => 'Отписать от группы',
                    4 => 'Получить все группы контакта',
                    5 => 'Получить все группы из аккаунта'
                ],
                'default' => 1,
                'show' => [
                    'option' => [2]
                ]
            ],
            'product_option' => [
                'title' => 'Что делаем',
                'values' => [
                    1 => 'Изменить настройки продукта',
                    2 => 'Удалить продукт',
                    3 => 'Получить список всех продуктов'
                ],
                'default' => 1,
                'show' => [
                    'option' => [3]
                ]
            ],
            'good_ids' => [
                'title' => 'ID продуктов',   // заголовок поля
                'desc' => 'Через запятую. Берется из адресной строки при редактировании продукта',
                'default' => '',
                'show' => [
                    'option' => [1],
                    'bill_option' => [6]
                ]
            ],
            'good_name' => [
                'title' => 'Название продукта',   // заголовок поля
                'desc' => '',    
                'default' => '',
                'show' => [
                    'option' => [1],
                    'bill_option' => [1]
                ]
            ],
            'good_sum' => [
                'title' => 'Стоимость продукта',   // заголовок поля
                'desc' => '',    
                'default' => '',
                'show' => [
                    'option' => [1],
                    'bill_option' => [1]
                ]
            ],
            'bill_id' => [
                'title' => 'ID счета',   // заголовок поля
                'desc' => '',
                'show' => [
                    'option' => [1],
                    'bill_option' => [2,3,5]
                ]
            ],
            'bill_status' => [
                'title' => 'Статус счета',   // заголовок поля
                'desc' => '',
                'values' => [
                    1 => 'Поступила оплата по заказу',
                    2 => 'Заказ отменен',
                    3 => 'Покупатель вернул заказ',
                    4 => 'Заказ отправлен по почте'
                ],
                'show' => [
                    'option' => [1],
                    'bill_option' => [2]
                ]
            ],
            'bill_pay_status' => [
                'title' => 'Статус оплаты',   // заголовок поля
                'desc' => '',
                'values' => [
                    1 => 'Оплачен',
                    2 => 'Ожидается',
                    3 => 'Отменен'
                ],
                'show' => [
                    'option' => [1],
                    'bill_option' => [4]
                ]
            ],
            'bill_kupon' => [
                'title' => 'Купон скидки',   // заголовок поля
                'desc' => '',
                'show' => [
                    'option' => [1],
                    'bill_option' => [1]
                ]
            ],
            'bill_comment' => [
                'title' => 'Комментарий',   // заголовок поля
                'desc' => 'Совет: используйте, чтобы записать vk id пользователя',
                'default' => '{uid}',
                'show' => [
                    'option' => [1],
                    'bill_option' => [1]
                ]
            ],
            'bill_first_name' => [
                'title' => 'Имя',   // заголовок поля
                'desc' => '',
                'show' => [
                    'option' => [1],
                    'bill_option' => [1]
                ]
            ],
            'bill_surname' => [
                'title' => 'Фамилия',   // заголовок поля
                'desc' => '',
                'show' => [
                    'option' => [1],
                    'bill_option' => [1]
                ]
            ],
            'bill_email' => [
                'title' => 'Email',   // заголовок поля
                'desc' => '',
                'show' => [
                    'option' => [1],
                    'bill_option' => [1,4]
                ]
            ],
            'bill_phone' => [
                'title' => 'Телефон',   // заголовок поля
                'desc' => '',
                'show' => [
                    'option' => [1],
                    'bill_option' => [1]
                ]
            ],

            // UTM данные
            'utm_exec' => [
                'title' => 'Добавить utm',
                'values' => [
                    0 => 'Нет',
                    1 => 'Да'
                ],
                'default' => 0,
                'show' => [
                    'option' => [1],
                    'bill_option' => [1]
                ]
            ],
            'utm_medium' => [
                'title' => 'utm_medium',   // заголовок поля
                'desc' => 'UTM-параметр канал',
                'show' => [
                    'option' => [1],
                    'bill_option' => [1],
                    'utm_exec' => [1]
                ]
            ],
            'utm_source' => [
                'title' => 'utm_source',
                'desc' => 'UTM-параметр источник',
                'show' => [
                    'option' => [1],
                    'bill_option' => [1],
                    'utm_exec' => [1]
                ]
            ],
            'utm_campaign' => [
                'title' => 'utm_campaign',
                'desc' => 'UTM-параметр кампания',
                'show' => [
                    'option' => [1],
                    'bill_option' => [1],
                    'utm_exec' => [1]
                ]
            ],
            'utm_content' => [
                'title' => 'utm_content',
                'desc' => 'UTM-параметр объявление',
                'show' => [
                    'option' => [1],
                    'bill_option' => [1],
                    'utm_exec' => [1]
                ]
            ],
            'utm_term' => [
                'title' => 'utm_term',
                'desc' => 'UTM-параметр ключ',
                'show' => [
                    'option' => [1],
                    'bill_option' => [1],
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
                'default' => 0,
                'show' => [
                    'option' => [1],
                    'bill_option' => [1]
                ]
            ],
            'aff_medium' => [
                'title' => 'aff_medium',   // заголовок поля
                'desc' => 'Партнерский-параметр канал',
                'show' => [
                    'option' => [1],
                    'bill_option' => [1],
                    'aff_exec' => [1]
                ]
            ],
            'aff_source' => [
                'title' => 'aff_source',
                'desc' => 'Партнерский-параметр источник',
                'show' => [
                    'option' => [1],
                    'bill_option' => [1],
                    'aff_exec' => [1]
                ]
            ],
            'aff_campaign' => [
                'title' => 'aff_campaign',
                'desc' => 'Партнерский-параметр кампания',
                'show' => [
                    'option' => [1],
                    'bill_option' => [1],
                    'aff_exec' => [1]
                ]
            ],
            'aff_content' => [
                'title' => 'aff_content',
                'desc' => 'Партнерский-параметр объявление',
                'show' => [
                    'option' => [1],
                    'bill_option' => [1],
                    'aff_exec' => [1]
                ]
            ],
            'aff_term' => [
                'title' => 'aff_term',
                'desc' => 'Партнерский-параметр ключ',
                'show' => [
                    'option' => [1],
                    'bill_option' => [1],
                    'aff_exec' => [1]
                ]
            ],

            // Даты
            'date_begin' => [
                'title' => 'От какой даты',   // заголовок поля
                'desc' => 'Формат: день.месяц.год. Например, 21.03.2020<br>Максимальный интервал между датами = 1 месяц',
                'default' => '',
                'show' => [
                    'option' => [1],
                    'bill_option' => [6]
                ]
            ],
            'date_end' => [
                'title' => 'До какой даты',   // заголовок поля
                'desc' => 'Формат: день.месяц.год. Например, 21.03.2020<br>Если не указать - подставится текущая дата',
                'default' => '',
                'show' => [
                    'option' => [1],
                    'bill_option' => [6]
                ]
            ],

            // Доп чекбоксы
            'add_good' => [
                'title' => 'Добавлять данные о продуктах',
                'desc' => '',
                'format' => 'checkbox',
                'show' => [
                    'option' => [1],
                    'bill_option' => [5]
                ]
            ],
            'paid' => [
                'title' => 'Только оплаченные заказы',
                'desc' => '',
                'format' => 'checkbox',
                'show' => [
                    'option' => [1],
                    'bill_option' => [6]
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