<?php 
header('Content-Type: text/html; charset=utf-8');

/*
При использовании шаблона Factory Method проблемой может стать появление параллельных иерархий наследования. Этот вид тесной связи вызывает у некоторых программистов чувство дискомфорта. Каждый раз при добавлении нового семейства продуктов вы вынуждены создавать связанного с ним конкретного создателя (например, кодировщикам ВloggsCal соответствует BloggsCommsManager).
В системе, которая быстро расширяется и включает в себя много продуктов, поддержание связи такого рода может стать очень утомительным.
Один из способов избежать этой зависимости - использовать ключевое слово РНР clone для дублирования существующих конкретных продуктов. Затем конкретные классы продуктов сами станут основой для их собственной генерации. Ниже мы описали шаблон Prototype.  который позволяет заменить наследование композицией. Такой подход, в свою очередь, способствует гибкости во время выполнения
программы и сокращает количество классов, которые мы должны создать.

Работая с шаблонами Abstract Factory и Factory Method, мы должны решить в определенный момент, с каким конкретно создателем хотим работать. Вероятно, это можно осуществить путем анализа значения некоторого флага. Поскольку так или иначе мы должны это сделать, почему бы просто не создать класс фабрики, хранящий конкретные продукты и размножающий их во время инициализации?
Таким образом мы можем избавиться от нескольких классов и, как мы вскоре увидим, воспользоваться другими преимуществами. Приведем пример простого кода, в котором в качестве фабрики используется шаблон Prototype.


class Sea {}
class EarthSea extends Sea {}
class MarsSea extends Sea {}
class Plains {}
class EarthPlains extends Plains {}
class MarsPlains extends Plains {}
class Forest {}
class EarthForest extends Forest {}
class MarsForest extends Forest {}

class TerrainFactory {
	private $sea;
	private $forest;
	private $plains;

	function __construct (Sea $sea, Plains $plains, Forest $forest) {
		$this->sea = $sea;
		$this->plains = $plains;
		$this->forest = $forest;
	}

	function getSea() {
		return clone $this->sea;
	}

	function getPlains() {
		return clone $this->plains;
	}

	function getForest() {
		return clone $this->forest;
	}
}

	$factory = new TerrainFactory(new EarthSea(),
																new EarthPlains(),
																new EarthForest());

	print_r ($factory->getSea());
	print_r ($factory->getPlains());
	print_r ($factory->getForest());



	Как видите, здесь мы загружаем в экземпляр конкретной фабрики типа TerrainFactory экземпляры объектов наших продуктов. Когда в клиентском коде вызывается метод getSea(), ему возвращается клон объекта Sea, который мы поместили в кеш во время инициализации. В результате мы не только сократили пару классов, но и достигли определенной гибкости. Хотите, чтобы игра происходила на новой планете с морями и лесами, как на Земле, и с равнинами, как на Марсе? Для этого не нужно писать новый класс создателя - достаточно просто изменить набор классов, который мы добавляем в TerrainFactory.

	$factory = new TerrainFactory(new EarthSea(),
																new MarsPlains(),
																new EarthForest());

	Итак шаблон Prototype позволяет пользоваться преимуществами гибкости, которые предоставляет композиция. Но мы получили нечто большее. Поскольку мы сохраняем и клонируем объекты во время выполнения, воспроизводим состояние объектов, когда генерируем новые продукты. Предположим, у объектов Sea есть свойство $navigaЬility (судоходность). От него зависит количество энергии движения,
	которое клетка моря отнимает у судна. С помощью этого свойства можно регулировать уровень сложности игры.


	class Sea {
		private $navigability = О;

		function construct ($navigability) {
			$this->navigability = $navigability;
		}
	}

	Теперь, во время инициализации объекта TerrainFactory, можно добавить объект 	Sea с модификатором судоходности. Затем это будет иметь силу для всех объектов Sea, создаваемых с помощью TerrainFactory.

	$factory = new TerrainFactory(new EarthSea(-1),
																new EarthPlains(),
																new EarthForest());
	

	*/

	class Sea {
		private $navigability = О;

		function __construct ($navigability) {
			$this->navigability = $navigability;
		}
	}
		class EarthSea extends Sea {}
		class MarsSea extends Sea {}
		class Plains {}
		class EarthPlains extends Plains {}
		class MarsPlains extends Plains {}
		class Forest {}
		class EarthForest extends Forest {}
		class MarsForest extends Forest {}

		class TerrainFactory {
			private $sea;
			private $forest;
			private $plains;

			function __construct (Sea $sea, Plains $plains, Forest $forest) {
				$this->sea = $sea;
				$this->plains = $plains;
				$this->forest = $forest;
			}

			function getSea() {
				return clone $this->sea;
			}

			function getPlains() {
				return clone $this->plains;
			}

			function getForest() {
				return clone $this->forest;
			}
		}

		$factory = new TerrainFactory(new EarthSea(-1),
																	new EarthPlains(),
																	new EarthForest());

		print_r ($factory->getSea());
		print_r ($factory->getPlains());
		print_r ($factory->getForest());


	/*															

	Эта гибкость также становится очевидной, когда объект, который нужно сгенерировать, состоит из других объектов. Например, все объекты типа Sea могут содержать объекты типа Resource (FishResource. OilResource и т.д.). В соответствии со значением свойства мы можем определить, что по умолчанию все объекты Sea содержат объекты типа FishResource. Но помните, что если в ваших продуктах имеются ссылки на другие объекты, вы можете реализовать метод __clone(), чтобы корректно можно было сделать глубокую копию этого продукта.
*/

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
	require_once ('../../myprogect/factory/classCommsManager.php');
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
	Класс AppConfig - это стандартный Singleton. Поэтому мы можем легко получить ссылку на экземпляр AppConfig в любом месте системы, причем всегда на один и тот же экземпляр. Метод init() вызывается конструктором класса и поэтому запускается только один раз в процессе выполнения программы. В нем проверяется свойство Settings::$СОММSТУРЕ, и в соответствии с его значением создается экземпляр конкретного объекта типа CoпunsManager. Теперь наш сценарий может получить объект типа CoпunsManager и работать с ним, даже не зная о его конкретных реализациях или конкретных классах, которые он генерирует.
	*/
	$commsMgr = AppConfig::getInstance()->getCommsManager();
	print $commsMgr->getApptEncoder()->encode();
