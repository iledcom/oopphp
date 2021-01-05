<?php

class ApprovedState extends State {
	
	public function __construct(Document $doc) {
		parent::__construct($doc);
	}

	public function __toString() {
		return "Approved";
	}
	
}
