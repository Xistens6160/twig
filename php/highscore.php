<?php
include "classes/database.class.php";
include "classes/highscore.class.php";

$db = new Database();
$db->connect("192.168.58.193", "schatzsuche@%", "Passw0rd!", "schatzsuche");

$highscore = new Highscore($db);


function calllist($step, $time, $name)
{
    return "<tr><td>" . $step . "</td> <td>" . $time . "</td> <td>" . $name . "</td></tr>";
}

function getTableHtml($tabledata)
{
    $html = '<table style="width: 50%;margin-left: 25%; text-align: center;border: solid">
                <tr>
                <th>Steps</th>
                <th>Time</th>
                <th>Name</th>
                </tr>';
    $html .= $tabledata;

    $html .= '</table>';
    return $html;
}

// holt sich die Scores aus der Datenbank
$tempdata = [];

$tempdata = $highscore->displayData();

// sortiert die Liste nach den Schritten aufsteigend
asort($tempdata);
$tabledata = '';

// triggert für jeden Eintrag die Function um die Daten in die Tabelle zu Speichern
foreach ($tempdata as $row) {
    $row = (array)$row;
    $steps = $row["steps"];
    $time = $row["time"];
    $name = $row["name"];
    $tabledata .= calllist($steps, $time, $name);
}
$response['body'] = getTableHtml($tabledata);
$json = json_encode($response);
echo $json;

