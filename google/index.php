<?php
/**
 * Подключаемся к API
 *******************************************************/
require_once ('vendor/autoload.php');
 
// Путь к файлу ключа сервисного аккаунта
$googleAccountKeyFilePath = 'my_key.json';
putenv( 'GOOGLE_APPLICATION_CREDENTIALS=' . $googleAccountKeyFilePath );
 
// Документация https://developers.google.com/sheets/api/
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
 
// Области, к которым будет доступ
// https://developers.google.com/identity/protocols/googlescopes
$client->addScope( 'https://www.googleapis.com/auth/spreadsheets' );
 
$service = new Google_Service_Sheets( $client );
 
// ID таблицы
$spreadsheetId = '1rqugmGsq312CM0H7DxlyI5-ftxQMdOn0zqzIuzFnkTE';
$range = 'List';
try{

$response = $service->spreadsheets_values->get($spreadsheetId, $range);

  	$php_text = $response->getValues();
  
  	$col_array = [
	"A","B","C","D","E","F","G",
	"H","I","J","K","L","M","N",
	"O","P","Q","R","S","T","U",
	"V","W","Z","Y","Z"];
  
  	$found = '2';
  
  	$sheetRowCount = 1000;
  	$sheetColumnCount = 25;
  
  	foreach($php_text as $key => $value)
    {
      $v = $value;
      foreach($value as $key => $val)
      {
        if($val == $found)
        {
          $s = 1;
        }
      }
      if($s)
        break;
    }
	
  print_r($v);
	
	  
}
catch(Exception $e)
{
	echo $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile() . ' ' . $e->getCode;
}





/* ЭТА ШЛЯПА ДОБАВЛЯЕТ СТРОКИ */