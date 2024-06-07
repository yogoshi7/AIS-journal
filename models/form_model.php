<?php

header("Content-Type: application/json");
const TEXT_PATTERN = "/^[а-яА-Я.,0-9;!?\- ]+$|^$|^\s+$/u";

/*
 *
 * form_handle_get
 *
 * обрабатывает GET запрос; выводит данные для дозаполнения формы
 */
function form_handle_get($pdo, $table)
{
    if (isset($_GET['id']))
    {
        $request = find_request($pdo, $table, $_GET['id']);
        
        foreach ($request as $key => $value)
            if (strpos($key, '_time') && !is_null($value))
                $request[$key] = format_time_to_hm($value);
        
        echo json_encode($request);
    }
    else
    {
        $currentDate = date('Y-m-d');
        $currentTime = get_current_time();
                
        $fillValues = assemble_locations_senders_receivers_types($pdo, $table);
        $fillValues['currentDate'] = date('Y-m-d');
        $fillValues['currentTime'] = get_current_time();
        
        echo json_encode($fillValues);
    }
}

/*
 *
 * prepare_insert_data
 *
 * достает данные из POST; обрабатывает их и готовит к отправке в БД
 * @return array
 */
function prepare_insert_data()
{
    //получает данные из POST
    $requestValues = json_decode(file_get_contents('php://input'));
    $formattedRequestValues = [];
    
    //обработка данных для добавления в БД
    foreach ($requestValues as $key => $value)
    {
        if (($key == 'sender' || $key == 'receiver' || $key == 'location' || $key == 'content' || $key == 'executor') && !preg_match(TEXT_PATTERN, $value))
            throw new Exception("Введено недопустимое значение; допустимые символы: кириллица, цифры, знаки препинания");
        
        if (strpos($key, '-')) 
            $key = str_replace('-', '_', $key);
        
        $formattedRequestValues[$key] = empty($value) ? null :
            ($key == 'sendance-time' || $key == 'receive-time' || $key == 'completion-time' ? format_time_to_hms($value) :
                mb_ucfirst(trim($value)));    
    }
    return $formattedRequestValues;
}

/*
 *
 * form_handle_post
 * отправляет новую заявку
 *
 */
function form_handle_post($pdo, $table)
{
    try
    {
        $formattedRequestValues = prepare_insert_data();
    }
    catch (Exception $e)
    {
        print(json_encode($e->getMessage()));
        die;
    }
    $insertResult = insert_new_request($pdo, $table, $formattedRequestValues);
    echo json_encode($insertResult);
}

/*
 *
 * form_handle_put
 * обновляет существующую
 *
 */
function form_handle_put($pdo, $table)
{
    try
    {
        $formattedRequestValues = prepare_insert_data();
    }
    catch (Exception $e)
    {
        print(json_encode($e->getMessage()));
        die;
    }
    $insertResult = update_journal_requests($pdo, $table, $formattedRequestValues);
    echo json_encode($insertResult);
}

/*
 *
 * form_handle_delete
 * удаляет запись
 *
 */
function form_handle_delete($pdo, $table)
{
    $requestValues = json_decode(file_get_contents('php://input'));
    
    try
    {
        $result = delete_request($pdo, $table, $requestValues);
        echo json_encode($result);
    }
    catch (Exception $e)
    {
        echo json_encode($e->getMessage());
    }
}