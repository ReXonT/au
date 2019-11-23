<?php


require_once('senler.php');



$act = $_REQUEST['act'];

if($act == 'options') 
{
    $responce = [
        'title' => 'ВРМ Senler Бот',      		// Это заголовок блока, который будет виден на схеме
        'paysys' => [                   // Группа полей, отвечающая за интеграцию с платёжными системами и внешними сервисами.
            'ps' => [                   // ВРМ получит доступ к ID аккаунта, секретному ключу и другим атрибутам выбранной системы
                'title' => 'Senler',
                'type' => 6
            ]
        ],
        'vars' => [                         // переменные, которые можно будет настроить в блоке
            'exec_type' => [
                'title' => 'Тип запроса',   
                'values' => [
                    1 => 'Добавить в бота',
                    2 => 'Удалить из бота'
                ]           
            ],
            'senler_bot_id' => [
                'title' => 'ID бота Senler',   
                'desc' => 'Положительное число',      
                'default' => ''             
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
   
    $ps = $_REQUEST['paysys']['ps'];            // Сюда придут настройки выбранной системы

    $exec_type = $options['exec_type'];         // получаем тип запроса
    $target_id = $options['target_id'];         // забираем id из цели

    $vk_group_id = $ps['options']['owner_id'];

    // если стоит цель не на инициатора активности
    if(isset($target_id))
    {
        $user_id = $target_id;
    }

    else
    {
        $user_id = $ums['from_id'];
    }

    // настройки сенлер
    $callback_key = $ps['options']['secret'];   //получаем callback key
    
    // бот
    $senler_bot_id = $options['senler_bot_id'];

    $senler = new Senler($callback_key, $vk_group_id);

    switch ($exec_type) 
    {
    	case '1':	// добавить в бота
    		$answer = $senler->addToBot($user_id, $senler_bot_id);
			$message = 'Добавлен в бота';
			$out = 1;	// устанавливаем 1 выход
    		break;

    	case '2':	// удалить из бота
    		$answer = $senler->delFromBot($user_id, $senler_bot_id);
			$message = 'Удалён из бота';
			$out = 1;	// устанавливаем 1 выход
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
            'message' => $message
        ]
    ];

} 
elseif($act == '') {
    /* Действие не задано, и что же нам сделать? Станцевать вальс, попрыгать, пойти в гости к Кролику? */

}

/* Отдать JSON, не кодируя кириллические символы в кракозябры */
echo json_encode($responce, JSON_UNESCAPED_UNICODE);