<?php

/*
	Шаблон Decorator
	В то время как шаблон Composite помогает создать гибкое представление из набора компонентов, шаблон Decorator использует сходную структуру, чтобы помочь модифицировать функции конкретных компонентов. И опять-таки, основа этого шаблона - важность композиции во время выполнения. Наследование - это хороший способ построения характеристик, определяемых родительским классом. Но это может привести к жесткому кодированию вариантов в иерархиях наследования, что, как правило, приводит к потере гибкости.

	Проблема
	Встраивание всех функций в структуру наследования может привести к бурному росту классов в системе. Хуже того, попытавшись применить аналогичные изменения к разным ветвям дерева наследования, скорее всего, вы увидите, что появляется дублирование.

	Давайте вернемся к нашей игре. Определим класс Tile и его производный тип.

*/

	abstract class Tile {
		abstract function getWealthFactor();
	}

	class Plains extends Tile {
		private $wealthfactor = 2;

		function getWealthFactor() {
			return $this->wealthfactor;
		}
	}

	/*
	Мы определили класс Tile, представляющий собой квадрат, в котором могут находиться боевые единицы. У каждой клетки есть определенные характеристики. В данном примере мы определили метод getWealthFactor() , влияющий на доход, который может генерировать определенная клетка, если ею владеет игрок. Как видите, у объектов типа Plains коэффициент богатства равен 2. Но, конечно, в клетках могут быть и другие характеристики. Они также могут хранить ссылку на информацию об изображении так, чтобы игровое поле можно было нарисовать. И снова постараемся все сделать просто.

	Нам нужно модифицировать поведение объекта Plains (равнины), чтобы работать с информацией о природных ресурсах и губительном воздействии на природу человеческого фактора. Мы хотим смоделировать месторождения алмазов на местности, а также вред, наносимый загрязнением окружающей среды. Один из подходов - воспользоваться наследованием от объекта Plains.
*/

	class DiamondPlains extends Plains {
		function getWealthFactor() {
			return parent::getWealthFactor() + 2;
		}
	}

	class PollutedPlains extends Plains {
		function getWealthFactor () {
			return parent::getWealthFactor() - 4;
		}
	}

	//Теперь можно легко получить загрязненную клетку.

	$tile = new PollutedPlains();
	print $tile->getWealthFactor();

	/*
	Очевидно, что этой структуре недостает гибкости. Мы можем получить равнины с алмазами. Мы можем получить загрязненные равнины. Но можно ли получить и то, и другое? Очевидно, нет, если только мы не хотим создать нечто ужасное вроде PollutedDiamondPlains. Ситуация становится еще хуже, когда мы вводим класс Forest, в котором тоже могут быть алмазы и загрязнения. 

	Конечно, это пример самого крайнего случая, но он хорошо иллюстрирует суть дела. Если при определении функций полагаться только на наследование, это приведет к увеличению количества классов и появится тенденция дублирования.

	А теперь давайте рассмотрим дежурный пример. Серьезным веб-приложениям обычно нужно выполнить ряд действий после поступления запроса, до того как инициируется задача для формирования ответа на запрос. Например, нужно аутентифицировать пользователя и зарегистрировать запрос в журнале. Вероятно, мы должны как-то обработать запрос, чтобы создать структуру данных на основе необработанных исходных данных. И наконец мы должны осуществить основную обработку данных. Перед нами встала та же проблема. Мы можем расширить функциональность на основе класса ProcessRequest с дополнительной обработкой в производном классе LogRequest, в классе StructureRequest и в классе AuthenticateRequest. 

	Но что произойдет, если нам нужно будет осуществить запись в журнальный файл и аутентификацию, но не подготовку данных? Мы создадим класс LogAndAuthenticateProcessor? Очевидно, настало время найти более гибкое решение.

	Реализация 
	Вместо того чтобы для решения проблемы меняющейся функциональности использовать только наследование, в шаблоне Decorator используются композиция и делегирование. В сущности, в классах Decorator хранится экземпляр другого класса его собственного типа. В классе Decorator реализуется собственно процесс выполнения операции и вызывается аналогичная операция на объекте, на который у него есть ссылка (до или после выполнения собственных действий). Таким образом, во время выполнения программы можно создать конвейер объектов типа Decorator.

	Чтобы проиллюстрировать это, давайте перепишем наш пример с игрой.
*/

	abstract class Tile {
		abstract function getWealthFactor();
	}

	class Plains extends Tile {
		private $wealth factor = 2;

		function getWealthFactor() {
			return $this->wealthfactor;
		}
	}

	abstract class TileDecorator extends Tile {
		protected $tile;

		function __construct(Tile $tile) {
			$this->tile = $tile;
		}
	}

	/*
	Итак, мы объявили классы Tile и Plains, как раньше, и ввели новый класс TileDecorator. В нем не реализуется метод getWealthFactor(), поэтому он должен быть объявлен абстрактным. Мы определили конструктор, которому передается объект типа Tile, и ссылка на него сохраняется в свойстве $tile. Мы сделали это свойство зашищенным, т.е. protected, чтобы дочерние классы могли получать к нему доступ. 
	Давайте переопределим классы Pollution и Diamond.
*/

	class DiamondDecorator extends TileDecorator {
		function getWealthFactor() {
			return $this->tile->getWealthFactor() +2;
		}
	}

	class PollutionDecorator extends TileDecorator {
		function getWealthFactor() {
			return $this->tile->getWealthFactor() - 4;
		}
	}

	/*
	Каждый из этих классов расширяет TileDecorator. Это означает, что у них есть ссылка на объект типа Tile. Когда вызывается метод getWealthFactor(), каждый из этих классов сначала вызывает такой же метод у объекта типа Tile, а затем выполняет собственную корректировку значения.
	Используя композицию и делегирование подобным образом, мы легко можем комбинировать объекты во время выполнения. Поскольку все объекты в шаблоне расширяют класс Tile, клиентскому коду не нужно знать, с какой комбинацией объектов он работает. Можно быть уверенным, что метод getWealthFactor() доступен для любого объекта типа Tile, независимо от того, перекрыт он "за сценой" другим
	объектом или нет.
	*/

	$tile = new Plains();
	print $tile->getWealthFactor(); 


	//Возвращается 2
	//Поскольку класс типа Plains является компонентом системы, он просто возвращает значение 2 .


	$tile = new DiamondDecorator(new Plains());
	print $tile->getWealthFactor(); 

	// Возвращается 4

	/*
	В объекте типа DiamondDecorator хранится ссылка на объект типа Plains. Перед прибавлением собственного значения 2 он вызывает метод getWealthFactor() объекта типа Plains.
	*/

	$tile = new PollutionDecorator (
							new DiamondDecorator(new Plains()));
	print $tile->getWealthFactor(); 
	// Возвращается О

	/*

	В объекте типа PollutionDecorator хранится ссылка на объект типа DiamondDecorator, а у того - собственная ссылка на другой объект типа Tile.

	Эту модель очень легко расширять. Вы можете очень легко добавлять новые объекты Decorator и компоненты. Имея множество объектов Decorator, можно строить очень гибкие структуры во время выполнения программы. Класс компонентов системы (Plains в данном примере) можно существенно модифицировать совершенно разными способами, при этом не встраивая всю совокупность модификаций в иерархию классов. Проще говоря, это означает, что можно создать загрязненную равнину (объект Plains) с месторождением алмазов, не создавая при этом объект PollutedDiamondPlains.
	В шаблоне Decorator строятся конвейеры, которые очень удобны для создания фильтров. В пакете jаva.io очень эффективно используются классы Decorator.

	Программист, разрабатывающий клиентский код, может комбинировать объекты Decorator с основными компонентами, чтобы добавить фильтрацию, буферизацию, сжатие и так далее к основным методам. таким как read(). Наш пример веб-запроса также может быть преобразован в конфигурируемый конвейер. Вот пример простой реализации, в которой используется шаблон Decorator.
	*/

	class RequestHelper{}

	abstract class ProcessRequest {
		abstract function process(RequestHelper $req);
	}

	class MainProcess extends ProcessRequest {
		function process(RequestHelper $req) {
			print __CLASS__ . ": выполнение запроса\n";
		}
	}

	abstract classDecorateProcess extends ProcessRequest {
		protected $processrequest;

		function __construct(ProcessRequest $pr) {
			$this->process request = $pr;
		}
	}

	/*
	Как и раньше, мы определяем абстрактный суперкласс (ProcessRequest), конкретный компонент (MainProcess) и абстрактный декоратор (DecorateProcess).
	Метод MainProcess::process() не делает ничего, только сообщает, что он был вызван. В классе DecorateProcess сохраняется ссылка на объект типа Process Request от имени его дочерних объектов. Вот примеры простых классов конкретных декораторов.
*/

	class LogRequest extends DecorateProcess {
		function process(RequestHelper $req) {
			print __CLASS__ . ": регистрация запроса \n";
			$this->processrequest->process($req);
		}
	}

	class AuthenticateRequest extends DecorateProcess {
		function process (RequestHelper $req) {
			print __CLASS__ . ": аутентификация запроса \n";
			$this->processrequest->process($req);
		}
	}

	class StructureRequest extends DecorateProcess {
		function process(RequestHelper $req) {
			print __CLASS__ . ": упорядочение данных запроса \n";
			$this->processrequest->process($req);
		}
	}

	/*
	Каждый метод process() выводит сообщение, прежде чем вызвать собственный метод process() объекта ProcessRequest, на который ссылаются. Теперь можно комбинировать объекты-экземпляры этих классов во время выполнения программы, чтобы создать фильтры, выполняющие различные действия по запросу, причем в различном порядке. Вот пример кода комбинирования объектов из всех этих конкретных классов в одном фильтре.
*/

	$process = new AuthenticateRequest(
						 	new StructureRequest(
						 		new LogRequest(
						 			new MainProcess()
					 	 )));
	$process->process(new RequestHelper());

/*

Выводы
Как и шаблон Composite, шаблон Decorator может показаться сложным для понимания. Важно помнить, что и композиция, и наследование вступают в действие одновременно. Поэтому LogRequest наследует свой интерфейс от ProcessRequest, но, в свою очередь, выступает в качестве оболочки для другого объекта типа ProcessRequest.

Поскольку объект-декоратор формирует оболочку вокруг дочернего объекта, очень важно поддерживать интерфейс настолько неплотным, насколько это возможно. Если мы создадим базовый класс с множеством функций, то объекты-декораторы вынуждены будут делегировать эти функции всем общедоступным методам в объекте, который они содержат. Это можно сделать в абстрактном классе-декораторе, но в результате получится такая тесная связь, которая может привести к ошибкам.

Некоторые программисты создают декораторы, не разделяющие общий тип с объектами, которые они модифицируют. Пока они работают в рамках того же интерфейса, что и данные объекты, описанная стратегия эффективна. При этом можно извлекать преимущества из того, что есть возможность использовать встроенные методы-перехватчики для автоматизации делегирования (реализуя метод _ call() для перехвата вызовов несуществующих методов и вызывая этот же метод на дочернем объекте автоматически). Но в результате вы теряете в безопасности, которую обеспечивает проверка типа класса. До сих пор в наших примерах клиентский код в своем списке аргументов мог требовать объект типа Tile или ProcessRequest и быть уверенным в его интерфейсе независимо от того, является ли этот объект сильно
"декорированным".
*/
