<?php 
require_once 'states/ReviewState.php';

abstract class State {

	protected $doc;

	public function __construct(Document $doc) {
		$this->doc = $doc;
	}

	public function onEnterState($oldState) {
		echo $this->oldState . ' -> ' . $this . '<br>';
	}
	// actions
	public function verify() {}
	public function approve() {}
	public function deny() {}

}
