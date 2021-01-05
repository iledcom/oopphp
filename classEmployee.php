<?php
	header('Content-Type: text/html; charset=utf-8');
	
	abstract class Employee {
		protected $name;
		private static $types = array('Minion', 'CluedUp', 'WellConnected');

		static function recruit ($name) {
			$num = rand(1, count(self::$types)) - 1;
			$class = self::$types[$num];
			return new $class($name);
		}

		function __construct($name) {
			$this->name = $name;
		}
		
		abstract function fire();
	}

	class Minion extends Employee {
		function fire() {
			print "{$this->name} : убери со стола";
		}
	}

	// Новый класс типа Employee...

	class CluedUp extends Employee {
		function fire() {
			print "{$this->name} : вызови адвоката \n";
		}
	}

	// Новый класс типа Employee...

	class WellConnected extends Employee {
		function fire() {
			print "{$this->name} : позвони папику \n";
		}
	}

	/*
	class NastyBoss {
		private $employees = array();

		function addEmployee ($employeeName) {
			$this->employees[] = new Minion($employeeName);
		}

		function projectFails() {
			if(count($this->employees) > О) {
				$emp = array_pop($this->employees);
				//array_pop() Извлекает последний элемент массива. Извлекает и возвращает последнее значение параметра array, уменьшая размер array на один элемент
				$emp->fire();
			}
		}
	}

	$boss = new NastyBoss();
	$boss->addEmployee("Игорь");
	$boss->addEmployee("Владимир");
	$boss->addEmployee("Мария");
	$boss->projectFails();

	//Выводится:
	//Мария:убери со стола
	*/

	class NastyBoss {
		private $employees = array();

		function addEmployee (Employee $employee) {
			$this->employees[] = $employee;
		}

		function projectFails() {
			if(count($this->employees) > О) {
				$emp = array_pop($this->employees);
				//array_pop() Извлекает последний элемент массива. Извлекает и возвращает последнее значение параметра array, уменьшая размер array на один элемент
				$emp->fire();
			}
		}
	}

	/*
	$boss = new NastyBoss();
	$boss->addEmployee(new Minion("Игорь"));
	$boss->addEmployee(new CluedUp("Владимир"));
	$boss->addEmployee(new Minion("Мария"));
	$boss->projectFails();
	$boss->projectFails();
	$boss->projectFails();

	// Выводится:
	// Мария: убери со стола
	// Владимир: вызови адвоката
	// Игорь: убери со стола
	*/

	$boss = new NastyBoss();
	$boss->addEmployee(Employee::recruit("Игорь"));
	$boss->addEmployee(Employee::recruit("Владимир"));
	$boss->addEmployee(Employee::recruit("Мария"));
	$boss->projectFails();
?>