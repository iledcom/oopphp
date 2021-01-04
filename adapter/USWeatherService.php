<?php

class USWeatherService {
	public function getTemperature(float $latitude, float $longtitude) {
		if($latitude == 38.53 && $longtitude == 77.02) {//Washington
			return 86;
		} else if($latitude == 40.43 && $longtitude == 73.59) {//New York
			return 95;
		} else {
			return 80;
		}
	}

	/**
	* Возвращает скорость ветра
	* @param latitude - широта
	* @param longtitude - долгота
	*Возвращает скорость ветра в ft/min
	*/

	public function getWind(float $latitude, float $longtitude) {
		if($latitude == 38.53 && $longtitude == 77.02) {//Washington
			return 1000;
		} else if($latitude == 40.43 && $longtitude == 73.59) {//New York
			return 2000;
		} else {
			return 1500;
		}
	}
}