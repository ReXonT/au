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

    public function getCountSubscribe(Contact $contact)
    {
        return $this->send('GetCountSubscribe',$contact->getData());
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
}

