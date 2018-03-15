<?php
//greift auf die Klassen zu
include "classes/database.class.php";
include "classes/gamestatus.class.php";
include "classes/map.class.php";
include "classes/orte.class.php";
include "classes/highscore.class.php";

//stellt die Verbindung zur Datenbank her
$db = new Database();
$db->connect("192.168.58.193", "schatzsuche@%", "Passw0rd!", "schatzsuche");

//erstellt die Objekte
$gamestatus = new Gamestatus($db);
$map = new Map($db);
$highscore = new Highscore($db);
$response = [];

/**
 * @param $var
 * @param $action
 * @return string
 * generiert Button
 */
function getButtonHtml($class, $var, $action)
{
    return "<button class='$class' onclick='callAction($action)'>" . $var . "</button>";
}

/**
 * @return string
 * generiert den "Zurück zur Startseite" Button
 */
function getbackButton()
{

    return "<button class='btn btn-info getbackbutton' onclick=\"location.href = '../html/start.html';\">Zurück zur Startseite</button>";

}

/**
 * startet ein neues Spiel beim Triggern
 */
function startNewGame()
{
    global $response, $x, $y, $startid, $db, $gamestatus, $steps, $map, $orte;

    $startid = $map->start;

    $orte = new Orte($db, $startid);
    $x = $orte->x;
    $y = $orte->y;
    $roomname = $orte->name;

    $steps = 0;

    $gamestatus->starttime = time();
    $gamestatus->ort_id = $startid;
    $gamestatus->updateData();

    $response = ["art" => "Position: ", "output" => $roomname, "art2" => "", "steps" => "", "art3" => "", "time" => ""];
}

/**
 *  gibt die Position vor der Sackgasse aus
 */
function callLastPosition()
{
    global $steps, $response, $db, $gamestatus;

    $newroomid = $gamestatus->last_ort_id;

    $orte = new Orte($db, $newroomid);
    $roomname = $orte->name;

    $gamestatus->ort_id = $newroomid;
    $gamestatus->updateData();

    $response = ["art" => "Position: ", "output" => $roomname, "art2" => "Schritte: ", "steps" => $steps, "art3" => "", "time" => ""];
}

/**
 * gibt ein Tipp entsprechend der Position aus
 */
function callTipp()
{
    global $steps, $db, $response, $gamestatus, $map;

    $roomid = $gamestatus->ort_id;

    $orte = new Orte($db, $roomid);
    $x = $orte->x;
    $y = $orte->y;

    $goalid = $map->goal;

    $orte = new Orte($db, $goalid);
    $goalx = $orte->x;
    $goaly = $orte->y;

    if ($goalx != $x) {
        if ($goalx > $x) {
            $answer = "Norden";
        } else {
            $answer = "Süden";
        }
    } else {
        if ($goaly > $y) {
            $answer = "Osten";
        } else {
            $answer = "Westen";
        }
    }
    $response = ["art" => "Tipp: ", "output" => "Gehe nach $answer", "art2" => "Schritte: ", "steps" => $steps, "art3" => "", "time" => ""];
}

/**
 * @param $action
 * gibt den nächsten Raum je nach Zug aus
 */
function callNextRoom($action)
{
    global $steps, $db, $response, $gamestatus;

    $roomid = $gamestatus->ort_id;
    $gamestatus->last_ort_id = $roomid;
    $gamestatus->updateData();

    $orte = new Orte($db, $roomid);
    $x = $orte->x;
    $y = $orte->y;


    if ($action == 1) {
        $x += 1;
    }
    if ($action == 2) {
        $y += 1;
    }
    if ($action == 3) {
        $x -= 1;
    }
    if ($action == 4) {
        $y -= 1;
    }

    $orte->x = $x;
    $orte->y = $y;
    $dataarray = $orte->selectNextRoom();
    $roomname = $dataarray["name"];
    $newroomid = $dataarray["id"];

    $gamestatus->ort_id = $newroomid;
    $gamestatus->updateData();

    // gibt bei leeren Feld "Sackgasse aus, sonst den ort der Koordinate
    if ($roomname != null) {
        $response = ["art" => "Position: ", "output" => $roomname, "art2" => "Schritte: ", "steps" => $steps, "art3" => "", "time" => ""];
    } else {
        $response = ["art" => "", "output" => "Sackgasse", "art2" => "Schritte: ", "steps" => $steps, "art3" => "", "time" => ""];
    }
}

/**
 * gibt die Knöpfe zum bewegen aus
 */
function callDirectionButton()
{
    global $response;
    $response['body'] = getButtonHtml("btn btn-primary norden", 'Norden', 1) . getButtonHtml("btn btn-primary osten", 'Osten', 2) . getButtonHtml("btn btn-primary süden", 'Süden', 3) . getButtonHtml("btn btn-primary westen", 'Westen', 4) . getButtonHtml("btn btn-success tipp", 'Tipp', 6) . getButtonHtml("btn btn-danger reset", 'Reset', 0);
}

/**
 * gibt die Sieges Oberfläche aus und speichert den Score
 */
function callVictoryScreen()
{
    global $steps, $response, $highscore, $gamestatus;
    $beginntime = $gamestatus->starttime;
    $time = time() - $beginntime;

    $response = ["art" => "Position: ", "output" => "Ziel", "art2" => "Schritte: ", "steps" => $steps, "art3" => "Zeit in Sekunden: ", "time" => $time];
    $response['body'] = getbackButton();

    $name = $gamestatus->name;

    $highscore->name = $name;
    $highscore->steps = $steps;
    $highscore->time = $time;
    $highscore->putScore();
}

// holt sich die Daten
$steps = $gamestatus->currentstep + 0;

$action = $_POST['action'];

// setzt bei jedem Zug die Schrittzahl um ein hoch
if ($action >= 1 && $action <= 6) {
    $steps += 1;
}

// triggert beim laden der Seite und beim resetten des Spiels startNewGame
if ($action == 0) {
    startNewGame();
}

// triggert nach einer Sackgasse callLastPosition
if ($action == 5) {
    callLastPosition();
}

// triggert bei Anfrage callTipp
if ($action == 6) {
    callTipp();
}

// triggert callNextRoom mit der entsprechenden Raum in der Himmelsrichtung
if ($action >= 1 && $action <= 4) {
    callNextRoom($action);
}

// solange man nicht Gewonnen hat triggert callDirectionButton
if ($response["output"] != "Ziel") {
    callDirectionButton();
}

// triggert wenn man Gewonnen hat callVictoryScreen
if ($response["output"] == "Ziel") {
    callVictoryScreen();
}

// wenn die Antwort "Sackgasse" ist gibt er den Zurück Button aus
if ($response["output"] == "Sackgasse") {
    $response['body'] = getButtonHtml("btn btn-primary norden", 'Zurück', 5, $position);
}

// speichert Daten
$gamestatus->currentstep = $steps;
$gamestatus->updateData();

$json = json_encode($response);
echo $json;