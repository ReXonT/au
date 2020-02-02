<?php
/*
 * Получение ссылки на оплату счета
 */

include __DIR__ . '/../AwoApi.php';

$api = new AwoApi([
    'apiKeyRead' => '85437a075d1fa6e75407df538864d4ee',
    'apiKeyWrite' => 'ed67019110a8f1b839a83d84770931d6',
    'subdomain' => 'test1305',
]);

// создаем контакт (если контакт с таким email существует, информация о нем будет обновлена)
$contact = $api->contact()->get(4);

$idContact = $contact->id_contact;

// создаем счет для этого контакта
$invoice = $api->invoice()->create([
    [
        'id_contact' => $idContact, // ID контакта
        'last_name' => $contact->last_name, // фамилия на момент покупки
        'name' => $contact->name, // имя на момент покупки
        'middle_name' => $contact->middle_name, // отчество на момент покупки
        'email' => $contact->email, // email на момент покупки

        // полный список доступных полей смотрите в документации
    ]
]);

if (!is_object($invoice)) {
    echo 'Ошибка при создании счета: ' . $invoice;
}

$idInvoice = $invoice->id_account;

// создаем строки счета
$invoiceLine = $api->invoiceLine()->create([
    [
        'goods' => 'Товар Тест', // Название товара на момент покупки
        'id_goods' => 1, // ID товара в системе
        'price_full' => 1, // цена без скидки
        'price' => 1, // цена со скидкой
        'quantity' => 1, // количество
        'sum_price' => 1, // сумма (количество * цена со скидкой)
        'id_account' => $idInvoice, // ID счета, к которому относится строка

        // полный список доступных полей смотрите в документации
    ],
]);

// ссылка на оплату находится в свойстве 'link_for_pay'
$paymentLink = $invoice->link_for_pay;

if (is_object($result)) {
    // счет найден.

    // ссылка на оплату находится в свойстве 'link_for_pay'
    $paymentLink = $result->link_for_pay;
}

echo $paymentLink;