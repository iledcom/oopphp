<?php

require_once 'RussianWeather.php';
//require_once '';
//require_once '';

$service = new RussianWeather();
//$service->setPosition("Moscow");
$service->setPosition("St. Petersburg");

echo "Moscow<h2>";
echo "Температура (С) : " . $service->getTemperature() . '<br>';
echo "Скорость ветра (м/с) : " . $service->getWind() . '<br>';
echo "Ощущаемая температура (С) : " . $service->getFeelsLikeTemperature() . '<br>';
echo "</h2>";
