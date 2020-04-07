<?php

ini_set('display_errors',1);

require_once('functions.php');
require_once('Bitrix24.php');
require_once('Entity.php');

$act = $_REQUEST['act'];

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Режим OPTIONS - в котором ВРМ создаёт в схеме блок управления  *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

// если запрос с АЮ
if(isset($act))
{
    if($act == 'options') {
        $responce = [
            'title' => 'ВРМ Bitrix24 Webhook',      // Это заголовок блока, который будет виден на схеме
            'paysys' => [                   // Группа полей, отвечающая за интеграцию с платёжными системами и внешними сервисами.
                'ps' => [                   // ВРМ получит доступ к ID аккаунта, секретному ключу и другим атрибутам выбранной системы
                    'title' => 'Bitrix24',
                    'type' => 8
                ]
            ],
            'vars' => [                     // переменные, которые можно будет настроить в блоке
                'entity_name' => [
                    'title' => 'Сущность',   // заголовок поля
                    'values' => [
                        'lead' => 'Лиды',
                        'deal' => 'Сделки',
                        'contact' => 'Контакты'
                    ],
                    'desc' => '',    // описание поля, можно пару строк
                ],
                'exec_type' => [
                    'title' => 'Выбор действия',   // заголовок поля
                    'values' => [
                        'add' => 'Добавить',
                        'update' => 'Изменить',
                        'get' => 'Получить',
                        'delete' => 'Удалить'
                    ],
                    'desc' => '',    // описание поля, можно пару строк
                ],

                // поля лида
                'title' => [
                    'title' => 'Заголовок карточки',
                    'desc' => '',
                    'show' => [
                        'entity_name' => ['lead','deal'],
                        'exec_type' => ['add','update']
                    ]
                ],
                'name' => [
                    'title' => 'Имя',
                    'desc' => '',
                    'show' => [
                        'exec_type' => ['add','update']
                    ]
                ],
                'last_name' => [
                    'title' => 'Фамилия',
                    'desc' => '',
                    'show' => [
                        'exec_type' => ['add','update']
                    ]
                ],
                'address' => [
                    'title' => 'Адрес',
                    'desc' => '',
                    'show' => [
                        'entity_name' => ['lead','deal'],
                        'exec_type' => ['add','update']
                    ]
                ],
                'comments' => [
                    'title' => 'Комментарий',
                    'desc' => '',
                    'show' => [
                        'exec_type' => ['add','update']
                    ]
                ],
                'phone' => [
                    'title' => 'Телефон',
                    'desc' => '',
                    'show' => [
                        'entity_name' => ['lead','deal','contact'],
                        'exec_type' => ['add','update']
                    ]
                ],
                'email' => [
                    'title' => 'Email',
                    'desc' => '',
                    'show' => [
                        'entity_name' => ['lead','deal','contact'],
                        'exec_type' => ['add','update']
                    ]
                ],
                'opportunity' => [
                    'title' => 'Сумма заказа',
                    'desc' => '',
                    'show' => [
                        'entity_name' => ['lead','deal'],
                        'exec_type' => ['add','update']
                    ]
                ],
                'currency' => [
                    'title' => 'Валюта',
                    'desc' => '',
                    'values' => [
                        0 => "RUB",
                        1 => "USD",
                    ],
                    'show' => [
                        'entity_name' => ['lead','deal'],
                        'exec_type' => ['add','update']
                    ],
                    'default' => 0
                ],
                'status_id' => [
                    'title' => 'Статус заказа',
                    'desc' => '',
                    'values' => [
                        'NEW' => 'Не обработан',
                        'IN_PROCESS' => 'В работе',
                        'PROCESSED' => 'Обработан'
                    ],
                    'show' => [
                        'entity_name' => 'lead',
                        'exec_type' => ['add','update']
                    ],
                    'default' => 1
                ],

                // id лида для разных запросов
                'input_id' => [
                    'title' => 'ID лида',
                    'desc' => 'Положительное число',
                    'show' => [
                        'exec_type' => ['update','delete','get']
                    ]
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

        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * Основные настройки для Bitrix24:                          *
         * получаем данные ссылки, client_id и client_secret         *
         * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
        $ps = $_REQUEST['paysys']['ps'];            // Сюда придут настройки выбранной системы
        // если стоит цель не на инициатора активности

        $input_url = $ps['options']['account']; // ссылка на битрикс

        $entity_name = $options['entity_name'];
        $exec_type = $options['exec_type'];

        // Оставляем только поля с данными. Сервисные убираем
        unset($options['entity_name']);
        unset($options['exec_type']);

        $entity = new Entity($input_url, $entity_name);

        $bitrix_fields_keys = [
            'title',
            'name',
            'last_name',
            'address',
            'comments',
            'phone',
            'email',
            'opportunity',
            'currency',
            'stage_id'
        ];

        $bitrix_fields = array();
        foreach ($bitrix_fields_keys as $value)
        {
            $bitrix_fields[$value] = $options[$value];
        }

        switch ($options['currency'])
        {
            case 0:
                $options['currency'] = "RUB";
                break;
            case 1:
                $options['currency'] = "USD";
                break;
        }

        switch ($exec_type)
        {
            case 'add':
                $fields = array();

                foreach ($bitrix_fields as $key => $value)
                {
                    if($value)
                    {
                        $field_name = mb_strtoupper($key);

                        if($field_name == 'PHONE')
                        {
                            $fields =
                                array_merge(
                                    $fields,
                                    array(
                                        "PHONE" => [
                                            "VALUE" => [                            // добавляем телефон
                                                "VALUE" => $options['phone'],
                                                "VALUE_TYPE" => "MOBILE"
                                            ]
                                        ]
                                    )
                                );
                            continue;
                        }

                        if($field_name == 'EMAIL')
                        {
                            $fields =
                                array_merge(
                                    $fields,
                                    array(
                                        "EMAIL" => [
                                            "VALUE" => [                            // изменение почты
                                                "VALUE" => $options['email'],
                                                "VALUE_TYPE" => "HOME"
                                            ]
                                        ]
                                    )
                                );
                            continue;
                        }
                        $fields = array_merge($fields, array($field_name => $value));
                    }
                }

                // сделки. Добавляем контакт в карточку. И проверка на товары
                if($entity_name == 'deal')
                {
                    $contact = new Entity($input_url, 'contact');

                    // Ищем такой же email в списках контактов битрикса
                    $filter = [
                        "EMAIL" => [
                            "VALUE" => [                            // изменение почты
                                "VALUE" => $options['email']
                            ]
                        ]
                    ];
                    $select = [
                        'ID'
                    ];

                    $response = $contact->getList([],$filter,$select);

                    $contact_id = $response['result'][0]['ID'];

                    // Если не нашли email, ищем телефон
                    if(!$contact_id)
                    {
                        // ищем в списке уже имеющийся ТЕЛЕФОН
                        $filter = [
                            "PHONE" => [
                                "VALUE" => [                            // поле телефона
                                    "VALUE" => $options['phone']
                                ]
                            ]
                        ];
                        $select = [
                            'ID'
                        ];

                        $response = $contact->getList([],$filter,$select);
                        $contact_id = $response['result'][0]['ID'];
                    }

                    // Если нет контакта - добавляем
                    if(!$contact_id)
                    {
                        $contact_fields = [
                            'NAME' => $options['Name'],
                            'LAST_NAME' => $options['Last_Name'],
                            "PHONE" => [
                                "VALUE" => [                            // добавляем телефон
                                    "VALUE" => $options['phone'],
                                    "VALUE_TYPE" => "MOBILE"
                                ]
                            ],
                            "EMAIL" => [
                                "VALUE" => [                            // добавляем email
                                    "VALUE" => $options['email'],
                                    "VALUE_TYPE" => "HOME"
                                ]
                            ]
                        ];

                        $response = $contact->add($contact_fields);
                        $contact_id = $response['result'];
                    }

                    $fields = array_merge($fields,
                        array(
                            'CONTACT_ID' => $contact_id
                        )
                    );
                }

                $response = $entity->add($fields);
                break;

            // изменить
            case 'update':
                $fields = array();

                foreach ($bitrix_fields as $key => $value)
                {
                    if($value)
                    {
                        $field_name = mb_strtoupper($key);

                        if($field_name == 'PHONE')
                        {
                            $fields =
                                array_merge(
                                    $fields,
                                    array(
                                        "PHONE" => [
                                            "VALUE" => [                            // добавляем телефон
                                                "VALUE" => $options['phone'],
                                                "VALUE_TYPE" => "MOBILE"
                                            ]
                                        ]
                                    )
                                );
                            continue;
                        }

                        if($field_name == 'EMAIL')
                        {
                            $fields =
                                array_merge(
                                    $fields,
                                    array(
                                        "EMAIL" => [
                                            "VALUE" => [                            // изменение почты
                                                "VALUE" => $options['email'],
                                                "VALUE_TYPE" => "HOME"
                                            ]
                                        ]
                                    )
                                );
                            continue;
                        }
                        $fields = array_merge($fields, array($field_name => $value));
                    }
                }

                $response = $entity->update($options['input_id'],$fields);
                break;

            // получить лид
            case 'get':
                $response = $entity->get($options['input_id']);

                $result = $response['result'];

                $found = "";
                foreach ($result as $key => $value)
                {
                    if($value)
                    {
                        // меняем значение ключа на русское для вывода
                        $russianKey = changeValueToRussian($key);

                        // если не нашли русского значения, то пишем англ
                        if(!$russianKey)
                        {
                            $russianKey = $key;
                        }

                        if($key == 'PHONE' || $key == 'EMAIL')
                        {
                            $found .= $russianKey.": ".$value[0]['VALUE']." Тип: ".$value[0]['VALUE_TYPE'].'<br>';
                        }
                        // пропускаем значение этого ключа
                        elseif ($key == 'STATUS_SEMANTIC_ID') continue;
                        else
                        {
                            // меняем значение, если Y или N на адекватные русские названия
                            switch ($value)
                            {
                                case 'Y':
                                    $value = "Да";
                                    break;
                                case 'N':
                                    $value = "Нет";
                                    break;
                            }
                            $found .= $russianKey.": ".$value.'<br>';
                        }
                    }
                }
                break;

            // удалить лид
            case 'delete':
                $response = $entity->delete($options['input_id']);
                break;
        }

        $result = $response['result'];

        $out = 1;

        $responce = [
            'out' => $out,         // Обязательно должен быть номер выхода out, отличный от нуля!

            'value' => [           // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через $bN_value.ваши_ключи_массива
                'result' => $result,
                'found' => $found,
                'response' => $response
            ]
        ];

    } elseif($act == '') {
        // Действие не задано, и что же нам сделать? Станцевать вальс, попрыгать, пойти в гости к Кролику?

    }
    elseif($act == 'man')
    {
        $responce = [
            'html' =>
                '##Описание
            Данная ВРМ работает с аккаунтом Bitrix24, который Вы указали в интеграции. Подробная инструкция тут - https://vk.com/@rexont-bitrix24-au

            **Версия ВРМ: 1.0** в инструкции есть более новая версия (beta)

            ###Доступные переменные:

            **{b.{bid}.value.found}**
            информация по найденной карточке клиента

            **{b.{bid}.value.vk_uid}**
            vk id клиента из карточки

            **{b.{bid}.value.message}**
            информация по выполнению метода (если есть)

            **{b.{bid}.value.result}**
            полный массив полученных данных при выполнении (для отладки, либо прямого доступа к нужным данным)
            '
        ];
    }

    // Отдать JSON, не кодируя кириллические символы в кракозябры
    echo json_encode($responce, JSON_UNESCAPED_UNICODE);
}
else
{
    echo '<p> Привет, Битрикс! Это приложение для АЮ. Управляй им там :)</p>';
}