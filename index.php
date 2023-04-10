<?php
declare(strict_types=1);
spl_autoload_register(function($class){
    require __DIR__ . "/src/$class.php";
});

set_exception_handler("ErrorHandler::handleException");

header("Content-type: application/json; charset=UTF-8");
$parts= explode("/",$_SERVER["REQUEST_URI"]);
if ($parts[2] != "recepies") {
    http_response_code(404);
    exit;
}
$category = $parts[3] ?? null;
$id = $parts[4] ?? null;

$database = new Database("localhost","recipe_db","root","");
$gateway = new RecepiesGateway($database);
$controller = new RecepiesController($gateway);
$controller->processRequest($_SERVER["REQUEST_METHOD"],$category,$id);

?>