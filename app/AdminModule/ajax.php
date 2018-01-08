<?php
$adminModulePath = $_SERVER['DOCUMENT_ROOT'] . dirname($_SERVER['REQUEST_URI']);
if(isset($_GET['script'])){
    $handlerFile = $adminModulePath . '/ajaxHandlers/' . $_GET['script'] . '.php';
    $scriptFile = __DIR__ . '/ajax/' . $_GET['script'] . '.php';
    $_GET['script'] = strtr($_GET['script'], array('/' => '-'));
    if(file_exists($handlerFile)) {
        require($handlerFile);
    } else if(file_exists($scriptFile)) {
        require($scriptFile);
    }
} else {
    return;
}
