<?php 

class Good extends Base
{
    public function setName($good_name)
    {
        $this->data['good_name'] = $good_name;
    }

    public function setSum($good_sum)
    {
        $this->data['good_sum'] = $good_sum;
    }

}

