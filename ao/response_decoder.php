<?php


$act = $_REQUEST['act'];

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Режим OPTIONS - в котором ВРМ создаёт в схеме блок управления  *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

if($act == 'options') {
    $responce = [
        'title' => 'Расшифровка ответа',      // Это заголовок блока, который будет виден на схеме
        'vars' => [                     // переменные, которые можно будет настроить в блоке
            'b0_request' => [
                'title' => 'Ответ',   // заголовок поля
                'default' => '$b0_request',
                'desc' => 'Не меняйте, если всё работает',    // описание поля, можно пару строк
            ],
        ],
        'out' => [                      // Это блоки выходов, мы задаём им номера и подписи (будут видны на схеме)
            1 => [                      // Номер 0 означает красный выход блока ВРМ, зарезервированный для случаев сбоя
                'title' => 'Найдено',    // название выхода 1
            ]        
        ]
    ];

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Режим RUN - в котором ВРМ получает, обрабатывает и возвращает  *
 * полученные от схемы данные                                   *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
} elseif($act == 'run') {              // Схема прислала данные, обрабатываем

    $target = $_REQUEST['target'];  // Пользователь, от имени которого выполняется блок
    $ums    = $_REQUEST['ums'];     // Данные об активности пользователя, массив в котором есть
                                    // id - номер элемента (комментария, поста, смотря о чём речь в активности)
                                    // from_id - UID пользователя
                                    // date - дата в формате timestamp
                                    // text - текст комментария, сообщения и т.д.
    $out    = 0;                    // Номер выхода по умолчанию. Если дальнейший код не назначит другой выход - значит что-то не так
    $options = $_REQUEST['options'];
    $b0_request = $options['b0_request'];

    $request_keys = array_keys($b0_request);

    foreach ($request_keys as $value) {
    	$array[$value] = $b0_request[$value];
    }

    $array['first_name'] = $array['name'];
    $array['phone'] = $array['phone_number'];
    $array['vk_id'] = $array['vk_user'];
    $array['vk_uid'] = $array['vk_user'];
    $array['b0_uid'] = $array['vk_user'];

    foreach ($b0_request['lines'] as $value) {
    	$value_keys = array_keys($value);
    	foreach ($value_keys as $v) {
	    	$array[$v] = $value[$v];
	    }
	    break;
    }

    
    $out = 1;
    
    $responce['out'] = $out;

    $responce['value']['b0'] = $b0_request;

    foreach ($array as $key => $value) {
    	$responce['value'][$key] = $value;
    }

} elseif($act == 'man') {
    $responce = [
    	'html' => 
    	"## Инструкция по применению
    	* * *
    		**{b.{bid}.value.id_account}** - Код счета  
    		**{b.{bid}.value.vk_id}** - VK ID пользователя
    		**{b.{bid}.value.id_account}** - Код счета  
    		**{b.{bid}.value.id_account}** - Код счета  
    		**{b.{bid}.value.id_account}** - Код счета  
    		**{b.{bid}.value.id_account}** - Код счета  
    		<p>{b.{bid}.value.id_account}** - Код счета</p>  
    		**{b.{bid}.value.id_account}** - Код счета  
    		**{b.{bid}.value.id_account}** - Код счета  
    	"
    ];

}

// Отдать JSON, не кодируя кириллические символы в кракозябры
echo json_encode($responce, JSON_UNESCAPED_UNICODE);

/* 
Значения в массиве b0_request
id_account							Код счета
account_number						Номер счета
account_sum							Общая сумма счета
id_account_status					код статуса счета (1 - создан, 2 - отказ, 3 - в обработке, 4 - ошибка, 5 - оплачен)
close_account						признак закрытия счета
id_partner							Код партнера
id_payment_system					Код платежной системы
close_date							дата закрытия счета
date_of_order						дата создания счета
goods_return						признак возврата товара
date_of_payment						Дата оплаты счета
goods_return_date					Дата возврата товара
last_name							фамилия
name								имя
middle_name							отчество
email								ящик
phone_number						телефон
skype								скайп
account_comment						комментарий к счету
id_organization						код организации
id_delivery_region					код региона доставки
area								область
city								город
delivery_address					адрес доставки
zip_code							почтовый индекс
barcode								почтовый идентификатор
id_delivery_region_method			код способа доставки для региона
id_advertising_channel_page			API код канала рекламы
advertising_channel_keyword			ключевое слово
advertising_channel_location		место размещения
advertising_channel_type_traffic	тип трафика
deleted								счет удален
vk_user								идентификатор вКонтакте
fb_user								идентификатор Facebook
id_account_line						код строки счета
id_goods							код товара
goods								название товара на момент заказа
price								цена
quantity							количество
sum_price							сумма строки счета
vendor_code							код поставщика
add_fields							дополнительные поля к заказу
name								название дополнительного поля к товару
value								комментарий, оставленный покупателем в дополнительном поле
link_for_pay						ссылка на выбор способа оплаты
datetime_notify						дата отправки уведомления

Пример ответа:

Array
  (
  [id_account] => 370211
  [account_number] => 289
  [account_sum] => 500
  [id_account_status] => 1
  [close_account] => 0
  [id_partner] => 1
  [id_payment_system] => 0
  [close_date] => 0000-00-00 00:00:00
  [date_of_order] => 2019-02-21 15:29:46
  [goods_return] => 0
  [date_of_payment] => 0000-00-00 00:00:00
  [goods_return_date] => 0000-00-00 00:00:00
  [last_name] => 
  [name] => 
  [middle_name] => 
  [email] => a@gmail.com
  [phone_number] => 
  [skype] => 
  [account_comment] => 
  [id_organization] => 0
  [id_delivery_region] => 0
  [area] => 
  [city] => 
  [delivery_address] => 
  [zip_code] => 
  [barcode] => 
  [id_delivery_region_method] => 0
  [id_advertising_channel_page] => 0
  [advertising_channel_keyword] => 
  [advertising_channel_location] => 
  [advertising_channel_type_traffic] => 
  [id_contact] => 3
  [deleted] => 0
  [vk_user] => 
  [fb_user] => 
  [lines] => Array
      (
          [321] => Array
              (
                  [id_account_line] => 321
                  [id_goods] => 1
                  [goods] => Вязаная игрушка "Котейка"
                  [price] => 500.00
                  [quantity] => 1
                  [sum_price] => 500.00
                  [vendor_code] => 
                  [add_fields] => Array
                      (
                          [0] => Array
                              (
                                  [name] => пожелание
                                  [value] => хочу желтую котейку
                              )
                      )
              )
      )
  [link_for_pay] => https://mila.autoweboffice.ru/?r=ordering/cart/s2&id=370211&vc=1533628675&lg=ru
  [datetime_notify] => 2019-02-21 15:29:46
  )

*/