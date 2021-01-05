<?php

abstract class Document {

private $state;

public abstract function getSumma();

public function getState() { 
	return $this->state;
}

public function changeState(State $newState) { 
	$oldState = $this->state; 
	$this->state = $newState; 
	$this->state->onEnterState($oldState);
}

public function verify() { 
	$this->state->verify();
}

public function approve() { 
	$this->state->approve();
}

public function deny() { 
	$this->state->deny();
}

}

