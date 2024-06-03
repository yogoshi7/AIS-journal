<?php

const RECORDS_PER_PAGE = 20;
const CONVERTED_PARAMETERS = [
    'sendance-date' => 'sendance_date',
    'request-content' => 'content',
    'request-type' => 'type_id',
    'request-location' => 'location',
    'sendance-time' => 'sendance_time',
    'receiver' => 'receiver',
    'receive-date' => 'receive_date',
    'receive-time' => 'receive_time',
    'sender' => 'sender',
    'request-id' => 'id',
    'completion-status' => 'completion_status',
    'completion-time' => 'completion_time',
    'completion-date' => 'completion_date'
];
const JOURNAL_DIRECTORY = '/practica/journal_refactoring/';

/**
 * check_if_get_parameters_set
 *
 * @return array
 */
function check_if_get_parameters_set()
{
    return ['sendance-date' => !empty($_GET['sendance-date']), 'type' => !empty($_GET['type']), 'location' => !empty($_GET['location'])];
}

/** 
 * set_filter_single_clause
 *
 * @param bool $isParameterSet
 * @param string $filter
 * 
 * @return string 
 */
function set_filter_single_clause(string $parameter, string $filter = '')
{
    // $filterLength = strlen($filter);
    // if($filterLength > 0) $filter .= " AND $parameter = :$parameter";
    // else $filter = " WHERE $parameter = :$parameter";
    return $filter." AND $parameter = :$parameter";
    
    // return $filter;
}

/**
 *
 * set_filter
 *
 * @return string
 *
 */
function set_filter()
{
    $filter = '';
    foreach ($_GET as $parameter => $value)
        if ($value != '' && $parameter != 'page')
            $filter = set_filter_single_clause($parameter, $filter);
    return $filter;
}

/**
*
* calc_max_page
*
* @param PDO $pdo
* @param string $filter
* @return int
*/
function calc_max_page(PDO $pdo, string $journal, string $filter = '')
{
    return ceil(get_request_count($pdo, $journal, $filter)/RECORDS_PER_PAGE);
}

/**
*
* set_page_limiter
*
* @param int $maxPage
* @return string
*/
function set_page_limiter(int $maxPage): string
{
    return 'LIMIT '. RECORDS_PER_PAGE*($maxPage-1) .', 20';
}

/**
 *
 * format_time_to_hms
 *
 * @param string $timeToFormat
 * @return string
 */
function format_time_to_hms(string $timeToFormat): string
{
    $newDateTime = new DateTime($timeToFormat);
    return $newDateTime->format('H:i:s');
}

/**
 *
 * format_time_to_hm
 *
 * @param string $timeToFormat
 * @return string
 */
function format_time_to_hm(string $timeToFormat): string
{
    $newDateTime = new DateTime($timeToFormat);
    return $newDateTime->format('H:i');
}

/**
 *
 * format_date_to_dmy
 *
 * @param string $dateToFormat
 * @return string
 */
function format_date_to_dmy(string $dateToFormat): string
{
    $newDateTime = new DateTime($dateToFormat);
    return $newDateTime->format('d.m.Y');
}

/**
 *
 * get_current_time
 * 
 * @return string
 */
function get_current_time(): string
{
    $currentTime = new DateTime('now', new DateTimeZone('GMT+05:00'));
    return $currentTime->format('H:i');
}

/**
 *
 * format_request_values_from_post
 * 
 * @return array
 */
function format_request_values_from_post()
{
    $formattedRequestValues = [];

    foreach($_POST as $key => $value){
        $formattedRequestValues[CONVERTED_PARAMETERS[$key]] = (empty($value) && $value !== '0') ? null :
            ($key == 'sendance-time' || $key == 'receive-time' ? format_time_to_hms(trim($value)) : mb_ucfirst(trim($value)));
    }
    
    return $formattedRequestValues;
}

/**
 *
 * mb_ucfirst
 *
 * @param string $str
 * @return string
 */
function mb_ucfirst(string $str): string {
    $fc = mb_strtoupper(mb_substr($str, 0, 1));
    return $fc.mb_substr($str, 1);
}

/**
 *
 * format_datetime_in_array
 *
 * проходит по массиву и форматирует дату и время в человекочитаемый формат
 * 
 * @param array $array
 * @return array
 *
 */
function format_datetime_in_array(array $arrayToFormat): array
{
    foreach($arrayToFormat as $num => $array)
    {
        foreach($array as $key => $value)
            $array[$key] = preg_match("/([1-2][0-9]{3})-([0-9]{2})-([0-9]{2})/", $value) ? format_date_to_dmy($value) :
                (preg_match("/(([0-9]{2}):([0-9]{2}):([0-9]{2}))/", $value) ? format_time_to_hm($value) : $value);
        $arrayToFormat[$num] = $array;
    }
    return $arrayToFormat;
}