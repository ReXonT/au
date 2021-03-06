<?php
require 'senler.class.php';

$act = $_REQUEST['act'];

if($act == 'options') 
{
    $responce = [
        'title' => 'ВРМ Senler',        // Это заголовок блока, который будет виден на схеме
        'paysys' => [                   // Группа полей, отвечающая за интеграцию с платёжными системами и внешними сервисами.
            'ps' => [                   // ВРМ получит доступ к ID аккаунта, секретному ключу и другим атрибутам выбранной системы
                'title' => 'Senler',
                'type' => 6
            ]
        ],
        'vars' => [                         // переменные, которые можно будет настроить в блоке
            
            'option' => [
                'title' => 'Раздел',
                'values' => [
                    1 => 'Подписки',
                    2 => 'Бот',
                    3 => 'Переменные',
                    4 => 'Метки'
                ],
                'default' => ''
            ],

            /* Основные типы запроса */ 
            'sub_type' => [
                'title' => 'Тип запроса',   
                'values' => [
                    1 => 'Подписать в группу',
                    2 => 'Отписать от группы',
                    3 => 'Получить данные подписок по группе рассылки',
                    4 => 'Получить информацию о группе рассылки'
                ],
                'show' => [
                    'option' => 1
                ],
                'default' => ''
            ],
            'bot_type' => [
                'title' => 'Тип запроса',   
                'values' => [
                    1 => 'Добавить в бота',
                    2 => 'Удалить из бота'
                ],
                'show' => [
                    'option' => 2
                ],
                'default' => ''   
            ],
            'var_type' => [
                'title' => 'Тип запроса',   
                'values' => [
                    1 => 'Установить переменную',
                    2 => 'Получить переменные',
                    3 => 'Удалить переменную'
                ],
                'show' => [
                    'option' => 3
                ],
                'default' => ''       
            ],
            'utm_type' => [
                'title' => 'Тип запроса',   
                'values' => [
                    1 => 'Добавить метку',
                    2 => 'Изменить метку',
                    3 => 'Удалить метку',
                    4 => 'Получить все метки',
                    5 => 'Получить ссылку метки'
                ],
                'show' => [
                    'option' => 4
                ],
                'default' => ''      
            ],

            /* Верхние поля */

            // subs
            'senler_group_id' => [
                'title' => 'ID группы рассылки',   
                'desc' => 'Из Senler',      
                'default' => '',
                'show' => [
                    'option' => 1,
                    'sub_type' => [1,2,3,4]
                ]           
            ],

            // bot
            'senler_bot_id' => [
                'title' => 'ID бота Senler',   
                'desc' => 'Положительное число',      
                'default' => '',
                'show' => [
                    'option' => 2,
                    'bot_type' => [1,2]
                ]           
            ],

            // var
            'senler_var_name' => [
                'title' => 'Имя переменной',   
                'desc' => 'Слитно на латинице. Оставьте пустым, если нужно получить все переменные',      
                'default' => '',
                'show' => [
                    'option' => 3,
                    'var_type' => [1,2,3]
                ]            
            ],

            // utm
            'utm_group_id' => [
                'title' => 'ID группы рассылки',   
                'desc' => 'Из Senler',      
                'default' => '',
                'show' => [
                    'option' => 4,
                    'sub_type' => 5
                ]           
            ],
            'utm_id' => [
                'title' => 'ID UTM метки',   
                'desc' => '',      
                'default' => '',
                'show' => [
                    'option' => 4,
                    'utm_type' => [2,3,5]
                ]            
            ],
            'utm_name' => [
                'title' => 'Название UTM метки',   
                'desc' => '',      
                'default' => '',
                'show' => [
                    'option' => 4,
                    'utm_type' => [1,2]
                ]            
            ],
            
            /* Второстепенные поля */

            // var
            'senler_var_value' => [
                'title' => 'Значение переменной',   
                'desc' => 'Число. Не обязательно при получении и удалении',     
                'default' => '',
                'show' => [
                    'option' => 3,
                    'var_type' => 1
                ]              
            ],
            'get_type' => [
                'title' => 'Формат вывода переменных',   
                'values' => [
                    1 => '*Переменная* равна *значение*',
                    2 => 'Только значения построчно',
                    3 => 'Только значения через запятую',
                    4 => 'Только имена построчно',
                    5 => 'Только имена через запятую'
                ],
                'show' => [
                    'option' => 3,
                    'var_type' => 2
                ]                
            ],

            // utm
            'force' => [
                'title' => 'Подписка при переходе',   
                'desc' => 'Автоматически подписывать при нажатии на ссылку',
                'format' => 'checkbox',      
                'show' => [
                    'option' => 4,
                    'utm_type' => 5
                ]            
            ],

            // для разных полей
            'date_from' => [
                'title' => 'Дата начала проверки',   
                'desc' => 'С какого времени проверяем',     
                'format' => 'datetime',
                'show' => [
                    'option' => 1,
                    'sub_type' => 3,
                ]             
            ],
            'date_to' => [
                'title' => 'Дата конца проверки',   
                'desc' => 'До какого времени проверяем',     
                'format' => 'datetime',
                'show' => [
                    'option' => 1,
                    'sub_type' => 3,
                ]            
            ],

            /* Доп. настройки */
            'senler_utm_id' => [
                'title' => 'ID UTM метки Senler',   
                'desc' => 'Не обязательно',     
                'default' => '',
                'show' => [
                    'option' => 1,
                    'sub_type' => 1
                ],            
            ],
        ],
        'out' => [                          // Это блоки выходов, мы задаём им номера и подписи (будут видны на схеме)
            1 => [                          // Номер 0 означает красный выход блока ВРМ, зарезервированный для случаев сбоя
                'title' => 'Результат',     // название выхода 1
            ]
        ]
    ];
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Режим RUN - в котором ВРМ получает, обрабатывает и возвращает  *
     * полученные от схемы данные                                   *
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
} 
elseif($act == 'run') 
{                                               // Схема прислала данные, обрабатываем
    $target = $_REQUEST['target'];              // Пользователь, от имени которого выполняется блок
    $ums    = $_REQUEST['ums'];                 // Данные об активности пользователя, массив в котором есть:
                                                // id - номер элемента (комментария, поста, смотря о чём речь в активности)
                                                // from_id - UID пользователя
                                                // date - дата в формате timestamp
                                                // text - текст комментария, сообщения и т.д.
    $options = $_REQUEST['options'];
    
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Основные настройки для Senler:                            *
     * получаем данные callback, vk group id и user id           *
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    $ps = $_REQUEST['paysys']['ps'];            // Сюда придут настройки выбранной системы
    
    // настройки сенлер
    $callback_key = $ps['options']['secret'];   // получаем callback key
    $vk_group_id = $ps['options']['owner_id'];  // id вк группы пользователя
    
    // если стоит цель не на инициатора активности
    if(isset($target)) { $user_id = $target; } 
    else { $user_id = $ums['from_id']; }

    $senler = new Senler($callback_key, $vk_group_id);
               
    $out = 0;                                   // выход в 0 (ошибка)

     /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Выбираем тип запроса: Подписки/Бот/Переменные/Метки       *
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    $option = $options['option'];               // номер выбранного раздела
    switch ($option) {

        // работа с подписками
        case 1:
            $sub_type = $options['sub_type'];               // получаем тип запроса
            $senler_group_id = $options['senler_group_id']; // получаем id группы подписок Senler
            $senler_utm_id = $options['senler_utm_id'];     // получаем id utm метки Senler
            
            // для проверки подписок/отписок в интервале дат
            $date_from = $options['date_from'];             // дата начала интервала
            $date_from = date('d.m.Y H:i:s',$date_from);    // перевод из unixtime в нужный формат
            $date_to = $options['date_to'];                 // дата конца интервала
            $date_to = date('d.m.Y H:i:s',$date_to);        // перевод из unixtime в нужный формат
            
            switch ($sub_type) 
            {
                //если нужно подписать человека
                case '1':
                    $answer = $senler->addSubscriber($senler_group_id, $user_id, $senler_utm_id);
                    $message = 'Подписан';
                    $out = 1;   // устанавливаем 1 выход
                    break;
                case '2':   //если нужно отписать человека
                    $answer = $senler->deleteSubscriber($senler_group_id, $user_id);
                    $message = 'Удалён';
                    $out = 1;   // устанавливаем 1 выход
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
            break;
        
        // работа с ботом
        case 2:
            $bot_type = $options['bot_type'];   // получаем тип запроса
            
            // бот id
            $senler_bot_id = $options['senler_bot_id'];
            switch ($bot_type) 
            {
                case '1':   // добавить в бота
                    $answer = $senler->addToBot($user_id, $senler_bot_id);
                    $message = 'Добавлен в бота';
                    $out = 1;   // устанавливаем 1 выход
                    break;
                case '2':   // удалить из бота
                    $answer = $senler->delFromBot($user_id, $senler_bot_id);
                    $message = 'Удалён из бота';
                    $out = 1;   // устанавливаем 1 выход
                    break;
                default:
                    // code...
                    break;
            }
            break;
        
        // работа с переменными
        case 3:
            $var_type = $options['var_type'];   // получаем тип запроса
            $senler_var_name = $options['senler_var_name'];
            $senler_var_value = $options['senler_var_value'];
            $get_type = $options['get_type'];
            switch ($var_type) 
            {
                
                case '1':   // установить переменную
                    $answer = $senler->setVar($user_id, $senler_var_name, $senler_var_value);
                    $message = 'Установлена переменная';
                    $out = 1;   // устанавливаем 1 выход
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
            break;
        
        // работа с метками
        case 4:
            $utm_type = $options['utm_type'];   // получаем тип запроса
            // utm
            $utm_id = $options['utm_id'];
            $utm_name = $options['utm_name'];
            $utm_group_id = $options['utm_group_id']; // получаем id группы подписок Senler
            $force = $options['force']; // для ссылки: автоподписка true/false
            switch ($utm_type) 
            {
                case '1':   // добавить метку
                    $answer = $senler->addUtm($utm_name);
                    $message = 'Добавлена метка';
                    $out = 1;   // устанавливаем 1 выход
                    break;
                case '2':   // редактировать метку
                    $answer = $senler->editUtm($utm_id, $utm_name);
                    $message = 'Метка отредактирована';
                    $out = 1;   // устанавливаем 1 выход
                    break;
                case '3':   // удалить метку
                    $answer = $senler->delUtm($utm_id);
                    $message = 'Метка удалена';
                    $out = 1;   // устанавливаем 1 выход
                    break;
                case '4':   // получить метки
                    $answer = $senler->getUtm();
                    $count = $answer['count'];
                    $found_utm = $answer['items'];
                    $message = 'Метки получены';
                    $out = 1;   // устанавливаем 1 выход
                    break;
                case '5':   // получить ссылку для метки
                    $answer = $senler->getUtmLink($utm_id, $utm_group_id, $force);
                    $utm_link = $answer['link'];
                    $message = 'Ссылка для метки получена';
                    $out = 1;   // устанавливаем 1 выход
                    break;
                default:
                    
                    break;
            }
            break;
        default:
            
            break;
    }
    
    if(!$answer['success'])
    {
        $error_name = $answer['error_message'];
        $message = 'Ошибка: ' . $answer['error_message'];
        $error_message = $senler->getErrorMessage($answer['error_code']);
    }

    /* Сформировать массив данных на отдачу */
    $responce = [
        'out' => $out,                          // Обязательно должен быть номер выхода out, отличный от нуля!
        'value' => [                            // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через 
                                                // $bN_value.ваши_ключи_массива
            'error_name' => $error_name,
            'error_message' => $error_message,
            'message' => $message,
            'count' => $sub_stat,
            'count_sub' => $count_sub,
            'count_unsub' => $count_unsub,
            'found_vars' => $found_vars,
            'found_utm' => $found_utm,
            'utm_link' => $utm_link
        ]
    ];
} 
elseif($act == '') {
    
}
elseif($act == 'man') {
    $responce = [
        'html' => 
        '##Описание
        Данная ВРМ работает с аккаунтом Senler, который Вы указали в интеграции. Подробная инструкция тут - https://vk.com/@rexont-senler-i-au
        
        ###Доступные переменные:

        **{b.{bid}.value.count}**
        Доступно для методов:
            1. Подписки → Получить данные подписок по группе рассылки
            Показывает общее количество подписок + отписок за указанные даты
            2. Подписки → Получить информацию о группе подписки
            Показывает общее количество подписок + отписок по выбранной группе
            3. Метки → Получить метки
            Показывает общее количество всех меток

        **{b.{bid}.value.count_sub}**
            Показывает общее количество подписок за указанные даты

        **{b.{bid}.value.count_unsub}**
            Показывает общее количество отписок за указанные даты

        **{b.{bid}.value.utm_link}**
            Формирует ссылку на подписку по выбранной метке

        **{b.{bid}.value.message}**
            Показывает сообщение о том, что метод выполнен

        **{b.{bid}.value.found_vars}**
            Показывает выбранные переменные

        **{b.{bid}.value.found_utm}**
            Смотрите инструкцию по интеграции vk.com/@rexont-senler-i-au

        ####Ошибки можно найти в переменных:
        **{b.{bid}.value.error_name}**
        Выводит понятное описание ошибки

        **{b.{bid}.value.error_message}**
        Дополнительные данные по ошибке. Используйте, только если error_name не дал понятного описания.
        '
    ];
}
/* Отдать JSON, не кодируя кириллические символы в кракозябры */
echo json_encode($responce, JSON_UNESCAPED_UNICODE);