<?php

$Result = array( 'title' => 'Types' );

// include_once( "lib/eztemplate/classes/eztemplate.php" );
// include_once( "lib/eztemplate/classes/eztemplatedelimitfunction.php" );

// include_once( "lib/ezlocale/classes/ezlocale.php" );

print( "<h1>Types</h1>
<p>eZ template supports different kinds of variable types. Some types can be input directly
in the template while others require operators* to create them, this also means that it's possible to
create custom types. Directly input types are numeric values, strings and variables, while operator
created types are boolean and arrays. For custom types objects are used, meaning that it's up to operators
to create proper objects and up the objects to know how to represent themselves.</p>

<h2>Numerical values</h2>
<p>
Numerical values are either integers or floats, they are input as normal numbers.
</p>
<pre class=\"example\">
{42}
{1.5}
{1000.98}
{-200}
</pre>

<h2>Strings</h2>
<p>
Strings are input by enclosing the text in either ' or \"** quotes, if you don't do this, they will either
be interpreted as identifiers or function names. If you need to use a quote inside the string you can
either switch to the other enclosing quote type or backslash the character with a \\.
The <i>}</i> character must also be backslashed.
</p>
<p>
Strings do not support variable expansion as they do in PHP, to create strings with text and variables
you must use the <i>concat</i> operator.
</p>
<pre class=\"example\">
{'this is some text'}
{\"again some more text\"}
{'quoted \"text\"'}
{\"single 'quoted'\"}
{'mixing \\'quote\\' \"types\" {still inside string\}'}
</pre>

<h2>Booleans</h2>
<p>
Booleans are either true or false, and must be input using either the <i>true()</i> or <i>false()</i>
operators.
It's possible to use integers as booleans for some operators and functions but they are not real booleans,
0 is false and any other value is true***. Booleans have several operators that can be used to modify
them, see logical operators.
</p>
<pre class=\"example\">
{true()}
{false()}
</pre>

<h2>Arrays</h2>
<p>
Arrays are containers that can contain any other type including arrays. Arrays may be simple vectors
where items are accessed with numbers, or they can be a hash map (also known as associative array) which
means lookup is done with strings.
Elements are fetched using attribute lookup, the attribute is either a number or an identifier (string).
Arrays must be created with the <i>array</i> operator or set from PHP code, associative arrays are created
with the <i>hash</i> operator.
</p>
<pre class=\"example\">
{array(1,2,5)}
{array('this','is','a','test')}
{hash('red',1,'blue',2,'green',3)}
</pre>

<h2>Objects</h2>
<p>
Objects are not a specific type but are created from classes by PHP code or by special operators. By using objects
it's possible to do more advanced template handling, for instance objects may contain state information
or other required bookkeeping. Objects may also sometimes inter-operate with associative arrays since they
support lookup using identifiers. Identifier lookup for objects is entirely up to the specific object,
it's only required that the class creates a couple of functions. See the objects page for information on
creating your own objects.
</p>

<h2>Variables</h2>
<p>
Variables are containers for other types. Variables allow for dynamic content and are often supplied by PHP code for
templates to display or handle. The variable consists of an identifier name and a namespace, the variable starts with
a <b>$</b> and namespaces are delimited with <b>:</b>. See namespaces for more information on how they work.
</p>
<pre class=\"example\">
{\$number}
{\$my_var}
{\$Name:Space:number}
</pre>

<p class=\"footnote\">* See operator example for more information.</p>
<p class=\"footnote\">** This decision was made to clearly distinguish between strings and functions.</p>
<p class=\"footnote\">*** Some also support arrays, where an empty array means false.</p>
" );


// $locale =& eZLocale::instance();

// // Init template
// $tpl =& eZTemplate::instance();
// $tpl->setShowDetails( true );

// $tpl->registerFunctions( new eZTemplateDelimitFunction() );

// $tpl->setVariable( "var1", "var1" );
// $tpl->setVariable( "var1", "var2", "Global" );
// $tpl->setVariable( "var1", "var3", "eZ:Global" );
// $tpl->setVariable( "var2", array( "a" => "var2.a",
//                                   "b" => "var2.b" ) );
// $tpl->setVariable( "var3", array( "a" => array( "b" => "var3.a.b" ) ) );
// $tpl->setVariable( "var4", "a" );

// // Class to show how object attributes work
// class tmp_class
// {
//     function hasAttribute( $attr )
//     {
//         return $attr == "a" or $attr == "b";
//     }

//     function attribute( $attr )
//     {
//         if ( $attr == "a" )
//             return "obj1.a";
//         else if ( $attr == "b" )
//             return "obj1.b";
//         else
//             return null;
//     }
// };

// $tpl->setVariable( "obj1", new tmp_class() );

// $tpl->display( "lib/eztemplate/sdk/templates/types.tpl" );

// eZDebug::addTimingPoint( "Template end" );

?>
