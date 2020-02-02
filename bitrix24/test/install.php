<?php
require_once (__DIR__.'/crest.php');

$result = CRest::installApp();
if($result['rest_only'] === false):?>
<head>
	<script src="//api.bitrix24.com/api/v1/"></script>
	<?if($result['install'] == true):?>
	<script>
		BX24.init(function(){
			BX24.installFinish();
		});
	</script>
	<?endif;?>
</head>
<body>
	<?
		echo 'installation has been finished <br>';
		echo '<pre>';
		$query = http_build_query($result['code']);
		$url = 'https://rexont.ru/au/bitrix24/php.php';
		$obCurl = curl_init();
		curl_setopt($obCurl, CURLOPT_URL, $url);
		curl_setopt($obCurl, CURLOPT_RETURNTRANSFER, FALSE);
		curl_setopt($obCurl, CURLOPT_POST, true);
		curl_setopt($obCurl, CURLOPT_POSTFIELDS, $query);
		curl_exec($obCurl);
	?>
</body>
<?endif;