<?php

include_once( "lib/eztemplate/classes/eztemplate.php" );
include_once( "lib/eztemplate/classes/eztemplateswitchfunction.php" );
include_once( "lib/eztemplate/classes/eztemplatesectionfunction.php" );
include_once( "lib/eztemplate/classes/eztemplatearrayoperator.php" );
include_once( "lib/eztemplate/classes/eztemplatelogicoperator.php" );

print( "<p>The <i>switch</i> function allows conditional control of output. For instance you can display
a piece of HTML code depending on a template variable. The matching can be directly between to types or
matching for an element in an array.</p>
<p>The matching is done by creating one ore more <i>case</i> blocks inside the <i>switch</i> block.
There must always be one default case present, a default case is created by inserting a <i>case</i>
block without any parameters.</p>
<p>The parameter to a case can either be <i>match</i> which determines the value to match against,
or <i>in</i> which must contain an array. The <i>match</i> does a direct match, while the <i>in</i>
looks for a match among the elements in the array. The <i>in</i> parameter behaves differently if
the <i>key</i> parameter is used, which must be an identifier, it then assumes that the array sent to
<i>in</i> has an array for each element and uses the <i>key</i> to match a key in the sub array.</p>
" );

// Init template
$tpl =& eZTemplate::instance();
$tpl->setShowDetails( true );

$tpl->registerFunctions( new eZTemplateSwitchFunction() );
$tpl->registerFunctions( new eZTemplateSectionFunction() );
$tpl->registerOperators( new eZTemplateArrayOperator() );
$tpl->registerOperators( new eZTemplateLogicOperator() );

$current = 1;
if ( isset( $GLOBALS["HTTP_POST_VARS"]["List"] ) )
    $current = $GLOBALS["HTTP_POST_VARS"]["List"];

$tpl->setVariable( "current", $current );
$tpl->setVariable( "list", array( 1 => "First choice",
                                  2 => "2nd",
                                  3 => "3rd",
                                  4 => "Custom" ) );
$tpl->setVariable( "rows", array( array( "id" => 1 ),
                                  array( "id" => 4 ) ) );

$tpl->display( "lib/eztemplate/sdk/templates/switch.tpl" );

eZDebug::addTimingPoint( "Template end" );

?>
