<?php
header('Content-Type: text/html; charset=utf-8');

	abstract class Tile {
		abstract function getWealthFactor();
	}

	class Plains extends Tile {
		private $wealthfactor = 2;

		function getWealthFactor() {
			return $this->wealthfactor;
		}
	}

	abstract class TileDecorator extends Tile {
		protected $tile;

		function __construct(Tile $tile) {
			$this->tile = $tile;
		}
	}

	class DiamondDecorator extends TileDecorator {
		function getWealthFactor() {
			return $this->tile->getWealthFactor() +2;
		}
	}

	class PollutionDecorator extends TileDecorator {
		function getWealthFactor() {
			return $this->tile->getWealthFactor() - 4;
		}
	}

	$tile = new Plains();
	print $tile->getWealthFactor() . "<br>";

	//Возвращается 2
	//Поскольку класс типа Plains является компонентом системы, он просто возвращает значение 2 .



	$tile = new DiamondDecorator(new Plains());
	print $tile->getWealthFactor() . "<br>";

	// Возвращается 4

	$tile = new PollutionDecorator (
						new DiamondDecorator(new Plains()));
	print $tile->getWealthFactor();
	// Возвращается О