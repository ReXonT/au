<?php
$responce = [
    'title' => 'ВРМ JustClick Счета и продукты',      // Это заголовок блока, который будет виден на схеме
    'paysys' => [
        'ps' => [
            'title' => 'Интеграция',
            'type' => 13
        ]
    ],
    'vars' => [                     // переменные, которые можно будет настроить в блоке
        'option' => [
            'title' => 'С чем работаем',
            'values' => [
                1 => 'Счета',
                2 => 'Продукты'
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
        'product_option' => [
            'title' => 'Что делаем',
            'values' => [
                1 => 'Удалить продукт',
                2 => 'Получить список всех продуктов'
            ],
            'default' => 2,
            'show' => [
                'option' => [2]
            ]
        ],

        'product_name' => [
            'title' => 'Название продукта',   // заголовок поля
            'desc' => '',
            'default' => '',
            'show' => [
                'option' => [2],
                'product_option' => [1]
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
        'status' => [
            'title' => 'Статус счета',   // заголовок поля
            'desc' => '',
            'values' => [
                'paid' => 'Поступила оплата по заказу',
                'cancel' => 'Заказ отменен',
                'return' => 'Покупатель вернул заказ',
                'sent' => 'Заказ отправлен по почте'
            ],
            'show' => [
                'option' => [1],
                'bill_option' => [2]
            ]
        ],
        'rpo' => [
            'title' => 'Номер почтового отделения',   // заголовок поля
            'desc' => 'Обязательно',
            'show' => [
                'option' => [1],
                'bill_option' => [2],
                'status' => ['sent']
            ]
        ],
        'pay_status' => [
            'title' => 'Статус оплаты',   // заголовок поля
            'desc' => '',
            'values' => [
                'paid' => 'Оплачен',
                'waiting' => 'Ожидается',
                'cancel' => 'Отменен'
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
        'bill_last_name' => [
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
        'utm_source' => [
            'title' => 'utm_source',
            'desc' => 'UTM-параметр источник',
            'show' => [
                'option' => [1],
                'bill_option' => [1],
                'utm_exec' => [1]
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
        'aff_source' => [
            'title' => 'aff_source',
            'desc' => 'Партнерский-параметр источник',
            'show' => [
                'option' => [1],
                'bill_option' => [1],
                'aff_exec' => [1]
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
        'begin_date' => [
            'title' => 'От какой даты',   // заголовок поля
            'desc' => 'Формат: день.месяц.год. Например, 21.03.2020<br>Максимальный интервал между датами = 1 месяц',
            'default' => '',
            'show' => [
                'option' => [1],
                'bill_option' => [6]
            ]
        ],
        'end_date' => [
            'title' => 'До какой даты',   // заголовок поля
            'desc' => 'Формат: день.месяц.год. Например, 21.03.2020<br>Если не указать - подставится текущая дата',
            'default' => '',
            'show' => [
                'option' => [1],
                'bill_option' => [6]
            ]
        ],

        // Домен принятого заказа
        'domain_exec' => [
            'title' => 'Домен заказа',   // заголовок поля
            'desc' => '',
            'values' => [
            	0 => 'Определить автоматически',
            	1 => 'Ввести вручную'
            ],
            'show' => [
                'option' => [1],
                'bill_option' => [1]
            ]
        ],
        'bill_domain' => [
            'title' => 'Введите домен',   // заголовок поля
            'desc' => '',
            'show' => [
                'option' => [1],
                'bill_option' => [1],
                'domain_exec' => [1]
            ]
        ],

        // Доп чекбоксы
        'good_info' => [
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
        'to_text' => [
            'title' => 'Выдать ответ текстом',
            'desc' => '',
            'format' => 'checkbox',
            'more' => 1
        ],
    ],
    'out' => [                      // Это блоки выходов, мы задаём им номера и подписи (будут видны на схеме)
        1 => [                      // Номер 0 означает красный выход блока ВРМ, зарезервированный для случаев сбоя
            'title' => 'Результат',    // название выхода 1
        ]
    ]
];