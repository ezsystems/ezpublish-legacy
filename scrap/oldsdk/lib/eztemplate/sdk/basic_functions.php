<?php

// $Result = array( 'title' => 'Functions' );

// include_once( "lib/eztemplate/classes/eztemplate.php" );
// include_once( "lib/eztemplate/classes/eztemplateattributeoperator.php" );
// include_once( "lib/eztemplate/classes/eztemplateunitoperator.php" );
// include_once( "lib/eztemplate/classes/eztemplatearrayoperator.php" );

// include_once( "lib/eztemplate/classes/eztemplatesectionfunction.php" );
// include_once( "lib/eztemplate/classes/eztemplatedelimitfunction.php" );
// include_once( "lib/eztemplate/classes/eztemplateincludefunction.php" );
// include_once( "lib/eztemplate/classes/eztemplateswitchfunction.php" );

// include_once( "lib/ezlocale/classes/ezlocale.php" );

// <p>It's also possible to let an operator work as a function, this is done by creating a user defined
// function that sends the sub-children as input to an operator*. A function cannot be used as an operator
// but it's still possible to capture the output of a function into a variable*. </p>
// <p class=\"footnote\">* This is not implemented yet.</p>

print( "<h1>Functions</h1>
<p>Functions are template tags that perform code on a set of sub-children, or work stand-alone.
Both functions and operators perform code but there are some differences.</p>

<p>Functions take named parameters and can work on sub children elements, that can be functions or text.
Operators work like Unix pipes where the data is input in one end, processed and output to either a
new operator or to the output. Operators also take sequenced parameters to modify their behaviour. Mixing
operators and variable types both as parameter and as input works.</p>

<p>Most functions are stand-alone classes that can be replaced or modified. One internal functions is
<i>literal</i> which must be done internally due to parsing issues*.</p>

<p class=\"footnote\">* A change in the parser might be made to allow for an external literal function.</p>

<p>This example shows usage of the <i>switch</i> function to show conditional content.</p>

<pre class='example'>
{switch match=\$match1}
  {case match=1}Matched 1{/case}
  {case match=2}Matched 2{/case}
  {case}Matched default{/case}
{/switch}
</pre>" );

// $locale =& eZLocale::instance();

// // Init template
// $tpl =& eZTemplate::instance();
// $tpl->setShowDetails( true );

// $tpl->registerOperators( new eZTemplateAttributeOperator() );
// $tpl->registerOperators( new eZTemplateUnitOperator() );
// $tpl->registerOperators( new eZTemplateArrayOperator() );

// $tpl->registerFunctions( new eZTemplateSectionFunction() );
// $tpl->registerFunctions( new eZTemplateDelimitFunction() );
// $tpl->registerFunctions( new eZTemplateIncludeFunction() );
// $tpl->registerFunctions( new eZTemplateSwitchFunction() );

// $tpl->setVariable( "array1", array( "a" => "Red",
//                                     "b" => "Green",
//                                     "c" => "Blue" ) );
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

// $tpl->display( "lib/eztemplate/sdk/templates/functions.tpl" );

// eZDebug::addTimingPoint( "Template end" );

?>
