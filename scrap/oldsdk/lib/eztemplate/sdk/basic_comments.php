<?php

// $Result = array( 'title' => 'Comments' );

// include_once( "lib/eztemplate/classes/eztemplate.php" );

// // Init template
// $tpl =& eZTemplate::instance();
// $tpl->setShowDetails( true );

// // Use template
// print( "<p>Template comments starts and ends with an asterix inside a template tag. Comments are
// not displayed in the resulting output and is useful for giving instructions on usage or personal
// comments. It can also be used to temporarily comment out template code including tags.</p>
// <p class=\"footnote\">Note: Comments cannot be nested so don't try to comment a comment.</p>" );

// $tpl->display( "lib/eztemplate/sdk/templates/comment.tpl" );

?>

<h1>Comments</h1>

<p>
Template comments starts and ends with an asterisk (*) inside a template tag. Comments are
not displayed in the resulting output and is useful for giving instructions on usage or personal
comments. It can also be used to temporarily comment out template code including tags.
</p>

<p class=\"footnote\">Note: Comments cannot be nested, so don't try to comment a comment.</p>

<p>
The following template code:
</p>

<pre class="example">
{* this is a comment *}

{* This is a
multi line
comment *}

{* The code below was temporarily commented out *}
{* {include uri=test.tpl} *}

{* {* This nested comment does not work. *} This text will be displayed *}

{* Bold display of some text *}
&lt;b&gt;eZ template&lt;/b&gt;
</pre>

<p>
Will produce this result:
</p>

<p>
This text will be displayed *} <b>eZ template</b>
</p>
