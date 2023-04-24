<?php
declare(strict_types=1);
spl_autoload_register(function($class){
    require __DIR__ . "/src/$class.php";
});

set_exception_handler("ErrorHandler::handleException");
header('Access-Control-Allow-Origin: *');
header("Content-type: application/json; charset=UTF-8");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: POST, PUT, PATCH, GET, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Api-Key, X-Requested-With, Content-Type, Accept, Authorization");
$parts= explode("/",$_SERVER["REQUEST_URI"]);
if ($parts[2] != "recepies") {
    http_response_code(404);
    exit;
}
$category = $parts[3] ?? null;
$query = $parts[4] ?? null;
$id = $parts[5] ?? null;

$database = new Database("localhost","recipe_db","root","");
$gateway = new RecepiesGateway($database);
$controller = new RecepiesController($gateway);
$controller->processRequest($_SERVER["REQUEST_METHOD"],$category,$query,$id);

?>