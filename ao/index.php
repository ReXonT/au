<?php
require_once('AwoApi.php');

$act = $_REQUEST['act'];

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Режим OPTIONS - в котором ВРМ создаёт в схеме блок управления  *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

if($act == 'options') {
    $responce = [
        'title' => 'АвтоВебОфис Добавить счет',      // Это заголовок блока, который будет виден на схеме
        'paysys' => [                   // Группа полей, отвечающая за интеграцию с платёжными системами и внешними сервисами.
                'ps' => [                   // ВРМ получит доступ к ID аккаунта, секретному ключу и другим атрибутам выбранной системы
                    'title' => 'Выберите из списка',
                    'type' => 10
                ]
            ],
        'vars' => [                     // переменные, которые можно будет настроить в блоке
            /* юзер данные */
            'user_email' => [
            	'title' => 'Email клиента',
                'desc' => ''
            ],
            'user_phone' => [
            	'title' => 'Телефон клиента',
                'desc' => ''
            ],
            'user_name' => [
            	'title' => 'Имя',
                'desc' => ''
            ],
            'user_last_name' => [
            	'title' => 'Фамилия',
                'desc' => ''
            ],

            /* данные счета */
            'deal_id' => [
            	'title' => 'ID товара',
            	'desc' => ''
            ],
            'deal_name' => [
            	'title' => 'Название товара',
            	'desc' => ''
            ],
            'deal_price' => [
            	'title' => 'Цена товара',
            	'desc' => ''
            ],
            'deal_count' => [
            	'title' => 'Количество товара',
            	'desc' => ''
            ]

        ],
        'out' => [                      // Это блоки выходов, мы задаём им номера и подписи (будут видны на схеме)
            1 => [                      // Номер 0 означает красный выход блока ВРМ, зарезервированный для случаев сбоя
                'title' => 'Успех',    // название выхода 1
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


/**
 * Оформление заказа
 * Создаем/изменяем контакт, создание счета к контакту, наполнение строк счета, получение ссылки на оплату созданного счета
 */
 	$ps = $_REQUEST['paysys']['ps'];            // Сюда придут настройки выбранной системы
 	$api_key_read = $ps['options']['secret'];
 	$api_key_write = $ps['options']['secret2'];
 	$subdomain = $ps['options']['account'];

	$user = [
		'name'=>$options['user_name'],
		'last_name'=>$options['user_last_name'],
		'email' => $options['user_email'],
		'phone' => $options['user_phone'],
		'vk_id' => $target
	];

	$deal = [
		'name'=>$options['deal_name'],
		'id'=>$options['deal_id'],
		'price' => $options['deal_price'],
		'count' => $options['deal_count']
	];


	$api = new AwoApi([
	    'apiKeyRead' => $api_key_read,
	    'apiKeyWrite' => $api_key_write,
	    'subdomain' => $subdomain,
	]);


	/*// создаем контакт (если контакт с таким email существует, информация о нем будет обновлена)
	$contact = $api->contact()->create([
	    [
	        'email' => $user['email'],
	        'phone_number' => $user['phone'],
	        'last_name' => $user['last_name'],
	        'name' => $user['name'],
	        'vk_user' => $user['vk_id']

	        // полный список доступных полей смотрите в документации
	    ]
	]);

	if (!is_object($contact)) {
	    echo 'Ошибка при создании контакта: ' . $contact;
	}

	$idContact = $contact->id_contact;*/

	// создаем счет для этого контакта
	$invoice = $api->invoice()->create([
	    [
	        'email' => $user['email'],
	        'phone_number' => $user['phone'],
	        'last_name' => $user['last_name'],
	        'name' => $user['name'],
	        'account_sum' => $deal['count']*$deal['price'],
	        'id_account_status' => 1 // создан
	        // полный список доступных полей смотрите в документации
	    ]
	]);

	if (!is_object($invoice)) {
	    echo 'Ошибка при создании счета: ' . $invoice;
	}

	$idInvoice = $invoice->id_account;

	// создаем строки счета
	$invoiceLine = $api->invoiceLine()->create([
	    [
	    	'id_goods' => $deal['id'],
	    	'goods' => $deal['name'],
        	'price' => $deal['price'], // цена со скидкой
        	'quantity' => $deal['count'], // количество
        	'sum_price' => $deal['count']*$deal['price'], // сумма (количество * цена со скидкой)
        	'id_account' => $idInvoice, // ID счета, к которому относится строка
	        // полный список доступных полей смотрите в документации
	    ]
	]);

	// ссылка на оплату находится в свойстве 'link_for_pay'
	$payment_link = $invoice->link_for_pay;
	$out = 1;

    $responce = [
        'out' => $out,         // Обязательно должен быть номер выхода out, отличный от нуля! 
        'value' => [           // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через $bN_value.ваши_ключи_массива
            'payment_link' => $payment_link     // где N - порядковый номер блока в схеме
        ]
    ];

} elseif($act == '') {
    // Действие не задано, и что же нам сделать? Станцевать вальс, попрыгать, пойти в гости к Кролику?

}

// Отдать JSON, не кодируя кириллические символы в кракозябры
echo json_encode($responce, JSON_UNESCAPED_UNICODE);