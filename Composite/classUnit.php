<?php

	abstract class Unit {
		abstract function bomЬardStrength();
	}

	class Archer extends Unit {
		function bomЬardStrength() {
			return 4;
		}

	class LaserCannonUnit extends Unit {
		function bomЬardStrength(){
			return 44;
		}
	}