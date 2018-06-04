<?php
header('Content-Type: text/html; charset=utf-8');

	class RequestHelper{}

	abstract class ProcessRequest {
		abstract function process(RequestHelper $req);
	}

	class MainProcess extends ProcessRequest {
		function process(RequestHelper $req) {
			print __CLASS__ . ": выполнение запроса\n";
		}
	}

	abstract class DecorateProcess extends ProcessRequest {
		protected $processrequest;

		function __construct(ProcessRequest $pr) {
			$this->processrequest = $pr;
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
