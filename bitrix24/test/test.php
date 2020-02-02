<?php 
	//https://b24-n6yr66.bitrix24.ru/oauth/authorize/?client_id=local.5dee6d167e1946.98019744

	// refresh d205165e0042d1ca0042c4ea00000001000003d113578ae6e38bf4416803454bf4cd35
	// access e286ee5d0042d1ca0042c4ea00000001000003ddcec4be808e54048343bd8def4d6408
	// member id 774dbd7770efccd9fee0e4e84af3c8fd

	$arParams = [
		'this_auth' => 'Y',
		'params'    =>
			[
				'client_id'     => 'local.5dee6d167e1946.98019744',
				'grant_type' => 'authorization_code',
				'client_secret' => 'UEKFqJMdYDflMojlcHtlE0ifcnvmoPM6FU7g8eXzN5g62i9WW4',
				'code' => 'b07aee5d0042d1ca0042c4ea000000010000038c3eb0315a7a820b588194972f693617'
			]
	];

	$url = 'https://oauth.bitrix.info/oauth/token/';

	$sPostFields = http_build_query($arParams[ 'params' ]);

	$obCurl = curl_init();
	curl_setopt($obCurl, CURLOPT_URL, $url);
	curl_setopt($obCurl, CURLOPT_RETURNTRANSFER, true);
	if($sPostFields)
	{
		curl_setopt($obCurl, CURLOPT_POST, true);
		curl_setopt($obCurl, CURLOPT_POSTFIELDS, $sPostFields);
	}
	curl_setopt(
		$obCurl, CURLOPT_FOLLOWLOCATION, (isset($arParams[ 'followlocation' ]))
		? $arParams[ 'followlocation' ] : 1
	);
	$out = curl_exec($obCurl);
	curl_close($obCurl);

	$out = json_decode($out, true);
	echo '<pre>';
	print_r($out);
	echo '</pre>';

 ?>
