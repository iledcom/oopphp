<?php

	header('Content-Type: text/html; charset=utf-8');

	abstract class Unit {
		function getComposite(){
			return null;
		}
		abstract function bomЬardStrength();
	}

	/*
Обратите внимание на новый метод getComposite(). Мы вернемся к нему через некоторое время. А теперь нам нужен новый абстрактный класс, который будет поддерживать методы addUnit() и removeUnit(). Мы можем обеспечить даже его стандартные реализации.
	*/

	abstract class CompositeUnit extends Unit {
		private $units = array();

		function getComposite() {
			return $this;
		}

		protected function units() {
			return $this->units;
		}

		function removeUnit (Unit $unit) {
			$this->units = array_udiff($this->units, array($unit),
			function($а, $b) {return ($а === $b) ?O : l; });
		}

		function addUnit (Unit $unit) {
			if (in_array($unit, $this->units, true)) {
				return;
			}
			$this->units[] = $unit;
		}
	}

	/*

	Класс CompositeUnit объявлен абстрактным, несмотря на то что в нем не объявлено никаких абстрактных методов. Однако он расширяет класс Unit и в нем не реализован абстрактный метод bombardStrength(). Класс Army (и любые другие классы-композиты) теперь может расширять класс CompositeUnit.
*/