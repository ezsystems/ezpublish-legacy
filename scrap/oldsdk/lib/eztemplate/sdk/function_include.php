<?php

// include_once( "lib/eztemplate/classes/eztemplate.php" );
// include_once( "lib/eztemplate/classes/eztemplateincludefunction.php" );

print( "<h1>Include</h1>
<p>It's possible to include other template files into a template file using the <i>include</i> function.
This makes it possible to share template code among different pages or create GUI components by passing
variables to the included template.</p>
<p>The include function has two special input parameters.
The <i>uri</i> parameter specifies the file to be included, the file does not have to reside
as a file but can be put in a database or created dynamically. By default the template engine
only loads files by their relative path to the index script, but it's possible to create
custom <i>resources</i> as they are called.
The <i>name</i> parameter specifies the namespace for the included template, this is useful
for avoiding variable name clashes with included files.
All other parameters are passed to the included template as template variables in the new namespace.</p>

<pre class='example'>
{* Including a header *}
{include uri='lib/eztemplate/sdk/templates/include_head.tpl' name='head'}

{* Including the body *}
{include uri='lib/eztemplate/sdk/templates/include_body.tpl' name='body' item='Body text'}

{* Include a button *}
{include uri='lib/eztemplate/sdk/templates/include_button.tpl' name='button' item=\$button_title}
</pre>" );

// // Init template
// $tpl =& eZTemplate::instance();
// $tpl->setShowDetails( true );

// $tpl->registerFunctions( new eZTemplateIncludeFunction() );

// $tpl->setVariable( "item", "An included title", "head" );
// $tpl->setVariable( "button_title", "Push Me" );

// $tpl->display( "lib/eztemplate/sdk/templates/include.tpl" );

// eZDebug::addTimingPoint( "Template end" );

?>
