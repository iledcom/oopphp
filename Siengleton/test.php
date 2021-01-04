<?php

require 'singleton.php';

$s1 = singleton::getInstance();
$s2 = singleton::getInstance();

echo $s1->data;

echo $s2->data;