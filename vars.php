<?php


require_once('senler.php');



$act = $_REQUEST['act'];

if($act == 'options') 
{
    $responce = [
        'title' => 'ВРМ Senler Переменные',      		// Это заголовок блока, который будет виден на схеме
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
                    1 => 'Установить переменную',
                    2 => 'Получить переменные',
                    3 => 'Удалить переменную'
                ]           
            ],
            'senler_var_name' => [
                'title' => 'Имя переменной',   
                'desc' => 'Слитно на латинице. Оставьте пустым, если нужно получить все переменные',      
                'default' => ''             
            ],
            'senler_var_value' => [
                'title' => 'Значение переменной',   
                'desc' => 'Число. Не обязательно при получении и удалении',     
                'default' => ''             
            ],
            'get_type' => [
                'title' => 'Формат вывода переменных',   
                'values' => [
                    1 => '*Переменная* равна *значение*',
                    2 => 'Только значения построчно',
                    3 => 'Только значения через запятую',
                    4 => 'Только имена построчно',
                    5 => 'Только имена через запятую'
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

    // переменные
    $senler_var_name = $options['senler_var_name'];
    $senler_var_value = $options['senler_var_value'];
    $get_type = $options['get_type'];

    $senler = new Senler($callback_key, $vk_group_id);

    switch ($exec_type) 
    {
    	
    	case '1':	// установить переменную
    		$answer = $senler->setVar($user_id, $senler_var_name, $senler_var_value);
			$message = 'Установлена переменная';
			$out = 1;	// устанавливаем 1 выход
    		break;

        case '2':   // получить переменную
            $answer = $senler->getVar($user_id, $senler_var_name);
            
            $found_vars = "";

            switch ($get_type) 
            {
                // *переменная* равна *значение*
                case 1:             
                    foreach ($answer['items'] as $value) 
                    {
                        $found_vars .= $value['name']." равна ".$value['value'].'\n';
                    }
                    break;

                // значения построчно
                case 2:
                    foreach ($answer['items'] as $value) 
                    {
                        $found_vars .= $value['value'].'\n';
                    }
                    break;

                // значения через запятую
                case 3:
                    foreach ($answer['items'] as $value) 
                    {
                        $found_vars .= $value['value'].', ';
                    }
                    $found_vars = rtrim($found_vars, ', ');
                    break;

                // названия построчно
                case 4:
                    foreach ($answer['items'] as $value) 
                    {
                        $found_vars .= $value['name'].'\n';
                    }
                    break;

                // названия через запятую
                case 5:
                    foreach ($answer['items'] as $value) 
                    {
                        $found_vars .= $value['name'].', ';
                    }
                    $found_vars = rtrim($found_vars, ', ');
                    break;

                default:
                    // code...
                    break;
            }
            

            $message = 'Получены переменные';
            $out = 1;   // устанавливаем 1 выход
            break;

        case '3':   // удалить переменную
            $answer = $senler->delVar($user_id, $senler_var_name);
            $message = 'Удалена переменная';
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
            'found_vars' => $found_vars
        ]
    ];

} 
elseif($act == '') {
    /* Действие не задано, и что же нам сделать? Станцевать вальс, попрыгать, пойти в гости к Кролику? */

}

/* Отдать JSON, не кодируя кириллические символы в кракозябры */
echo json_encode($responce, JSON_UNESCAPED_UNICODE);