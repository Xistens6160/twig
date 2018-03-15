<?php
/**
 * Created by PhpStorm.
 * User: gue
 * Date: 12.03.2018
 * Time: 11:26
 */

include "classes/database.class.php";
include "classes/gamestatus.class.php";

$name = $_GET['name'];

$db = new Database();
$db->connect("192.168.58.193", "schatzsuche@%", "Passw0rd!", "schatzsuche");

$gamestatus = new Gamestatus($db);

$gamestatus->name = $name;
$gamestatus->updateData();

header("location: ../html/index.html");
