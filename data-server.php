<?php
/**
 * Created by PhpStorm.
 * User: alexp
 * Date: 10/10/14
 * Time: 1:11 PM
 */
define('POPULATION_LOWER_BOUND', 200000);
define('RADIUS_DEVISOR', 100);

include "lib/db.php";

header('Content-Type: application/json');
$data = array();

if ('countries' == $_GET['name']) {

    $data = db::query("SELECT country, country_human FROM countries ORDER BY country_human");

}
else {

    $result = db::query("SELECT * FROM cities WHERE country = '" . db::quote($_GET['name']) . "' AND population > " . POPULATION_LOWER_BOUND);

    foreach ($result as $row) {
        $row['radius'] = intval($row['population']) / RADIUS_DEVISOR;
        array_push($data, $row);
    //    error_log($row['city']);
    }

}

echo json_encode($data);