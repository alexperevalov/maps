<?php
/**
 * Created by PhpStorm.
 * User: alexp
 * Date: 10/13/14
 * Time: 1:21 PM
 */

class db {

    const DB_HOST = 'localhost';
    const DB_USER = 'root';
    const DB_PASS = '123321';
    const DB_NAME = 'maps';

    const LOGGING = true;

    private static $connection = null;

    public static function getConnection()
    {
        if (self::$connection) {
            return self::$connection;
        }
        if (self::$connection = mysql_connect(self::DB_HOST, self::DB_USER, self::DB_PASS)) {
            return self::$connection;
        }
        throw new \Exception('Can\'t connect to MySQL server named "' . self::DB_HOST . '"');
    }

    public static function close()
    {
        if (self::$connection) {
            mysql_close(self::$connection);
        }
    }

    public static function quote($str)
    {
        return mysql_real_escape_string($str, self::getConnection());
    }

    public static function query($query)
    {
        if (self::LOGGING) {
            $t_mark = microtime(1);
            error_log('--- DB QUERY --------------------------------------------');
            error_log($query);
        }

        mysql_select_db(self::DB_NAME, self::getConnection());
        $result = mysql_query($query, self::getConnection());

        $data = array();
        while ($row = mysql_fetch_assoc($result)) {
            array_push($data, $row);
        }

        if (self::LOGGING) {
            error_log('--- result: ' . mysql_num_rows($result) . ' rows --- ' . (1000000 * (microtime(1) - $t_mark)) . ' microsec. taken ---');
        }

        mysql_free_result($result);
        //close the connection
        self::close();

        return $data;
    }
} 