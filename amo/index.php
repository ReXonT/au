<?php

require_once('amo_class.php');
require_once('global.php');

set_time_limit(0);

$act = $_REQUEST['act'];

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Режим OPTIONS - в котором ВРМ создаёт в схеме блок управления  *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

if($act == 'options') {
    $responce = [
        'title' => 'ВРМ AmoCRM',      // Это заголовок блока, который будет виден на схеме
        'paysys' => [                   // Группа полей, отвечающая за интеграцию с платёжными системами и внешними сервисами.
            'ps' => [                   // ВРМ получит доступ к ID аккаунта, секретному ключу и другим атрибутам выбранной системы
                'title' => 'AmoCRM',
                'type' => 11
            ]
        ],
        'vars' => [                     // переменные, которые можно будет настроить в блоке
            'entity' => [
                'title' => 'С чем работаем?',   // заголовок поля
                'values' => [
                    'leads' => 'Сделки',
                    'contacts' => 'Контакты'
                ],
                'desc' => '',
            ],
            'exec_type' => [
                'title' => 'Выбор действия',
                'values' => [
                    'add' => 'Добавить',
                    'update' => 'Обновить'
                ],
                'show' => [
                    'entity' => ['leads']
                ],
                'desc' => '',
            ],

            'name' => [
                'title' => 'Имя',
                'desc' => '',
                'show' => [
                    'entity' => ['leads', 'contacts'],
                ]
            ],

            // сделки
            'status_name' => [
                'title' => 'Этап воронки',
                'desc' => 'Название этапа (например: Переговоры)',
                'default' => '',
                'show' => [
                    'entity' => ['leads'],
                ]
            ],

            'sale' => [
                'title' => 'Бюджет',
                'desc' => '',
                'show' => [
                    'entity' => ['leads'],
                ]
            ],
            'tags' => [
                'title' => 'Теги',
                'desc' => 'Не обязательно',
                'show' => [
                    'entity' => ['leads'],
                ]
            ],


            // контакты
            'phone' => [
                'title' => 'Телефон',
                'desc' => '',
                'show' => [
                    'entity' => ['contacts'],
                ]
            ],
            'email' => [
                'title' => 'Email',
                'desc' => '',
                'show' => [
                    'entity' => ['contacts'],
                ]
            ],
            'add_note' => [
                'title' => 'Примечание',
                'values' => [
                    'none' => 'Не добавлять',
                    'add' => 'Добавить'
                ],
                'default' => 'none'
            ],
            'note' => [
                'title' => 'Примечание',
                'desc' => 'Необязательно',
                'format' => 'textarea',
                'show' => [
                    'entity' => ['leads', 'contacts'],
                    'add_note' => ['add']
                ]
            ],
            'add_fields' => [
                'title' => 'Дополнительные поля',
                'values' => [
                    'none' => 'Не добавлять',
                    'add' => 'Добавить'
                ],
                'default' => 'none'
            ],
            'add_fields_num' => [
                'title' => 'Количество',
                'values' => [
                    1 => '1',
                    2 => '2',
                    3 => '3',
                    4 => '4',
                    5 => '5',
                ],
                'show' => [
                    'add_fields' => ['add']
                ],
                'default' => 'none'
            ],
            'add_field_1_id' => [
                'title' => 'ID поля №1',
                'show' => [
                    'add_fields' => ['add'],
                    'add_fields_num' => [1,2,3,4,5]
                ]
            ],
            'add_field_1_val' => [
                'title' => 'Значение поля №1',
                'format' => 'textarea',
                'show' => [
                    'entity' => ['leads', 'contacts'],
                    'add_fields' => ['add'],
                    'add_fields_num' => [1,2,3,4,5]
                ]
            ],
            'add_field_2_id' => [
                'title' => 'ID поля №2',
                'show' => [
                    'entity' => ['leads', 'contacts'],
                    'add_fields' => ['add'],
                    'add_fields_num' => [2,3,4,5]
                ]
            ],
            'add_field_2_val' => [
                'title' => 'Значение поля №2',
                'format' => 'textarea',
                'show' => [
                    'entity' => ['leads', 'contacts'],
                    'add_fields' => ['add'],
                    'add_fields_num' => [2,3,4,5]
                ]
            ],
            'add_field_3_id' => [
                'title' => 'ID поля №3',
                'show' => [
                    'entity' => ['leads', 'contacts'],
                    'add_fields' => ['add'],
                    'add_fields_num' => [3,4,5]
                ]
            ],
            'add_field_3_val' => [
                'title' => 'Значение поля №3',
                'format' => 'textarea',
                'show' => [
                    'entity' => ['leads', 'contacts'],
                    'add_fields' => ['add'],
                    'add_fields_num' => [3,4,5]
                ]
            ],
            'add_field_4_id' => [
                'title' => 'ID поля №4',
                'show' => [
                    'entity' => ['leads', 'contacts'],
                    'add_fields' => ['add'],
                    'add_fields_num' => [4,5]
                ]
            ],
            'add_field_4_val' => [
                'title' => 'Значение поля №4',
                'format' => 'textarea',
                'show' => [
                    'entity' => ['leads', 'contacts'],
                    'add_fields' => ['add'],
                    'add_fields_num' => [4,5]
                ]
            ],
            'add_field_5_id' => [
                'title' => 'ID поля №5',
                'show' => [
                    'entity' => ['leads', 'contacts'],
                    'add_fields' => ['add'],
                    'add_fields_num' => [5]
                ]
            ],
            'add_field_5_val' => [
                'title' => 'Значение поля №5',
                'format' => 'textarea',
                'show' => [
                    'add_fields' => ['add'],
                    'add_fields_num' => [5]
                ]
            ],

            'responsible_user_id' => [
                'title' => 'ID ответственного',
                'desc' => 'Не обязательно',
                'more' => 1
            ],
            'vk_uid_type' => [
                'title' => 'Тип добавления vk id',
                'values' => [
                    'prefix' => 'С префиксом au',
                    'none' => 'Без префикса'
                ],
                'default' => 'prefix',
                'more' => 1
            ],
        ],
        'out' => [                          // Это блоки выходов, мы задаём им номера и подписи (будут видны на схеме)
            1 => [                          // Номер 0 означает красный выход блока ВРМ, зарезервированный для случаев сбоя
                'title' => 'Результат',    // название выхода 1
            ]
        ]
    ];

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Режим RUN - в котором ВРМ получает, обрабатывает и возвращает  *
 * полученные от схемы данные                                   *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
} elseif($act == 'run') {              // Схема прислала данные, обрабатываем

    $target     = $_REQUEST['target'];          // Пользователь, от имени которого выполняется блок
    $ums        = $_REQUEST['ums'];             // Данные об активности пользователя, массив в котором есть
    $out        = 0;                            // Номер выхода по умолчанию. Если дальнейший код не назначит другой выход - значит что-то не так
    $options    = $_REQUEST['options'];
    $ps         = $_REQUEST['paysys']['ps']['options'];    // Сюда придут настройки выбранной системы

    $amo = new Amo(
        trim($ps['account']), // Логин
        trim($ps['secret']), // Api key
        trim($ps['domain']), // sub domain
        $ums['id']   // id сессии
    );

    $auth = $amo->auth();   // Авторизация
    $auth_info = json_decode($response, true)['response'];  // Информация по авторизации

    $cab_custom_fields = $amo->getCustomFields();    // Получаем доп. поля по аккаунту

    $entity_fields = [];    // Данные по сущности

    $parse_fields = ['vk_uid', 'Телефон', 'Email']; // Названия полей, id которых нужно вытащить из amo
                                                    // для последующей записи новых значений

    $entity_fields = getFieldsIds(                  // Получить id полей из custom_fields, которые нам нужны
        $cab_custom_fields, // Доп. поля аккаунта
        $options['entity'], // Название сущности, id полей которой будем получать
        $parse_fields       // Названия полей, id которых будем получать
    );

    if($options['vk_uid_type'] == 'prefix')
        $vk_uid = 'au' . $target;
    else
        $vk_uid = $target;


    # Заметка: Определили есть ли поле vk_uid. Теперь можно выполнять уже сами действия

    switch ($options['entity'])
    {
        case 'contacts':    // Контакты
            $result = $amo->changeContact($options, $entity_fields, $vk_uid);
            break;

        case 'leads':   // Сделки
            if($options['exec_type'] == 'add')
                $result = $amo->addLead($options, $entity_fields, $vk_uid);
            else if ($options['exec_type'] == 'update')
                $result = $amo->updateLead($options, $entity_fields, $vk_uid);
            break;
    }

    # Добавляем примечание

    if($options['add_note'] == 'add')
    {
        $card_id = $result['_embedded']['items'][0]['id'];   // id элемента для привязки примечания
        $add_note_result = $amo->addNote($card_id, $options['entity'], $options['note'], $options['responsible_user_id']);
    }

    $amo->clean();          // Удаляем куки
    $out = 1;

    $responce = [
        'out' => $out,
        'value' => [
            'result' => $result,
            'note_result' => $add_note_result
        ]
    ];

}

// Отдать JSON, не кодируя кириллические символы в кракозябры
echo json_encode($responce, JSON_UNESCAPED_UNICODE);