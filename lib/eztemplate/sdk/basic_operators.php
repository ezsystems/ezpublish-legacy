<?php

include_once( "lib/eztemplate/classes/eztemplate.php" );
include_once( "lib/eztemplate/classes/eztemplateattributeoperator.php" );
include_once( "lib/eztemplate/classes/eztemplateunitoperator.php" );
include_once( "lib/eztemplate/classes/eztemplatearrayoperator.php" );
include_once( "lib/eztemplate/classes/eztemplatelogicoperator.php" );

include_once( "lib/eztemplate/classes/eztemplatesectionfunction.php" );
include_once( "lib/eztemplate/classes/eztemplatedelimitfunction.php" );
include_once( "lib/eztemplate/classes/eztemplateincludefunction.php" );
include_once( "lib/eztemplate/classes/eztemplateswitchfunction.php" );

include_once( "lib/ezlocale/classes/ezlocale.php" );

print( "<p>Operators allows the template engine to modify variable types or create new values.
Operators receives data input from the left and outputs the result to the right, how the
operator modifies the data is up to each operator. For instance some operators behave differently
depending on whether the type is an array or not, while other may ignore the input and create and
output variable.</p>
<p>The operators can also take extra parameters separated by <i>,</i> and enclosed in <i>(</i> and
<i>)</i>. The parameters can be constants, variables or operators which means that you can nest
operators. For instance theres no bultin syntax for creating booleans or arrays, instead you use
an operator. <i>true</i> creates a boolean with a true value and <i>false</i> creates a boolean with a
false value. <i>array</i> takes all it's parameters and creates an array out of it.</p>
<p>Some operators work only on the logical level, meaning that they only see values as true or false,
for consistency all operators should follow these following rules. Arrays are <i>true</i> if the count is larger than
0, numerics are <i>true</i> if the values is different than 0, booleans behave as normal, null is always
<i>false</i> and all other values are <i>false</i>.</p>
" );

$locale =& eZLocale::instance();

// Init template
$tpl =& eZTemplate::instance();
$tpl->setShowDetails( true );

$tpl->registerOperators( new eZTemplateAttributeOperator() );
$tpl->registerOperators( new eZTemplateUnitOperator() );
$tpl->registerOperators( new eZTemplateArrayOperator() );
$tpl->registerOperators( new eZTemplateLogicOperator() );

$tpl->registerFunctions( new eZTemplateSectionFunction() );
$tpl->registerFunctions( new eZTemplateDelimitFunction() );
$tpl->registerFunctions( new eZTemplateIncludeFunction() );
$tpl->registerFunctions( new eZTemplateSwitchFunction() );

$tpl->setVariable( "var1", array( "a" => "data1",
                                  "b" => "data2" ) );
$tpl->setVariable( "unit1", 200000 );
$tpl->setVariable( "uri", "lib/eztemplate/sdk/templates/comment.tpl" );

$tpl->setVariable( "match1", 3 );
$tpl->setVariable( "match_id1", 3 );
$tpl->setVariable( "match_arr1", array( 1, 2 ) );
$tpl->setVariable( "match_arr2", array( 3, 4 ) );

$tpl->setVariable( "match_id2", 3 );
$tpl->setVariable( "match_assoc1", array( array( "id" => 1 ),
                                          array( "id" => 2 ) ) );
$tpl->setVariable( "match_assoc2", array( array( "id" => 3 ),
                                          array( "id" => 4 ) ) );

$tpl->display( "lib/eztemplate/sdk/templates/operators.tpl" );

eZDebug::addTimingPoint( "Template end" );

?>
