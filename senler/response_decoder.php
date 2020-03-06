<?php


$act = $_REQUEST['act'];

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Режим OPTIONS - в котором ВРМ создаёт в схеме блок управления  *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

if($act == 'options') {
    $responce = [
        'title' => 'Расшифровка ответа',      // Это заголовок блока, который будет виден на схеме
        'vars' => [                     // переменные, которые можно будет настроить в блоке
            'b0_request' => [
                'title' => 'Ответ',   // заголовок поля
                'default' => '$b0_input',
                'desc' => 'Не меняйте, если всё работает',    // описание поля, можно пару строк
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
    $b0_request = $options['b0_request'];

    $request_keys = array_keys($b0_request['object']);

    $responce['value']['type'] = $b0_request['type'];

    foreach ($request_keys as $value) {
    	$array[$value] = $b0_request['object'][$value];
    }

    $array['uid'] = $array['vk_user_id'];

    if( $array['type'] == 'subscribe' )
    {
    	$array['type'] = 'подписка';
    }
    else if ( $array['type'] == 'unsubscribe' )
    {
    	$array['type'] = 'отписка';
    }

    
    $out = 1;
    
    $responce['out'] = $out;

    $responce['value']['b0'] = $b0_request;

    foreach ($array as $key => $value) {
    	$responce['value'][$key] = $value;
    }

} elseif($act == 'man') {
    $responce = [
    	'html' => 
    	"Доступные переменные

    	{b.{bid}.value.type} - Тип запроса (подписка или отписка)
    	{b.{bid}.value.vk_group_id} - ID группы VK, в котором произошло действие
		{b.{bid}.value.uid} - VK ID пользователя
		или также можно использовать
		{b.{bid}.value.vk_user_id} - VK ID пользователя
		{b.{bid}.value.date} - Дата события в формате 2020-02-21 03:10:52
		{b.{bid}.value.subscription_id} - Идентификатор группы подписчиков (Если 0, то без группы)
		{b.{bid}.value.first} - Передается 1, если пользователя не было в базе до подписки
		{b.{bid}.value.full_unsubscribe} - Передается 1, если подписчик полностью отписался от сообщений (при отписке)
		{b.{bid}.value.utm_id} - Передается идентификатор метки (если есть)
		{b.{bid}.value.utm_source} - Передается utm_source (если есть)
		{b.{bid}.value.utm_medium} - Передается utm_medium (если есть)
		{b.{bid}.value.utm_campaign} - Передается utm_campaign (если есть)
		{b.{bid}.value.utm_content} - Передается utm_content (если есть)
		{b.{bid}.value.utm_term} - Передается utm_term (если есть)
    	"
    ];

}

// Отдать JSON, не кодируя кириллические символы в кракозябры
echo json_encode($responce, JSON_UNESCAPED_UNICODE);
