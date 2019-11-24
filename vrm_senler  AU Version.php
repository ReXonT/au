<?php
class Senler
{
    
    /* Callback key Senler */
    protected $callback_key;

    /* ID группы ВКонтакте */
    protected $vk_group_id;

    public function __construct($callback, $group_id)
    {
        $this->callback_key = $callback;
        $this->vk_group_id = $group_id;
    }


    /* Формирование подписи. Взято из документации Senler */
    public function getHash($params, $secret)           
    { 
        $values = "";  
        foreach ($params as $value) {  
            $values .= (is_array($value) ? implode("", $value) : $value);  
        } 
        return md5($values . $secret); 
    }



    /* РАБОТА С ПОДПИСКАМИ SUBSCRIBERS */

    // добавление человека из группы подписок
    public function addSubscriber($senler_group_id, $user_id, $senler_utm_id)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id,  
                'subscription_id' => $senler_group_id,  
                'vk_user_id' => $user_id
            ];

        if(isset($senler_utm_id))
        {
            $params['utm_id'] = $senler_utm_id;
        }

        return $this->request('subscribers/add', $params);
    }

    // удаление подписчика из группы подписок
    public function deleteSubscriber($senler_group_id, $user_id)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id,  
                'subscription_id' => $senler_group_id,  
                'vk_user_id' => $user_id
            ];
        return $this->request('subscribers/del', $params);
    }

    // получение показателей подписок/отписок
    public function getStatSubscribe($date_from, $date_to, $subscription_id)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id,  
                'date_from' => $date_from,  
                'date_to' => $date_to,
                'subscription_id' => $subscription_id
            ];
        return $this->request('subscribers/StatSubscribe', $params);
    }

    // получение числа подписчиков группы
    public function getSubscribersFromGroup($subscription_id)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id, 
                'subscription_id' => $subscription_id
            ];
        return $this->request('subscribers/get', $params);
    }


    /* РАБОТА С БОТОМ BOTS */


    // добавить подписчика в бота
    public function addToBot($user_id, $bot_id)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id, 
                'vk_user_id' => $user_id,
                'bot_id' => $bot_id
            ];
        return $this->request('bots/AddSubscriber', $params);
    }

    // удалить подписчика из бота
    public function delFromBot($user_id, $bot_id)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id, 
                'vk_user_id' => $user_id,
                'bot_id' => $bot_id
            ];
        return $this->request('bots/DelSubscriber', $params);
    }


    /* РАБОТА С ПЕРЕМЕННЫМИ VARS */

    // установить переменную
    public function setVar($user_id, $var_name, $var_value)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id,
                'vk_user_id' => $user_id,
                'name' => $var_name,
                'value' => $var_value
            ];
        return $this->request('vars/set', $params);
    }

    // получить переменные
    public function getVar($user_id, $var_name)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id,
                'vk_user_id' => $user_id
            ];
        // если передано имя - ищем по имени
        if(isset($var_name))
        {
            $params['name'] = $var_name;
        }
        return $this->request('vars/get', $params);
    }

    // удалить переменные
    public function delVar($user_id, $var_name)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id,
                'vk_user_id' => $user_id,
                'name' => $var_name
            ];
        return $this->request('vars/del', $params);
    }


    /* РАБОТА С МЕТКАМИ UTM */

    // добавить метку
    public function addUtm($utm_name)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id,
                'name' => $utm_name
            ];
        return $this->request('utms/Add', $params);
    }

    // редактировать метку
    public function editUtm($utm_id, $utm_name)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id,
                'utm_id' => $utm_id,
                'name' => $utm_name
            ];
        return $this->request('utms/Edit', $params);
    }

    // удалить метку
    public function delUtm($utm_id)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id,
                'utm_id' => $utm_id
            ];
        return $this->request('utms/Del', $params);
    }

    // получить метки
    public function getUtm()
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id
            ];
        return $this->request('utms/Get', $params);
    }

    // получить ссылку для метки
    public function getUtmLink($utm_id, $subscription_id, $force)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id,
                'utm_id' => $utm_id,
                'subscription_id' => $subscription_id,
                'force' => $force
            ];
        return $this->request('utms/GetLink', $params);
    }

    // получить статистику меток
    public function getUtmStat($date_from, $date_to, $utm_id)
    {
        $params = [ 
                'vk_group_id' => $this->vk_group_id,
                'date_from' => $date_from,
                'date_to' => $date_to,
                'utm_id' => $utm_id
            ];
        return $this->request('utms/StatCount', $params);
    }




    /* ДОП. ФУНКЦИИ */


    public function getErrorMessage($error_code)
    {
        $error_name = "";
        switch ($error_code) {
            case 0:
                $error_name = "Неизвестная ошибка";
                break;
            case 1:
                $error_name = "Один из обязательных параметров запроса отсутствуют ";
                break;
            case 2:
                $error_name = "Hash отсутствует";
                break;
            case 3:
                $error_name = "Неправильный hash";
                break;
            case 4:
                $error_name = "Пользователь не разрешил отправку сообщений";
                break;
            case 5:
                $error_name = "Переданный пользователь не найден";
                break;
            case 6:
                $error_name = "Переданный идентификатор сообщества не найден";
                break;
            case 7:
                $error_name = "Переданный идентификатор группы не найден";
                break;
            case 8:
                $error_name = "Переданный идентификатор метки не найден";
                break;
            case 9:
                $error_name = "Слишком много обращений в секунду (максимум 20) ";
                break;
            case 10:
                $error_name = "Переданный идентификатор бота не найден";
                break;
            case 11:
                $error_name = "Переменная должна содержать только латинские буквы или цифры";
                break;
            
            default:
                $error_name = "Fail. Switch default";
                break;
        }
        return $error_name;
    }

    /* ОТПРАВКА + ПОЛУЧЕНИЕ ЗАПРОСА НА SENLER */

    public function request($request_name, array $params)
    {
        //добавляем hash к params
        $params['hash'] = $this->getHash($params, $this->callback_key);
        $myCurl = curl_init(); 
        curl_setopt_array($myCurl, [ 
            CURLOPT_URL => 'https://senler.ru/api/'.$request_name, 
            CURLOPT_RETURNTRANSFER => true, 
            CURLOPT_POST => true, 
            CURLOPT_POSTFIELDS => http_build_query($params) 
        ]); 
        $response = curl_exec($myCurl); 
        curl_close($myCurl);
        $answer = json_decode($response, true);
        return $answer;
    }
}

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
                'desc' => 'Текст',     
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
    $success = $answer['success'];
    
    if(!$success)
    {
        $error_name = $answer['error_message'];
    }
    $error_message = $senler->getErrorMessage($answer['error_code']);
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
    /* Действие не задано, и что же нам сделать? Станцевать вальс, попрыгать, пойти в гости к Кролику? */
}
/* Отдать JSON, не кодируя кириллические символы в кракозябры */
echo json_encode($responce, JSON_UNESCAPED_UNICODE);