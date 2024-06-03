<?php

header("Content-Type: application/json");

function table_handle_get($pdo, $table)
{
    $filter = (!empty($_GET)) ? set_filter() : '';
    $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
            
    // $requests = format_datetime_in_array(find_all_requests($pdo, $page, $filter));
    $requests = find_all_requests($pdo, $table, $page, $filter);
    foreach ($requests as $index => $request)
        foreach ($request as $key => $value)
        {
            if (strpos($key, '_date') && !is_null($value)) $requests[$index][$key] = format_date_to_dmy($value);
            elseif (strpos($key, '_time') && !is_null($value)) $requests[$index][$key] = format_time_to_hm($value);
            else continue;
        }
    
    echo json_encode($requests);
}