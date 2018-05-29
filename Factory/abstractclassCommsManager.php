<?php 
header('Content-Type: text/html; charset=utf-8');

	/*
		Давайте переопределим класс Coпunsмanager в виде абстрактного класса. Таким образом мы сохраним гибкий cyпepклacc и поместим весь наш код, связанный с конкретным протоколом, в отдельные подклассы.	

	*/

	abstract class ApptEncoder {
		abstract function encode();
	}
		
	class BloggsApptEncoder extends ApptEncoder {
		function encode() {
			return "Данные о встрече закодированы в формате BloggsCal \n";
		}
	}

	abstract class CommsManager {
		abstract function getHeaderText();
		abstract function getApptEncoder();
		abstract function getFooterText();
	}

	class BloggsCommsManager extends CommsManager {
		function getHeaderText() {
			return "BloggsCal верхний колонтитул \n";
		}

		function getApptEncoder() {
			return new BloggsApptEncoder();
		}

		function getFooterText() {
			return "BloggsCal нижний колонтитул \n";
		}
	}

/*
	$mgr = new BloggsCommsManager();
	print $mgr->getHeaderText() . ('<br>');
	print $mgr->getApptEncoder()->encode() . ('<br>');
	print $mgr->getFooterText() . ('<br>');

	
		BloggsCal верхний колонтитул 
		Данные о встрече закодированы в формате BloggsCal 
		BloggsCal нижний колонтитул

		Метод BloggsCommsManager::getApptEncoder() возвращает объект типа BloggsApptEncoder. Клиентский код, вызывающий getApptEncoder(), ожидает получить объект типа ApptEncoder и необязательно должен знать что-либо о конкретном классе продукта, который он получил. В некоторых языках программирования жестко оговариваются типы, возвращаемые методом. Поэтому клиентский код вызывающий такой метод, как getApptEncoder(), может быть абсолютно уверен, что он получит объект типа ApptEncoder. В РНР 5 это вопрос соглашения. Поэтому очень важно документировать возвращаемые типы либо сообщать о них с помощью соглашений о наименовании.
	*/