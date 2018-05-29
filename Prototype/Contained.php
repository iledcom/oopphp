<?php

	class Contained {}

		class Container {
			public $contained;

			function __construct() {
				$this->contained = new Contained();
			}

			function __clone() {
				// Нужно сделать так, чтобы в клонируемом объекте был
				// именно клон объекта, содержащегося в свойстве self::$contained,
				// а не ссылка на него

				$this->contained = clone $this->contained;
			}
		}

		/*
		Можно сделать это вручную или написать сценарий, который автоматически генерирует файл класса. Вот пример класса, в который
	включен признак типа протокола календаря.

		class Settings {
			static $СОММSТУРЕ = 'Bloggs';
		}

	Теперь, когда у нас есть значение параметра (хотя и сделанного неэлегантно), можно создать класс, который использует его для принятия решения о том, какой объект типа CommsManager нужно предоставить по запросу. В таких ситуациях часто используют шаблон Singleton в сочетании с шаблоном Abstract Factory, поэтому давайте так и поступим.
		*/

		require_once ('Settings.php');
		require_once ('../../myprogect/Factory/abstractclassCommsManager.php');

		class AppConfig {
			private static $instance;
			private $commsManager;

			private function __construct() {
			//Выполняется только один раз
				$this->init();
			}
			private function init() {
				switch (Settings::$СОММSТУРЕ) {
					case'Mega':
						$this->commsManager = new MegaCommsManager();
						break;
					default:
						$this->commsManager = new BloggsCommsManager();
				}
			}

			public static function getInstance() {
				if(empty(self::$instance)) {
					self::$instance = new self();
				}
				return self::$instance;
			}
			
			public function getCommsManager() {
				return $this->commsManager;
			}
		}

		/*
		Класс AppConfig - это стандартный Singleton. Поэтому мы можем легко получить ссылку на экземпляр AppConfig в любом месте системы, причем всегда на один и тот же экземпляр. Метод init() вызывается конструктором класса и поэтому запускается только один раз в процессе выполнения программы. В нем проверяется свойство Settings::$СОММSТУРЕ, и в соответствии с его значением создается экземпляр конкретного объекта типа CommsManager. Теперь наш сценарий может получить объект типа CommsManager и работать с ним, даже не зная о его конкретных реализациях или конкретных классах, которые он генерирует.
		*/
		$commsMgr = AppConfig::getInstance()->getCommsManager();
		print $commsMgr->getApptEncoder()->encode();
