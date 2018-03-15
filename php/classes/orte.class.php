<?php
/**
 * Created by PhpStorm.
 * User: gue
 * Date: 08.03.2018
 * Time: 16:20
 */

class Orte
{
    public $id;
    public $x;
    public $y;
    public $name;
    private $db;
    public $randomy;
    public $randomx;
    public $counter;

    function __construct($db, $id)
    {
        $this->db = $db;
        $sql = "SELECT * FROM orte WHERE id=$id";
        $dataarray = $this->db->getData($sql);
        $this->id = $dataarray["id"];
        $this->x = $dataarray["x"];
        $this->y = $dataarray["y"];
        $this->name = $dataarray["name"];
    }

    /**
     * aktualisiert die GoalID
     */
    function updateGoalID()
    {
        $sql = "UPDATE orte SET name = 'Ziel' WHERE y = $this->randomy AND x = $this->randomx";
        $this->db->query($sql);
    }

    /**
     * aktualisiert die StartID
     */
    function updateStartID()
    {
        $sql = "UPDATE orte SET name = 'Start' WHERE y = $this->randomy AND x = $this->randomx";
        $this->db->query($sql);
    }

    /**
     * schreibt jeden neuen Raum in die Tabelle
     */
    function insertData()
    {
        $sql = "INSERT INTO orte SET x = " . $this->x . ", y = " . $this->y . ", name = 'Raum " . $this->counter . "'";
        $this->db->query($sql);
    }

    /**
     * @return mixed
     * holt sich die StartID
     */
    function selectStartID()
    {
        $sql = "SELECT id FROM orte WHERE name = 'Start'";
        $startid = $this->db->getInformation($sql, "id");
        return $startid;
    }

    /**
     * @return mixed
     * holt sich die GoalID
     */
    function selectGoalID()
    {
        $sql = "SELECT id FROM orte WHERE x = $this->randomx AND y = $this->randomy";
        $goalid = $this->db->getInformation($sql, "id");
        return $goalid;
    }

    /**
     * löscht den Inhalt der Tabelle
     */
    function clearTable()
    {
        $sql = "DELETE FROM orte";
        $this->db->query($sql);
    }

    /**
     * @return mixed
     * gibt den neuen Raum zurück
     */
    function selectNextRoom()
    {
        $sql = "SELECT * FROM orte WHERE x= $this->x AND y= $this->y";
        $dataarray = $this->db->getData($sql);
        return $dataarray;
    }
}