<?php 

$collection = [
	1 => 'Артем',
	2 => 'Князев'
];

$str = "";
foreach ($collection as $value) {
	$str .= $value." ";
}

echo $str;
 ?>