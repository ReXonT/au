<?php

ini_set('display_errors', 1);

require_once 'src/models/base.php';
require_once 'src/justclick.php';
require_once 'src/functions.php';
require_once 'src/models/order.php';
require_once 'src/models/good.php';

$act = $_REQUEST['act'];

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Режим OPTIONS - в котором ВРМ создаёт в схеме блок управления  *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

if ($act == 'options')
{
    include_once 'src/fields/vrm_fields_index.php';
}

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* Режим RUN - в котором ВРМ получает, обрабатывает и возвращает  *
* полученные от схемы данные                                   *
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
elseif ($act == 'run')
{
    // Схема прислала данные, обрабатываем
    $target = $_REQUEST['target'];              // Пользователь, от имени которого выполняется блок
    $ums = $_REQUEST['ums'];                    // Данные об активности пользователя, массив в котором есть
                                                // id - номер элемента (комментария, поста, смотря о чём речь в активности)
                                                // from_id - UID пользователя
                                                // date - дата в формате timestamp
                                                // text - текст комментария, сообщения и т.д.
    $out = 0;                                   // Номер выхода по умолчанию. Если дальнейший код не назначит другой выход - значит что-то не так
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
                    // Создаем товар
                    $good = new Good();

                    $good->setName($options['good_name']);
                    $good->setSum($options['good_sum']);
                    // Формируем массив купленных товаров
                    $order->addProduct($good);

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

                    // UTM метки
                    $order->setUtm([
                        'utm_source' => $options['utm_source'],
                        'utm_medium' => $options['utm_medium'],
                        'utm_campaign' => $options['utm_campaign'],
                        'utm_content' => $options['utm_content'],
                        'utm_term' => $options['utm_term']
                    ]);

                    // Партнерские метки
                    $order->setUtmAff([
                        'aff_source' => $options['aff_source'],
                        'aff_medium' => $options['aff_medium'],
                        'aff_campaign' => $options['aff_campaign'],
                        'aff_content' => $options['aff_content'],
                        'aff_term' => $options['aff_term']
                    ]);

                    // Домен для оплаты заказа. Указанный вручную
                    if ($options['domain_exec']) $order->setDomainName($options['bill_domain']);

                    // Создаем счет. Вернет bill_id при создании нового или нахождении старого с теми же параметрами
                    $response = $jc->createOrder($order);
                    $bill_id = $response['result']['bill_id'];

                    if($response['error_code'] != 0)
                        $answer .= errorCodeToRussian($response['error_code']);

                    // Чистим данные заказа
                    $order->clearData();
                    $order->setId($response['result']['bill_id']);

                    // Получаем ссылку на оплату по id заказа
                    $response = $jc->getOrderDetails($order);
                    $payment_link = $response['result']['link'];
                    break;

                // Изменить статус счета
                case 2:
                    $order->setId($options['bill_id']);
                    $order->setStatus($options['status']);
                    $order->setDate(time());

                    // Если статус "Отправлен по почте" - указываем обязательный номер почтового отделения
                    if ($options['status'] == 'sent')
                        $order->setRPO($options['rpo']);

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

                    if($options['to_text'])
                        $result = transformGetBillsToText($response['result']);
                    break;

                // Получить информацию по счету
                case 5:
                    $order->setId($options['bill_id']);
                    $order->setGoodInfo($options['good_info']);

                    $response = $jc->getOrderDetails($order);

                    if($options['to_text'])
                        $result = transformDataToText($response['result']);
                    break;

                // Получить все счета за указанную дату
                case 6:
                    $order->setBeginDate($options['begin_date']);
                    $order->setEndDate($options['end_date']);
                    $order->setPaid($options['paid']);
                    $order->setGoods($options['good_ids']);

                    $response = $jc->getOrdersWithGoods($order);

                    if($options['to_text'])
                    {
                        $result = '';
                        $number = 1;
                        foreach ($response['result'] as $value)
                        {
                            $result .= "Заказ №" . $number++  . "\n";
                            $result .= transformDataToText($value);
                            $result .= "------\n";
                        }
                    }
                    break;
            }
            break;

        // Работа с продуктами
        case 2:
            $good = new Good();

            switch ($options['product_option'])
            {
                // Удаление продукта
                case 1:
                    $good->setName($options['product_name']);

                    $response = $jc->deleteGood($good);
                    break;

                // Получить список всех продуктов
                case 2:
                    $good->clearData();

                    $response = $jc->getAllGoods($good);
                    break;
            }
            break;
    }

    // Декодируем код ответа в текст для отладки
    $answer .= ' '. errorCodeToRussian(
        $response['error_code']
    );


    $out = 1;

    $responce = [
        'out' => $out,              // Обязательно должен быть номер выхода out, отличный от нуля!
        'value' => [                // Ещё можно отдать ключ value и переменные в нём будут доступны в схеме через $bN_value.ваши_ключи_массива
            'result' => $result,    // где N - порядковый номер блока в схеме
            'response' => $response,
            'payment_link' => $payment_link,
            'bill_id' => $bill_id,
            'answer' => $answer,
            //'result_big' => $result_big
        ]
    ];

}
elseif ($act == '')
{
    // Действие не задано, и что же нам сделать? Станцевать вальс, попрыгать, пойти в гости к Кролику?

}

// Отдать JSON, не кодируя кириллические символы в кракозябры
echo json_encode($responce, JSON_UNESCAPED_UNICODE);


