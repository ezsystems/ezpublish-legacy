<?php

// include_once( "lib/eztemplate/classes/eztemplate.php" );
// include_once( "lib/eztemplate/classes/eztemplatedelimitfunction.php" );

print( "<h1>Delimiter</h1>
<p>The { and } characters are treated as block start and block end by the template parser.
This means that one cannot input them directly in the template code to get a { or } on the web page.
Instead one must use the <i>ldelim</i> and <i>rdelim</i> functions.</p>

<pre class='example'>
{* Outputting the special { and } characters using functions *}
{ldelim} {rdelim}
</pre>" );

// // Init template
// $tpl =& eZTemplate::instance();
// $tpl->setShowDetails( true );

// $tpl->registerFunctions( new eZTemplateDelimitFunction() );

// $tpl->display( "lib/eztemplate/sdk/templates/delimit.tpl" );

// eZDebug::addTimingPoint( "Template end" );

?>
