<?php 

require_once 'states/DraftState.php';

class Invoice extends Document {

private $summa;
private $contragent;

public function __construct(int $summa, String $contragent) {
	$this->summa = $summa;
	$this->contragent = $contragent;
	$this->changeState(new DraftState($this));
}

public function getSumma() {
	return $this->summa;
}

public function getContragent() {
	return $this->contragent;
}

public function __toString() {
	return $this->getContragent().' : '.$this->getSumma().' : '.$this->getState();
}


}


