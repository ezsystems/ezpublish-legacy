<?php

include_once( "lib/eztemplate/classes/eztemplate.php" );
include_once( "lib/eztemplate/classes/eztemplateattributeoperator.php" );
include_once( "lib/eztemplate/classes/eztemplatearrayoperator.php" );
include_once( "lib/eztemplate/classes/eztemplatelogicoperator.php" );
include_once( "lib/eztemplate/classes/eztemplatetextoperator.php" );

print( "<p>Some types can only be created from the PHP side or with the use of operators,
arrays are created with the <i>array</i> operator and booleans with the <i>true</i>
and <i>false</i> operators.</p>" );

// Init template
$tpl =& eZTemplate::instance();
$tpl->setShowDetails( true );

$tpl->registerOperators( new eZTemplateAttributeOperator() );
$tpl->registerOperators( new eZTemplateArrayOperator() );
$tpl->registerOperators( new eZTemplateLogicOperator() );
$tpl->registerOperators( new eZTemplateTextOperator() );

$tpl->display( "lib/eztemplate/sdk/templates/array.tpl" );

eZDebug::addTimingPoint( "Template end" );

?>
