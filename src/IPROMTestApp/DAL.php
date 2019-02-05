<?php
/**
 * Created by PhpStorm.
 * User: apolon
 * Date: 4.2.2019
 * Time: 21:27
 */

namespace IPROMTestApp;

use Simplon\Mysql\PDOConnector;
use Simplon\Mysql\Mysql;

/***
 * Singleton pattern class DAL (Data access layer)
 * @package IPROMTestApp
 */
final class DAL
{
    public $db;

    /**
     * Singleton pattern
     *
     * @return DAL
     */
    public static function Instance() {

        static $inst = null;
        if ($inst === null) {
            $inst = new DAL();
        }
        return $inst;
    }

    /***
     * DAL constructor.
     */
    private function __construct()
    {
        try {

            $pdo = new PDOConnector(
                getenv('DB_HOST'),
                getenv('DB_USER'),
                getenv('DB_PASSWORD'),
                getenv('DB_DATABASE')
            );
            $pdoConn = $pdo->connect('utf8', []);
            $this->db = new Mysql($pdoConn);

        } catch (\Exception $e) {

            echo $e->getMessage();
        }
    }
}