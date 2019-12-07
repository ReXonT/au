<?php 

function changeValueToRussian($name)
{
	$russianValue = array(
		'ADDRESS' => "Адрес контакта",
		'ADDRESS_2' => "Вторая страница адреса",
		'ADDRESS_CITY' => "Город",
		'ADDRESS_COUNTRY' => "Страна",
		'ADDRESS_COUNTRY_CODE' => "Код страны",
		'ADDRESS_POSTAL_CODE' => "Почтовый индекс",
		'ADDRESS_PROVINCE' => "Область",
		'ADDRESS_REGION' => "Район",
		'ASSIGNED_BY_ID' => "Связано с пользователем по ID",
		'BIRTHDATE' => "Дата рождения",
		'COMMENTS' => "Комментарии",
		'COMPANY_ID' => "Привязка лида к компании",
		'COMPANY_TITLE' => "Название компании, привязанной к лиду",
		'CONTACT_ID' => "Привязка лида к контакту",
		'CREATED_BY_ID' => "Кем создана",
		'DATE_CREATE' => "Дата создания",
		'DATE_MODIFY' => "Дата изменения",
		'EMAIL' => "Адрес электронной почты",
		'HAS_EMAIL' => "Проверка заполненности поля электронной почты",
		'HAS_PHONE' => "Проверка заполненности поля телефон",
		'HONORIFIC' => "Вид обращения",
		'ID' => "Идентификатор контакта ",
		'IM' => "Мессенджеры",
		'IS_RETURN_CUSTOMER' => "Признак повторного лида",
		'MODIFY_BY_ID' => "Идентификатор автора последнего изменения",
		'NAME' => "Имя",
		'OPENED' => "Доступен для всех",
		'ORIGINATOR_ID' => "Идентификатор источника данных",
		'ORIGIN_ID' => "Идентификатор элемента в источнике данных",
		'ORIGIN_VERSION' => "Оригинальная версия",
		'PHONE' => "Телефон контакта",
		'POST' => "Должность",
		'SECOND_NAME' => "Отчество",
		'SOURCE_DESCRIPTION' => "Описание источника",
		'SOURCE_ID' => "Идентификатор источника",
		'STATUS_DESCRIPTION' => "",
		'STATUS_ID' => "",
		'STATUS_SEMANTIC_ID' => "",
		'TITLE' => "Название лида",
		'UTM_CAMPAIGN' => "Обозначение рекламной кампании",
		'UTM_CONTENT' => " 	Содержание кампании",
		'UTM_MEDIUM' => "Тип трафика",
		'UTM_SOURCE' => "Рекламная система",
		'UTM_TERM' => "Условие поиска кампании",
		'WEB' => "URL ресурсов лида",
		'LAST_NAME' => "Фамилия",
		'OPPORTUNITY' => "Сумма заказа",
		'CURRENCY_ID' => "Валюта",
		'STATUS_ID' => 'Статус лида'
	);

	return $russianValue[$name];
}

 ?>