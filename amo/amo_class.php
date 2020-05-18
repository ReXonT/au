<?php

class Amo
{

    protected $sub_domain;
    protected $user_login;
    protected $user_hash;   // Api key (hash) юзера
    protected $cookie;      // Путь до cookie авторизации
    protected $useragent = 'activeusers';

    protected $field_names = [    // Массив имён полей, которые нам нужны для действий
        'name',
        'tags',
        'sale',
        'responsible_user_id',
        'status_name'
    ];

    public function __construct($user_login, $user_hash, $sub_domain, $session_id)
    {
        $this->sub_domain = $sub_domain;
        $this->user_login = $user_login;
        $this->user_hash = $user_hash;
        $this->cookie = 'cookies/' . $session_id . "cookie.txt";
    }

    public function getElementType($entity)
    {
        $element_type_codes = [
            'contacts'  => 1,
            'leads'     => 2,
         ];

        return $element_type_codes[$entity];
    }

    public function changeContact(array $options, array $entity_fields, $vk_uid)
    {
        /*
         * Находим уже существующий по vk_uid
         * если найдем - ставим действие на update. Нет - на добавление нового (add)
         */
        $entity_info = $this->getInfo('contacts');   // Получаем полную информацию по выбранной сущности
        if ( $found_item_ids = findItemsWithVkUid($entity_info, $vk_uid) ) // Находим объект с vk_uid инициатора активности
            $options['exec_type'] = 'update';
        else
            $options['exec_type'] = 'add'; // Если не нашли такой, то будем добавлять

        $options['found_items_ids'] = $found_item_ids;  // Передаем найденные id карточек в создание

        $new_data = $this->createCommonData($options, $entity_fields, $vk_uid);

        $new_data = addPhoneToData(   // Проставляем телефон
            $new_data,                  // Массив, куда добавляем данные по полю
            $options['exec_type'],      // Тип действия
            $entity_fields['Телефон'],  // Id поля для добавления
            $options['phone']           // Значение поле для добавления
        );

        $new_data = addEmailToData(   // Проставляем email
            $new_data,                  // Массив, куда добавляем данные по полю
            $options['exec_type'],      // Тип действия
            $entity_fields['Email'],    // Id поля для добавления
            $options['email']           // Значение поле для добавления
        );

        # Привязываем сделки к контакту
        $leads_info = $this->getInfo('leads');   // Получаем данные по всем сделкам

        # Находим и привязываем сделки этого контакта к самой карточке по vk_uid
        $new_data[ $options['exec_type'] ][0]['leads_id'] = findItemsWithVkUid($leads_info, $vk_uid);

        return $this->apiPostRequest('contacts', $new_data); // Запрос на добавление общий
    }

    public function addLead(array $options, array $entity_fields, $vk_uid)
    {
        $new_data = $this->createCommonData($options, $entity_fields, $vk_uid);

        // узнаем id статуса воронки по его названию
        if($options['status_name'])
        {
            $options['status_name'] = mb_strtolower($options['status_name']);

            $pipelines = $this->getInfo('pipelines');   // запрашиваем этапы воронки
            $pipelines_items = $pipelines['_embedded']['items'];
            foreach ($pipelines_items as $pipelines_item)
            {
                foreach ($pipelines_item['statuses'] as $status)
                {
                    $status['name'] = mb_strtolower($status['name']);
                    if($status['name'] == $options['status_name'])        // если нашлось такое название этапа
                    {
                        $new_data[ $options['exec_type'] ][0]['status_id'] = $status['id'];    // получаем id этапа по названию
                        break;  // УБРАТЬ И ПЕРЕРАБОТАТЬ, ЕСЛИ МНОГО ВОРОНОК
                    }
                }
                break;
            }
        }

        # Привязываем контакт к сделке
        $contacts_info = $this->getInfo('contacts');   // Получаем данные по всем сделкам

        # Находим и привязываем контакты этой сделки к самой карточке по vk_uid
        $new_data['add'][0]['contacts_id'] = findItemsWithVkUid($contacts_info, $vk_uid);

        return $this->apiPostRequest('leads', $new_data); // Запрос на добавление общий
    }

    public function updateLead(array $options, array $entity_fields, $vk_uid)
    {
        /*
        * Находим уже существующий по vk_uid
        * если найдем - ставим действие на update. Нет - на добавление нового (add)
        */

        $entity_info = $this->getInfo('leads');   // Получаем полную информацию по выбранной сущности
        file_put_contents('log.txt', json_encode($entity_info, JSON_UNESCAPED_UNICODE));
        if ( $found_items_ids = findItemsWithVkUid($entity_info, $vk_uid) ) // Находим объект с vk_uid инициатора активности
            $options['exec_type'] = 'update';
        else
            return 'Ошибка изменения: не найдено объектов у этого vk_uid';



        $options['found_items_ids'] = $found_items_ids;  // Передаем найденные id карточек в создание
        $new_data = $this->createCommonData($options, $entity_fields, $vk_uid);

        // узнаем id статуса воронки по его названию
        if($options['status_name'])
        {
            $options['status_name'] = mb_strtolower($options['status_name']);

            $pipelines = $this->getInfo('pipelines');   // запрашиваем этапы воронки
            $pipelines_items = $pipelines['_embedded']['items'];
            foreach ($pipelines_items as $pipelines_item)
            {
                foreach ($pipelines_item['statuses'] as $status)
                {
                    $status['name'] = mb_strtolower($status['name']);
                    if($status['name'] == $options['status_name'])        // если нашлось такое название этапа
                    {
                        $new_data[ $options['exec_type'] ][0]['status_id'] = $status['id'];    // получаем id этапа по названию
                        break;  // УБРАТЬ И ПЕРЕРАБОТАТЬ, ЕСЛИ МНОГО ВОРОНОК
                    }
                }
                break;
            }
        }

        # Привязываем контакт к сделке
        $contacts_info = $this->getInfo('contacts');   // Получаем данные по всем сделкам

        # Находим и привязываем контакты этой сделки к самой карточке по vk_uid
        $new_data['update'][0]['contacts_id'] = findItemsWithVkUid($contacts_info, $vk_uid);

        return $this->apiPostRequest('leads', $new_data); // Запрос на добавление общий
    }

    public function addNote($card_id, $entity, $text, $responsible_user_id)
    {
        $data['add'][0] = [
            'element_id' => $card_id,
            'element_type' => $this->getElementType($entity),
            'text' => $text,
            'note_type' => '4',
            'created_at' => time(),
            'responsible_user_id' => $responsible_user_id
        ];

        return $this->apiPostRequest('notes', $data);
    }

    public function updateNote($card_id, $entity, $text)
    {
        if($entity == 'leads')
            $notes = $this->getInfo('notes', ['type' => 'lead']);   // запрашиваем данные по notes из сделок
        else if ($entity == 'contacts')
            $notes = $this->getInfo('notes', ['type' => 'contact']);   // запрашиваем данные по notes из контакта
        else
            return 'Ошибка при изменении примечания: Неверная сущность';

        $notes_items = $notes['_embedded']['items'];
        foreach ($notes_items as $item)
        {
            if($item['element_id'] == $card_id)
            {
                $note_id = $item['id'];    // находим id сущности примечания
                break;
            }
        }

        $data['update'][0] = [
            'id' => $note_id,
            'text' => $text,
            'updated_at' => time()
        ];

        return $this->apiPostRequest('notes', $data);
    }

    /**
     * Получить данные по всем дополнительным полям аккаунта
     * @return mixed
     */
    public function getCustomFields()
    {
        return $this->apiGetRequest('account', ['with' => 'custom_fields']);
    }

    public function getInfo($entity, $data = [])
    {
        $end = 0;
        $result = [];
        $limit_offset = 0;
        
        while(!$end)
        {
            $response = $this->apiGetRequest($entity, [
                'limit_rows' => 500,
                'limit_offset' => $limit_offset
            ]);

            if($limit_offset == 0)
                $result = $response;
            else
            {
                foreach ($response['_embedded']['items'] as $item)
                    $result['_embedded']['items'][] = $item;
            }

            $limit_offset += 500;

            if(count($response['_embedded']['items']) < 500)
                $end = 1;
        }

        return $result;
    }

    public function createCommonData(array $options, array $entity_fields, $vk_uid)
    {
        $new_data = [];
        foreach ($this->field_names as $field_name)
        {
            if($options[$field_name])
                $new_data[ $options['exec_type'] ][0][$field_name] = $options[$field_name];
        }

        $new_data[ $options['exec_type'] ][0]['custom_fields'] = [];

        if($options['exec_type'] == 'add')
            $new_data['add'][0]['created_at'] = time(); // добавляем дату создания
        else if($options['exec_type'] == 'update')
        {
            $new_data['update'][0]['updated_at'] = time(); // добавляем дату изменения
            $new_data['update'][0]['id'] = $options['found_items_ids'][0]; // Добавляем id карточки для изменения
        }

        if($options['add_fields'] == 'add')    // Добавляем доп. поля
        {
            for($i = 1; $i <= $options['add_fields_num']; $i++)
            {
                $new_data = addCustomFieldToData(   // Проставляем доп. поле
                    $new_data,                  // Массив, куда добавляем данные по полю
                    $options['exec_type'],      // Тип действия
                    $options['add_field_' . $i . '_id'],   // Id поля для добавления
                    $options['add_field_' . $i . '_val']    // Значение поле для добавления
                );
            }
        }

        $new_data = addCustomFieldToData(   // Проставляем vk_uid
            $new_data,                  // Массив, куда добавляем данные по полю
            $options['exec_type'],      // Тип действия
            $entity_fields['vk_uid'],   // Id поля для добавления
            $vk_uid    // Значение поле для добавления
        );

        return $new_data;
    }

    // Авторизация
    public function auth()
    {
        // Массив с параметрами, которые нужно передать методом POST к API системы
        $user = array(
            'USER_LOGIN' =>  $this->user_login, // Ваш логин (электронная почта)
            'USER_HASH' =>  $this->user_hash, // Хэш для доступа к API (смотрите в профиле пользователя)
        );

        $link = 'https://' . $this->sub_domain . '.amocrm.ru/private/api/auth.php?type=json';

        return $this->postRequest($link, $user);
    }

    private function apiPostRequest($entity, $data)
    {
        $link = 'https://' . $this->sub_domain . '.amocrm.ru/api/v2/' . $entity;
        $response = $this->postRequest($link, $data);

        return json_decode($response,TRUE);
    }

    private function postRequest($link, $data)
    {
        $curl = curl_init();        // Сохраняем дескриптор сеанса cURL
        curl_setopt_array($curl,[   // Устанавливаем необходимые опции для сеанса cURL
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_USERAGENT       => $this->useragent,
            CURLOPT_URL             => $link,
            CURLOPT_CUSTOMREQUEST   => 'POST',
            CURLOPT_POSTFIELDS      => json_encode($data),
            CURLOPT_HTTPHEADER      => array('Content-Type: application/json'),
            CURLOPT_HEADER          => false,
            CURLOPT_COOKIEFILE      => $this->cookie,
            CURLOPT_COOKIEJAR       => $this->cookie,
        ]);
        $response = curl_exec($curl); // Инициируем запрос к API и сохраняем ответ в переменную
        curl_close($curl); // Завершаем сеанс cURL

        return $response;
    }

    private function apiGetRequest($entity, $data)
    {
        $link = 'https://' . $this->sub_domain . '.amocrm.ru/api/v2/' . $entity . '?' . http_build_query($data);
        $headers[] = "Accept: application/json";
        $curl = curl_init();
        curl_setopt_array($curl,[
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_USERAGENT       => $this->useragent,
            CURLOPT_HTTPHEADER      => $headers,
            CURLOPT_URL             => $link,
            CURLOPT_HEADER          => false,
            CURLOPT_COOKIEFILE      => $this->cookie,
            CURLOPT_COOKIEJAR       => $this->cookie,
        ]);
        $out = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($out,TRUE);
        $response['link'] = $link;
        return $response;
    }

    public function clean()
    {
        unlink($this->cookie);
    }
}