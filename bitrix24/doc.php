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
        "MESSAGE" => "Комментарий",
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