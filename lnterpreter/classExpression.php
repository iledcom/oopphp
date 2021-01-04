<?php
header('Content-Type: text/html; charset=utf-8');
/*
Давайте представим себе приложение для разработки викторин. Создатели придумывают вопросы и устанавливают правила оценки ответов конкурсантов. Существует требование, что ответы на вопросы викторины должны оцениваться без вмешательства человека, хотя некоторые ответы пользователи могут вводить в текстовые поля.

Вот пример вопроса.
Сколько членов в группе разработки проектных шаблонов?

Мы можем принимать "четыре" или "4" в качестве правильных ответов. Нам потребуется создать веб-интерфейс, позволяющий создателям использовать регулярное выражение для оценки ответов.
^4|четыре$

Но, к сожалению, не все создатели владеют регулярными выражениями. Поэтому, чтобы облегчить всем жизнь, мы можем реализовать более дружественный пользователю механизм оценки ответов.

$input equals "4" or $input equals "четыре"

Мы предлагаем язык, который поддерживает переменные, оператор equals и булеву логику (операторы or и and). 

Программисты любят все называть, поэтому давайте назовем наш язык MarkLogic. Этот язык должен легко расширяться, поскольку мы предвидим множество запросов на более сложные функции. Давайте пока оставим в стороне вопрос анализа входных данных и сосредоточимся на механизме подключения этих элементов вместе во время выполнения программы для
генерации ответа. Вот тут, как и можно было ожидать, нам и пригодится шаблон Interpreter.

Реализация
Наш язык состоит из выражений, вместо которых подставляются некоторые значения. Как видно из табл. 11.1 , даже в таком небольшом языке, как MarkLogic, необходимо отслеживать множество элементов.

Описание                        Имя ЕВNF               Имя класса                   Пример

Переменная                      variable               VariableExpression           $input

Строковый литерал               <stringLiteral>        LiteralExpression            "четыре"

Булево "И"                      andExpr                BooleanAndExpression          $input equals'4' and
                                                                                     $other equals '6'

Булево "Или"                    orExpr                 BooleanOrExpression           $input equals'4' and
                                                                                     $other equals '6'

Проверка равенства              equalsExpr             EqualsExpression              $input equals'4'


В таблице приведены имена EBNF. А что такое EBNF? Это - обозначение, которое можно использовать для описания грамматики языка. EBNF расшифровывается как "Extended Backus-Naur Form" - расширенная форма Бэкуса-Наура. Она
состоит из набора строк, которые называются продукциями (productions). Каждая продукция состоит из имени и описания, которое принимает форму ссылок на другие продукции и на терминалы (т.е. элементы, которые сами не состоят из ссылок на другие продукции). Вот как можно описать нашу грамматику с помощью EBNF.


expr :: = operand(orExpr 1 andExpr)*
operand :: = ('('expr')'1 <stringLi teral> 1 variaЫe) (eqExpr)*
orExpr :: = 'or' operand
andExpr :: = 'and' operand
eqExpr :: = 'equals' operand
variable :: = '$' <word>

Некоторые символы несут специальный смысл (они должны быть вам знакомы по регулярным выражениям), например '*' означает "нуль или больше", а '1' - "или". Группировать элементы можно с помощью скобок. Так, в приведенном примере выражение (expr) состоит из лексемы operand, за которой следует нуль или более лексем либо orExpr, либо andExpr. В качестве operand может быть выражение в скобках, строковая константа (я опустил продукцию для него) или переменная, за которой следует нуль или больше лексем eqExpr. Как только вы поймете, как ссылаться с одной продукции на другую. EBNF станет очень простой формой для прочтения.

Класс BooleanAndExpression и его "братья" наследуют класс OperatorExpression. Причина в том, что все эти классы выполняют операции над другими объектами типа Expression. Объекты Variable Expression и LiteralExpression оперируют непосредственно значениями.

Во всех объектах типа Expression реализован метод interpret(), который определен в абстрактном базовом классе Expression. Методу interpret() передается объект типа InterpreterContext, который используется как совместно используемое хранилище данных. Каждый объект типа Expression может сохранять данные в объекте типа InterpreterContext. Объект InterpreterContext затем передается другим объектам типа Expression. Поэтому, чтобы данные было легко извлекать из объекта InterpreterContext, в базовом классе Expression реализован метод getКеу(), возвращающий уникальный дескриптор. Давайте посмотрим, как это работает на практике, на примере реализации класса Expression.

*/
	abstract class Expression {
    private static $keycount=0;
    private $key;
    abstract function interpret(InterpreterContext $context);

		function getKey() {
        if ( ! isset( $this->key ) ) {
            self::$keycount++;
            $this->key=self::$keycount;
        }
        return $this->key;
    }
	}

	class LiteralExpression extends Expression {
    private $value;

    function __construct( $value ) {
        $this->value = $value;
    }

    function interpret(InterpreterContext $context) {
			$context->replace( $this, $this->value );
		}
	}

	class InterpreterContext {
		private $expressionstore = array();

		function replace( Expression $exp, $value ) {
			$this->expressionstore[$exp->getKey()] = $value;
		}

		function lookup(Expression $exp) {
			return $this->expressionstore[$exp->getKey()];
		}
	}

	$context = new InterpreterContext();
	$literal = new LiteralExpression('Четыре');
	$literal->interpret($context);
	print $context->lookup($literal);

	/*
	Начнем с класса InterpreterContext. Как видите, на самом деле он представляет собой только внешний интерфейс для ассоциативного массива $expressionstore, который мы используем для хранения данных. Методу replace() передаются ключ и значение, которые сохраняются в ассоциативном массиве $expressionstore. В качестве ключа используется объект типа Expression, а значение может быть любого типа. В классе InterpreterContext реализован также метод lookup() для извлечения данных. 

	В классе Expression определены абстрактный метод interpret() и конкретный метод getKey(), оперирующий статическим значением счетчика; оно и возвращается в качестве дескриптора выражения. Этот метод используется в методах InterpreterContext::lookup() и InterpreterContext::replace() для индексирования данных.

	В классе LiteralExpression определен конструктор, которому передается аргумент-значение. Методу interpret() нужно передать объект типа InterpreterContext. В нем просто вызывается метод replace() этого объекта, которому передаются ключ (ссылка на сам объект типа LiteralExpression) и значение $value.

	В методе replace() объекта InterpreterContext для определения численного значения ключа используется метод getKey(). По мере знакомства с другими классами типа Expression подобный шаблонный подход покажется вам знакомым. Метод interpret() всегда сохраняет свои результаты в объекте InterpreterContext. В приведенный выше пример включен также фрагмент клиентского кода, в котором создаются экземпляры объектов InterpreterContext и LiteralExpression (со значением 'Четыре'). Объект типа InterpreterContext передается методу LiteralExpression::interpret(). Метод interpret() сохраняет пару "ключ-значение" в объекте InterpreterContext, из которого мы затем извлекаем значение, вызывая метод lookup().

	Давайте определим оставшийся конечный класс. Класс VariableExpression немного сложнее.
*/

	class VariableExpression extends Expression {
    private $name;
    private $val;

    function __construct( $name, $val=null ) {
        $this->name = $name;
        $this->val = $val;
    }

    function interpret( InterpreterContext $context ) {
        if ( ! is_null( $this->val ) ) {
            $context->replace( $this, $this->val );
            $this->val = null;
        }
    }

    function setValue( $value ) {
        $this->val = $value;
    }

    function getKey() {
        return $this->name;
    }
 }

 	$context = new InterpreterContext();
	$myvar = new VariableExpression ('input', 'Четыре');
	$myvar->interpret($context);
	print $context->lookup($myvar) . "\n";
	// Выводится : 'Четыре'
	$newvar = new VariableExpression ('input');
	$newvar->interpret($context);
	print $context->lookup($newvar) . "\n";
	// Выводится : 'Четыре'
	$myvar->setValue("Пять");
	$myvar->interpret($context);
	print $context->lookup($myvar) . "\n";
	// Выводится : 'Пять'
	print $context->lookup($newvar) . "\n";
	// Выводится : 'Пять'

	/*
	Конструктору класса VariableExpression передаются два аргумента (имя и значение), которые сохраняются в свойствах объекта. В классе реализован метод setValue(), чтобы клиентский код мог изменить значение переменной в любое время.
	Метод interpret() проверяет, имеет ли свойство $val ненулевое значение. Если у свойства $val есть некоторое значение, то его значение сохраняется в объекте InterpreterContext. Затем мы устанавливаем для свойства $val значение null. Это делается для того, чтобы повторный вызов метода interpret() не испортил значение переменной с тем же именем, сохраненной в объекте InterpreterContext другим экземпляром объекта VariableExpression. Возможности нашей переменной достаточно ограничены, так как ей могут быть присвоены только строковые значения. Если бы мы собирались расширить наш язык, то нужно было бы сделать так, чтобы он работал с другими объектами типа Expression, содержащими результаты выполнения булевых и других операций. Но пока VariableExpression будет делать то, что нам от него нужно. Обратите внимание на то, что мы заменили метод getKey(), чтобы значения переменных были связаны с именем переменной, а не с произвольным статическим идентификатором.

	В нашем языке все операторные выражения работают с двумя другими объектами типа Expression. Поэтому есть смысл, чтобы они расширяли общий суперкласс.
	Вот определение класса OperatorExpression.
	*/


	abstract class OperatorExpression extends Expression {
    protected $l_op;
    protected $r_op;

    function __construct( Expression $l_op, Expression $r_op ) {
        $this->l_op = $l_op;
        $this->r_op = $r_op;
    }

    function interpret( InterpreterContext $context ) {
        $this->l_op->interpret( $context );
        $this->r_op->interpret( $context );
        $result_l = $context->lookup( $this->l_op );
        $result_r = $context->lookup( $this->r_op  );
        $this->doInterpret( $context, $result_l, $result_r );
    }

    protected abstract function doInterpret( InterpreterContext $context, 
                                             $result_l, 
                                             $result_r );
	}

	/*
	В абстрактном классе OperatorExpression реализован метод interpret(), а также определен абстрактный метод dointerpret().
	Конструктору этого класса передаются два объекта типа Expression для левого и правого операндов ($1_ор и $r_ор), которые он сохраняет в свойствах объекта.
	Выполнение метода interpret() начинается с вызовов методов interpret() для обоих операндов, сохраненных в свойствах (если вы читали предыдущую главу, то, наверное, заметили, что здесь мы воспользовались экземпляром шаблона Composite). После этого в методе interpret() определяются значения левого и правого операндов с помощью вызова метода InterpreterContext::lookup() для каждого из них. Затем вызывается метод dointerpret(), чтобы дочерние классы могли решить, какую именно операцию нужно выполнить над полученными значениями операндов.
*/


