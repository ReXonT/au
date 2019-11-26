<?php

/* Файл документации, ибо без него тут просто АД */

/* Range D:D - это слобец D

/* 
Requests с созданием new Google_Service_Sheets_Request нужен для создания запроса из таблицы https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/request. Тут пример на findReplaceRequest.

Нужен для BatchRequest

В requests может быть много разных запросов из таблицы выше. Если один false = все false
*/

$requests = [
    new Google_Service_Sheets_Request([
        'findReplace' => [
            "find" => "phone",
    		"replacement" => "Телефон",
    		"allSheets"=> true
        ]
    ])
];


/* Сам Sheets BatchRequest создается так */

$batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
	'requests' => $requests
]);


/* ВСЁ ПРОЩЕ: деалешь json запись любую, потом json_decode(значение, true) и отправляешь в нужный запрос. ВСЁ */