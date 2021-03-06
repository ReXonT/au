<?php

ini_set('display_errors', 1);

require_once 'src/functions.php';


$act = $_REQUEST['act'];

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Режим OPTIONS - в котором ВРМ создаёт в схеме блок управления  *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

if ($act == 'options')
{
    $responce = [
        'title' => 'ВРМ JustClick Обработчик ответа',      // Это заголовок блока, который будет виден на схеме
        'vars' => [                     // переменные, которые можно будет настроить в блоке
            'input_data' => [
                'title' => 'Входные данные',
                'desc' => 'Не меняйте, если всё работает',
                'default' => '$b0_request',
                'more' => 1
            ],
        ],
        'out' => [                      // Это блоки выходов, мы задаём им номера и подписи (будут видны на схеме)
            1 => [                      // Номер 0 означает красный выход блока ВРМ, зарезервированный для случаев сбоя
                'title' => 'Оформлен',    // название выхода 1
            ],
            2 => [                      // Номер 0 означает красный выход блока ВРМ, зарезервированный для случаев сбоя
                'title' => 'Предоплата',    // название выхода 2
            ],
            3 => [                      // Номер 0 означает красный выход блока ВРМ, зарезервированный для случаев сбоя
                'title' => 'Оплата',    // название выхода 3
            ],
            4 => [                      // Номер 0 означает красный выход блока ВРМ, зарезервированный для случаев сбоя
                'title' => 'Отмена',    // название выхода 4
            ],
            5 => [                      // Номер 0 означает красный выход блока ВРМ, зарезервированный для случаев сбоя
                'title' => 'Возврат',    // название выхода 5
            ],
            6 => [
                'title' => 'Другое'
            ]
        ]
    ];
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
    $out = 0;                                   // Номер выхода по умолчанию
    $options = $_REQUEST['options'];

    switch ($options['input_data']['a'])
    {
        case 'make':
            $out = 1;
            break;
        case 'prepaid':
            $out = 2;
            break;
        case 'paid':
            $out = 3;
            break;
        case 'cancel':
            $out = 4;
            break;
        case 'moneyback':
            $out = 5;
            break;
        default:
            $out = 6;
            break;
    }

    $responce['out'] = $out;

    $result = transformDataToText($options['input_data']);
    $responce['value']['result'] = $result;

    foreach ($options['input_data'] as $key => $value)
    {
        if($key == 'comment')
        {
            $responce['value']['vk_uid'] = $value;
        }
        $responce['value'][$key] = $value;
    }

}
elseif ($act == 'man')
{
    $responce = [
        'html' => '##Описание
        Данная ВРМ работает с аккаунтом JustClick, который Вы указали в интеграции. Подробная инструкция тут - https://vk.com/@rexont-justclick-and-au

        ###Доступные переменные:
        
        **{b.{bid}.value.result}** текстовое отображение входящих данных
        **{b.{bid}.value.vk_uid}** vk id из поля Комментария
        **{b.{bid}.value.email}** Email
        **{b.{bid}.value.phone}** Номер телефона
        **{b.{bid}.value.bill_id}** Номер заказа
        **{b.{bid}.value.last_payment_sum}** Сумма последней оплаты
        **{b.{bid}.value.first_name}** Имя
        **{b.{bid}.value.last_name}** Фамилия
        **{b.{bid}.value.link}** Ссылка на страницу оплаты
        **{b.{bid}.value.city}** Город
        **{b.{bid}.value.address}** Адрес
        **{b.{bid}.value.items.0.title}** Название первого продукта в заказе
        **{b.{bid}.value.items.1.title}** Название второго продукта в заказе и тд
        **{b.{bid}.value.utm.*название*}** Вместо *название* можно вписать: source, medium, campaign, content, term и получить значение соответствуещей utm метки
        
        ВРМ декодирует все данные в те же ключи, которые получает из JustClick. Вы можете увидеть ключи в $b0_request, в режиме отладки и на сайте JustClick. Пример https://help.justclick.ru/archives/605
        '
    ];
}

// Отдать JSON, не кодируя кириллические символы в кракозябры
echo json_encode($responce, JSON_UNESCAPED_UNICODE);


