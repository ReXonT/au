<?php
require_once ('vendor/autoload.php');

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
            		'option' => 5
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

    /* Настройки Google Sheets API V4 */

    $ps = $_REQUEST['paysys']['ps'];            // Сюда придут настройки выбранной системы

    $token = $ps['options']['credentials'];

	$php_token = json_decode($token,true);

	// Путь к файлу ключа сервисного аккаунта
	/*$googleAccountKeyFilePath = 'my_key.json';
	putenv('GOOGLE_APPLICATION_CREDENTIALS='.$googleAccountKeyFilePath);*/
	 
	// Документация https://developers.google.com/sheets/api/
	$client = new Google_Client();
	$client->setAuthConfig($php_token);
	//$client->useApplicationDefaultCredentials();
	 
	// Области, к которым будет доступ
	$client->addScope('https://www.googleapis.com/auth/spreadsheets');
	 
	$service = new Google_Service_Sheets($client);

	/* =============================== */

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
	
	foreach ($response->getSheets() as $sheet) 
    {
		// Свойства листа
		$sheetProperties = $sheet->getProperties();
		if($sheetProperties->title == $work_sheet_title) // Название листа
		{
			$range = $work_sheet_title."!".$range;	// A1 notation для диапазона
			$gridProperties = $sheetProperties->getGridProperties();
			$sheetColumnCount = $gridProperties->columnCount; // Количество колонок
			$sheetRowCount = $gridProperties->rowCount; // Количество строк
			$work_sheet_id = $sheetProperties->sheetId;	// id рабочего листа
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
    $message = "";					// сообщение о результате
    $return_row = "";               // инициализация

	switch ($option) 
    {
		// добавить строчку в конец
		case 1:

            $parse_cols = $options['parse_cols'];   // парсить по спец. символу по колонкам?
            if($parse_cols)
            {
                $v = "";
                for($i = 1;$i<=$field_count;$i++)
                {
                    $v .= ${'value'.$i}.';';
                }
                $v = rtrim($v,';');

                $values[] = explode(';',$v);  // массив values
            }
            else
            {
                for($i = 1;$i<=$field_count;$i++)
                {
                    $values[0][] = ${'value'.$i};
                }
            }

			$body = new Google_Service_Sheets_ValueRange([		// по api google
			    'values' => $values
			]);
			// range обязателен
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

			$add_range = $work_sheet_title."!".$options['add_range'];	// формируем диапазон для добавления

			if($add_type == 2)
			{
				$find_col = $options['find_col'];	// колонка для поиска
				$find_row = $options['find_row'];	// строка для поиска

				// достать данные из листа рабочего
				$json_text = $service->spreadsheets_values->get($spreadsheetId, $work_sheet_title);

				$php_text = $json_text->getValues();	// функция получения значений. Чтобы её узнать потратил 2 дня. 
														// json_decode не работает
				
				// поиск нужного столбца
			  	for($i = 0;$i <= $sheetRowCount;$i++)
			    {
			      for($j = 0;$j <= $sheetColumnCount;$j++)
			      {
			          if($php_text[$i][$j] == $find_col)
			          {
			              $found_col = $j+1;				// найденный индекс колонки
			          }
			      }
			    }
			  	
			  	// поиск нужной строки
			  	for($i = 0;$i <= $sheetRowCount;$i++)
			    {
			      for($j = 0;$j <= $sheetColumnCount;$j++)
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
			// вызываем findReplace по google api
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

		// получить строку
		case 4:
			$value_to_find = $options['value_to_find'];
            $s = 0;
			
			// запросить данные по листу
			$response = $service->spreadsheets_values->get($spreadsheetId, $work_sheet_title);
			$php_text = $response->getValues();	// получить значения листа
			
			foreach($php_text as $key => $value)
		    {
		      	$return_row = $value;	// задаем полную строку на возращение
			    foreach($value as $key => $val)
			    {
			    	if($val == $value_to_find)	// если нашли внутри строки нужное сообщение, то выходим из цикла полностью
			        {
			          $s = 1;
                      $message = 'Строка найдена и возвращена';
			        }
			    }
			    if($s) break;
		    }
            if(!$s)
            {
                $return_row = "false";
                $message = 'Строка не найдена';
            }
		    $out = 1;
			break;

		// удалить диапазон значений
		case 5:
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

		case 6:
			$shift_dimension = $options['shift_dimension']; // что удалить

			// достать данные из листа рабочего
			$json_text = $service->spreadsheets_values->get($spreadsheetId, $work_sheet_title);

			$php_text = $json_text->getValues();	// функция получения значений 
													// json_decode не работает

			// если удаляем строку
			if($shift_dimension == 1)
			{
				$find_row = $options['value_to_find'];	// строка для поиска

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

			    // запрос по удалению строки
				$requests = [
				    new Google_Service_Sheets_Request([
				        'deleteRange' => [
				            "shiftDimension" => "ROWS",
				    		"range" => [
				    			"startRowIndex" => $found_row-1,
	                        	"endRowIndex" => $found_row,
	                            "sheetId" => $work_sheet_id 
	                        ]
				        ]
				    ])
				];

			}
			else
			{
				$find_col = $options['value_to_find'];	// столбец для поиска

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

			    // запрос по удалению столбца
			    $requests = [
				    new Google_Service_Sheets_Request([
				        'deleteRange' => [
				            "shiftDimension" => "COLUMNS",
				    		"range" => [
				    			"startColumnIndex" => $found_col-1,
	                        	"endColumnIndex" => $found_col,
	                            "sheetId" => $work_sheet_id 
	                        ]
				        ]
				    ])
				];

			}

			$batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
				'requests' => $requests
			]);

			$response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
			$message = 'Удалено';
			$out = 1;
			break;
		
		default:
			// code...
			break;
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Сформировать массив на отдачу                              *
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    $responce = [
        'out' => $out,                          // Обязательно должен быть номер выхода out, отличный от нуля!
        'value' => [                            // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через 
                                                // $bN_value.ваши_ключи_массива
            'message' => $message,
            'range' => $range,
            'return_row' => $return_row
        ]
    ];
} 
elseif($act == '') {
    /* Действие не задано, и что же нам сделать? Станцевать вальс, попрыгать, пойти в гости к Кролику? */
}
elseif($act == 'man') {
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