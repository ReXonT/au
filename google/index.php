<?php

ini_set('display_errors', 1);

require_once ('vendor/autoload.php');
require_once ('global.php');

$act = $_REQUEST['act'];

if($act == 'options') 
{
    $responce = [
        'title' => 'ВРМ Google',        // Это заголовок блока, который будет виден на схеме
        
        'paysys' => [                   // Группа полей, отвечающая за интеграцию с платёжными системами и внешними сервисами.
            'ps' => [                   // ВРМ получит доступ к ID аккаунта, секретному ключу и другим атрибутам выбранной системы
                'title' => 'Google',
                'type' => 7
            ]
        ],

        'vars' => [                         // переменные, которые можно будет настроить в блоке
            
        	// основные поля для таблиц
            'spreadsheet_id' => [
            	'title' => 'ID таблицы',
            	'desc' => 'Можно вставить ссылку на таблицу',
            	'default' => ''
            ],
            'work_sheet_title' => [
            	'title' => 'Название листа',
            	'desc' => 'Название листа для работы',
            	'default' => ''
            ],
            'option' => [
                'title' => 'Что делаем',
                'values' => [
                    1 => 'Добавить строку в конец таблицы',
                    2 => 'Вставить значение в ячейку',
                    3 => 'Найти и заменить',
                    4 => 'Получить строчку',
                    5 => 'Удалить ячейку',
                    6 => 'Полностью удалить строку/столбец'
                ],
                'default' => ''
            ],

            // тип добавления
            'add_type' => [
            	'title' => 'Как добавляем',
            	'values' => [
            		1 => 'По координате ячейки',
            		2 => 'По заданным строке и столбцу'
            	],
            	'default' => '',
            	'show' => [
            		'option' => 2
            	]
            ],


            'field_count' => [
            	'title' => 'Количество ячеек для записи',
            	'values' => [
                    1 => '1',
                    2 => '2',
                    3 => '3',
                    4 => '4',
                    5 => '5',
                    6 => '6',
                    7 => '7',
                    8 => '8',
                    9 => '9',
                    10 => '10',
                    11 => '11',
                    12 => '12',
                    13 => '13',
                    14 => '14',
                    15 => '15'
                ],
                'default' => '',
                'show' => [
                	'option' => [1],
                ]
            ],

            // основное поле диапазона ячеек
            'range' => [
            	'title' => 'Диапазон ячеек',
            	'desc' => "Например A1:C1",
            	'default' => '',
            	'show' => [
            		'option' => [5]
            	]
            ],

            // поле для добавления

            'add_range' => [
            	'title' => 'Диапазон ячеек',
            	'desc' => "Например A1:C1",
            	'default' => '',
            	'show' => [
            		'option' => 2,
            		'add_type' => 1
            	]
            ],

            // найти по строке/таблице
            'find_col' => [
            	'title' => 'Название столбца',
            	'desc' => 'Например: Город',
            	'default' => '',
                'format' => 'textarea',
            	'show' => [
            		'option' => 2,
            		'add_type' => 2
            	]
            ],
            'find_row' => [
            	'title' => 'Строка по уникальному ключу',
            	'desc' => 'Например id пользователя',
            	'default' => '',
                'format' => 'textarea',
            	'show' => [
            		'option' => 2,
            		'add_type' => 2
            	]
            ],

            // найти и заменить
            'value_to_find' => [
            	'title' => 'Значение для поиска',
            	'desc' => "",
            	'default' => '',
                'format' => 'textarea',
            	'show' => [
            		'option' => [3,4,6]
            	]
            ],
            'replacement' => [
            	'title' => 'Значение на замену',
            	'desc' => "",
            	'default' => '',
                'format' => 'textarea',
            	'show' => [
            		'option' => [3]	
            	]
            ],

            // выбрать строку или столбец удалить

            'shift_dimension' => [
            	'title' => 'Что удалить',
            	'values' => [
            		1 => 'Строку',
            		2 => 'Столбец'
            	],
            	'default' => '',
            	'show' => [
            		'option' => 6
            	]
            ],

            // поле для вставки в ячейку
          	'value0' => [
            	'title' => 'Значение',
            	'desc' => "",
            	'default' => '',
                'format' => 'textarea',
            	'show' => [
            		'option' => 2,
            		'add_type' => [1,2]
            	]
            ],

            // поля
            'value1' => [
            	'title' => 'Значение 1',
            	'desc' => "",
            	'default' => '',
                'format' => 'textarea',
            	'show' => [
            		'option' => 1,
            		'field_count' => [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]	
            	]
            ],
            'value2' => [
            	'title' => 'Значение 2',
            	'desc' => "",
            	'default' => '',
                'format' => 'textarea',
            	'show' => [
            		'option' => [1],
            		'field_count' => [2,3,4,5,6,7,8,9,10,11,12,13,14,15]	
            	]
            ],
            'value3' => [
            	'title' => 'Значение 3',
            	'desc' => "",
            	'default' => '',
                'format' => 'textarea',
            	'show' => [
            		'option' => [1],
            		'field_count' => [3,4,5,6,7,8,9,10,11,12,13,14,15]	
            	]
            ],
            'value4' => [
            	'title' => 'Значение 4',
            	'desc' => "",
            	'default' => '',
                'format' => 'textarea',
            	'show' => [
            		'option' => [1],
            		'field_count' => [4,5,6,7,8,9,10,11,12,13,14,15]	
            	]
            ],
            'value5' => [
            	'title' => 'Значение 5',
            	'desc' => "",
            	'default' => '',
                'format' => 'textarea',
            	'show' => [
            		'option' => [1],
            		'field_count' => [5,6,7,8,9,10,11,12,13,14,15]	
            	]
            ],
            'value6' => [
            	'title' => 'Значение 6',
            	'desc' => "",
            	'default' => '',
                'format' => 'textarea',
            	'show' => [
            		'option' => [1],
            		'field_count' => [6,7,8,9,10,11,12,13,14,15]	
            	]
            ],
            'value7' => [
            	'title' => 'Значение 7',
            	'desc' => "",
            	'default' => '',
                'format' => 'textarea',
            	'show' => [
            		'option' => [1],
            		'field_count' => [7,8,9,10,11,12,13,14,15]	
            	]
            ],
            'value8' => [
            	'title' => 'Значение 8',
            	'desc' => "",
            	'default' => '',
                'format' => 'textarea',
            	'show' => [
            		'option' => [1],
            		'field_count' => [8,9,10,11,12,13,14,15]	
            	]
            ],
            'value9' => [
            	'title' => 'Значение 9',
            	'desc' => "",
            	'default' => '',
                'format' => 'textarea',
            	'show' => [
            		'option' => [1],
            		'field_count' => [9,10,11,12,13,14,15]	
            	]
            ],
            'value10' => [
            	'title' => 'Значение 10',
            	'desc' => "",
            	'default' => '',
                'format' => 'textarea',
            	'show' => [
            		'option' => [1],
            		'field_count' => [10,11,12,13,14,15]	
            	]
            ],
            'value11' => [
                'title' => 'Значение 11',
                'desc' => "",
                'default' => '',
                'format' => 'textarea',
                'show' => [
                    'option' => [1],
                    'field_count' => [11,12,13,14,15]    
                ]
            ],
            'value12' => [
                'title' => 'Значение 12',
                'desc' => "",
                'default' => '',
                'format' => 'textarea',
                'show' => [
                    'option' => [1],
                    'field_count' => [12,13,14,15]    
                ]
            ],
            'value13' => [
                'title' => 'Значение 13',
                'desc' => "",
                'default' => '',
                'format' => 'textarea',
                'show' => [
                    'option' => [1],
                    'field_count' => [13,14,15]    
                ]
            ],
            'value14' => [
                'title' => 'Значение 14',
                'desc' => "",
                'default' => '',
                'format' => 'textarea',
                'show' => [
                    'option' => [1],
                    'field_count' => [14,15]    
                ]
            ],
            'value15' => [
                'title' => 'Значение 15',
                'desc' => "",
                'default' => '',
                'format' => 'textarea',
                'show' => [
                    'option' => [1],
                    'field_count' => [15]    
                ]
            ],
            // парсить по спец.символу или нет?
            'parse_cols' => [
                'title' => 'Разделять текст по символу ";"?',
                'desc' => 'Если да, то каждый символ ";" в тексте будет означать запись в следующий столбец (для метода записи в конец таблицы)',
                'default' => '',
                'format' => 'checkbox',
                'more' => 1
            ],
            
        ],
        'out' => [                          // Это блоки выходов, мы задаём им номера и подписи (будут видны на схеме)
            1 => [                          // Номер 0 означает красный выход блока ВРМ, зарезервированный для случаев сбоя
                'title' => 'Результат',     // название выхода 1
            ]
        ]
    ];
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Режим RUN - в котором ВРМ получает, обрабатывает и возвращает  *
     * полученные от схемы данные                                   *
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
} 
elseif($act == 'run')  // Схема прислала данные, обрабатываем
{
    $target                  = $_REQUEST['target'];         // Пользователь, от имени которого выполняется блок
    $ums                    = $_REQUEST['ums'];             // Данные об активности пользователя
    $options                = $_REQUEST['options'];         // Данные из полей, введенные пользователем
    $ps                     = $_REQUEST['paysys']['ps'];    // Сюда придут настройки выбранной системы
    $field_count            = $options['field_count'];      // Количество полей
    $work_spreadsheet_id    = $options['spreadsheet_id'];   // ID таблицы
    $work_sheet_title       = $options['work_sheet_title']; // Название рабочего листа
    $out                    = 0;                            // Выход ВРМ в 0
    $message                = "";                           // Сообщение о результате

    $col_array = [  // Массив букв для колонок
        "A", "B", "C", "D", "E", "F", "G", "H", "I",
        "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z"
    ];

    /*
     * Определяет как введенные данные будут интерпретированы:
     * RAW - Данные от юзера не будут парситься и будут записаны как есть
     * USER_ENTERED     Значения будут так, как юзер их типизировал в UI.
     * Числа останутся числами, но строки могут конвертироваться в числа, даты и тд.
     */
    $params = ["valueInputOption" => "RAW"];

    /*
     * Определяет как будут изменяться существующие данные после ввода новых
     * OVERWRITE    Новые данные перезапишут старые в тех ячейках, где они записаны
     * INSERT_ROWS    Строки вставляются для новых данных.
     *
     */
    $insert = ["InsertDataOption" => 'INSERT_ROWS'];

    /*
     * Варианты отображения возвращаемых данных ValueRenderOption
     * https://developers.google.com/sheets/api/reference/rest/v4/ValueRenderOption
     * FORMATTED_VALUE | UNFORMATTED_VALUE | FORMULA
     * $render = ['ValueRenderOption' => 'FORMATTED_VALUE'];
     */


    $token = json_decode($ps['options']['credentials'], true);  // Декодируем токен

    /*
     * Настройки Google Sheets API V4
     * Документация https://developers.google.com/sheets/api/
     */
    $client = new Google_Client();
    $client->setAuthConfig($token); // Данные для подключения
    $client->addScope('https://www.googleapis.com/auth/spreadsheets'); // Области, к которым будет доступ
    $service = new Google_Service_Sheets($client);

    /* =============================== */

    if (strpos($work_spreadsheet_id, '/d/') !== FALSE)  // Если вставлена ссылка
    {
        $start = strpos($work_spreadsheet_id, '/d/') + 3; // Запишет стартовую позицию. 3 - количество символов в /d/
        $end = strpos($work_spreadsheet_id, '/edit');
        $spreadsheet_id = substr($work_spreadsheet_id, $start, $end - $start); // Получаем id таблицы из ссылки
    } 
    else
        $spreadsheet_id = $work_spreadsheet_id;


    switch ($options['option'])  // Выбранный метод выполнения
    {
        case 1: // Добавить строчку в конец

            if ($options['parse_cols']) // Парсить по спец. символу по колонкам?
            {
                $str_values = "";
                for ($i = 1; $i <= $field_count; $i++) // Перебираем все значения и приводим их в 1 строку через ;
                    $str_values .= $options['value' . $i] . ';';

                $str_values = rtrim($str_values, ';');
                $values[0] = explode(';', $str_values);
            } 
            else 
            {
                for ($i = 1; $i <= $field_count; $i++)
                    $values[0][] = $options['value' . $i];
            }

            $request_body = new Google_Service_Sheets_ValueRange([ // По api google
                'values' => $values
            ]);

            $result = $service->spreadsheets_values->append(  // Добавить в конец
                $spreadsheet_id,
                $work_sheet_title,
                $request_body,
                $params,
                $insert
            );

            $message = 'Данные добавлены в конец таблицы';
            $out = 1;
        break;

        case 2: // Добавить ячейку

            if ($options['add_type'] == 2) // Тип добавления по конкретным значениям
            {
                $json_table_values = $service->spreadsheets_values->get($spreadsheet_id, $work_sheet_title);
                $table_values = $json_table_values->getValues();

                $found_value = findCoordsInTable($table_values, $options['find_row'], $options['find_col']);

                // Найденная ячейка под замену
                $add_range = $work_sheet_title . "!" . $col_array[$found_value['found_col'] - 1] . $found_value['found_row'];
            }
            else
                $add_range = $work_sheet_title . "!" . $options['add_range']; // формируем диапазон для добавления

            $request = [
                'data' => [
                    [
                        'range' => $add_range,
                        'values' => [ [ $options['value0'] ] ]
                    ]
                ]
            ];

            $request = array_merge($request, $params); // Добавляем параметр valueInputOption

            $batch_update_request = new Google_Service_Sheets_BatchUpdateValuesRequest(
                $request
            ); // Создаем batch clear request

            $result = $service->spreadsheets_values->batchUpdate(
                $spreadsheet_id,
                $batch_update_request
            ); // Вызываем изменение

            $message = 'Данные добавлены';
            $out = 1;
        break;

        case 3: // Найти и заменить

            $table_info = getTableInfo(  // Получаем инфо по таблице
                $service,
                $spreadsheet_id,
                $work_sheet_title,
                $options['range'] // Диапазон ячеек таблицы
            );

            $requests = [
                new Google_Service_Sheets_Request([
                    'findReplace' => [
                        "find" => $options['value_to_find'],
                        "replacement" => $options['replacement'],
                        "sheetId" => $table_info['work_sheet_id']
                    ]
                ]) 
            ];

            $batch_update_request = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
                'requests' => $requests
            ]);

            $result = $service->spreadsheets->batchUpdate(
                $spreadsheet_id,
                $batch_update_request
            );

            $message = 'Заменено значение';
            $out = 1;
        break;

        case 4: // Получить строку
            $return_row = "";

            $response = $service->spreadsheets_values->get($spreadsheet_id, $work_sheet_title);
            $table_values = $response->getValues();

            // Если надо вернуть конкретный диапазон
            if(!$options['value_to_find'] && strpos($work_sheet_title, '!') !== FALSE)
            {
                $return_row = $table_values;
                $out = 1;
                break;
            }
            else
            {
                $return_row = getRowFromTable($table_values, $options['value_to_find']);

                if($return_row)
                    $message = 'Строка найдена и возвращена';
                else
                    $message = 'Строка не найдена';
            }
            $out = 1;
            break;

        case 5: // Удалить диапазон значений

            $request = [
                'ranges' => [ $options['range'] ]
            ];

            $batch_clear_request = new Google_Service_Sheets_BatchClearValuesRequest($request);
            $response = $service->spreadsheets_values->batchClear(
                $spreadsheet_id,
                $batch_clear_request
            );

            $message = 'Данные удалены';
            $out = 1;
            break;

        case 6: // Полностью удалить

            $table_info = getTableInfo(  // Получаем инфо по таблице
                $service,
                $spreadsheet_id,
                $work_sheet_title,
                $options['range'] // Диапазон ячеек таблицы
            );

            $json_table_values = $service->spreadsheets_values->get($spreadsheet_id, $work_sheet_title);
            $table_values = $json_table_values->getValues();

            if ($options['shift_dimension'] == 1)  // Если удаляем строку
            {
                $found_row = findRowCoordsInTable($table_values, $options['value_to_find']);

                $shift_dimension = "ROWS";
                $range_values = [
                    "startRowIndex" => $found_row,
                    "endRowIndex" => $found_row + 1,
                ];
            } 
            else 
            {
                $found_col = findColCoordsInTable($table_values, $options['value_to_find']);

                $shift_dimension = "COLUMNS";
                $range_values = [
                    "startColumnIndex" => $found_col,
                    "endColumnIndex" => $found_col + 1,
                ];
            }

            $request_array = [
                'deleteRange' => [
                    "shiftDimension" => $shift_dimension,
                    "range" => [
                        "sheetId" => $table_info['work_sheet_id']
                    ]
                ]
            ];

            foreach($range_values as $key => $value)    // Добавляем данные по range
                $request_array['deleteRange']['range'][$key] = $value;

            $requests = [ // Запрос по удаление
                new Google_Service_Sheets_Request($request_array)
            ];
            
            $batch_update_request = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
                'requests' => $requests
            ]);

            $result = $service->spreadsheets->batchUpdate(
                $spreadsheet_id,
                $batch_update_request
            );

            $message = 'Удалено';
            $out = 1;
            break;
    }


    $responce = [
        'out' => $out, // Обязательно должен быть номер выхода out, отличный от нуля!
        'value' => [
            'message' => $message,
            'return_row' => $return_row
        ]
    ];
}
elseif($act == 'man')
{
    $responce = [
        'html' => 
        "##Описание
        Данная ВРМ работает напрямую с Гугл Таблицами через сервисный аккаунт. Подробная инструкция тут - https://vk.com/@rexont-google-tables-from-activeusers

        ###Доступные переменные:
        
        **{b.{bid}.value.message}**
        Текстовое сообщение о результате выполнения действия (чаще для отладки)

        **{b.{bid}.value.return_row}**
        Элементы найденной строки или false - если не найдена строка по значению
        "
    ];
}
/* Отдать JSON, не кодируя кириллические символы в кракозябры */
echo json_encode($responce, JSON_UNESCAPED_UNICODE);