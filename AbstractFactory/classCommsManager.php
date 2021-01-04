<?php 
header('Content-Type: text/html; charset=utf-8');
/*
	abstract class CommsManager {
		abstract function getHeaderText();
		abstract function getApptEncoder();
		abstract function getTtdEncoder();
		abstract function getContactEncoder();
		abstract function getFooterText();
	}

	class BloggsCommsManager extends CommsManager {

		function getHeaderText() {
			return "BloggsCal верхний колонтитул \n";
		}

		function getApptEncoder() {
			return new BloggsApptEncoder();
		}

		function getTtdEncoder() {
			return new BloggsTtdEncoder();
		}

		function getContactEncoder() {
			return new BloggsContactEncoder();
		}

		function getFooterText() {
			return "BloggsCal нижний колонтитул \n";
		}

	*/
		abstract class CommsManager {
			const АРРТ = 1;
			const TTD = 2;
			const CONTACT = 3;
			abstract function getHeaderText();
			abstract function make($flag_int);
			abstract function getFooterText();
		}

		class BloggsCommsManager extends CommsManager {

			function getHeaderText() {
				return "BloggsCal верхний колонтитул \n";
			}

			function make ($flag_int) {
				switch ($flag_int) {
					case self::АРРТ:
						return new BloggsApptEncoder();
					case self::CONTACT:
						return new BloggsContactEncoder();
					case self::TTD:
						return new BloggsTtdEncoder();
				}
			}

			function getFooterText(){
				return "BloggsCal нижний колонтитул \n";
			}
		}

		/*
			Как видите, мы сделали интерфейс класса более компактным. Но мы достаточно дорого за это заплатили. 
			Используя шаблон FactoryMethod, мы определяем четкий интерфейс и заставляем все конкретные объекты 
			фабрики подчиняться ему. Используя единственный метод make(), мы должны помнить о поддержке всех 
			объектов-продуктов во всех конкретных создателях. Мы также используем параллельные условные операторы, 
			поскольку в каждом конкретном создателе должны быть реализованы одинаковые проверки флага-аргумента. 
			Клиентский класс не может быть уверен, что конкретные создатели генерируют весь набор продуктов, 
			поскольку внутренняя организация метода make() может отличаться в каждом случае. С другой стороны, 
			мы можем построить более гибкие создатели. В базовом классе создателя можно предусмотреть метод make(), 
			который будет гарантировать стандартную реализацию для каждого семейства продуктов. И тогда конкретные 
			дочерние классы могут избирательно модифицировать это поведение. Реализующим создателя классам будет 
			предоставлено право выбора - вызывать стандартный метод make() после собственной реализации или нет.
		*/
