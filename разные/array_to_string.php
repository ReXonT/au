<?php


$act = $_REQUEST['act'];

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Режим OPTIONS - в котором ВРМ создаёт в схеме блок управления  *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

if($act == 'options') {
    $responce = [
        'title' => 'Массив в строку',      // Это заголовок блока, который будет виден на схеме
        'vars' => [                     // переменные, которые можно будет настроить в блоке
            'arr' => [
                'title' => 'Переменная с массивом',   // заголовок поля
                'desc' => '',    // описание поля, можно пару строк
            ],
            'var_from_arr' => [
                'title' => 'Название объекта из массива',   // заголовок поля
                'desc' => 'То, что нужно будет конвертировать в строку',    // описание поля, можно пару строк
            ],
            'extype' => [
                'title' => 'Тип вывода строки',   // заголовок поля
                'values' => [
                	1 => 'Через запятую',
                	2 => 'Через пробел'
                ],
                'desc' => '',    // описание поля, можно пару строк
                'default' => 1
            ],
            'negative' => [
                'title' => 'Отбрасывать отрицательные значения?',   
                'desc' => '',
                'format' => 'checkbox'    
            ],

        ],
        'out' => [                      // Это блоки выходов, мы задаём им номера и подписи (будут видны на схеме)
            1 => [                      // Номер 0 означает красный выход блока ВРМ, зарезервированный для случаев сбоя
                'title' => 'Строка',    // название выхода 1
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

    $arr = $options['arr'];
    $extype = $options['extype'];
    $var_from_arr = $options['var_from_arr'];
    $negative = $options['negative'];


    $str = "";
    switch ($extype) {
    	case 1:

    		foreach ($arr as $value) 
    		{
    			if($value[$var_from_arr] < 0)
    			{
    				if($negative)
    					continue;
    			}
    			$str .= $value[$var_from_arr].',';
    		}
    		$str = rtrim($str,',');
    		break;

    	case 2:
    		foreach ($arr as $value) 
    		{
    			if($value[$var_from_arr] < 0)
    			{
    				if($negative)
    					continue;
    			}
    			$str .= $value[$var_from_arr].' ';
    		}
    		break;
    	
    }

    $out = 1;

    $responce = [
        'out' => $out,         // Обязательно должен быть номер выхода out, отличный от нуля!
        
        'value' => [           // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через $bN_value.ваши_ключи_массива
            'str' => $str,     // где N - порядковый номер блока в схеме
            'ng' => $negative
        ]
    ];

} elseif($act == '') {
    // Действие не задано, и что же нам сделать? Станцевать вальс, попрыгать, пойти в гости к Кролику?

}
elseif($act == 'man') {
	$responce = [
			'html' =>
			"
			Доступная Переменная

			{b.{bid}.value.str} - возвращает полученную строку
			"
	];
}

// Отдать JSON, не кодируя кириллические символы в кракозябры
echo json_encode($responce, JSON_UNESCAPED_UNICODE);