<?php 

class Good extends Base
{
    /**
     * @param string $good_name
     */
    public function setName($good_name)
    {
        $this->data['good_name'] = $good_name;
    }

    /**
     * @param int $good_sum
     */
    public function setSum($good_sum)
    {
        $this->data['good_sum'] = $good_sum;
    }

}

