<?php
require_once ('vendor/autoload.php');
ini_set('display_errors',1);

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
            'video_id' => [
            	'title' => 'ID видео',
            	'desc' => 'Можно вставить ссылку на видео',
            	'default' => ''
            ],
            'video_status' => [
                'title' => 'Статус видео',
                'desc' => 'Public, private, unlisted',
                'default' => 'public'
            ]
            
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

    /* Настройки Google Sheets API V4 */

    $ps = $_REQUEST['paysys']['ps'];            // Сюда придут настройки выбранной системы

    $token = $ps['options']['credentials'];

    $video_id = $options['video_id'];
    $video_status = $options['video_status'];

	$php_token = json_decode($token,true);

	// Путь к файлу ключа сервисного аккаунта
	/*$googleAccountKeyFilePath = 'my_key.json';
	putenv('GOOGLE_APPLICATION_CREDENTIALS='.$googleAccountKeyFilePath);*/
	 
	// Документация https://developers.google.com/sheets/api/
	$client = new Google_Client();
	$client->setAuthConfig($php_token);
	//$client->useApplicationDefaultCredentials();
	 
	// Области, к которым будет доступ
	$client->addScope('https://www.googleapis.com/auth/youtube.force-ssl');
	 
	$service = new Google_Service_YouTube($client);

    $video = new Google_Service_YouTube_Video();

    // Add 'id' string to the $video object.
    $video->setId('dGasYyV7tBs');

    // Add 'status' object to the $video object.
    $videoStatus = new Google_Service_YouTube_VideoStatus();
    $videoStatus->setPrivacyStatus('public');
    $video->setStatus($videoStatus);

    $response = $service->videos->update('status', $video);

	/* =============================== */


    $out = 1;						// выход в ноль


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Сформировать массив на отдачу                              *
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    $responce = [
        'out' => $out,                          // Обязательно должен быть номер выхода out, отличный от нуля!
        'value' => [                            // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через 
                                                // $bN_value.ваши_ключи_массива
            'message' => $response
        ]
    ];
} 
elseif($act == '') {
    /* Действие не задано, и что же нам сделать? Станцевать вальс, попрыгать, пойти в гости к Кролику? */
}
/* Отдать JSON, не кодируя кириллические символы в кракозябры */
echo json_encode($responce, JSON_UNESCAPED_UNICODE);