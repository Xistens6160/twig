<?php
ini_set("display_errors", 1);
include "classes/database.class.php";
include "classes/map.class.php";
include "classes/orte.class.php";

$db = new Database();
$db->connect("192.168.58.193", "schatzsuche@%", "Passw0rd!", "schatzsuche");

$map = new Map($db);
$orte = new Orte($db);

$maxX = $_GET['maxx'];
$maxY = $_GET['maxy'];
$maxX = $maxX - 1;
$maxY = $maxY - 1;
$x = 0;
$y = -1;
$counter = 0;

/**
 * setz ein zufälligen Zielpunkt
 */
function newGoal()
{
    global $maxY, $maxX, $map, $orte;
    $randomx = rand(0, $maxX);
    $randomy = rand(0, $maxY);
    $startid = $orte->selectStartID();
    $orte->randomx = $randomx;
    $orte->randomy = $randomy;
    $goalid = $orte->selectGoalID();


    if ($goalid != $startid) {
        $orte->updateGoalID();

        $map->start = $startid;
        $map->goal = $goalid;
        $map->updateData();

    } else {
        newGoal();
    }
}

/**
 * weißt jedem Raum in der X-Achse ein Wert zu
 * @param $x
 * @param $y
 */
function nextRoom($x, $y)
{
    global $maxY, $counter, $orte;

    while ($y < $maxY) {
        $y += 1;
        $counter += 1;

        $orte->x = $x;
        $orte->y = $y;
        $orte->counter = $counter;
        $orte->insertData();
    }
    changex();
}

/**
 * geht wenn die X-Achse voll ist ein hoch
 */
function changex()
{
    global $x, $y, $maxX;

    if ($x < $maxX) {
        $y = -1;
        $x += 1;
        nextRoom($x, $y);
    }
}

//löscht die alte Map
$orte->clearTable();

nextRoom($x, $y);

//erstellt ein random Start und speichert den
$randomx = rand(0, $maxX);
$randomy = rand(0, $maxY);
$orte->randomy = $randomy;
$orte->randomx = $randomx;
$orte->updateStartID();

newGoal();

header("location: ../html/start.html");