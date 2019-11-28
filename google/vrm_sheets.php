<?php
require_once ('vendor/autoload.php');


/* Настройки Google Sheets API V4 */

// Путь к файлу ключа сервисного аккаунта
$googleAccountKeyFilePath = 'my_key.json';
putenv('GOOGLE_APPLICATION_CREDENTIALS='.$googleAccountKeyFilePath);
 
// Документация https://developers.google.com/sheets/api/
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
 
// Области, к которым будет доступ
$client->addScope('https://www.googleapis.com/auth/spreadsheets');
 
$service = new Google_Service_Sheets($client);

/* =============================== */

$act = $_REQUEST['act'];

if($act == 'options') 
{
    $responce = [
        'title' => 'ВРМ Google',        // Это заголовок блока, который будет виден на схеме
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
                    4 => 'Удалить ячейку',
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
                    10 => '10'
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
            		'option' => 4
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
            	'show' => [
            		'option' => 2,
            		'add_type' => 2
            	]
            ],
            'find_row' => [
            	'title' => 'Строка по уникальному ключу',
            	'desc' => 'Например id пользователя',
            	'default' => '',
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
            	'show' => [
            		'option' => [3]	
            	]
            ],
            'replacement' => [
            	'title' => 'Значение на замену',
            	'desc' => "",
            	'default' => '',
            	'show' => [
            		'option' => [3]	
            	]
            ],

            // поле для вставки в ячейку
          	'value0' => [
            	'title' => 'Значение',
            	'desc' => "",
            	'default' => '',
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
            	'show' => [
            		'option' => 1,
            		'field_count' => [1,2,3,4,5,6,7,8,9,10]	
            	]
            ],
            'value2' => [
            	'title' => 'Значение 2',
            	'desc' => "",
            	'default' => '',
            	'show' => [
            		'option' => [1],
            		'field_count' => [2,3,4,5,6,7,8,9,10]	
            	]
            ],
            'value3' => [
            	'title' => 'Значение 3',
            	'desc' => "",
            	'default' => '',
            	'show' => [
            		'option' => [1],
            		'field_count' => [3,4,5,6,7,8,9,10]	
            	]
            ],
            'value4' => [
            	'title' => 'Значение 4',
            	'desc' => "",
            	'default' => '',
            	'show' => [
            		'option' => [1],
            		'field_count' => [4,5,6,7,8,9,10]	
            	]
            ],
            'value5' => [
            	'title' => 'Значение 5',
            	'desc' => "",
            	'default' => '',
            	'show' => [
            		'option' => [1],
            		'field_count' => [5,6,7,8,9,10]	
            	]
            ],
            'value6' => [
            	'title' => 'Значение 6',
            	'desc' => "",
            	'default' => '',
            	'show' => [
            		'option' => [1],
            		'field_count' => [6,7,8,9,10]	
            	]
            ],
            'value7' => [
            	'title' => 'Значение 7',
            	'desc' => "",
            	'default' => '',
            	'show' => [
            		'option' => [1],
            		'field_count' => [7,8,9,10]	
            	]
            ],
            'value8' => [
            	'title' => 'Значение 8',
            	'desc' => "",
            	'default' => '',
            	'show' => [
            		'option' => [1],
            		'field_count' => [8,9,10]	
            	]
            ],
            'value9' => [
            	'title' => 'Значение 9',
            	'desc' => "",
            	'default' => '',
            	'show' => [
            		'option' => [1],
            		'field_count' => [9,10]	
            	]
            ],
            'value10' => [
            	'title' => 'Значение 10',
            	'desc' => "",
            	'default' => '',
            	'show' => [
            		'option' => [1],
            		'field_count' => [10]	
            	]
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
elseif($act == 'run') 
{                                               // Схема прислала данные, обрабатываем
    $target = $_REQUEST['target'];              // Пользователь, от имени которого выполняется блок
    $ums    = $_REQUEST['ums'];                 // Данные об активности пользователя, массив в котором есть:
                                                // id - номер элемента (комментария, поста, смотря о чём речь в активности)
                                                // from_id - UID пользователя
                                                // date - дата в формате timestamp
                                                // text - текст комментария, сообщения и т.д.
    $options = $_REQUEST['options'];
    // если стоит цель не на инициатора активности
    if(isset($target)) { $user_id = $target; } 
    else { $user_id = $ums['from_id']; }

    // массив букв для колонок
    $col_array = [
	"A","B","C","D","E","F","G",
	"H","I","J","K","L","M","N",
	"O","P","Q","R","S","T","U",
	"V","W","Z","Y","Z"];

    // диапазон ячеек таблицы
    $range = $options['range'];

    // выбранный метод выполнения
    $option = $options['option'];


    // поля
    $value0 = $options['value0']; // значение для добавления
    $field_count = $options['field_count'];
    for($i = 1; $i <= $field_count; $i++) 
    {
    	${'value'.$i} = $options['value'.$i];
    }

    // поля для найти и заменить
    $value_to_find = $options['value_to_find'];
    $replacement = $options['replacement'];

    // ID таблицы
    $work_spreadsheet_id = $options['spreadsheet_id'];

    // название рабочего листа
    $work_sheet_title = $options['work_sheet_title'];

	// если вставлена ссылка
	if(strpos($work_spreadsheet_id,'/d/') !== FALSE)
	{
		$start = strpos($work_spreadsheet_id,'/d/') + 3;	// запишет стартовую позицию. 3 - количество символов в /d/
		$end = strpos($work_spreadsheet_id,'/edit');
		$spreadsheetId = substr($work_spreadsheet_id, $start, $end - $start);	// получаем id таблицы из ссылки
	}
	else
	{
		$spreadsheetId = $work_spreadsheet_id;
	}

	// получаем инфо по таблице
	$response = $service->spreadsheets->get($spreadsheetId);
	 
	// Свойства таблицы
	$spreadsheetProperties = $response->getProperties();
	$work_spreadsheet_name = $spreadsheetProperties->title; // Название таблицы
	
	foreach ($response->getSheets() as $sheet) {
	 
		// Свойства листа
		$sheetProperties = $sheet->getProperties();
		if($sheetProperties->title == $work_sheet_title) // Название листа
		{
			$range = $work_sheet_title."!".$range;	// A1 notation для диапазона
			$gridProperties = $sheetProperties->getGridProperties();
			$sheetColumnCount = $gridProperties->columnCount; // Количество колонок
			$sheetRowCount = $gridProperties->rowCount; // Количество строк
			$work_sheet_id = $sheetProperties->sheetId;
		}
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Определяет как введенные данные будут интерпретированы:						*
     * RAW - Данные от юзера не будут парситься и будут записаны как есть  			*
     * USER_ENTERED 	Значения будут так, как юзер их типизировал в UI. 			*
     * Числа останутся числами, но строки могут конвертироваться в числа, даты и тд.*
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	$params = [
		"valueInputOption" => "RAW"				
	];  										
				

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Определяет как будут изменяться существующие данные после ввода новых 		*
     * OVERWRITE 	Новые данные перезапишут старые в тех ячейках, где они записаны  *
     * INSERT_ROWS    Строки вставляются для новых данных.        					*
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	$insert = [
		"InsertDataOption" => 'INSERT_ROWS'	
	];

	
	// Варианты отображения возвращаемых данных ValueRenderOption
	// https://developers.google.com/sheets/api/reference/rest/v4/ValueRenderOption
	// FORMATTED_VALUE | UNFORMATTED_VALUE | FORMULA
	$render = [
		'ValueRenderOption' => 'FORMATTED_VALUE'
	];


    $out = 0;						// выход в ноль
    $message = "";

	switch ($option) {
		// добавить строчку в конец
		case 1:
			$values = [					// значения для именно так
			    [
			        $value1,
			      	$value2,
			      	$value3,
			      	$value4,
			      	$value5,
			      	$value6,
			      	$value7,
			      	$value8,
			      	$value9,
			      	$value10
			    ],    
			];
			$body = new Google_Service_Sheets_ValueRange([		// какая-то шляпа для правильного формирования добавления
			    'values' => $values
			]);
			// A1 здесь только потому что range обязателен
			$result = $service
				->spreadsheets_values
				->append(
					$spreadsheetId, 
					$work_sheet_title, 
					$body, 
					$params,
					$insert
				);	// добавить в конец
			$message = 'Данные добавлены в конец таблицы';
			$out = 1;
			break;
		// добавить ячейку
		case 2:
			$add_type = $options['add_type'];	// тип добавления

			$add_range = $work_sheet_title."!".$options['add_range'];

			if($add_type == 2)
			{
				$find_col = $options['find_col'];	// колонка для поиска
				$find_row = $options['find_row'];	// строка для поиска


				// достать данные из листа рабочего
				$json_text = $service->spreadsheets_values->get($spreadsheetId, $work_sheet_title);

				$php_text = $json_text->getValues();	// функция получения значений. Чтобы её узнать потратил 2 дня. 
														// json_decode не работает

			  	for($j = 0;$j <= $sheetRowCount;$j++)
			    {
			      for($i = 0;$i <= $sheetColumnCount;$i++)
			      {
			          if($php_text[$j][$i] == $find_col)
			          {
			              $found_col = $i+1;				// найденный индекс колонки
			          }
			      }
			    }
			  
			  	for($j = 0;$j <= $sheetRowCount;$j++)
			    {
			      for($i = 0;$i <= $sheetColumnCount;$i++)
			      {
			          if($php_text[$i][$j] == $find_row)
			          {
			              $found_row = $i+1;				// найденный индекс строки
			          }
			      }
			    }

				$add_range = $work_sheet_title."!".$col_array[$found_col-1].$found_row;	// найденная ячейка под замену
				
			}
			

			$json_request = '
				{
				    "data": [
				        {
				            "range": "'.$add_range.'",
				            "values": [
				                [
				                    "'.$value0.'"
				                ]
				            ]
				        }
				    ],
				    "valueInputOption": "RAW"
				}
			';

			$php_request = json_decode($json_request,true);		// json to php

			$batchUpdateRequest = new Google_Service_Sheets_BatchUpdateValuesRequest($php_request);	// создаем batch clear request
			$response = $service->spreadsheets_values->batchUpdate($spreadsheetId, $batchUpdateRequest);	// вызываем batch clear

			$message = 'Данные добавлены';
			$out = 1;
			break;
		// найти и заменить
		case 3:
			$requests = [
			    new Google_Service_Sheets_Request([
			        'findReplace' => [
			            "find" => $value_to_find,
			    		"replacement" => $replacement,
			    		"sheetId"=> $work_sheet_id
			        ]
			    ])
			];

			$batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
				'requests' => $requests
			]);

			$response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);

			$message = 'Заменено значение';
			$out = 1;
			break;

		// удалить диапазон значений
		case 4:
			// создаем json строку запроса по api документации
			$json_request = '
				{
					"ranges": ["'.$range.'"]
				}
			';

			$php_request = json_decode($json_request, true);	// переводим json в php

			$batchUpdateRequest = new Google_Service_Sheets_BatchClearValuesRequest($php_request);	// создаем batch clear request
			$response = $service->spreadsheets_values->batchClear($spreadsheetId, $batchUpdateRequest);	// вызываем batch clear

			$message = 'Данные удалены';
			$out = 1;
			break;
		
		default:
			// code...
			break;
	}

	$out = 1;


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Сформировать массив на отдачу                              *
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    $responce = [
        'out' => $out,                          // Обязательно должен быть номер выхода out, отличный от нуля!
        'value' => [                            // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через 
                                                // $bN_value.ваши_ключи_массива
            'message' => $message,
            'range' => $range,
            'find_row' => $found_row,
            'find_col' => $found_col,
            'sheet_id' => $work_sheet_id
        ]
    ];
} 
elseif($act == '') {
    /* Действие не задано, и что же нам сделать? Станцевать вальс, попрыгать, пойти в гости к Кролику? */
}
/* Отдать JSON, не кодируя кириллические символы в кракозябры */
echo json_encode($responce, JSON_UNESCAPED_UNICODE);