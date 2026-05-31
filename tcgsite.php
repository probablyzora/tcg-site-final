<?php 
require 'vendor/autoload.php';
use GuzzleHttp\Client;

$client = new Client();
$query = $_GET['name'] ?? $_GET['id'] ?? 'celebi'; // celebi va jamais etre utilisé de base mais juste placeholder 
$response = $client->request('GET',"https://pokeapi.co/api/v2/pokemon/$name"); // url api de pokemon de pokeapi 
$data = json_decode($response->getBody(), true); // utilisation du json 

//$sprite = $data['sprites']["versions"]["generation-ii"]["crystal"]["front_default"] // les front-sprites de pokemon cristal
//    ?? $data['sprites']['front_default']; // sinon les sprites par defaut si il n'y a pas de sprite gen2 (pokemon #252+)
$sprite = $data['sprites']["front_default"]; // pas envie de me battre avec les images non-transparentes de la gen2
echo json_encode([
    'name' => $data['name'],
    'id' => $data['id'],
    'sprite'=> $sprite
]);
if ($response === false) {
    http_response_code(500);
    echo json_encode('Error, incorrect name or data');
    exit;
}
?>