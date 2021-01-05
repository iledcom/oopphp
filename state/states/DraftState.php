<?php

class DraftState extends State {

	public function __construct(Document $doc) {
		parent::__construct($doc);
	}

	public function verify() {
		if ($this->doc->getSumma() > 0) {
			$this->doc->changeState(new ReviewState($this->doc));
		}
	}

	public function __toString() {
		return "Draft";
	}

	public function onEnterState($oldState) {
		if ($this->doc->getSumma() > 0) {
			$this->verify();
		}
	} 

} 


