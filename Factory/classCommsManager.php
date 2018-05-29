<?php 
header('Content-Type: text/html; charset=utf-8');

	/*
		Нам нужен кодировщик данных, который преобразовывает объекты типа Appointment во внутренний формат BloggsCal. Давайте назовем этот класс ApptEncoder. Нам нужен управляющий класс, который будет выполнять поиск кодировщика и, возможно, работать с ним, чтобы осуществлять коммуникации с третьей стороной. Назовем этот класс CommsManager. Используя терминологию шаблона, можем сказать, что CommsManager - это создатель, а Appt Encoder - продукт.	

	*/

	abstract class ApptEncoder {
		abstract function encode();
	}
		
	class BloggsApptEncoder extends ApptEncoder {
		function encode() {
			return "Данные о встрече закодированы в формате BloggsCal \n";
		}
	}

	class MegaApptEncoder extends ApptEncoder {
		function encode() {
			return "Данные о встрече закодированы в формате MegaCal \n";
		}
	}

	/*
	class CommsManager {
		function getApptEncoder() {
			return new BloggsApptEncoder();
		}
	}

	
	Класс CommsManager отвечает за генерацию объектов B loggsApptEncoder. Теперь, когда нас попросят преобразовать нашу систему для работы с новым форматом MegaCal, мы просто добавим условный оператор в метод CommsManager::getApptEncoder(). В конце концов, это стратегия, которую мы использовали в прошлом.
	Давайте создадим новую реализацию CommsManager, которая будет работать с обоими форматами : BloggsCal и MegaCal.



	class CommsManager {
		const BLOGGS = 1;
		const MEGA = 2;
		private $mode = 1;

		function __construct($mode) {
			$this->mode = $mode;
		}

		function getApptEncoder() {
			switch($this->mode) {
				case(self::MEGA):
				return new MegaApptEncoder();
			default:
				return new BloggsApptEncoder();
			}
		}
	}
		$comms = new CommsManager(CommsManager::MEGA);
		$apptEncoder = $comms->getApptEncoder();
		print $apptEncoder->encode();

	
	В качестве флажков, определяющих два режима, в которых может работать сценарий,	мы используем константы класса MEGA и BLOGGS. В методе getApptEncoder() используется оператор switch, чтобы протестировать свойство $mode и создать экземпляр соответствующей реализации класса ApptEncoder.
	Однако описанный подход не совсем удачен. Использование условных операторов иногда считается дурным тоном, но при создании объектов часто приходится их применять в определенный момент. Но не стоит слишком оптимистично относиться к тому, что в коде один за другим дублируются условные операторы. Класс CommsManager обеспечивает функции для передачи календарных данных. Предположим, что в используемом нами протоколе нужно вывести данные в верхний и нижний колонтитулы, чтобы очертить границы каждой встречи. Давайте расширим предыдущий пример и создадим метод getHeaderText().

	*/

		class CommsManager {
		const BLOGGS = l;
		const MEGA = 2;
		private $mode = l;

		function __construct($mode) {
			$this->mode = $mode;
		}

		function getHeaderText() {
			switch ($this->mode) {
				case (self::MEGA) :
					return "MegaCal верхний колонтитул \n";
				default:
					return "BloggsCal верхний колонтитул \n";
			}
		}

		function getApptEncoder() {
			switch($this->mode) {
				case(self::MEGA):
				return new MegaApptEncoder();
			default:
				return new BloggsApptEncoder();
			}
		}
	}
		$comms = new CommsManager(CommsManager::MEGA);
		$apptEncoder = $comms->getHeaderText();
		print $apptEncoder;

	/*
	Как видите, необходимость в поддержке выходных данных верхнего колонтитула заставила нас продублировать проверку типа протокола с помощью оператора switch. Но по мере добавления новых протоколов код становится громоздким, особенно если мы еще добавим метод getFooterText().

	Итак, подытожим основные моменты проблемы.
	• До момента выполнения программы мы не знаем, какой вид объекта нам понадобится создать (B loggsApptEncoder или MegaApptEncoder) .
	• Мы должны иметь возможность достаточно просто добавлять новые типы объектов (например, следующее требование бизнеса - поддержка протокола SyncML).
	• Каждый тип продукта связан с контекстом, который требует других специализированных операций ( getHeaderText(), getFooterText() ).
	Кроме того, нужно отметить, что мы используем условные операторы, и мы уже видели, что их можно заменить полиморфизмом. Шаблон Factory Method позволяет использовать наследование и полиморфизм, чтобы инкапсулировать создание конкретных продуктов. Другими словами, для каждого протокола создается свой подкласс типа CommsManager, в котором реализован свой метод getApptEncoder().

		Реализация
	В шаблоне Factory Method классы создателей отделены от продуктов, которые они должны генерировать. Создатель - это класс фабрики, в котором определен метод для генерации объекта-продукта. Если стандартной реализации этого метода не предусмотрено, то создание экземпляров объектов оставляют дочерним классам создателя. Обычно в каждом подклассе создателя создается экземпляр параллельного
	дочернего класса продукта.
		Давайте переопределим класс CommsManager в виде абстрактного класса. Таким образом мы сохраним гибкий cyпepкласс и поместим весь наш код, связанный с конкретным протоколом, в отдельные подклассы.
*/

		