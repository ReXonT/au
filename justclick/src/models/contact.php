<?php

class Contact extends Base
{
    public function setLeadName($lead_name)
    {
        $this->data['lead_name'] = $lead_name;
    }

    public function setLeadEmail($lead_email)
    {
        $this->data['lead_email'] = $lead_email;
    }

    public function setEmail($email)
    {
        $this->data['email'] = $email;
    }

    public function setPhone($lead_phone)
    {
        $this->data['lead_phone'] = $lead_phone;
    }

    public function setMailingName($rass_name)
    {
        $this->data['rass_name'] = $rass_name;
    }

	public function setCity($lead_city)
    {
        $this->data['lead_city'] = $lead_city;
    }

    public function setTag($tag)
    {
        $this->data['tag'] = $tag;
    }

    public function setDoneUrl($doneurl)
    {
    	$this->data['doneurl2'] = $doneurl;
    }

    public function setMailingId($rid)
    {
        $this->data['rid[0]'] = $rid;
    }




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
}

