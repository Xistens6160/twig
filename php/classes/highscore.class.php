<?php

class Highscore
{
    public $id;
    public $steps;
    public $time;
    public $name;
    private $db;

    function __construct($db)
    {
        $this->db = $db;
    }


    /**
     * speichert neuen Score
     */
    function putScore()
    {
        $sql = "INSERT INTO highscore SET steps =$this->steps, times = $this->time, playername = '$this->name'";
        $this->db->query($sql);
    }

    /**
     *tut alle Scores in ein Array
     */
    function displayData()
    {
        $sql = "SELECT * FROM highscore";
        $results = $this->db->query($sql);
        while ($row = mysqli_fetch_assoc($results)) {
            $tempdata[] = ["steps" => $row["steps"], "time" => $row["times"], "name" => $row["playername"]];
        }
        return $tempdata;
    }
}