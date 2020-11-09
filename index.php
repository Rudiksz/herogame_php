<?php

include 'autoloader.php';

error_reporting(0);
GameConfig::init();
session_start();


// action = [Class name, handler method]
$actions = [
    'characters' => ['Character', 'list'],
    'character.new' => ['Character', 'create'],
    'battle' => ['Battle', 'battle'],
    'battle.attack' => ['Battle', 'attack'],
    'battle.auto' => ['Battle', 'autoFight'],
];


$action = $_REQUEST['action'] ?? '';
$handler = $actions[$action] ?? $actions['characters'];

$handlerClass = $handler[0] . 'Controller';
$handlerMethod = $handler[1];

$result = (new $handlerClass)->$handlerMethod();

// Generate the output
// Make sure the output format is supported
$output = isset($_REQUEST['o']) && in_array($_REQUEST['o'], ['html', 'json',]) ?  $_REQUEST['o'] : 'html';

if ($output == 'json') {
    echo json_encode($result);
} else {
    $template = $handler[0] . ucfirst($output);
    (new $template)->$handlerMethod($result);
}
