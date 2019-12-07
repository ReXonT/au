<?php
require_once('crest.php');

$act = $_REQUEST['act'];

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Режим OPTIONS - в котором ВРМ создаёт в схеме блок управления  *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

// если запрос с АЮ
if(isset($act))
{
    if($act == 'options') {
        $responce = [
            'title' => 'ВРМ Bitrix24',      // Это заголовок блока, который будет виден на схеме
            'vars' => [                     // переменные, которые можно будет настроить в блоке
                'execType' => [
                    'title' => 'Выбор действия',   // заголовок поля
                    'values' => [
                        1 => 'Добавить лид',
                        2 => 'Удалить лид'
                    ],
                    'desc' => '',    // описание поля, можно пару строк
                ],


                // для удаления лида
                'inputId' => [
                    'title' => 'ID лида',
                    'desc' => 'Положительное число',
                    'show' => [
                        'execType' => 2
                    ]
                ]
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

        // тип запроса
        $type = $options['execType'];

        /* Поля для добавления */        
        $inputId = $options['inputId']; // id лида
        $leadTitle = $options['leadTitle']; // title лида
        $leadName = $options['leadName']; // имя лида
        $leadLastName = $options['leadLastName']; // фамилия лида
        $leadComments = $options['leadComments']; // комментарии для лида
        $leadOpportunity = $options['leadOpportunity']; // сумма заказа лида
        $leadPhone = $options['leadPhone']; // номер телефона лида

        switch ($type) {
            case 1:
                $response = CRest::call(
                   'crm.lead.add',
                   [
                      'fields' => [
                        "TITLE" => $leadTitle, 
                        "NAME" => $leadName,
                        "LAST_NAME" => $leadLastName,
                        "MESSAGE" => $leadComments,
                        "STATUS_ID" => "NEW", 
                        "OPENED" => "Y", 
                        "ASSIGNED_BY_ID" => 1, 
                        "CURRENCY_ID" => "RUB", 
                        "OPPORTUNITY" => $leadOpportunity,
                        "PHONE" => [
                            "VALUE" => [                            // телефон создается именно так!
                                "VALUE" => $leadPhone,
                                "VALUE_TYPE" => "WORK"
                            ]
                        ]
                    ]
                ]);
                $result = $response['result'];
                $out = 1;
                break;

            case 2:
                $response = CRest::call(
                   'crm.lead.delete',
                   [
                      'id' => $inputId
                ]);
                $result = $response['result'];
                $out = 1;
            default:
                // code...
                break;
        }
        

        $responce = [
            'out' => $out,         // Обязательно должен быть номер выхода out, отличный от нуля!
            
            'value' => [           // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через $bN_value.ваши_ключи_массива
                'result' => $result,     // где N - порядковый номер блока в схеме
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
    echo '<p> Привет Битрикс! Это приложение для АЮ. Управляй им там :)</p>';
}