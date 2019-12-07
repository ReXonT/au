<?


function redirect($url)
{
    Header("HTTP 302 Found");
    Header("Location: ".$url);
    die();
}

define('APP_ID', 'local.5deac0a3d9c003.59939966'); // из Bitrix24 после добавления приложения
define('APP_SECRET_CODE', 'ep5hA07NAnRmmv6YzGSkCiACkhW7r33OIW6f5PYaN2Wvai6OUU'); // из Bitrix24 после добавления приложения
define('APP_REG_URL', 'http://cx83146.tmweb.ru/au/bitrix/index.php'); // url index.php приложения Bitrix24
define('APP_PORTAL', 'https://b24-n6yr66.bitrix24.ru'); //

function executeHTTPRequest ($queryUrl, array $params = array()) {
    $result = array();  // объявляем массив
    $queryData = http_build_query($params); // делаем http запрос из $params

    $curl = curl_init();    // инициализация курла
    curl_setopt_array($curl, array(
        CURLOPT_POST => TRUE,   
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_URL => $queryUrl,   // url для курла
        CURLOPT_POSTFIELDS => $queryData,   // пост значения
    ));

    $curlResult = curl_exec($curl); // получаем данные
    curl_close($curl);  // закрываем курл

    if ($curlResult != '') $result = json_decode($curlResult, true);    // если ответ не пустой, то декодируем json ответ

    return $result;
}

// функция получения кода авторизации
function requestCode ($domain) {
    $url = 'https://' . $domain . '/oauth/authorize/?client_id=' . urlencode(APP_ID);   // делаем валидный url
    redirect($url);
}

// функция получения access_token
function requestAccessToken ($code, $server_domain) {
    $url = 'https://' . $server_domain . '/oauth/token/?' .
        'grant_type=authorization_code'.
        '&client_id='.urlencode(APP_ID).
        '&client_secret='.urlencode(APP_SECRET_CODE).
        '&code='.urlencode($code);
    return executeHTTPRequest($url);
}

function executeREST ($rest_url, $method, $params, $access_token) {
    $url = $rest_url.$method.'.json';
    return executeHTTPRequest(
        $url, 
        array_merge(
            $params, 
            array("auth" => $access_token)
        )
    );
}

// в domain запишется либо portal (если есть), либо domain (если есть), либо empty (если ничего нет)
$domain = isset($_REQUEST['portal']) ? $_REQUEST['portal'] : ( isset($_REQUEST['domain']) ? $_REQUEST['domain'] : 'empty');

$step = 0;  // ставим шаг выполнения кода на 0

if (isset($_REQUEST['portal'])) $step = 1;  // если уже есть portal, то шаг 1
if (isset($_REQUEST['code'])) $step = 2;    // если уже есть code, то шаг 2

$btokenRefreshed = null;

$arScope = array('user');

switch ($step) {
    case 1:
        // нам нужно получить первый авторизационный код из Битрикс24 когда наше приложение уже установлено
        requestCode($domain);
        break;

    case 2:
        // мы уже получили первый авторизационный код и используем его, чтобы получить access_token и refresh_token (если нам он будет нужен позже)
        /*echo "Шаг 2 (полученный код авторизации):<pre>";
        print_r($_REQUEST);
        echo "</pre><br/>";*/

        $arAccessParams = 
        requestAccessToken(
            $_REQUEST['code'], 
            $_REQUEST['server_domain']
        );

        /*echo "Шаг 3 (полученный код доступа (access_token)):<pre>";
        print_r($arAccessParams);
        echo "</pre><br/>";*/

        $response = 
        executeREST(
            $arAccessParams['client_endpoint'], 
            'crm.lead.add', 
            array(
                'fields' => [
                    "TITLE" => "Илья С", 
                    "NAME" => "Илья", 
                    "SECOND_NAME" => "Вячеславович", 
                    "LAST_NAME" => "Соколов",
                    "COMMENTS" => "Комментарии",
                    "STATUS_ID" => "NEW", 
                    "OPENED" => "Y", 
                    "ASSIGNED_BY_ID" => 1, 
                    "CURRENCY_ID" => "RUB", 
                    "OPPORTUNITY" => 12500,
                    "PHONE" => [
                        "VALUE" => [
                            "VALUE" => 89200756364,
                            "VALUE_TYPE" => "WORK"
                        ]
                    ]
                ],
                'params' => ['REGISTER_SONET_EVENT' => FALSE]
            ),
            $arAccessParams['access_token']);

        break;
    default:
        break;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Быстрый старт. Локальное серверное приложение с интерфейсом Bitrix24</title>

</head>
<body>
<?if ($step == 0) {?>
    step 1 (Перенаправление на Bitrix24):<br/>
    <form action="" method="post">
        <input type="text" name="portal" placeholder="Bitrix24 Ссылка">
        <input type="submit" value="Авторизоваться">
    </form>
    <?
}
elseif ($step == 2) {
    echo '<pre>';
    print_r($response);
    echo '</pre>';
}
?>
</body>
</html>
