<?php

	class ShopProduct {
		private $title;
		private $producerMainName;
		private $producerFirstName;
		protected $price;
		private $discount = 0;
		private $id = 0;

		public function __construct($title, $firstName, $mainName, $price) {
			$this->title             = $title;
			$this->producerFirstName = $firstName;
			$this->producerMainName  = $mainName;
			$this->price             = $price;
		}

		public static function setId($id) {
			$this->id = $id;
		}


		public static function getlnstance ($id, PDO $dbh) {
			$query = "select * from products where id = ?";
			$stmt = $dbh->prepare($query);
			if(!$stmt->execute(array($id)) {
				$error = $dЬh->errorinfo();
				die("Ошибка: " . $error[1]);
			}
			$row = $stmt->fetch();
			if(empty($row)){return null;}

			if($row['type'] == "book") {
				//Создадим объект типа BookProduct
				$product =new BookProduct()
			} else if($row['type'] == "cd") {
				//Создадим объект типа CDProduct
				$product = new CDProduct()
			} else {
				//Создадим объект типа ShopProduct
				$product = new ShopProduct()
			}
			$product->setid($row['id']);
			$product->setDiscount($row['discount']);
			return $product;
		}



		public function getProducerFirstName() {
			return $this->producerFirstName;
		}

		public function getProducerMainName() {
			return $this->producerMainName;
		}

		public function setDiscount($num) {
			$this->discount = $num;
		}

		public function getDiscount() {
			return $this->discount;
		}

		public function getTitle() {
			return $this->title;
		}

		public function getPrice() {
			return ($this->price - $this->discount);
		}

		public function getProducer() {
			return $this->producerFirstName . " " . $this->producerMainName;
		}

		public function getSummaryLine() {
			$base  = "{$this->title} ({$this->producerMainName}, ";
			$base .= "{$this->producerFirstName} )";
			return $base;
		}
	}

?>



