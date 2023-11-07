<?php
function getShortestRoute($cities, $connections, $startCity, $endCity) {
    $numCities = count($cities);
    $costs = array_fill(0, $numCities, INF);
    $previous = array_fill(0, $numCities, null);
    $unvisited = range(0, $numCities - 1);
    if ($startCity == $endCity) {
        throw new Exception("La ciudad de inicio y destino no pueden ser la misma");
    }
    $startIdx = array_search($startCity, $cities);
    $endIdx = array_search($endCity, $cities);
    if ($startIdx === false) {
        throw new Exception("La ciudad de inicio no existe");
    } elseif ($endIdx === false) {
        throw new Exception("La ciudad de destino no existe");
    }
    $costs[$startIdx] = 0;

    while (!empty($unvisited)) {
        $minDistance = INF;
        $minIdx = null;

        foreach ($unvisited as $cityIdx) {
            if ($costs[$cityIdx] < $minDistance) {
                $minDistance = $costs[$cityIdx];
                $minIdx = $cityIdx;
            }
        }
        $currentIdx = $minIdx;

        if ($currentIdx === $endIdx) {
            break;
        }

        foreach ($unvisited as $neighborIdx) {
            if ($connections[$currentIdx][$neighborIdx] > 0) {
                $alt = $costs[$currentIdx] + $connections[$currentIdx][$neighborIdx];
                if ($alt < $costs[$neighborIdx]) {
                    $costs[$neighborIdx] = $alt;
                    $previous[$neighborIdx] = $currentIdx;
                }
            }
        }

        $unvisited = array_diff($unvisited, [$currentIdx]);
    }

    $route = [];
    $currentIdx = $endIdx;

    while ($currentIdx !== null) {
        $route[] = $cities[$currentIdx];
        $currentIdx = $previous[$currentIdx];
    }

    return [
        'route' => array_reverse($route),
        'cost' => $costs[$endIdx],
    ];
}

function getShortestRoutesFromStartCity($cities, $connections, $startCity) {
    $routes = [];

    if (!in_array($startCity, $cities)) {
        throw new Exception("La ciudad de inicio no existe");
    }
    foreach ($cities as $city) {
        if ($city !== $startCity) {
            $routeData = getShortestRoute($cities, $connections, $startCity, $city);
            $routes[$city] = $routeData;
        }
    }

    return $routes;
}

function findShortestRouteForAllCities($cities, $connections, $startCity) {
    $shortestRoute = [];
    $totalCost = 0;
    $currentCity = $startCity;

    $unvisitedCities = array_diff($cities, [$startCity]);
    
    while (!empty($unvisitedCities)) {
        $minCost = INF;
        $nextCity = null;

        foreach ($unvisitedCities as $endCity) {
            $routeData = getShortestRoute($cities, $connections, $currentCity, $endCity);
            $cost = $routeData['cost'];

            if ($cost < $minCost) {
                $minCost = $cost;
                $nextCity = $endCity;
            }
        }

        $shortestRoute[] = [
            'from' => $currentCity,
            'to' => $nextCity,
            'cost' => $minCost,
        ];

        $totalCost += $minCost;
        $currentCity = $nextCity;

        $key = array_search($currentCity, $unvisitedCities);
        if ($key !== false) {
            unset($unvisitedCities[$key]);
        }
    }

    return [
        'route' => $shortestRoute,
        'total_cost' => $totalCost,
    ];
}