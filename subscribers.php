<?php


require_once('senler.php');

$act = $_REQUEST['act'];

if($act == 'options') 
{
    $responce = [
        'title' => 'ВРМ Senler',      		// Это заголовок блока, который будет виден на схеме
        'paysys' => [                   // Группа полей, отвечающая за интеграцию с платёжными системами и внешними сервисами.
            'ps' => [                   // ВРМ получит доступ к ID аккаунта, секретному ключу и другим атрибутам выбранной системы
                'title' => 'Senler',
                'type' => 6
            ]
        ],
        'vars' => [                     	// переменные, которые можно будет настроить в блоке
            'option' => [
                'title' => 'Раздел',
                'values' => [
                    1 => 'Подписки',
                    2 => 'Бот',
                    3 => 'Переменные',
                    4 => 'Метки'
                ],
                'default' => 0
            ],
            'exec_type' => [
                'title' => 'Тип запроса',   
                'values' => [
                    1 => 'Подписать в группу',
                    2 => 'Отписать от группы',
                    3 => 'Получить данные подписок по группе рассылки',
                    4 => 'Получить информацию о группе рассылки'
                ],
                'default' => 0,
                'show' => [
                    'option' => 1
                ]          	
            ],
            'senler_group_id' => [
                'title' => 'ID группы Senler',   
                'desc' => 'Из Senler',    	
                'default' => '',
                'show' => [
                    'exec_type' => 1,
                ]       	
            ],
            'senler_utm_id' => [
                'title' => 'ID UTM метки Senler',   
                'desc' => 'Не обязательно',    	
                'default' => '',
                'show' => [
                    'exec_type' => 1
                ],
                'more' => 1           	
            ],
            'date_from' => [
                'title' => 'Дата начала проверки',   
                'desc' => 'Формат: 13.05.2019 00:00:00',     
                'default' => '13.05.2019 00:00:00',
                'show' => [
                    'exec_type' => 3,
                ]             
            ],
            'date_to' => [
                'title' => 'Дата конца проверки',   
                'desc' => 'Формат: 13.05.2019 00:00:00',     
                'default' => '15.05.2019 23:59:59',
                'show' => [
                    'exec_type' => 3,
                ]            
            ],
        ],
        'out' => [                      	// Это блоки выходов, мы задаём им номера и подписи (будут видны на схеме)
            1 => [                      	// Номер 0 означает красный выход блока ВРМ, зарезервированный для случаев сбоя
                'title' => 'Результат',    	// название выхода 1
            ]
        ]
    ];

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Режим RUN - в котором ВРМ получает, обрабатывает и возвращает  *
     * полученные от схемы данные                                   *
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
} 
elseif($act == 'run') 
{              			// Схема прислала данные, обрабатываем

    $target = $_REQUEST['target'];  			// Пользователь, от имени которого выполняется блок
    $ums    = $_REQUEST['ums'];     			// Данные об активности пользователя, массив в котором есть:
											    // id - номер элемента (комментария, поста, смотря о чём речь в активности)
											    // from_id - UID пользователя
											    // date - дата в формате timestamp
											    // text - текст комментария, сообщения и т.д.
    $options = $_REQUEST['options'];

    $ps = $_REQUEST['paysys']['ps'];			// Сюда придут настройки выбранной системы

    $exec_type = $options['exec_type'];			// получаем тип запроса
    $target_id = $options['target_id'];			// забираем id из цели

    $vk_group_id = $ps['options']['owner_id'];

    // если стоит цель не на инициатора активности
 	if(isset($target_id)) { $user_id = $target_id; }
 	else { $user_id = $ums['from_id']; }

 	// настройки сенлер
	$callback_key = $ps['options']['secret'];	//получаем callback key
    
    // специфические для подписки
    $senler_group_id = $options['senler_group_id'];
    $senler_utm_id = $options['senler_utm_id'];

    // для проверки подписок/отписок в интервале дат
    $date_from = $options['date_from'];
    $date_to = $options['date_to'];

    $senler = new Senler($callback_key, $vk_group_id);

    $out = 0;

    switch ($exec_type) 
    {
    	//если нужно подписать человека
    	case '1':
			$answer = $senler->addSubscriber($senler_group_id, $user_id, $senler_utm_id);
			$message = 'Подписан';
			$out = 1;	// устанавливаем 1 выход
    		break;

    	case '2':	//если нужно отписать человека
    		$answer = $senler->deleteSubscriber($senler_group_id, $user_id);
			$message = 'Удалён';
			$out = 1;	// устанавливаем 1 выход
    		break;

        case '3':   //если нужно отписать человека
            $answer = $senler->getStatSubscribe($date_from, $date_to, $senler_group_id);
            $sub_stat = $answer['count'];
            $count_sub = $answer['count_subscribe'];
            $count_unsub = $answer['count_unsubscribe'];
            $message = 'Получены данные';
            $out = 1;   // устанавливаем 1 выход
            break;

        case '4':
            $answer = $senler->getSubscribersFromGroup($senler_group_id);
            $sub_stat = $answer['count'];
            $message = 'Данные получены';
            $out = 1;   // устанавливаем 1 выход
            break;

    	default:
    		// code...
    		break;

    }
    $success = $answer['success'];
    
    if(!$success)
    {
    	$error_name = $answer['error_message'];
    }


/* Сформировать массив данных на отдачу */
    $responce = [
        'out' => $out,         					// Обязательно должен быть номер выхода out, отличный от нуля!
        'value' => [           					// Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через 
        										// $bN_value.ваши_ключи_массива
            'error_name' => $error_name,
            'message' => $message,
            'count' => $sub_stat,
            'count_sub' => $count_sub,
            'count_unsub' => $count_unsub
        ]
    ];

} 
elseif($act == '') {
    /* Действие не задано, и что же нам сделать? Станцевать вальс, попрыгать, пойти в гости к Кролику? */

}

/* Отдать JSON, не кодируя кириллические символы в кракозябры */
echo json_encode($responce, JSON_UNESCAPED_UNICODE);