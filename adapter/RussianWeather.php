<?php 

require_once 'WeatherService.php';

class RussianWeather implements WeatherService {
	private $city = null;

	public function getTemperature() {
		switch($this->city) {
			case "Moscow" : return 25; 
			case "St. Petersburg" : return 18;
			default : return 20;
		}
	}

	public function getWind() {
		switch($this->city) {
			case "Moscow" : return 5; 
			case "St. Petersburg" : return 13;
			default : return 1;
		}
	}

	public function getFeelsLikeTemperature() {
		switch($this->city) {
			case "Moscow" : return 23; 
			case "St. Petersburg" : return 16;
			default : return 20;
		}
	}

	public function setPosition(String $city) {
		$this->city = $city;
	}
}