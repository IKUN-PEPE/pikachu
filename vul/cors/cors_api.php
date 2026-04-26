<?php
/**
 * Created by runner.han
 * There is nothing new under the sun
 */

$PIKA_ROOT_DIR = "../../";
include_once $PIKA_ROOT_DIR . 'inc/config.inc.php';

$scenario = isset($_GET['scenario']) ? $_GET['scenario'] : 'reflect';
$origin = isset($_SERVER['HTTP_ORIGIN']) ? trim($_SERVER['HTTP_ORIGIN']) : '';

header('Content-Type: application/json; charset=utf-8');
header('Vary: Origin');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if($origin !== ''){
    header('Access-Control-Allow-Origin: ' . $origin);

    if($scenario == 'credential'){
        header('Access-Control-Allow-Credentials: true');
    }
}

if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS'){
    exit;
}

$response = array(
    'scenario' => $scenario,
    'origin' => $origin,
    'message' => 'CORS is not auth. This endpoint is only for demo.',
    'data' => array(
        'username' => 'guest',
        'email' => 'guest@pikachu.com'
    )
);

$link = @mysqli_connect(DBHOST, DBUSER, DBPW, DBNAME, DBPORT);
if($link){
    mysqli_set_charset($link, 'utf8');
    $query = "select id,username,email from member limit 0,1";
    $result = @mysqli_query($link, $query);
    if($result && mysqli_num_rows($result) == 1){
        $response['data'] = mysqli_fetch_assoc($result);
    }
}

echo json_encode($response);
?>
