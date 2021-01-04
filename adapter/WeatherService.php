<?php
interface WeatherService {
	function getTemperature();
	function getWind();
	function getFeelsLikeTemperature();
	function setPosition(String $city);
}