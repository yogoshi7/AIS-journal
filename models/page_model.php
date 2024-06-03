<?php
//require_once('../app.php');
header("Content-Type: application/json");

function get_pages_count($pdo, $table)
{
    $filter = (!empty($_GET)) ? set_filter() : '';
    $maxPage = calc_max_page($pdo, $table, $filter);
    
    echo json_encode($maxPage);
}