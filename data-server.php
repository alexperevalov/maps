<?php
/**
 * Created by PhpStorm.
 * User: alexp
 * Date: 10/10/14
 * Time: 1:11 PM
 */
define('POPULATION_LOWER_BOUND', 200000);
define('RADIUS_DEVISOR', 100);

function safe($value){
    return mysql_real_escape_string($value);
}

//connection to the database
$dbcn = mysql_connect('localhost', 'root', '123321')
or die("Unable to connect to MySQL");

header('Content-Type: application/json');

$country = safe($_GET['name']);

//select a database to work with
$db = mysql_select_db('maps', $dbcn);

//execute the SQL query and return records
$result = mysql_query("SELECT * FROM cities WHERE country = '{$country}' AND population > " . POPULATION_LOWER_BOUND);

$data = array();
//fetch tha data from the database
while ($row = mysql_fetch_array($result)) {
    $row['radius'] = intval($row['population']) / RADIUS_DEVISOR;
    array_push($data, $row);
    error_log($row['city']);
}

//close the connection
mysql_close($dbcn);


echo json_encode($data);