<?php

$map = [];
$coordinates = [];
$roomsCreated = 0;
$maxDepth = 1;

function saveRoomCoordinates($id, $coords)
{
    global $map, $coordinates;
    $map[$id]['x'] = $coords['x'];
    $map[$id]['y'] = $coords['y'];
    $coordinates[$coords['x']][$coords['y']] = $id;
}

function getNewRoom()
{
    global $roomsCreated;
    $roomsCreated += 1;
    $room = [];
    $room['name'] = "Raum " . $roomsCreated;
    $room['id'] = $roomsCreated;
    return $room;
}

function changeCoordinatesByDirection($direction)
{
    $changeCoordinates = ['x' => 0, 'y' => 0];
    if ($direction == 'n') $changeCoordinates['y'] += 1;
    if ($direction == 's') $changeCoordinates['y'] -= 1;
    if ($direction == 'e') $changeCoordinates['x'] += 1;
    if ($direction == 'w') $changeCoordinates['x'] -= 1;
    return $changeCoordinates;
}

function getOppositeDirection($direction)
{
    if ($direction == 'n') return 's';
    if ($direction == 's') return 'n';
    if ($direction == 'e') return 'w';
    if ($direction == 'w') return 'e';
}

/**
 * Erzeugt die nächsten Räume
 * @param $id
 * @param int $count
 */
function createNextRooms($id, $count = 0)
{
    global $map, $maxDepth, $coordinates;
    $directions = ['n', 'e', 's', 'w'];
    // Abbruch falls mehr als 5 Rekursionen
    if ($count > $maxDepth) return;

    foreach ($directions as $direction) {
        // Neue Kordinaten ermitteln
        $changeCoordinates = changeCoordinatesByDirection($direction);
        $newX = $map[$id]['x'] + $changeCoordinates['x'];
        $newY = $map[$id]['y'] + $changeCoordinates['y'];

        if ((!isset($coordinates[$newX]) || !isset($coordinates[$newX][$newY]))) {
            $room = getNewRoom();

            // Speichere Koordinaten für neuen Raum
            saveRoomCoordinates($room['id'], ['x' => $newX, 'y' => $newY]);

            // Raum speichern
            $map[$id][$direction] = $room['id'];
            $room[getOppositeDirection($direction)] = $id;
            $map[] = $room;

            // Fehlende Räume ergänzen
            createNextRooms($room['id'], $count + 1);
        } else {
            echo ".";
        }
    }
}

function createMap()
{
    global $map, $coordinates;
    $room = getNewRoom();
    $map[$room['id']] = $room;
    saveRoomCoordinates($room['id'], ['x' => 0, 'y' => 0]);
    createNextRooms($room['id']);
}

createMap();
var_dump($map);
var_dump($coordinates);
