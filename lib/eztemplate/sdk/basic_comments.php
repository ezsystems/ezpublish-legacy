<?php

$Result = array( 'title' => 'Comments' );

include_once( "lib/eztemplate/classes/eztemplate.php" );

// Init template
$tpl =& eZTemplate::instance();
$tpl->setShowDetails( true );

// Use template
print( "<p>Template comments starts and ends with an asterix inside a template tag. Comments are
not displayed in the resulting output and is useful for giving instructions on usage or personal
comments. It can also be used to temporarily comment out template code including tags.</p>
<p class=\"footnote\">Note: Comments cannot be nested so don't try to comment a comment.</p>" );

$tpl->display( "lib/eztemplate/sdk/templates/comment.tpl" );

?>
