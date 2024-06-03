<?php
require_once("app.php");
//header('Content-Type: application/json');
//
//$types = find_all_types($pdo);
//$types = json_encode($types);
//
//echo $types;
main($pdo);
function main(PDO $pdo)
{
    $pageTitle = "Типы заявок";
    $pageContent = "type/types_table.php";
    
    $types = find_all_types($pdo);
    include_once('view/index.php');
}