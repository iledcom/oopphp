<?php 
header('Content-Type: text/html; charset=utf-8');
/*
Рассмотрим в качестве примера класс Preferences, в котором хранятся данные, используемые в процессе выполнения приложения. Мы можем использовать объект Preferencess для хранения таких параметров, как DSN-строки (т.е. строки, содержащие имена источников данных. в которых хранится информация о базе данных). URL сайта. пути к файлам и т.д. Очевидно, что подобная информация может меняться в зависимости от конкретной инсталляции. Данный объект может также использоваться, как доска объявлений (или центр для сообщений). которые размещаются или извлекаются объектами системы, в других отношениях не связанными с ним.

Но передавать объект Preferences от одного объекта к другому - это не всегда хорошо. Многие классы, которые в других отношениях не используют этот объект, будут вынуждены принимать его просто для того, чтобы передать объектам, с которыми они работают. В результате получаем просто другой вид тесной связи.

Мы также должны быть уверены, что все объекты в нашей системе работают с одним и тем же объектом Preferences. Нам не нужна ситуация, когда одни объекты устанавливают значения в каком-то объекте, в то время как другие считывают данные с совершенно другого объекта.

Давайте выделим действующие факторы данной проблемы.

• Объект Preferences должен быть доступен для любого объекта в системе.
• Объект Preferences не должен сохраняться в глобальной переменной, значение которой может быть случайно затерто.
• В системе не должно быть больше одного объекта Preferences. Это означает, что объект Z устанавливает свойство в объекте Preferences, а объект Z извлекает то же самое значение этого свойства, причем они не связываются один с другим непосредственно (мы предполагаем, что оба объекта имеют доступ к объекту Preferences).

Реализация
Чтобы решить эту проблему, начнем с установления контроля над созданием экземпляров объектов. Мы создадим класс, экземпляр которого нельзя создать за его пределами. Может показаться, что это трудно сделать, но на самом деле это просто вопрос определения закрытого конструктора.


	class Preferences {
		private $props array();

		private function _construct() {}

		public function setProperty($key, $val) {
			$this->props[$key] = $val;
		}

		public function getProperty($key) {
			return $this->props[$key];
		}
	}

	
Конечно, на данном этапе класс Preferences совершенно бесполезен. Мы довели ограничение доступа до уровня абсурда. Поскольку конструктор объявлен как private, никакой клиентский код не может создать на его основе экземпляр объекта. Поэтому методы setProperty() и getProperty() оказываются лишними.
Мы можем использовать статический метод и статическое свойство, чтобы создавать экземпляры объекта через посредника.
	*/

	class Preferences {
		private $props = array();
		private static $instance;

		private function __construct() {}

		public static function getInstance() {
			if(empty(self::$instance)) {
				//empty — Проверяет, пуста ли переменная
				self::$instance = new Preferences();
			}
			return self::$instance;
		}

		public function setProperty($key, $val) {
			$this->props[$key] = $val;
		}

		public function getProperty($key) {
			return $this->props[$key];
		}
	}

	/*
Свойство $instance - закрытое и статическое, поэтому к нему нельзя получить доступ из-за пределов класса. Но у метода getinstance() есть доступ к нему. Поскольку метод getinstance() - общедоступный и статический, его можно вызвать через класс из какого-либо места сценария.
	*/

	$pref = Preferences::getInstance();
	$pref->setProperty("name", "Иван");
	unset($pref); // Удалим ссылку
	$pref2 = Preferences::getInstance();
	//Убедимся, что ранее установленное значение сохранено
	print $pref2->getProperty("name") . "\n";

	

/*В результате будет выведено единственное значение параметра 'name', которое мы ранее добавили к объекту Preferences, воспользовавшись разными объектными переменными.

Иван

Статический метод не может получить доступ к свойствам объектов, потому что он по определению вызывается через класс, а не в контексте объекта. Но он может получить доступ к статическому свойству. Когда вызывается метод getinstance(), мы проверяем свойство Preferences::$instance. Если оно пусто, то создаем экземпляр класса Preferences и сохраняем его в свойстве. Затем мы возвращаем этот
экземпляр вызывающему коду. Поскольку статический метод getInstance() - это часть класса Preferences, у нас нет проблем с созданием экземпляра объекта Preferences, даже несмотря на то что конструктор закрытый.

Проблема в том, что глобальная природа шаблона Singleton позволяет программисту обойти каналы коммуникации, определенные интерфейсами класса. Когда используется Singleton, зависимость скрыта внутри метода и не объявляется в его сигнатуре. Это затрудняет отслеживание связей внутри системы. Поэтому классы Singleton должны использоваться редко и очень осторожно. 

Тем не менее я считаю, что умеренное использование шаблона Singleton может улучшить проект системы, избавив ее от излишнего загромождения при передаче ненужных объектов в системе.

Шаблоны Singleton - это шаг вперед по сравнению с использованием глобальных переменных в объектно-ориентированном контексте. Вы не сможете затереть объекты Singleton неправильными данными. Такой вид защиты особенно важен в версиях РНР, в которых нет поддержки пространства имен. Любой конфликт имен будет обнаружен на стадии компиляции, что приведет к завершению выполнения
программы.
*/