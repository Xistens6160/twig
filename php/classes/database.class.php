<?php
/**
 * Created by PhpStorm.
 * User: gue
 * Date: 08.03.2018
 * Time: 08:15
 */

class Database
{
    private $conn;

    public function connect($servername, $username, $password, $dbname)
    {
        $this->conn = new mysqli($servername, $username, $password, $dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    function query($sql)
    {
        $result = mysqli_query($this->conn, $sql);
        return $result;
    }

    function fetch($result)
    {
        $dataarray = mysqli_fetch_assoc($result);
        return $dataarray;
    }

    function getData($sql)
    {
        $result = $this->query($sql);
        $dataarray = $this->fetch($result);
        return $dataarray;
    }

    function getInformation($sql, $data)
    {
        $dataarray = $this->getData($sql);
        $data = $dataarray[$data];
        return $data;
    }
}