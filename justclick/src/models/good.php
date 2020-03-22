<?php 

class Good
{
    protected $data;

    public function setName($good_name)
    {
        $this->data['good_name'] = $good_name;
    }

    public function setSum($good_sum)
    {
        $this->data['good_sum'] = $good_sum;
    }



    public function getData()
    {
        return $this->data;
    }

    public function clearData()
    {
        $this->data = [];
    }

}

?>