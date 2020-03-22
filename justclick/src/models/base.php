<?php

class Base
{
	protected $data;

	public function getData()
    {
        return $this->data;
    }

    public function clearData()
    {
        $this->data = [];
    }
}