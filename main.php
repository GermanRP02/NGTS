<?php 
require_once "Libraries/routes_helper.php";

$cities = ['Logroño', 'Zaragoza', 'Teruel', 'Madrid', 'Lleida', 'Alicante', 'Castellón', 'Segovia', 'Ciudad Real'];
$connections = [
    [0, 4, 6, 8, 0, 0, 0, 0, 0],
    [4, 0, 2, 0, 2, 0, 0, 0, 0],
    [6, 2, 0, 3, 5, 7, 0, 0, 0],
    [8, 0, 3, 0, 0, 0, 0, 0, 0],
    [0, 2, 5, 0, 0, 0, 4, 8, 0],
    [0, 0, 7, 0, 0, 0, 3, 0, 7],
    [0, 0, 0, 0, 4, 3, 0, 0, 6],
    [0, 0, 0, 0, 8, 0, 0, 0, 4],
    [0, 0, 0, 0, 0, 7, 6, 4, 0]
];

$startCity = 'Logroño';
$endCity = 'Ciudad Real';

try {
    $result = getShortestRoute($cities, $connections, $startCity, $endCity);
    echo "La ruta más econócima entre $startCity y $endCity es:". PHP_EOL. 
    implode(" -> ", $result["route"]).PHP_EOL.
    " Con un coste de: ".$result["cost"].PHP_EOL;
} catch (Exception $e) {
    echo "Error: ".$e->getMessage().PHP_EOL;
    exit(1);
}


try {
    $result = getShortestRoutesFromStartCity($cities, $connections, $startCity);
    foreach ($result as $city => $data) {
        echo "Ruta más económica a $city: " . implode(" -> ", $data['route']) . "\n";
        echo "Costo: " . $data['cost'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: ".$e->getMessage().PHP_EOL;
    exit(1);
}

try {
    $result = findShortestRouteForAllCities($cities, $connections, $startCity);
    echo "Ruta más corta que pasa por todas las ciudades:\n";
    foreach ($result['route'] as $route) {
        echo $route['from'] . " -> " . $route['to'] . " (Costo: " . $route['cost'] . ")\n";
    }
    echo "Costo total: " . $result['total_cost'] . "\n";
} catch (Exception $e) {
    echo "Error: ".$e->getMessage().PHP_EOL;
    exit(1);
}