<?php

class JustClick
{
    private $user_id;
    private $user_rps_key;

    public function __construct($user_id, $user_rps_key)
    {
        $this->user_id = $user_id;
        $this->user_rps_key = $user_rps_key;
    }

    /* Счета */
    public function createOrder(Order $order)
    {
        if( ! $this->data['bill_domain'] )
            $order->setDomainName($this->user_id . '.justclick.ru');

        return $this->send('CreateOrder', $order->getData());
    }

    public function updateOrderStatus(Order $order)
    {
        return $this->send('UpdateOrderStatus', $order->getData());
    }

    public function deleteOrder(Order $order)
    {
        return $this->send('DeleteOrder', $order->getData());
    }

    public function getBills(Order $order)
    {
        return $this->send('GetBills', $order->getData());
    }

    public function getOrderDetails(Order $order)
    {
        return $this->send('getOrderDetails', $order->getData());
    }

    public function getOrdersWithGoods(Order $order)
    {
        return $this->send('getOrdersWithGoods', $order->getData());
    }

    /* Продукты */
    public function getAllGoods(Good $good)
    {
        return $this->send('GetAllGoods', $good->getData());
    }
    
    public function deleteGood(Good $good)
    {
        return $this->send('DeleteGood',$good->getData());
    }

    /* Контакты */
    public function addLeadToGroup(Contact $contact)
    {
        return $this->send('AddLeadToGroup',$contact->getData());
    }

    public function updateSubscriberData(Contact $contact)
    {
        return $this->send('UpdateSubscriberData',$contact->getData());
    }

    public function deleteSubscribe(Contact $contact)
    {
        return $this->send('DeleteSubscribe',$contact->getData());
    }

    public function getAllGroups(Contact $contact)
    {
        return $this->send('GetAllGroups',$contact->getData());
    }

    public function getLeadGroupStatuses(Contact $contact)
    {
        return $this->send('GetLeadGroupStatuses',$contact->getData());
    }

    /* Работа с API */
    private function send($method, $data)
    {
        $url = 'https://' . $this->user_id . '.justclick.ru/api/' . $method;
        $data['hash'] = $this->getHash($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // выводим ответ в переменную
        $res = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($res, 1);

        return $result;
    }

    // Формируем подпись к передаваемым в API данным
    function getHash($params)
    {
        $params = http_build_query($params);
        $user_id = $this->user_id;
        $secret = $this->user_rps_key;
        $str = "$params::$user_id::$secret";
        return md5($str);
    }

    // Проверяем полученную подпись к ответу
    function checkHash($resp)
    {
        $secret = $this->user_rps_key;
        $code = $resp->error_code;
        $text = $resp->error_text;
        $hash = md5("$code::$text::$secret");
        if ($hash == $resp->hash)
            return true; // подпись верна
        else
            return false; // подпись не верна
    }

    /* Получаем текст с get запросов */
    public function transformDataToText(array $response)
    {
        $result = '';
        foreach ($response as $key => $value)
        {
            if( empty($value) )
                continue;

            if($key == 'utm')
            {
                $result .= "\n UTM метки: \n";
                foreach ($value as $k => $v)
                {
                    $result .= $k. ": ". $v . "\n";
                }
                continue;
            }

            if($key == 'items')
            {
                $result .= "\n Продукты: \n";

                $number = 1;
                foreach ($value as $item)
                {
                    $result .= $number++ . ": \n";
                    foreach ($item as $k => $v)
                    {
                        $russian_key = $this->changeToRussian($k);
                        $result .= $russian_key . ": ". $v . "\n";
                    }
                    $result .= "\n";
                }
                continue;
            }

            if($key == 'created')
                $value = date('Y-m-d H:i:s',$value);

            $russian_key = $this->changeToRussian($key);
            $result .= $russian_key . ": ". $value . "\n";
        }

        return $result;
    }

    public function transformGetBillsToText(array $response)
    {
        $result = '';
        foreach ($response as $key => $value)
        {
            $result .= "*ID заказа: ". $key . "* \n";
            foreach ($value as $item_key => $item)
            {
                $result .= "ID продукта: ". $item_key . "\n";
                foreach ($item as $k => $v)
                {
                    $russian_key = $this->changeToRussian($k);
                    $result .= $russian_key . ": ". $v . "\n";
                }
            }
            $result .= "--- \n";
        }
        return $result;
    }

    /* Декодим ошибку */
    public function errorCodeToRussian($code)
    {
        $text = '';
        switch ($code)
        {
            /* Общие ошибки */
            case 0:
                $text = 'Действие выполнено успешно';
                break;
            case 1:
                $text = 'Не передана хеш-подпись запроса';
                break;
            case 2:
                $text = 'Не переданы параметры запроса';
                break;
            case 3:
                $text = 'Ошибочные параметры запроса';
                break;
            case 4:
                $text = 'Хеш-подпись к запросу неверна';
                break;
            case 5:
                $text = 'Не передан или не найден логин в системе JustClick';
                break;
            case 6:
                $text = 'Для указанного ip доступ запрещён';
                break;
            case 7:
                $text = 'Аккаунт отключен';
                break;
            case 8:
                $text = 'Лимит запросов по API с данного адреса исчерпан. Попробуйте позже. Как правило связано с тем, что функции API отключены для аккаунта.';
                break;

            /* Ошибки добавления контакта */
            case 100:
                $text = 'В переданных параметрах отсутствует e-mail контакта';
                break;
            case 101:
                $text = 'Ошибка добавления пользователя в группу';
                break;
            case 102:
                $text = 'Контакт уже есть во всех переданных группах';
                break;
            case 103:
                $text = 'В запросе передана несуществующая группа';
                break;
            case 104:
                $text = 'Добавление в эту группу невозможно. например автогруппа.';
                break;

            /* Ошибки работы с заказами */
            case 200:
                $text = 'Заказ с указанным номером не существует';
                break;
            case 201:
                $text = 'Передан неверный статус заказа';
                break;
            case 202:
                $text = 'Во время оплаты заказа произошла ошибка';
                break;
            case 203:
                $text = 'Не передан номер заказа';
                break;

            /* Ошибки удаления и изменения статуса заказа */
            case 302:
                $text = 'В запросе передан не существующий номер заказа';
                break;
            case 303:
                $text = 'Такого статуса заказа нет в системе';
                break;

            case 400:
                $text = 'Заказ с таким номером не существует';
                break;

            /* Ошибки получения списка групп контактов по email-у клиента */
            case 500:
                $text = 'Контакт с таким и-мейлом не существует';
                break;
            case 501:
                $text = 'Контакт не состоит ни в одной группе';
                break;

            /* Ошибки создания заказа */
            case 600:
                $text = 'Передан не правильный и-мейл клиента';
                break;
            case 601:
                $text = 'Такой заказ уже существует. (в result bill_id будет передан его номер)';
                break;
            case 602:
                $text = 'Не удалось создать заказ';
                break;
            case 603:
                $text = 'В заказе отсутствуют товары';
                break;
            case 604:
                $text = 'В вашем магазине нет продукта с таким id (будет возвращён id этого продукта)';
                break;
            case 605:
                $text = 'Не хватает данных для доставки продукта (отсутсвует адресс или имя)';
                break;

            /* Ошибки получения всех продуктов */
            case 700:
                $text = 'В магазине отсутствуют продукты';
                break;

            /* Ошибки добавления контакта */
            case 800:
                $text = 'Указанная группа контактов не найдена (не существует)';
                break;
            case 801:
                $text = 'Контакт с указанным email не найден (не существует)';
                break;
        }
        return $text;
    }

    public function changeToRussian($text)
    {
        $russian_keys = [
            'id' => 'ID',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'middle_name' => 'Отчество',
            'email' => 'Email',
            'phone' => 'Телефон',
            'city' => 'Город',
            'country' => 'Страна',
            'address' => 'Адрес',
            'region' => 'Регион',
            'postalcode' => 'Почтовый индекс',
            'created' => 'Время создания',
            'pay_status' => 'Статус оплаты',
            'paid' => 'Оплачено',
            'type' => 'Тип',
            'payway' => 'Тип оплаты',
            'comment' => 'Комментарий',
            'domain' => 'Домен принятого заказа',
            'link' => 'Ссылка',
            'price' => 'Цена',
            'is_recurrent' => 'Повторный заказ',
            'bill_sum_topay' => 'Сумма к оплате по счету',
            'tag' => 'Тэг',
            'kupon' => 'Купон скидки',
            'title' => 'Название',
            'utm' => 'UTM метки',
            'items' => 'Продукты',
            'sum' => 'Сумма заказа',
            'good_name' => 'Название продукта в системе',
            'good_title' => 'Название продукта',
            'good_count' => 'Количество продуктов',
            'rass_title' => 'Название группы рассылки',
            'rass_name' => 'API название группы подписок',
            'rass_status' => 'Статус',
            'subscription_time' => 'Время подписки',
            'good_ids' => 'ID продуктов'
        ];

        return $russian_keys[$text];
    }
}

