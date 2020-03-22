<?php
$responce = [
    'title' => 'ВРМ JustClick Контакты',      // Это заголовок блока, который будет виден на схеме
    'paysys' => [
        'ps' => [
            'title' => 'Интеграция',
            'type' => 4
        ]
    ],
    'vars' => [                     // переменные, которые можно будет настроить в блоке
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

        // Данные пользователя
        'lead_name' => [
            'title' => 'Имя контакта',   // заголовок поля
            'desc' => '',
            'show' => [
                'lead_option' => [1,2]
            ]
        ],
        'lead_email' => [
            'title' => 'Email',   // заголовок поля
            'desc' => '',
            'show' => [
                'lead_option' => [1,2,3,4]
            ]
        ],
        'lead_phone' => [
            'title' => 'Телефон',   // заголовок поля
            'desc' => '',
            'show' => [
                'lead_option' => [1,2]
            ]
        ],
        'lead_city' => [
            'title' => 'Город',   // заголовок поля
            'desc' => '',
            'show' => [
                'lead_option' => [1,2]
            ]
        ],
        'tag' => [
            'title' => 'Метка контакта',   // заголовок поля
            'desc' => '',
            'show' => [
                'lead_option' => [1]
            ]
        ],

        // Данные для групп подписок
        'mailing_id' => [
            'title' => 'Группа рассылки',   // заголовок поля
            'desc' => 'ID группы из вкладки API',
            'show' => [
                'lead_option' => [1]
            ]
        ],
        'done_url' => [
            'title' => 'Страница после подтверждения',   // заголовок поля
            'desc' => 'Адрес куда будет перенаправлен контакт после подтверждения (не обязательно)',
            'show' => [
                'lead_option' => [1]
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
                'lead_option' => [1]
            ]
        ],
        'utm_source' => [
            'title' => 'utm_source',
            'desc' => 'UTM-параметр источник',
            'show' => [
                'lead_option' => [1],
                'utm_exec' => [1]
            ]
        ],
        'utm_medium' => [
            'title' => 'utm_medium',   // заголовок поля
            'desc' => 'UTM-параметр канал',
            'show' => [
                'lead_option' => [1],
                'utm_exec' => [1]
            ]
        ],
        'utm_campaign' => [
            'title' => 'utm_campaign',
            'desc' => 'UTM-параметр кампания',
            'show' => [
                'lead_option' => [1],
                'utm_exec' => [1]
            ]
        ],
        'utm_content' => [
            'title' => 'utm_content',
            'desc' => 'UTM-параметр объявление',
            'show' => [
                'lead_option' => [1],
                'utm_exec' => [1]
            ]
        ],
        'utm_term' => [
            'title' => 'utm_term',
            'desc' => 'UTM-параметр ключ',
            'show' => [
                'lead_option' => [1],
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
                'lead_option' => [1]
            ]
        ],
        'aff_source' => [
            'title' => 'aff_source',
            'desc' => 'Партнерский-параметр источник',
            'show' => [
                'lead_option' => [1],
                'aff_exec' => [1]
            ]
        ],
        'aff_medium' => [
            'title' => 'aff_medium',   // заголовок поля
            'desc' => 'Партнерский-параметр канал',
            'show' => [
                'lead_option' => [1],
                'aff_exec' => [1]
            ]
        ],
        'aff_campaign' => [
            'title' => 'aff_campaign',
            'desc' => 'Партнерский-параметр кампания',
            'show' => [
                'lead_option' => [1],
                'aff_exec' => [1]
            ]
        ],
        'aff_content' => [
            'title' => 'aff_content',
            'desc' => 'Партнерский-параметр объявление',
            'show' => [
                'lead_option' => [1],
                'aff_exec' => [1]
            ]
        ],
        'aff_term' => [
            'title' => 'aff_term',
            'desc' => 'Партнерский-параметр ключ',
            'show' => [
                'lead_option' => [1],
                'aff_exec' => [1]
            ]
        ],
    ],
    'out' => [                      // Это блоки выходов, мы задаём им номера и подписи (будут видны на схеме)
        1 => [                      // Номер 0 означает красный выход блока ВРМ, зарезервированный для случаев сбоя
            'title' => 'Результат',    // название выхода 1
        ]
    ]
];