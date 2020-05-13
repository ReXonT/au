<?php

function addCustomFieldToData(array $data, $exec_type, $field_id, $field_value)
{
    $custom_field = [
        'id' => $field_id,
        'values' => [
            [
                'value' => $field_value,
            ],
        ],
    ];

    array_push($data[$exec_type][0]['custom_fields'], $custom_field);

    return $data;
}

function addPhoneToData(array $data, $exec_type, $field_id, $field_value)
{
    $custom_field = [
        'id' => $field_id,
        'values' => [
            [
                'value' => $field_value,
                'enum'  => 'MOB'
            ],
        ],
    ];

    array_push($data[$exec_type][0]['custom_fields'], $custom_field);

    return $data;
}

function addEmailToData(array $data, $exec_type, $field_id, $field_value)
{
    $custom_field = [
        'id' => $field_id,
        'values' => [
            [
                'value' => $field_value,
                'enum'  => 'WORK'
            ],
        ],
    ];

    array_push($data[$exec_type][0]['custom_fields'], $custom_field);

    return $data;
}

function findItemsWithVkUid(array $all_info, $vk_uid)
{
    $items = $all_info['_embedded']['items'];   // массив со всеми данными сущности

    $found_items_ids = [];
    foreach ($items as $item)
    {
        foreach ($item['custom_fields'] as $custom_field)
        {
            if($custom_field['name'] == 'vk_uid')
            {
                if($custom_field['values'][0]['value'] == $vk_uid)
                    $found_items_ids[] =  $item['id'];
            }
        }
    }

    return $found_items_ids;
}

function getFieldsIds(array $cab_fields, $entity, array $parse_fields)
{
    $custom_fields = $cab_fields['_embedded']['custom_fields'][$entity]; // подробнее в response_custom_fields.json
    $result = [];

    foreach ($custom_fields as $custom_field)
    {
        foreach ($parse_fields as $parse_field)
        {
            if($custom_field['name'] == $parse_field)
            {
                $result[$parse_field] = $custom_field['id'];
                break;
            }
        }
    }

    return $result;
}