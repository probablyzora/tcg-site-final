<?php 
require 'vendor/autoload.php';
use GuzzleHttp\Client;

$client = new Client();
$query = $_GET['name'] ?? $_GET['id'] ?? 'celebi'; // celebi will never be used 
$response = $client->request('GET',"https://pokeapi.co/api/v2/pokemon/$query"); // pokeapi api 
$data = json_decode($response->getBody(), true); // using json 

$sprite = $data['sprites']["front_default"];
echo json_encode([
    'name' => $data['name'],
    'id' => $data['id'],
    'sprite'=> $sprite,
    'types' => array_map(fn($t) => $t['type']['name'], $data['types']), // put type into array
    'height' => $data['height'],
    'weight' => $data['weight']
]);
if ($response === false) {
    http_response_code(500);
    echo json_encode('Error, incorrect name or data');
    exit;
}
?>