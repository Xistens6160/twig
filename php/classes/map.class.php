<?php
/**
 * Created by PhpStorm.
 * User: gue
 * Date: 08.03.2018
 * Time: 15:31
 */

class Map
{
    private $id;
    public $start;
    public $goal;
    private $db;

    function __construct($db)
    {
        $this->db = $db;
        $sql = "SELECT * FROM map WHERE id='1'";
        $dataarray = $this->db->getData($sql);
        $this->id = $dataarray["id"];
        $this->start = $dataarray["start"];
        $this->goal = $dataarray["goal"];
    }

    function updateData()
    {
        $sql = "UPDATE map SET start = '.$this->start.', goal = '.$this->goal.' WHERE id='1'";
        $this->db->query($sql);
    }
}