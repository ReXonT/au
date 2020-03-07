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
                    2 => 'Заказ'
                ],
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
            'deal_cost' => [
                'title' => 'Сумма заказа',   // заголовок поля
                'desc' => '',    // описание поля, можно пару строк
                'default' => '',
                'show' => [
                    'exec_type' => 2
                ]
            ],
            'groups' => [
                'title' => 'Группы (по названию)',   // заголовок поля
                'desc' => 'Через запятую',    // описание поля, можно пару строк
                'default' => '',
                'show' => [
                    'exec_type' => [1]
                ]
            ],
            'phone' => [
                'title' => 'Номер телефона',   // заголовок поля
                'desc' => '',    // описание поля, можно пару строк
                'default' => '',
                'show' => [
                    'exec_type' => [1,2]
                ]
            ],
            'email' => [
                'title' => 'Е-майл пользователя',   // заголовок поля
                'desc' => '',    // описание поля, можно пару строк
                'default' => '',
                'show' => [
                    'exec_type' => [1,2]
                ]
            ],

            // Дополнительные поля
            'addFields_exec' => [
            	'title' => 'Дополнительные поля',
                'desc' => 'Выберите количество (не обязательно)',
                'values' => [
                    1 => '1',
                    2 => '2',
                    3 => '3',
                    4 => '4',
                    5 => '5'
                ],
                'more' => 1,
                'default' => 0
            ],
            'addField_name_1' => [
                'title' => 'Заголовок доп. поля 1',   // заголовок поля
                'desc' => 'Заголовок доп.поля из GetCourse',    // описание поля, можно пару строк
                'default' => '',
                'more' => 1,
                'show' => [
                    'addFields_exec' => [1,2,3,4,5]
                ],
                
            ],
            'addField_val_1' => [
                'title' => 'Значение доп. поля 1',   // заголовок поля
                'desc' => '',    // описание поля, можно пару строк
                'default' => '',
                'more' => 1,
                'show' => [
                    'addFields_exec' => [1,2,3,4,5]
                ],
            ],

          	'addField_name_2' => [
                'title' => 'Заголовок доп. поля 2',   // заголовок поля
                'desc' => 'Заголовок доп.поля из GetCourse',    // описание поля, можно пару строк
                'default' => '',
                'show' => [
                    'addFields_exec' => [2,3,4,5]
                ],
                'more' => 1
            ],
            'addField_val_2' => [
                'title' => 'Значение доп. поля 2',   // заголовок поля
                'desc' => '',    // описание поля, можно пару строк
                'default' => '',
                'show' => [
                    'addFields_exec' => [2,3,4,5]
                ],
                'more' => 1
            ],

            'addField_name_3' => [
                'title' => 'Заголовок доп. поля 3',   // заголовок поля
                'desc' => 'Заголовок доп.поля из GetCourse',    // описание поля, можно пару строк
                'default' => '',
                'show' => [
                    'addFields_exec' => [3,4,5]
                ],
                'more' => 1
            ],
            'addField_val_3' => [
                'title' => 'Значение доп. поля 3',   // заголовок поля
                'desc' => '',    // описание поля, можно пару строк
                'default' => '',
                'show' => [
                    'addFields_exec' => [3,4,5]
                ],
                'more' => 1
            ],

            'addField_name_4' => [
                'title' => 'Заголовок доп. поля 4',   // заголовок поля
                'desc' => 'Заголовок доп.поля из GetCourse',    // описание поля, можно пару строк
                'default' => '',
                'show' => [
                    'addFields_exec' => [4,5]
                ],
                'more' => 1
            ],
            'addField_val_4' => [
                'title' => 'Значение доп. поля 4',   // заголовок поля
                'desc' => '',    // описание поля, можно пару строк
                'default' => '',
                'show' => [
                    'addFields_exec' => [4,5]
                ],
                'more' => 1
            ],

            'addField_name_5' => [
                'title' => 'Заголовок доп. поля 5',   // заголовок поля
                'desc' => 'Заголовок доп.поля из GetCourse',    // описание поля, можно пару строк
                'default' => '',
                'show' => [
                    'addFields_exec' => [5]
                ],
                'more' => 1
            ],
            'addField_val_5' => [
                'title' => 'Значение доп. поля 5',   // заголовок поля
                'desc' => '',    // описание поля, можно пару строк
                'default' => '',
                'show' => [
                    'addFields_exec' => [5]
                ],
                'more' => 1
            ],
        ],
        'out' => [                      // Это блоки выходов, мы задаём им номера и подписи (будут видны на схеме)
            1 => [                      // Номер 0 означает красный выход блока ВРМ, зарезервированный для случаев сбоя
                'title' => 'Добавлено',    // название выхода 1
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

    $exec_type = $options['exec_type'];
    // id - номер элемента (комментария, поста, смотря о чём речь в активности)
    // from_id - UID пользователя
    // date - дата в формате timestamp
    // text - текст комментария, сообщения и т.д.
    // Можно передавать несколько хранилищ, но не увлекайтесь с объёмом
    $out = 1;                    // Номер выхода по умолчанию. Если дальнейший код не назначит другой выход - значит что-то не так
    $gc_user_id = 0;


    /* Теперь, начинаем работать с тем, что прислала активность */

    switch ($exec_type) {
        case 1:

                $user = new \GetCourse\User();

                $user::setAccountName($pso['account']);
                $user::setAccessToken($pso['secret']);

                $user
                    ->setEmail($options['email'])
                    ->setFirstName($options['first_name'])
                    ->setLastName($options['last_name'])
                    ->setUserAddField('vk_uid', $target)
                    ->setUserAddField('vk_id', $target)
                    //->setVkId($target)
                    ->setOverwrite()
                    ->setSessionReferer('http://activeusers.ru');

                if(!empty($options['phone']))
                {
                	$user->setPhone($options['phone']);
                }

                if (!empty($options['groups'])) {
                    $tmp = explode(',', $options['groups']);
                    foreach ($tmp as $g) {
                        $g = trim($g);
                        if (!empty($g)) {
                            $user->setGroup($g);
                        }
                    }
                }

                if( !empty( $options[ 'addFields_exec' ] ) && $options[ 'addFields_exec' ] != 0 )
                {
                	for ($i = 1; $i <= $options['addFields_exec'] ; $i++) 
				    {
						$user
							->setUserAddField(
								$options['addField_name_'.$i],
								$options['addField_val_'.$i]
							);    	
				    }
                }

                $result = $user->apiCall($action = 'add');

                if(!empty($_GET['debug'])) {
                    echo '<pre>';
                    print_r($options);
                    print_r($result);
                    echo '</pre>';
                }

                $gc_user_id = $result->result->user_id;

                if($result->result->success != 1) {
                    $error = $result->result->error_message;
                    $out = 0;
                }

            break;
        case 2:

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
                    //->setDealNumber($options['deal_number'])
                    ->setDealCost($options['deal_cost'])
                    ->setReturnPaymentLink();

                if(!empty($options['phone']))
                {
                	$deal->setPhone($options['phone']);
                }

                if( !empty( $options[ 'addFields_exec' ] ) && $options[ 'addFields_exec' ] != 0 )
                {
                	for ($i = 1; $i <= $options['addFields_exec'] ; $i++) 
				    {
						$deal
							->setUserAddField(
								$options['addField_name_'.$i],
								$options['addField_val_'.$i]
							);    	
				    }
                }

                $result = $deal->apiCall($action = 'add');

                $payment_link = $result->result->payment_link;
                $gc_user_id = $result->result->user_id;
                $deal_id = $result->result->deal_id;


            break;
    }
    

    // Сформировать массив данных на отдачу

    $responce = [
        'out' => $out,         // Обязательно должен быть номер выхода out, отличный от нуля!
        'value' => [           // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через $bN_value.ваши_ключи_массива
            'gc_user_id' => $gc_user_id,
            'error' => $error,
            'deal_id' => $deal_id,
            'payment_link' => $payment_link,
            'result' => $result
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
        Данная ВРМ работает с аккаунтом GetCourse, который Вы указали в интеграции. Подробная инструкция тут - https://vk.com/@rexont-au-getcourse

        ###Доступные переменные:
        **{b.{bid}.value.payment_link}**
        ссылка на оплату созданного заказа

        **{b.{bid}.value.gc_user_id}**
        id созданного клиента в Геткурс (для добавления Пользователя)

        **{b.{bid}.value.deal_id}**
        id созданного заказа в Геткурс (для добавления Заказа)
        
        ####Для отладки
        **{b.{bid}.value.error}**
        описание ошибки (если есть)
        
        **{b.{bid}.value.result}**
        полный массив ответа от Геткурс
        '
    ];
}

// Отдать JSON, не кодируя кириллические символы в кракозябры
echo json_encode($responce, JSON_UNESCAPED_UNICODE);


