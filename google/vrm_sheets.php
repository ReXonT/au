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
            'sheet_id' => [
            	'title' => 'ID таблицы',
            	'desc' => 'Можно вставить ссылку на таблицу',
            	'default' => ''
            ],
            'range' => [
            	'title' => 'Диапазон ячеек',
            	'desc' => "Например A1:C1",
            	'default' => ''
            ],
            'option' => [
                'title' => 'Что делаем',
                'values' => [
                    1 => 'Добавить строку в конец таблицы',
                    2 => 'Удалить запись',
                    3 => 'Добавить столбец',
                    4 => 'Удалить столбец'
                ],
                'default' => ''
            ],
            'value1' => [
            	'title' => 'Значение 1',
            	'desc' => "",
            	'default' => ''
            ],
            'value2' => [
            	'title' => 'Значение 2',
            	'desc' => "",
            	'default' => ''
            ],
            'value3' => [
            	'title' => 'Значение 3',
            	'desc' => "",
            	'default' => ''
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

    // диапазон ячеек таблицы
    $range = $options['range'];

    // выбранный метод выполнения
    $option = $options['option'];

    // поля
    $value1 = $options['value1'];
    $value2 = $options['value2'];
    $value3 = $options['value3'];

    // ID таблицы
    $sheet_id = $options['sheet_id'];

	// если вставлена ссылка
	if(strpos($sheet_id,'/d/') !== FALSE)
	{
		$start = strpos($sheet_id,'/d/') + 3;	// запишет стартовую позицию. 3 - количество символов в /d/
		$end = strpos($sheet_id,'/edit');
		$spreadsheetId = substr($sheet_id, $start, $end - $start);	// получаем id таблицы из ссылки
	}
	else
	{
		$spreadsheetId = $sheet_id;
	}
	

    $out = 0;


	switch ($option) {
		// добавить строчку в конец
		case 1:
			$values = [					// значения для именно так
			    [
			        $value1,
			      	$value2,
			      	$value3
			    ],    
			];
			$body = new Google_Service_Sheets_ValueRange([		// какая-то шляпа для правильного формирования добавления
			    'values' => $values
			]);
			$conf = ["valueInputOption" => "USER_ENTERED"];  	// а без этой штуки не работает
			$result = $service->spreadsheets_values->append($spreadsheetId, $range, $body, $conf);	// добавить в конец
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
            'out' => $sheet_id,
            'spread' => $spreadsheetId
        ]
    ];
} 
elseif($act == '') {
    /* Действие не задано, и что же нам сделать? Станцевать вальс, попрыгать, пойти в гости к Кролику? */
}
/* Отдать JSON, не кодируя кириллические символы в кракозябры */
echo json_encode($responce, JSON_UNESCAPED_UNICODE);