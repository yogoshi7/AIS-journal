<?php

//header('Location: view.php');

include_once('app.php');

$requestURIToken = strtok($_SERVER['REQUEST_URI'], '?');
$requestMethod = $_SERVER['REQUEST_METHOD'];
//потом переименовать
$requestPath = substr($requestURIToken, 1, strpos($requestURIToken, '/', 1)-1); // journals|api

if ($requestURIToken == '/journals/')
{
    $journals = get_journals_name_table($pdo);
    include_once ('view/journals.php');
}
//API handler
elseif (strpos($requestURIToken, '/api/'))
{
    list($table, $action) = explode('/', substr($requestURIToken, strpos($requestURIToken, '/api/')+5));
    if ($action == 'requests')
    {
        require_once (__DIR__.'/models/table_model.php');
        table_handle_get($pdo, $table);
    }
    elseif ($action == 'request')
    {
        require_once (__DIR__.'/models/form_model.php');
        switch ($requestMethod)
        {
            case 'GET':
                form_handle_get($pdo, $table);
                break;
            case 'POST':
                form_handle_post($pdo, $table);
                break;
            case 'PUT':
                form_handle_put($pdo, $table);
                break;
            case 'DELETE':
                form_handle_delete($pdo, $table);
                break;
        }
    }
    // elseif ($action == 'filter')
    // {
    //     require_once (__DIR__.'/models/filter_model.php');
    //     get_filter_values($pdo, $table);
    // }
    elseif ($action == 'pages')
    {
        require_once (__DIR__.'/models/page_model.php');
        get_pages_count($pdo, $table);
    }
}
else
{
    list($table, $entity, $action) = explode('/', substr($requestURIToken, strpos($requestURIToken, '/', 1)+1));
    $table_info = get_journal_name_description($pdo, $table);
    if ($action == '' && $entity == '')
    {
        header('Location: /journals/'.$table.'/requests');
    }
    elseif ($entity == 'requests')
    {
        require_once (__DIR__.'/models/filter_model.php');
        $filterData = get_filter_values($pdo, $table);

        if ($action == 'edit')
        {
            $clientIp = $_SERVER['REMOTE_ADDR'];
            $clientHostName = exec("dig -x $clientIP +short");
                
            $existingClientIp = get_ip_from_db($pdo, $clientIp, $clientHostName);
                    
            //проверяет есть ли пользователь с таким ip в БД
            if ($existingClientIp == $clientIp || (isset($_GET['bd']) && $_GET['bd'] == '73'))
            {
                $pageTitle = 'Список заявок';
                $pageContent = 'table/table.php';
                include_once('view/template.php');
            }
            else
                include_once('view/access_denied.php');

        }
        else
        {
            $pageTitle = 'Список заявок';
            $pageContent = 'table/table.php';
            include_once('view/template.php');
            
        }
    }
    elseif ($entity == 'request')
    {
        if (isset($_GET['put']) && $_GET['put'] == 'true')
        {
            for ($i = 1; $i < 21; $i++)
            {
                $randDate = '2024-'.rand(1,4).'-'.rand(1,28);
                $randTime = rand(0,24).':'.rand(0,59);

                $randDate = new DateTime($randDate);
                $randDate = $randDate->format('Y-m-d');

                $request = [
                    'location' => null,
                    'type_id' => rand(1, 3),
                    'sendance_date' => $randDate,
                    'sendance_time' => $randTime,
                    'receive_date' => $randDate,
                    'receive_time' => $randTime,
                    'sender' => null,
                    'receiver' => null,
                    'content' => $i
                ];
                insert_new_request($pdo, $table, $request);
            }
        }
        if ($action == 'new' || $action == 'edit')
        {
            $pageTitle = 'Форма заявки';
            $pageContent = 'form/form.php';
            include_once('view/template.php');
        }
    }
}