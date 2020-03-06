<?php


$act = $_REQUEST['act'];

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Режим OPTIONS - в котором ВРМ создаёт в схеме блок управления  *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

if($act == 'options') {
    $responce = [
        'title' => 'Неограниченный массовый вызов',      // Это заголовок блока, который будет виден на схеме
        'vars' => [                     // переменные, которые можно будет настроить в блоке
            'extype' => [
                'title' => 'Выбор действия',   // заголовок поля
                'values' => [
                	1 => 'Запустить программу',
                	2 => 'Начислить ресурс'
                ],
                'desc' => '',    // описание поля, можно пару строк
            ],
            'api_key_cab' => [
                'title' => 'API KEY Кабинета',   // заголовок поля
                'desc' => 'Можно найти в настройках',    // описание поля, можно пару строк
            ],
            'list_ids' => [
                'title' => 'Список ID юзеров для выполнения',   // заголовок поля
                'desc' => 'Строка через запятую или массив. Кому начисляем/для кого выполняем',    // описание поля, можно пару строк
            ],
            'var_from_arr' => [
                'title' => 'Название объекта из массива',   // заголовок поля
                'desc' => 'В какой переменной находится ID. Например, from_id или user_id 
                (указывайте в случае, если в прошлом поле указали массив)',    // описание поля, можно пару строк
            ],
            'negative' => [
                'title' => 'Отбрасывать отрицательные значения?',   
                'desc' => '',
                'format' => 'checkbox'    
            ],
            'act_id' => [
                'title' => 'ID ресурса/активности',   // заголовок поля
                'desc' => '',    // описание поля, можно пару строк
            ],
            'res_count' => [
                'title' => 'Количество ресурса к начислению',   // заголовок поля
                'desc' => '',    // описание поля, можно пару строк
                'show' => [
                	'extype' => [2]
                ]
            ],
            'comment' => [
                'title' => 'Комментарий к начислению/текст для активности',   // заголовок поля
                'desc' => 'Необязательно',    // описание поля, можно пару строк
                'show' => [
                	'extype' => [2]
                ]
            ],
            'act_start' => [
                'title' => 'С какого блока запускать',   // заголовок поля
                'desc' => 'Число. Необязательно. По умолчанию 0',    // описание поля, можно пару строк
                'show' => [
                	'extype' => [1]
                ]
            ],
            
        ],
        'out' => [                      // Это блоки выходов, мы задаём им номера и подписи (будут видны на схеме)
            1 => [                      // Номер 0 означает красный выход блока ВРМ, зарезервированный для случаев сбоя
                'title' => 'Выполнено',    // название выхода 1
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

    $extype = $options['extype'];
    $list_ids = $options['list_ids'];
    $var_from_arr = $options['var_from_arr'];
    $res_count = $options['res_count'];
    $comment = $options['comment'];
    $act_id = $options['act_id'];
    $act_start = $options['act_start'];
    $api_key_cab = $options['api_key_cab'];
    $negative = $options['negative'];

    $i = 0;

    switch ($extype) {
    	// Запуск программы
    	case 1:
    		$i = 0;
    		foreach ($list_ids as $value) 
    		{
    			if($value[$var_from_arr] < 0)
				{
					if($negative)
						continue;
				}
    			$url = 'http://activeusers.ru/api/run/'.$act_id.'?from_id='.$value[$var_from_arr].'&text='.$comment.'&start='.$act_start.'&api_key='.$api_key_cab;

	    		$urls[$i++] = $url;
	    		$myCurl = curl_init(); 
			    curl_setopt_array($myCurl, [ 
			        CURLOPT_URL => $url, 
			        CURLOPT_RETURNTRANSFER => false
			    ]);
			    curl_exec($myCurl); 
			    curl_close($myCurl);
    		}
    		
    		break;

    	// Начислить ресурс
    	case 2:
    		$i = 0;
    		foreach ($list_ids as $value) 
    		{
    			if($value[$var_from_arr] < 0)
				{
					if($negative)
						continue;
				}
    			$url = 'http://activeusers.ru/api/user/'.$value[$var_from_arr].'/res/add/'.$act_id.'?count='.$res_count.'&comment='.$comment.'&api_key='.$api_key_cab;

	    		$urls[$i++] = $url;
	    		$myCurl = curl_init(); 
			    curl_setopt_array($myCurl, [ 
			        CURLOPT_URL => $url, 
			        CURLOPT_RETURNTRANSFER => false
			    ]);
			    curl_exec($myCurl); 
			    curl_close($myCurl);
    		}
    		
    		break;
    	
    }

    
    $out = 1;

    $responce = [
        'out' => $out,         // Обязательно должен быть номер выхода out, отличный от нуля!
        
        'value' => [           // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через $bN_value.ваши_ключи_массива
            'result' => $urls,     // где N - порядковый номер блока в схеме
        ]
    ];

} elseif($act == '') {
    // Действие не задано, и что же нам сделать? Станцевать вальс, попрыгать, пойти в гости к Кролику?

}

// Отдать JSON, не кодируя кириллические символы в кракозябры
echo json_encode($responce, JSON_UNESCAPED_UNICODE);