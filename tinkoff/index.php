<?php


$act = $_REQUEST['act'];

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Режим OPTIONS - в котором ВРМ создаёт в схеме блок управления  *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

if($act == 'options') {
    $responce = [
        'title' => 'ВРМ Тинькофф',      // Это заголовок блока, который будет виден на схеме
        'vars' => [                     // переменные, которые можно будет настроить в блоке
            'tinkoff_url' => [
                'title' => 'URL Платежа',   
                'desc' => 'Обязательно',    
            ],
            'shopId' => [
                'title' => 'Идентификатор магазина',   
                'desc' => 'Обязательно',    
            ],
            'showcaseId' => [
                'title' => 'Идентификатор витрины',   
                'desc' => 'Обязательно',    
            ],
            'promoCode' => [
                'title' => 'Промокод',   
                'desc' => 'Необязательно',    
            ],
            'customerPhone' => [
                'title' => 'Телефон',   
                'desc' => 'Не обязательно',    
            ],
            'customerEmail' => [
                'title' => 'Email',   
                'desc' => 'Необязательно',    
            ],
            'orderNumber' => [
                'title' => 'Номер заказа',   
                'desc' => 'Необязательно',    
            ],
            'itemName_0' => [
                'title' => 'Название товара',   
                'desc' => 'Обязательно',    
            ],
            'itemQuantity_0' => [
                'title' => 'Количество единиц товара',   
                'desc' => 'Обязательно',    
            ],
            'itemPrice_0' => [
                'title' => 'Стоимость единицы товара в рублях',   
                'desc' => 'Обязательно',    
            ],
        ],
        'out' => [                      // Это блоки выходов, мы задаём им номера и подписи (будут видны на схеме)
            1 => [                      // Номер 0 означает красный выход блока ВРМ, зарезервированный для случаев сбоя
                'title' => 'Результат',    // название выхода 1
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

    $tinkoff_url = $options['tinkoff_url'];

    $field_names = [
        'shopId',
        'showcaseId',
        'promoCode',
        'customerEmail',
        'customerPhone',
        'itemName_0',
        'itemQuantity_0',
        'itemPrice_0',
        'orderNumber'
    ];

    foreach ($field_names as $value) 
    {
        $params[$value] = $options[$value];
    }

    $params['sum'] = $options['itemPrice_0']*$options['itemQuantity_0'];


    $curl = curl_init();

    $arData = http_build_query($params);
    curl_setopt_array($curl, array(
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_POST => 1,
    CURLOPT_HEADER => 0,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $tinkoff_url,
    CURLOPT_POSTFIELDS => $arData,
    ));

    $result = curl_exec($curl);
    curl_close($curl);

    $start = strpos($result, '"');
    $res = substr($result, $start+1);

    $end = strpos($res, '"');
    $link = substr($res, 0, $end);

    $out = 1;

    $responce = [
        'out' => $out,         // Обязательно должен быть номер выхода out, отличный от нуля!
        
        'value' => [           // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через $bN_value.ваши_ключи_массива
            'link' => $link,     // где N - порядковый номер блока в схеме
            'result' => $result
        ]
    ];

} elseif($act == '') {
    // Действие не задано, и что же нам сделать? Станцевать вальс, попрыгать, пойти в гости к Кролику?

}

// Отдать JSON, не кодируя кириллические символы в кракозябры
echo json_encode($responce, JSON_UNESCAPED_UNICODE);