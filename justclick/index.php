<?php

ini_set('display_errors', 1);

require_once 'src/justclick.php';
require_once 'src/models/order.php';

$act = $_REQUEST['act'];

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Режим OPTIONS - в котором ВРМ создаёт в схеме блок управления  *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

if($act == 'options') {

    include_once 'src/vrm_fields.php';

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Режим RUN - в котором ВРМ получает, обрабатывает и возвращает  *
 * полученные от схемы данные                                   *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
} elseif($act == 'run') {              // Схема прислала данные, обрабатываем

    $target = $_REQUEST['target'];  // Пользователь, от имени которого выполняется блок
    $ums = $_REQUEST['ums'];     // Данные об активности пользователя, массив в котором есть
                                    // id - номер элемента (комментария, поста, смотря о чём речь в активности)
                                    // from_id - UID пользователя
                                    // date - дата в формате timestamp
                                    // text - текст комментария, сообщения и т.д.
    $out = 0;  // Номер выхода по умолчанию. Если дальнейший код не назначит другой выход - значит что-то не так
    $options = $_REQUEST['options'];
    $ps = $_REQUEST['paysys']['ps'];
    $pso = $ps['options']; // Здесь параметры платежной системы

    $jc = new JustClick($pso['account'], $pso['secret']);

    switch ($options['option'])
    {
        // Работа со счетами
        case 1:
            $order = new Order();

            switch ($options['bill_option'])
            {
                // Создать счет
                case 1:
                    // Формируем массив купленных товаров
                    $order->addProduct($options['good_name'], $options['good_sum']);

                    // Информация о клиенте
                    $order->setNameFirst($options['bill_first_name']);
                    $order->setNameLast($options['bill_last_name']);
                    $order->setOtchestvo($options['bill_otchestvo']);
                    $order->setEmail($options['bill_email']);
                    $order->setPhoneNumber($options['bill_phone']);

                    // Скидочный купон
                    $order->setCoupon($options['bill_coupon']);

                    // Текстовые заметки
                    $order->setComment($options['bill_comment']);
                    $order->setTag($options['bill_tag']);

                    // Время
                    $order->setTimerKill(true); // есть ли ограничение на время оплаты заказа, где:
                    // false или 0 - счет автоматически не отменяется;
                    // true или 1 - автоматическая отмена счета согласно настройкам в продукте;
                    // при передаче времени в unixtime - автоотмена счета выставляется по этому времени.
                    $order->setDateCreated(time());

                    // Доп. информация
                    $order->setDomainName($options['bill_domain']);

                    // UTM метки
                    $order->setUtm([
                        'utm_source' => $options['utm_source'],
                        'utm_medium' => $options['utm_medium'],
                        'utm_campaign' => $options['utm_campaign'],
                        'utm_content' => $options['utm_content'],
                        'utm_term' => $options['utm_term'],
                    ]);

                    // Партнерские метки
                    $order->setUtmAff([
                        'aff_source' => $options['aff_source'],
                        'aff_medium' => $options['aff_medium'],
                        'aff_campaign' => $options['aff_campaign'],
                        'aff_content' => $options['aff_content'],
                        'aff_term' => $options['aff_term'],
                    ]);

                    $response = $jc->createOrder($order);
                    break;

                // Изменить статус счета
                case 2:
                    $order->setId($options['bill_id']);
                    $order->setStatus($options['status']);
                    $order->setDate(time());
                    if($options['status'] == 'sent')
                    {
                        $order->setRPO($options['rpo']);
                    }

                    $response = $jc->updateOrderStatus($order);
                    break;

                // Удалить счет
                case 3:
                    $order->setId($options['bill_id']);

                    $response = $jc->deleteOrder($order);
                    break;

                // Получить счета клиента
                case 4:
                    $order->setEmailForBills($options['bill_email']); // чтоб этот ваш jc...
                    if($options['pay_status'])
                        $order->setPayStatus($options['pay_status']);

                    $response = $jc->getBills($order);
                    break;

                // Получить информацию по счету
                case 5:
                    $order->setId($options['bill_id']);
                    $order->setGoodInfo($options['good_info']);

                    $response = $jc->getOrderDetails($order);
                    break;
                // Получить все счета за указанную дату
                case 6:
                    $order->setBeginDate( strtotime($options['begin_date']) );
                    $order->setEndDate( strtotime($options['end_date']) );
                    $order->setPaid($options['paid']);
                    $order->setGoods($options['good_ids']);

                    $response = $jc->getOrdersWithGoods($order);
                    break;
            }
            $result = $jc->errorCodeToRussian(
                $response['error_code']
            );
            break;

        // Работа с контактами
        case 2:
            // контакты
            break;

        // Работа с продуктами
        case 3:
            // продукты
            break;
    }

    logToFile($result);

    $out = 1;

    $responce = [
        'out' => $out,         // Обязательно должен быть номер выхода out, отличный от нуля!
        
        'value' => [           // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через $bN_value.ваши_ключи_массива
            'result' => $result,     // где N - порядковый номер блока в схеме
            'response' => $response
        ]
    ];

} elseif($act == '') {
    // Действие не задано, и что же нам сделать? Станцевать вальс, попрыгать, пойти в гости к Кролику?

}

// Отдать JSON, не кодируя кириллические символы в кракозябры
echo json_encode($responce, JSON_UNESCAPED_UNICODE);


function logToFile($data)
{
    $json = json_encode($data);
    $dir_home = __DIR__;
    $res = file($dir_home . '/log.txt');
    $res[] = $json . " \n";
    $str = implode ("", $res);
    file_put_contents($dir_home . '/log.txt', $str);
}