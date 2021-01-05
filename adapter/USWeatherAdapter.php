<?php

require_once 'WeatherService.php';
require_once 'USWeatherService.php';

class USWetherAdapter implements WeatherService {
	private $latitude;
	private $longtitude;
	private $service;

	public function __construct(USWeatherService $service) {
		$this->service = $service;
	}

	public function getTemperature() {
		$tf = $this->service->getTemperature($this->latitude, $this->longtitude);
		return ($tf-32)*5/9; // F -> C 
	}

	public function getWind(){
		$windFtMin = $this->service->getWind($this->latitude, $this->longtitude);
		return $windFtMin / 196.85; // ft/min -> m/s
	}

	public function getFeelsLikeTemperature() {
		return 1.04*$this->getTemperature() - $this->getWind()*0.65-0.9;
	}

	public function setPosition(String $city) {
		switch($city) {
			case "Washington" :
			$this->latitude = 38.53;
			$this->longtitude = 77.02;

			case "New York" :
			$this->latitude = 40.43;
			$this->longtitude = 73.59;

			break;
		}
	}
}