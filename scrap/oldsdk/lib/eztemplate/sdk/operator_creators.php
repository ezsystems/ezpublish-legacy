<?php

// include_once( "lib/eztemplate/classes/eztemplate.php" );
// include_once( "lib/eztemplate/classes/eztemplateattributeoperator.php" );
// include_once( "lib/eztemplate/classes/eztemplatearrayoperator.php" );
// include_once( "lib/eztemplate/classes/eztemplatelogicoperator.php" );
// include_once( "lib/eztemplate/classes/eztemplatetextoperator.php" );

print( "<h1>Type creators</h1>
<p>Some types can only be created from the PHP side or with the use of operators.
Arrays are created with the <i>array</i> or the <i>hash</i> operator and booleans with the <i>true</i>
and <i>false</i> operators.</p>

<pre class='example'>
{* Creating array with numerics *}
{array(1,2,5)}

{* Creating array with strings *}
{array('red','green','blue')}

{* Creating associative array *}
{hash(name,'Ola Norman',age,26)}

{* Creating booleans *}
{true()}
{false()}

{* Creating array with booleans *}
{array(true(),false(),true())}
</pre>" );

// // Init template
// $tpl =& eZTemplate::instance();
// $tpl->setShowDetails( true );

// $tpl->registerOperators( new eZTemplateAttributeOperator() );
// $tpl->registerOperators( new eZTemplateArrayOperator() );
// $tpl->registerOperators( new eZTemplateLogicOperator() );
// $tpl->registerOperators( new eZTemplateTextOperator() );

// $tpl->display( "lib/eztemplate/sdk/templates/array.tpl" );

// eZDebug::addTimingPoint( "Template end" );

?>
