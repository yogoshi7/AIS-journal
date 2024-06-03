<?php
//require_once('../app.php');
header("Content-Type: application/json");



function get_filter_values($pdo, $table)
{
    $filterData = assemble_locations_sendancedate_types($pdo, $table);

    //foreach ($filterData['sendance_dates'] as $key => $date)
    //{
    //    //raw => необработанная дата для фильтрации | formatted => обработанная дата для вывода пользователю
    //    $filterData['sendance_dates'][$key] = ['raw' => $date, 'formatted' => format_date_to_dmy($date)];
    //}
    
    echo json_encode($filterData);
}