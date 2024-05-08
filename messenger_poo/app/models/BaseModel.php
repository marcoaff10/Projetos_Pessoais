<?php

namespace messenger\Models;

use messenger\System\Database;

abstract class BaseModel {

    public $db;

    public function db_connection()
    {
        
        $options = [
            'host' => MYSQL_HOST,
            'database' => MYSQL_DATABASE,
            'username' => MYSQL_USERNAME,
            'password' => MYSQL_PASSWORD
        ];
        $this->db = new Database($options);
    }

    public function query($sql = "", $paramers = [])
    {
        return $this->db->execute_query($sql, $paramers);
    }

    public function non_query($sql = "", $paramers = [])
    {
        return $this->db->execute_non_query($sql, $paramers);
    }
}