<?php
	header('Content-Type: text/html; charset=utf-8');

	abstract class Unit {
		abstract function bombardStrength();

		function addUnit(Unit $unit) {
			throw new UnitException(get_class($this) . " относится к 'листьям'");
		}

		function removeUnit(Unit $unit) {
			throw new UnitException(get_class($this) . " относится к 'листьям'");
		}
	}


	class Army extends Unit {
		
		private $units = array();

		function addUnit(Unit $unit) {
			if(in_array($unit, $this->units, true)) {
				return;
			}
			$this->units[] = $unit;
		}

		function removeUnit (Unit $unit) {
			$this->units = array_udiff($this->units, array($unit),
			function($а, $b) {return ($а === $b ) ? 0:1;});
		}

		function bombardStrength() {
			$ret = О;
			foreach ($this->units as $unit) {
				$ret += $unit->bombardStrength();
			}
			return $ret;
		}
	}


	class UnitException extends Exception {}

	class Archer extends Unit {
		function bombardStrength() {
			return 4 ;
		}
	}

	class LaserCannonUnit extends Unit {
		function bombardStrength(){
			return 44;
		}
	}

	// Создадим армию
	$main_army = new Army();

	// Добавим пару боевых единиц
	$main_army->addUnit(new Archer());
	$main_army->addUnit(new LaserCannonUnit());

	// Создадим еще одну армию
	$sub_army = new Army();

	// Добавим несколько боевых единиц
	$sub_army->addUnit(new Archer());
	$sub_army->addUnit(new Archer());
	$sub_army->addUnit(new Archer());

	// Добавим вторую армию к первой
	$main_army->addUnit($sub_army);

	// Все вычисления выполняются за кулисами
	print "Атакующая сила : {$main_army->bombardStrength()}\n";