<?php

require_once 'RussianWeather.php';
require_once 'USWeatherService.php';
require_once 'USWeatherAdapter.php';

$service = new RussianWeather();
//$service->setPosition("Moscow");
$service->setPosition("St. Petersburg");

echo "Moscow<h2>";
echo "Температура (С) : " . $service->getTemperature() . '<br>';
echo "Скорость ветра (м/с) : " . $service->getWind() . '<br>';
echo "Ощущаемая температура (С) : " . $service->getFeelsLikeTemperature() . '<br>';
echo "</h2>";

$usservice = new USWetherAdapter(new USWeatherService());

//$usservice->setPosition("New York");
$usservice->setPosition("Washington");

echo "New York<h2>";
echo "Температура (С) : " . $service->getTemperature() . '<br>';
echo "Скорость ветра (м/с) : " . $service->getWind() . '<br>';
echo "Ощущаемая температура (С) : " . $service->getFeelsLikeTemperature() . '<br>';
echo "</h2>";
