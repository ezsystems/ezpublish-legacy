<?php

include_once( "lib/eztemplate/classes/eztemplate.php" );
include_once( "lib/eztemplate/classes/eztemplatesequencefunction.php" );
include_once( "lib/eztemplate/classes/eztemplatesectionfunction.php" );
include_once( "lib/eztemplate/classes/eztemplatearrayoperator.php" );
include_once( "lib/eztemplate/classes/eztemplatelogicoperator.php" );

print( "<p>The <i>sequence</i> function allows for creating a sequence which is iterated over
and wrapped around when the end is reached. This is particulary useful when you want to
alternate colors in a list. It's possible to create multiple sequences and advance them
individually.</p>
" );

// Init template
$tpl =& eZTemplate::instance();
$tpl->setShowDetails( true );

$tpl->registerFunctions( new eZTemplateSectionFunction() );
$tpl->registerFunctions( new eZTemplateSequenceFunction() );
$tpl->registerOperators( new eZTemplateArrayOperator() );
$tpl->registerOperators( new eZTemplateLogicOperator() );

$tpl->setVariable( "numbers", array( 1001, 1002, 1003 ) );

$tpl->display( "lib/eztemplate/sdk/templates/sequence.tpl" );

eZDebug::addTimingPoint( "Template end" );

?>
