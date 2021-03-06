<?php

//ini_set('display_errors', 1);

require_once 'src/models/base.php';
require_once 'src/justclick.php';
require_once 'src/functions.php';
require_once 'src/models/contact.php';

$act = $_REQUEST['act'];

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Режим OPTIONS - в котором ВРМ создаёт в схеме блок управления  *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

if ($act == 'options')
{
    include_once 'src/fields/vrm_fields_contacts.php';
}

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Режим RUN - в котором ВРМ получает, обрабатывает и возвращает  *
 * полученные от схемы данные                                   *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
elseif ($act == 'run')
{   
    // Схема прислала данные, обрабатываем
    $target = $_REQUEST['target']; // Пользователь, от имени которого выполняется блок
    $ums = $_REQUEST['ums'];    // Данные об активности пользователя, массив в котором есть
                                // id - номер элемента (комментария, поста, смотря о чём речь в активности)
                                // from_id - UID пользователя
                                // date - дата в формате timestamp
                                // text - текст комментария, сообщения и т.д.
    $out = 0; // Номер выхода по умолчанию. Если дальнейший код не назначит другой выход - значит что-то не так
    
    $options = $_REQUEST['options'];

    $ps = $_REQUEST['paysys']['ps'];
    $pso = $ps['options']; // Здесь параметры платежной системы
    
    $jc = new JustClick($pso['account'], $pso['secret']);

    $contact = new Contact();

    switch ($options['lead_option'])
    {
        // Добавить контакт в группу  
        case 1:
            $contact->setMailingId($options['mailing_id']);
            $contact->setLeadName($options['lead_name']);
            $contact->setLeadEmail($options['lead_email']);
            $contact->setPhone($options['lead_phone']);
            $contact->setCity($options['lead_city']);
            $contact->setTag($options['tag']);
            $contact->setDoneUrl($options['done_url']);

            // UTM метки
            $contact->setUtm([
                'utm_source' => $options['utm_source'],
                'utm_medium' => $options['utm_medium'],
                'utm_campaign' => $options['utm_campaign'],
                'utm_content' => $options['utm_content'],
                'utm_term' => $options['utm_term']
            ]);

            // Партнерские метки
            $contact->setUtmAff([
                'aff_source' => $options['aff_source'],
                'aff_medium' => $options['aff_medium'],
                'aff_campaign' => $options['aff_campaign'],
                'aff_content' => $options['aff_content'],
                'aff_term' => $options['aff_term']
            ]);

            $response = $jc->addLeadToGroup($contact);
            break;

        // Изменить данные контакта  
        case 2:
            $contact->setLeadEmail($options['lead_email']);
            $contact->setLeadName($options['lead_name']);
            $contact->setPhone($options['lead_phone']);
            $contact->setCity($options['lead_city']);

            $response = $jc->updateSubscriberData($contact);
            break;

        // Отписать от группы
        case 3:
            $contact->setLeadEmail($options['lead_email']);
            $contact->setMailingName($options['mailing_id']);

            $response = $jc->deleteSubscribe($contact);
            break;

        // Получить все группы контакта  
        case 4:
            $contact->setEmail($options['lead_email']);

            $response = $jc->getLeadGroupStatuses($contact);

            if($options['to_text'])
            {
                $result = '';
                $number = 1;
                foreach ($response['result'] as $value)
                {
                    $result .= "Группа №" . $number++ . "\n";
                    $result .= transformDataToText($value);
                    $result .= "------\n";
                }
            }
            break;

        // Получить все группы из аккаунта  
        case 5:
            $contact->clearData();

            $response = $jc->getAllGroups($contact);
            break;
        case 6:
            if( empty($options['getcount_type']) )
                $contact->setGroupName($options['mailing_id']);
            else
                $contact->setGroupName($options['getcount_type']);

            $response = $jc->getCountSubscribe($contact);
            $result = $response['result'];
            break;
    }

    // Декодируем код ответа в текст для отладки
    $answer = errorCodeToRussian(
        $response['error_code']
    );

    $out = 1;

    $responce = [
        'out' => $out, // Обязательно должен быть номер выхода out, отличный от нуля!
        'value' => [ // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через $bN_value.ваши_ключи_массива
            'result' => $result, // где N - порядковый номер блока в схеме
            'response' => $response,
            'answer' => $answer
        ]
    ];

}
elseif ($act == 'man')
{
    $responce = [
        'html' => '##Описание
        Данная ВРМ работает с аккаунтом JustClick, который Вы указали в интеграции. Подробная инструкция тут - https://vk.com/@rexont-justclick-and-au

        ###Доступные переменные:
        **{b.{bid}.value.result}**
        текстовое отображение результата выполнения

        **{b.{bid}.value.response}**
        полный массив данных в ответе
        
        ####Для отладки
        **{b.{bid}.value.answer}**
        текстовый ответ о выполнении запроса от JustClick
        '
    ];
}

// Отдать JSON, не кодируя кириллические символы в кракозябры
echo json_encode($responce, JSON_UNESCAPED_UNICODE);