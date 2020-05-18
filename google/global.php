<?php

/**
 * Получаем массив с информацией о таблице
 * @param $service
 * @param $spreadsheet_id
 * @param $work_sheet_title
 * @param $range
 * @return array
 */
function getTableInfo($service, $spreadsheet_id, $work_sheet_title)
{
    // Получаем инфо по таблице
    $response = $service->spreadsheets->get($spreadsheet_id);

    foreach ($response->getSheets() as $sheet)
    {
        $sheet_properties = $sheet->getProperties(); // Свойства листа
        if ($sheet_properties->title == $work_sheet_title) // Название листа
        {
            $grid_properties = $sheet_properties->getGridProperties();
            $sheet_column_count = $grid_properties->columnCount; // Количество колонок
            $sheet_row_count = $grid_properties->rowCount; // Количество строк
            $work_sheet_id = $sheet_properties->sheetId; // id рабочего листа

            break;
        }
    }

    return [
        'work_spreadsheet_name' => $response->getProperties()->title, // Название таблицы
        'grid_properties' => $sheet_properties->getGridProperties(),
        'sheet_column_count' => $grid_properties->columnCount, // Количество колонок
        'sheet_row_count' => $grid_properties->rowCount, // Количество строк
        'work_sheet_id' => $sheet_properties->sheetId, // id рабочего листа
    ];
}

/**
 * Найти и получить строку по значению из таблицы
 * @param $table_info
 * @param $value
 * @return bool|mixed
 */
function getRowFromTable($table_info, $value)
{
    foreach ($table_info as $row_index => $row)
    {
        $return_row = $row; // Задаем полную строку на возращение
        foreach ($row as $col => $col_value)
        {
            if ($col_value == $value) // Если нашли внутри строки нужное сообщение
            {
                return $return_row;
            }
        }
    }

    return false;
}

/**
 * Найти и получить координаты найденных значений
 * @param $table_info
 * @param $find_row
 * @param $find_col
 * @return array
 */
function findCoordsInTable($table_info, $find_row, $find_col)
{
    $found_col = '';
    $found_row = '';

    foreach ($table_info as $row_index => $row)
    {
        foreach ($row as $col => $col_value)
        {
            if ($find_col != '' && $col_value == $find_col)
            {
                $found_col = $col + 1; // найденный индекс колонки
                if($found_row)
                    break;
            }
            if ($find_row != '' && $col_value == $find_row)
            {
                $found_row = $row_index + 1; // найденный индекс строки
                if($found_col)
                    break;
            }
        }

        if($found_row && $found_col)
            break;
    }

    return [
        'found_row' => $found_row,
        'found_col' => $found_col
    ];
}

/**
 * Найти координаты строки в таблице
 * @param $table_info
 * @param $value
 * @return bool|int|string
 */
function findRowCoordsInTable($table_info, $value)
{
    foreach ($table_info as $row_index => $row)
    {
        foreach ($row as $col => $col_value)
        {
            if ($col_value == $value)
                return $row_index; // найденный индекс строки
        }
    }

    return false;
}

/**
 * Найти координаты столбца в таблице
 * @param $table_info
 * @param $value
 * @return bool|int|string
 */
function findColCoordsInTable($table_info, $value)
{
    foreach ($table_info as $row_index => $row)
    {
        foreach ($row as $col => $col_value)
        {
            if ($col_value == $value)
                return $col; // найденный индекс колонки
        }
    }

    return false;
}