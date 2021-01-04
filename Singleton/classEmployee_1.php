<?php

		header('Content-Type: text/html; charset=utf-8');
	
		abstract class Employee {
			protected $name;

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

?>