<?php
//require_once('crest.php');
require_once('functions.php');

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
                'methodType' => [
                    'title' => 'Лиды/Сделки',   // заголовок поля
                    'values' => [
                        1 => 'Лиды',
                        2 => 'Сделки',
                        3 => 'Контакты'
                    ],
                    'desc' => '',    // описание поля, можно пару строк
                ],
                'execType' => [
                    'title' => 'Выбор действия',   // заголовок поля
                    'values' => [
                        1 => 'Добавить',
                        2 => 'Изменить',
                        3 => 'Получить',
                        4 => 'Удалить'
                    ],
                    'desc' => '',    // описание поля, можно пару строк
                ],

                // поля лида
                'Title' => [
                    'title' => 'Заголовок карточки',
                    'desc' => '',
                    'show' => [
                        'methodType' => [1,2],
                        'execType' => [1,2]
                    ]
                ],
                'Name' => [
                    'title' => 'Имя',
                    'desc' => '',
                    'show' => [
                        'execType' => [1,2]
                    ]
                ],
                'Last_Name' => [
                    'title' => 'Фамилия',
                    'desc' => '',
                    'show' => [
                        'execType' => [1,2]
                    ]
                ],
                'Address' => [
                    'title' => 'Адрес',
                    'desc' => '',
                    'show' => [
                        'methodType' => [1,2],
                        'execType' => [1,2]
                    ]
                ],
                'Comments' => [
                    'title' => 'Комментарий',
                    'desc' => '',
                    'show' => [
                        'execType' => [1,2]
                    ]
                ],
                'Phone' => [
                    'title' => 'Телефон',
                    'desc' => '',
                    'show' => [
                        'methodType' => [1,3],
                        'execType' => [1,2]
                    ]
                ],
                'Email' => [
                    'title' => 'Email',
                    'desc' => '',
                    'show' => [
                        'methodType' => [1,3],
                        'execType' => [1,2]
                    ]
                ],
                'Opportunity' => [
                    'title' => 'Сумма заказа',
                    'desc' => '',
                    'show' => [
                        'methodType' => [1,2],
                        'execType' => [1,2]
                    ]
                ],
                'Currency' => [
                    'title' => 'Валюта',
                    'desc' => '',
                    'values' => [
                        0 => "RUB",
                        1 => "USD",
                    ],
                    'show' => [
                        'methodType' => [1,2],
                        'execType' => [1,2]
                    ],
                    'default' => 0
                ],
                'Status_Id' => [
                    'title' => 'Статус заказа',
                    'desc' => '',
                    'values' => [
                        1 => 'Не обработан',
                        2 => 'В работе',
                        3 => 'Обработан'
                    ],
                    'show' => [
                        'methodType' => 1,
                        'execType' => [1,2]
                    ],
                    'default' => 1
                ],

                // id лида для разных запросов
                'inputId' => [
                    'title' => 'ID лида',
                    'desc' => 'Положительное число',
                    'show' => [
                        'execType' => [2,3,4]
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

        $clientWebhook = $ps['options']['secret']; // webhook данные
        $inputUrl = $ps['options']['account']; // ссылка на битрикс
        if(!(strpos($inputUrl, '//')))
        {
            $clientUrl = $inputUrl;
        }
        else 
        {
            $arUrl = parse_url($inputUrl);
            $clientUrl = $arUrl['host'];
        }

        $queryUrl = 'https://'.$clientUrl.'/rest/1/'.$clientWebhook.'/';    // ссылка на webhook


        $methodType = $options['methodType']; // выбор метода запроса
        switch ($methodType) {
            case 1:
                $nameVar = 'lead';  // часть названия переменной, ответственная за лиды
                break;
            case 2:
                $nameVar = 'deal';  // часть названия переменной, ответственная за сделки
                break;
            case 3:
                $nameVar = 'contact';  // часть названия переменной, ответственная за сделки
                break;
        }

        $arrFieldNames = [
            'Title',
            'Name',
            'Last_Name',
            'Address',
            'Comments',
            'Phone',
            'Email',
            'Opportunity',
            'Currency',
            'Status_id', 
            'Address'
        ];
        
        // тип запроса
        $type = $options['execType'];

        $inputId = $options['inputId']; // id лида

        /* Поля */

        foreach ($arrFieldNames as $value) {
            ${$nameVar.$value} = $options[$value];
        }

        /*

        Составятся такие переменные:

        $leadTitle = $options['leadTitle']; // title лида
        $leadName = $options['leadName']; // имя лида
        $leadLast_Name = $options['leadLast_Name']; // фамилия лида
        $leadComments = $options['leadComments']; // комментарии для лида
        $leadPhone = $options['leadPhone']; // номер телефона лида
        $leadEmail = $options['leadEmail']; // email лида
        $leadOpportunity = $options['leadOpportunity']; // сумма заказа лида
        $leadCurrency = $options['leadCurrency']; // валюта заказа лида
        $leadStatus = $options['leadStatus_id']; // валюта заказа лида
        $leadAddress = $options['leadAddress']; // адрес лида 

        */


        switch (${$nameVar.'Currency'}) {
            case 0:
                ${$nameVar.'Currency'} = "RUB";
                break;
            case 1:
                ${$nameVar.'Currency'} = "USD";
                break;
            default:
                ${$nameVar.'Currency'} = "RUB";
                break;
        }

        switch (${$nameVar.'Status'}) {
            case 1:
                ${$nameVar.'Status'} = "NEW";
                break;
            case 2:
                ${$nameVar.'Status'} = "IN_PROCESS";
                break;
            case 3:
                ${$nameVar.'Status'} = "PROCESSED";
                break;
            default:
                ${$nameVar.'Status'} = "NEW";
                break;
        }
  

        switch ($type) {
            
            // добавить лид
            case 1:

                $fieldsToAdd = array();

                /*
                Цикл ниже составит такой fieldsToAdd:

                'fields' => [
                    "TITLE" => $leadTitle, 
                    "NAME" => $leadName,
                    "LAST_NAME" => $leadLast_Name,
                    "COMMENTS" => $leadComments,
                    "ADDRESS" => $leadAddress,
                    "STATUS_ID" => $leadStatus_Id,  
                    "CURRENCY_ID" => $leadCurrency, 
                    "OPPORTUNITY" => $leadOpportunity,
                    "PHONE" => [
                        "VALUE" => [                            // телефон создается именно так!
                            "VALUE" => $leadPhone,
                            "VALUE_TYPE" => "MOBILE"
                        ]
                    ],
                    "EMAIL" => [
                        "VALUE" => [                            // телефон создается именно так!
                            "VALUE" => $leadEmail,
                            "VALUE_TYPE" => "HOME"
                        ]
                    ]
                ]
                */

                foreach ($arrFieldNames as $value) {
                    if(${$nameVar.$value} != "")
                    {
                        $fieldName = mb_strtoupper($value);
                        if($fieldName == 'PHONE')
                        {
                            $fieldsToAdd = 
                            array_merge(
                                $fieldsToAdd, 
                                array(
                                    "PHONE" => [
                                        "VALUE" => [                            // добавляем телефон
                                            "VALUE" => ${$nameVar.'Phone'},
                                            "VALUE_TYPE" => "MOBILE"
                                        ]
                                    ]
                                )
                            );
                            continue;   
                        }
                        if($fieldName == 'EMAIL')
                        {
                            $fieldsToAdd = 
                            array_merge(
                                $fieldsToAdd, 
                                array(
                                    "EMAIL" => [
                                        "VALUE" => [                            // изменение почты
                                            "VALUE" => ${$nameVar.'Email'},
                                            "VALUE_TYPE" => "HOME"
                                        ]
                                    ]
                                )
                            );
                            continue;   
                        }
                        $fieldsToAdd = array_merge($fieldsToAdd, array($fieldName => ${$nameVar.$value}));
                    }
                }

                $response = call(
                    $queryUrl,
                   'crm.'.$nameVar.'.add',
                    [
                      'fields' => $fieldsToAdd
                    ]);
                $result = $response['result'];
                $out = 1;
                break;

            // изменить лид
            case 2:
                $fieldsToChange = array();

                // процесс добавления описан в первом кейсе

                foreach ($arrFieldNames as $value) {
                    if(${$nameVar.$value} != "")
                    {
                        $fieldName = mb_strtoupper($value);
                        if($fieldName == 'PHONE')
                        {
                            $fieldsToChange = 
                            array_merge(
                                $fieldsToChange, 
                                array(
                                    "PHONE" => [
                                        "VALUE" => [                            // добавляем телефон
                                            "VALUE" => ${$nameVar.'Phone'},
                                            "VALUE_TYPE" => "MOBILE"
                                        ]
                                    ]
                                )
                            );
                            continue;   
                        }
                        if($fieldName == 'EMAIL')
                        {
                            $fieldsToChange = 
                            array_merge(
                                $fieldsToChange, 
                                array(
                                    "EMAIL" => [
                                        "VALUE" => [                            // почта как и телефон
                                            "VALUE" => ${$nameVar.'Email'},
                                            "VALUE_TYPE" => "HOME"
                                        ]
                                    ]
                                )
                            );
                            continue;   
                        }
                        $fieldsToChange = array_merge($fieldsToChange, array($fieldName => ${$nameVar.$value}));
                    }
                }

                $response = call(
                    $queryUrl,
                   'crm.'.$nameVar.'.update',
                   [
                      'id' => $inputId,
                      'fields' => $fieldsToChange
                    ]);
                $result = $response['result'];
                $out = 1;
                break;

            // получить лид
            case 3:
                $response = call(
                    $queryUrl,
                   'crm.'.$nameVar.'.get',
                   [
                      'id' => $inputId
                ]);
                $result = $response['result'];

                $found = "";
                foreach ($result as $key => $value) {
                    if($value != "")
                    {
                        // меняем значение ключа на русское для вывода
                        $russianKey = changeValueToRussian($key);

                        // если не нашли русского значения, то пишем англ
                        if(!($russianKey != ""))
                        {
                            $russianKey = $key;
                        }

                        if($key == 'PHONE')
                        {
                            $found .= $russianKey.": ".$value[0]['VALUE']." Тип: ".$value[0]['VALUE_TYPE'].'<br>';
                        }
                        else if($key == 'EMAIL')
                        {
                            $found .= $russianKey.": ".$value[0]['VALUE']." Тип: ".$value[0]['VALUE_TYPE'].'<br>';
                        }
                        // пропускаем значение этого ключа
                        elseif ($key == 'STATUS_SEMANTIC_ID') continue;
                        else
                        {
                            // меняем значение, если Y или N на адекватные русские названия
                            switch ($value) {
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
                $out = 1;
                break;

            // удалить лид
            case 4:
                $response = call(
                    $queryUrl,
                   'crm.'.$nameVar.'.delete',
                   [
                      'id' => $inputId
                ]);
                $result = $response['result'];
                $out = 1;
                break;

            default:
                // code...
                break;
        }
        

        $responce = [
            'out' => $out,         // Обязательно должен быть номер выхода out, отличный от нуля!
            
            'value' => [           // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через $bN_value.ваши_ключи_массива
                'result' => $result,
                'found' => $found
            ]
        ];

    } elseif($act == '') {
        // Действие не задано, и что же нам сделать? Станцевать вальс, попрыгать, пойти в гости к Кролику?

    }

    // Отдать JSON, не кодируя кириллические символы в кракозябры
    echo json_encode($responce, JSON_UNESCAPED_UNICODE);
}
else
{
    echo '<p> Привет, Битрикс! Это приложение для АЮ. Управляй им там :)</p>';
}