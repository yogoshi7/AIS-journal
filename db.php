<?php

/**
 * get_journals
 *
 * @param  PDO $pdo
 * @return array
 */
function get_journals_name_table(PDO $pdo)
{
    return $pdo->query('SELECT id, name
    FROM journals')->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * get_journal_name_description
 *
 * @param  PDO $pdo
 * @param  string $table_name
 * @return array
 */
function get_journal_name_description(PDO $pdo, string $id)
{
    // return $pdo->query('SELECT *
    // FROM journals_list
    // WHERE table_name = '.$table_name)->fetch(PDO::FETCH_ASSOC);

    $pdoStatement = $pdo->prepare('SELECT name, description FROM journals WHERE id = :id');
    $pdoStatement->bindValue('id', $id);
    $result = $pdoStatement->execute();
    if (!$result) return $pdoStatement->errorInfo();
    return $pdoStatement->fetch(PDO::FETCH_ASSOC);
}

/**
 * assemble_locations_senders_receivers_types
 *
 * @param PDO $pdo
 * @return array
 */
function assemble_locations_senders_receivers_types(PDO $pdo, string $journal)
{
    $locations = find_all_locations($pdo, $journal);
    $senders = find_all_senders($pdo, $journal);
    $receivers = find_all_receivers($pdo, $journal);
    $types = find_all_types($pdo, $journal);
    $executors = find_all_executors($pdo, $journal);

    return ['locations' => $locations, 'senders' => $senders, 'receivers' => $receivers, 'types' => $types, 'executors' => $executors];
}

/**
 * assemble_locations_sendancedate_types
 *
 * @param PDO $pdo
 * @return array
 */
function assemble_locations_sendancedate_types(PDO $pdo, string $journal)
{
    $locations = find_all_locations($pdo, $journal);
    //$sendanceDates = find_all_sendance_dates($pdo);
    $sendanceDates = find_minmax_sendance_date($pdo, $journal);
    $types = find_all_types($pdo, $journal);
    
    return ['locations' => $locations, 'sendance_dates' => $sendanceDates[0], 'types' => $types];
}

/**
 * find_all_locations
 * 
 * @param PDO $pdo
 * @return array|false
 */
function find_all_locations(PDO $pdo, string $journal)
{
    return $pdo->query('select distinct location from requests where location is not null and journal_id = '.$journal.';')->fetchAll(PDO::FETCH_COLUMN);
}

/**
 * find_all_senders
 *
 * @param PDO $pdo
 * @return array|false
 */
function find_all_senders(PDO $pdo, string $journal)
{
    return $pdo->query('select distinct sender from requests where sender is not null and journal_id = '.$journal.';')->fetchAll(PDO::FETCH_COLUMN);
}

/**
 * find_all_receivers
 *
 * @param PDO $pdo
 * @return array|false
 */
function find_all_receivers(PDO $pdo, string $journal)
{
    return $pdo->query('select distinct receiver from requests where receiver is not null and journal_id = '.$journal.';')->fetchAll(PDO::FETCH_COLUMN);
}

/**
 * find_all_contents
 *
 * @param PDO $pdo
 * @return array|false
 */
function find_all_contents(PDO $pdo, string $journal)
{
    return $pdo->query('select distinct content from requests where content is not null and journal_id = '.$journal.';')->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * find_all_sendance_dates
 *
 * @param PDO $pdo
 * @return array|false
 */
function find_all_sendance_dates(PDO $pdo, string $journal)
{
    return $pdo->query('SELECT DISTINCT sendance_date FROM requests where sendance_date is not null and journal_id = '.$journal.';')->fetchAll(PDO::FETCH_COLUMN);
}

/**
 * find_all_executors
 *
 * @param  PDO $pdo
 * @param  string $journal
 * @return array|false
 */
function find_all_executors(PDO $pdo, string $journal)
{
    return $pdo->query('SELECT DISTINCT executor FROM requests WHERE executor IS NOT NULL AND journal_id = '.$journal.';')->fetchAll(PDO::FETCH_COLUMN);
}

/**
 * find_all_types
 *
 * @param PDO $pdo
 * @return array|false
 */
function find_all_types(PDO $pdo, string $journal)
{
    $types_table = substr($journal, 0, strpos($journal, '_journal')).'_types';
    return $pdo->query('SELECT * FROM request_types;')->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * find_minmax_sendance_date
 *
 * @param PDO $pdo
 * @return array|false
 */
function find_minmax_sendance_date(PDO $pdo, string $journal)
{
    return $pdo->query('SELECT MAX(sendance_date) max_date, MIN(sendance_date) min_date FROM requests WHERE journal_id = '.$journal.'')->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * get_host_name
 *
 * @param PDO $pdo
 * @param string $ip
 * @param string $host
 * @return string|false 
 */
function get_ip_from_db(PDO $pdo, string $ip, string $hostName)
{
    return $pdo->query("SELECT ip
                FROM hosts
                WHERE ip = '$ip' AND host = '$hostName';")
        ->fetch(PDO::FETCH_COLUMN);
}

/**
 *
 * delete_request
 *
 * @param PDO $pdo
 * @param int $requestId
 * @return bool
 */
function delete_request(PDO $pdo, string $journal, int $requestId)
{
    $deleteQuery = 'DELETE FROM requests WHERE id = :requestId and journal_id = '.$journal.'';
    
    $pdoStatement = $pdo->prepare($deleteQuery);
    return $pdoStatement->execute(['requestId' => $requestId]);
}

/**
 * find_request
 *
 * @param PDO $pdo
 * @param int $requestId
 * @return array|???
 */
function find_request(PDO $pdo, string $journal, int $requestId)
{
    $selectQuery = 'SELECT location, type_id, tp.type, sendance_date, sendance_time, receive_date, receive_time, sender, receiver, content, completion_status, completion_date, executor, completion_time
    FROM requests req
    JOIN request_types tp ON tp.id = req.type_id
    WHERE req.id = :requestId and journal_id = '.$journal.'';

    $pdoStatement = $pdo->prepare($selectQuery);
    $result = $pdoStatement->execute(['requestId' => $requestId]);

    if ($result == false) {
        $error = $pdoStatement->errorInfo();
        
        print '<pre>';var_dump($error);print '</pre>';die;
    }

    return $pdoStatement->fetch(PDO::FETCH_ASSOC);
}

/**
 * find_all_requests
 *
 * @param PDO $pdo
 * @param int $page
 * @param string $filter
 * @return array
 */
function find_all_requests(PDO $pdo, string $journal, int $page, string $filter = '')
{
    $pageLimiter = set_page_limiter($page);

    $selectQuery = 'SELECT req.id,
        location,
        sendance_date,
        receive_date,
        sender,
        receiver,
        content,
        completion_status,
        completion_date,
        sendance_time,
        receive_time,
        completion_time,
        tp.type,
        executor
        FROM requests req
        JOIN request_types tp ON tp.id = req.type_id
        WHERE journal_id = '.$journal.$filter.'
        ORDER BY sendance_date DESC, sendance_time DESC '.$pageLimiter.';';
            
    $pdoStatement = $pdo->prepare($selectQuery);
    
    $updatedPdoStatement = bind_filter_data($pdoStatement);
    
    $result = $updatedPdoStatement->execute();
    
    if(!$result) {
        $error = $updatedPdoStatement->errorInfo();
        
        print '<pre>';var_dump($error);print '</pre>';die;
    }

    return $updatedPdoStatement->fetchAll(PDO::FETCH_ASSOC);
    
    
    
}

/**
 * update_journal_requests
 *
 * @param PDO $pdo
 * @param array $updateValues
 * @return bool
 */
function update_journal_requests(PDO $pdo, string $journal, array $requestValues)
{
    $updateQuery = 'UPDATE requests
        SET location = :location,
        sendance_date = :sendance_date,
        sendance_time = :sendance_time,
        receive_date = :receive_date,
        receive_time = :receive_time,
        content = :content,
        type_id = :type_id,
        receiver = :receiver,
        sender = :sender,
        completion_status = :completion_status,
        completion_date = :completion_date,
        executor = :executor,
        completion_time = :completion_time
        WHERE id = :id and journal_id = '.$journal.'';
    
    $pdoStatement = $pdo->prepare($updateQuery);
    
    return $pdoStatement->execute([
        'location' => $requestValues['location'],
        'sendance_date' => $requestValues['sendance_date'],
        'sendance_time' => $requestValues['sendance_time'],
        'receive_date' => $requestValues['receive_date'],
        'receive_time' => $requestValues['receive_time'],
        'content' => $requestValues['content'],
        'type_id' => $requestValues['type_id'],
        'receiver' => $requestValues['receiver'],
        'sender' => $requestValues['sender'],
        'completion_status' => $requestValues['completion_status'],
        'completion_date' => $requestValues['completion_date'],
        'completion_time' => $requestValues['completion_time'],
        'executor' => $requestValues['executor'],
        'id' => $requestValues['id']
    ]);
}

/**
 * get_request_count
 *
 * @param PDO $pdo
 * @param string $filter
 * @return string
 */
function get_request_count(PDO $pdo, string $journal, string $filter = '')
{
    $query = "SELECT COUNT(id) FROM requests WHERE journal_id = $journal $filter;";
    $pdoStatement = $pdo->prepare($query);
    $updatedPdoStatement = bind_filter_data($pdoStatement);
    $result = $updatedPdoStatement->execute();
    if (!$result)
    {
        return $updatedPdoStatement->errorInfo();
    }
    return $updatedPdoStatement->fetch(PDO::FETCH_COLUMN);
}

/**
 *
 * insert_new_request
 *
 * @param PDO $pdo
 * @param array $requestValues
 * @return bool
 */
function insert_new_request(PDO $pdo, string $journal, array $requestValues)
{
    $query = 'INSERT INTO requests
        (location, content, type_id, sendance_date, sendance_time, sender, receiver, receive_time, receive_date, executor, journal_id)
        VALUES (:location, :content, :type_id, :sendance_date, :sendance_time, :sender, :receiver, :receive_time, :receive_date, :executor, '.$journal.')';
    
    $pdoStatement = $pdo->prepare($query);
    $updatedPdoStatement = bind_values($pdoStatement, $requestValues);
    $result = $updatedPdoStatement->execute();
    if(!$result){
        $error = $updatedPdoStatement->errorInfo();
        print '<pre>';var_dump($error);print '</pre>';die;
    }
    
    return $result;
}

function bind_requestcontent_location_senddate(PDOStatement $pdoStatement)
{    
    $isParametersSet = check_if_get_parameters_set();
    foreach($isParametersSet as $parameter => $isSet)
        if ($isSet) $pdoStatement->bindParam($parameter, $_GET[$parameter]);
    
    return $pdoStatement;
}


function bind_filter_data(PDOStatement $pdoStatement)
{    
    foreach($_GET as $parameter => $value)
        if ($value != '' && $parameter != 'page')
            $pdoStatement->bindValue($parameter, $value);
    
    return $pdoStatement;
}

/**
 *
 * bind_values
 * 
 * @param PDOStatement $pdoStatement
 * @param array $valuesToInsert
 * @return PDOStatement
 */
function bind_values(PDOStatement $pdoStatement, array $valuesToInsert)
{
    foreach($valuesToInsert as $valueColumn => $value)
        $pdoStatement->bindValue($valueColumn, $value);
    
    return $pdoStatement;
}