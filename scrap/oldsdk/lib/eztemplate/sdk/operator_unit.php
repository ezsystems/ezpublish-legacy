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

print( "<p>Displaying different units (such as filesize) is possible with the <i>si</i> operator.
The operator uses a mixture of prefixes and a base*. The base and prefix specifications come with
default values for the most common bases and prefixes and can easily be expanded by editing an ini file.</p>
<p>All prefix values are input as powers of 10, with the exception of bytes and bits which use
prefixes with the power of 2. However it's possible to still use the same power of 10 prefixes for
bytes and bits by either editing the ini file or specifying it to the operator.</p>
<p>Requires: eZINI</p>
<p class=\"footnote\">*See <a href=\"http://physics.nist.gov/cuu/Units/\">International Systems of Units</a> for more information</p>
<p class=\"footnote\">Note: Derivative bases and conversion is not supported yet.</p>
" );

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

$tpl->setVariable( "var1", 1 );

$tpl->display( "lib/eztemplate/sdk/templates/units.tpl" );

eZDebug::addTimingPoint( "Template end" );

?>
