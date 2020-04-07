<?php

// Добавление лида выглядит так:
// документация по полям есть тут https://dev.1c-bitrix.ru/rest_help/crm/leads/crm_lead_fields.php
'crm.lead.add', 
array(
    'fields' => [
        "TITLE" => "Илья С", 
        "NAME" => "Илья", 
        "SECOND_NAME" => "Вячеславович", 
        "LAST_NAME" => "Соколов",
        "COMMENTS" => "Комментарий",
        "STATUS_ID" => "NEW", 
        "OPENED" => "Y", 
        "ASSIGNED_BY_ID" => 1, 
        "CURRENCY_ID" => "RUB", 
        "OPPORTUNITY" => 12500,
        "PHONE" => [
            "VALUE" => [                            // телефон создается именно так!
                "VALUE" => 89200756364,
                "VALUE_TYPE" => "WORK"
            ]
        ]
    ],
    'params' => ['REGISTER_SONET_EVENT' => FALSE]
),



Emailarray(3) {
  ["result"]=>
  array(2) {
    [0]=>
    array(1) {
      ["ID"]=>
      string(2) "10"
    }
    [1]=>
    array(1) {
      ["ID"]=>
      string(2) "14"
    }
  }
  ["total"]=>
  int(2)
  ["time"]=>
  array(6) {
    ["start"]=>
    float(1586166089.6171)
    ["finish"]=>
    float(1586166089.6594)
    ["duration"]=>
    float(0.042255878448486)
    ["processing"]=>
    float(0.010555982589722)
    ["date_start"]=>
    string(25) "2020-04-06T12:41:29+03:00"
    ["date_finish"]=>
    string(25) "2020-04-06T12:41:29+03:00"
  }
}
Phonearray(3) {
  ["result"]=>
  array(2) {
    [0]=>
    array(1) {
      ["ID"]=>
      string(2) "10"
    }
    [1]=>
    array(1) {
      ["ID"]=>
      string(2) "14"
    }
  }
  ["total"]=>
  int(2)
  ["time"]=>
  array(6) {
    ["start"]=>
    float(1586166089.7499)
    ["finish"]=>
    float(1586166089.7909)
    ["duration"]=>
    float(0.04096508026123)
    ["processing"]=>
    float(0.012337923049927)
    ["date_start"]=>
    string(25) "2020-04-06T12:41:29+03:00"
    ["date_finish"]=>
    string(25) "2020-04-06T12:41:29+03:00"
  }
}