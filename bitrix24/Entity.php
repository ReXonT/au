<?php

class Entity extends Bitrix24
{
    private $name;

    public function __construct($webhook_url, $name)
    {
        $this->setWebhookUrl($webhook_url);
        $this->name = $name;
    }

    public function add(array $fields)
    {
        return $this->call(
            'crm.' . $this->name . '.add',
            [
                'fields' => $fields
            ]
        );
    }

    public function update($id, array $fields)
    {
        return $this->call(
            'crm.' . $this->name . '.update',
            [
                'id' => $id,
                'fields' => $fields
            ]
        );
    }

    public function delete($id)
    {
        return $this->call(
            'crm.' . $this->name . '.delete',
            [
                'id' => $id
            ]
        );
    }

    public function get($id)
    {
        return $this->call(
            'crm.' . $this->name . '.get',
            [
                'id' => $id
            ]
        );
    }

    public function getList(array $order, array $filter, array $select, $start = 0)
    {
        return $this->call(
            'crm.' . $this->name . '.list',
            [
                'order' => $order,
                'filter'=> $filter,
                'select'=> $select,
                'start'	=> $start
            ]
        );
    }
}