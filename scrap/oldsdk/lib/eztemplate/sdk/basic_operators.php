<?php

// include_once( "lib/eztemplate/classes/eztemplate.php" );
// include_once( "lib/eztemplate/classes/eztemplateattributeoperator.php" );
// include_once( "lib/eztemplate/classes/eztemplateunitoperator.php" );
// include_once( "lib/eztemplate/classes/eztemplatearrayoperator.php" );
// include_once( "lib/eztemplate/classes/eztemplatelogicoperator.php" );

// include_once( "lib/eztemplate/classes/eztemplatesectionfunction.php" );
// include_once( "lib/eztemplate/classes/eztemplatedelimitfunction.php" );
// include_once( "lib/eztemplate/classes/eztemplateincludefunction.php" );
// include_once( "lib/eztemplate/classes/eztemplateswitchfunction.php" );

// include_once( "lib/ezlocale/classes/ezlocale.php" );

print( "<h1>Operators</h1>
<p><img src='/doc/images/operator.png'/></p>

<p>Operators allow the template engine to modify variable types or create new values.
Operators receive data input from the left and output the result to the right. How the
operator modifies the data is up to each operator. For instance, some operators behave differently
depending on whether the input data is an array or not, while others may ignore the input and create an
output variable.</p>

<p>The operators can also take extra parameters separated by ',' (comma) and enclosed in '(' and
')' (brackets). The parameters can be constants, variables or operators which means that you can nest
operators. For instance, there's no builtin syntax for creating booleans or arrays, instead you use
an operator. <i>true()</i> creates a boolean with a true value and <i>false()</i> creates a boolean with a
false value. <i>array()</i> takes all its parameters and creates an array out of it.</p>

<p>The general syntax for an operator is: <b>{input|operator(parameter1,parameter2...)}</b> Remember that both the input
and the parameters can be operators. You could for instance write: {cond(\$b|ne(0),div(\$a,\$b),0)} This example will divide \$a by \$b and return the result. If \$b is null, however, it will not attempt to divide, and return null instead.</p>

<p>If you want to send input to the operator, then the input and the operator must be separated by '|' (the pipe symbol).
If for instance you are calling an operator that takes three parameters, and you want to omit the second parameter, just write nothing at that position, e.g. {operator(parameter1,,parameter2)}.
</p>

<p>Some operators work only on the logical level, meaning that they only see values as true or false.
For consistency, all operators should follow these following rules: Arrays are <i>true</i> if the count is larger than
0, numerical values are <i>true</i> if the value is different than 0, booleans behave as normal, null is always
<i>false</i> and all other values are <i>false</i>.</p>

<p>Examples of some variable operators:</p>

<pre class='example'>
{* Creating and showing an array *}
{array(1,5,'test')|attribute(,,show)}

{* Multiple serialized operators *}
{array(1,2)|gt(1)|choose('Tiny array','Greater than one')}
{* This will output 'Greater than one', because the array
consists of more than one element.*}
</pre>
" );

// $locale =& eZLocale::instance();

// // Init template
// $tpl =& eZTemplate::instance();
// $tpl->setShowDetails( true );

// $tpl->registerOperators( new eZTemplateAttributeOperator() );
// $tpl->registerOperators( new eZTemplateUnitOperator() );
// $tpl->registerOperators( new eZTemplateArrayOperator() );
// $tpl->registerOperators( new eZTemplateLogicOperator() );

// $tpl->registerFunctions( new eZTemplateSectionFunction() );
// $tpl->registerFunctions( new eZTemplateDelimitFunction() );
// $tpl->registerFunctions( new eZTemplateIncludeFunction() );
// $tpl->registerFunctions( new eZTemplateSwitchFunction() );

// $tpl->setVariable( "var1", array( "a" => "data1",
//                                   "b" => "data2" ) );
// $tpl->setVariable( "unit1", 200000 );
// $tpl->setVariable( "uri", "lib/eztemplate/sdk/templates/comment.tpl" );

// $tpl->setVariable( "match1", 3 );
// $tpl->setVariable( "match_id1", 3 );
// $tpl->setVariable( "match_arr1", array( 1, 2 ) );
// $tpl->setVariable( "match_arr2", array( 3, 4 ) );

// $tpl->setVariable( "match_id2", 3 );
// $tpl->setVariable( "match_assoc1", array( array( "id" => 1 ),
//                                           array( "id" => 2 ) ) );
// $tpl->setVariable( "match_assoc2", array( array( "id" => 3 ),
//                                           array( "id" => 4 ) ) );

// $tpl->display( "lib/eztemplate/sdk/templates/operators.tpl" );

// eZDebug::addTimingPoint( "Template end" );

?>
