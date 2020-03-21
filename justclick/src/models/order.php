<?php

class Order
{
    /** @var  array */
    protected $data;


    /**
     * Adds product to the order.
     *
     * @param string $name
     * @param integer|null $price
     */
    public function addProduct($name, $price)
    {
        if (!isset($this->data['goods'])) {
            $this->data['goods'] = [];
        }

        $product = [
            'good_name' => $name,
            'good_sum' => (int) $price
        ]; 

        $this->data['goods'][] = $product;
    }

    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $this->data['bill_id'] = $id;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->data['status'] = $status;
    }

    /**
     * @param string $status
     */
    public function setPayStatus($status)
    {
        $this->data['pay_status'] = $status;
    }

    /**
     * @param bool $good_info
     */
    public function setGoodInfo($good_info)
    {
        $this->data['good_info'] = $good_info;
    }

    /**
     * @param string $good_ids
     */
    public function setGoods($good_ids)
    {
        $this->data['goods'] = $good_ids;
    }


    /**
     * @param string $first_name
     */
    public function setNameFirst($first_name)
    {
        $this->data['bill_first_name'] = $first_name;
    }

    /**
     * @param string $last_name
     */
    public function setNameLast($last_name)
    {
        $this->data['bill_surname'] = $last_name;
    }

    /**
     * @param string $patronymic
     */
    public function setOtchestvo($patronymic)
    {
        $this->data['bill_otchestvo'] = $patronymic;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->data['bill_email'] = $email;
    }

    /**
     * @param string $email
     */
    public function setEmailForBills($email)
    {
        $this->data['email'] = $email;
    }

    /**
     * @param string $phone_number
     */
    public function setPhoneNumber($phone_number)
    {
        $this->data['bill_phone'] = $phone_number;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->data['bill_country'] = $country;
    }

    /**
     * @param string $region
     */
    public function setRegion($region)
    {
        $this->data['bill_region'] = $region;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->data['bill_city'] = $city;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->data['bill_address'] = $address;
    }

    /**
     * @param string $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->data['bill_postal_code'] = $postalCode;
    }

    /**
     * @param string $coupon
     */
    public function setCoupon($coupon)
    {
        $this->data['bill_kupon'] = $coupon;
    }

    /**
     * @param string $tag
     */
    public function setTag($tag)
    {
        $this->data['bill_tag'] = $tag;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->data['bill_comment'] = $comment;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->data['bill_ip'] = $ip;
    }

    /**
     * @param string $domain_name
     */
    public function setDomainName($domain_name)
    {
        $this->data['bill_domain'] = $domain_name;
    }

    /**
     * @param bool $paid
     */
    public function setPaid($paid)
    {
        $this->data['paid'] = $paid;
    }

    /**
     * @param int $date
     * unixtime
     */
    public function setDate($date)
    {
        $this->data['date'] = $date;
    }

    /**
     * @param int $date
     * unixtime
     */
    public function setBeginDate($date)
    {
        $this->data['begin_date'] = $date;
    }

    /**
     * @param int $date
     * unixtime
     */
    public function setEndDate($date)
    {
        $this->data['end_date'] = $date;
    }

    /**
     * @param string $timer_kill
     */
    public function setTimerKill($timer_kill)
    {
        $this->data['bill_timer_kill'] = $timer_kill;
    }

    /**
     * @param (int) $date_created
     * unixtime
     */
    public function setDateCreated($date_created)
    {
        $this->data['bill_created'] = $date_created;
    }

    /**
     * @param string $rpo
     */
    public function setRPO($rpo)
    {
        $this->data['rpo'] = $rpo;
    }

    /**
     * @param array $utm
     */
    public function setUtm(array $utm)
    {
        $temp_data = [
            'utm[utm_source]' => $utm['utm_source'],
            'utm[utm_medium]' => $utm['utm_medium'],
            'utm[utm_campaign]' => $utm['utm_campaign'],
            'utm[utm_content]' => $utm['utm_content'],
            'utm[utm_term]' => $utm['utm_term']
        ];
        $this->data = array_merge($this->data, $temp_data);
    }

    /**
     * @param array $utm_aff
     */
    public function setUtmAff(array $utm_aff)
    {
        $temp_data = [
            'utm[aff_source]' => $utm_aff['aff_source'],
            'utm[aff_medium]' => $utm_aff['aff_medium'],
            'utm[aff_campaign]' => $utm_aff['aff_campaign'],
            'utm[aff_content]' => $utm_aff['aff_content'],
            'utm[aff_term]' => $utm_aff['aff_term']
        ];
        $this->data = array_merge($this->data, $temp_data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}