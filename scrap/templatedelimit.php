<?php

include_once( "lib/ezutils/classes/ezini.php" );

eZINI::setIsCacheEnabled( false );
eZINI::setIsDebugEnabled( true );

include_once( "kernel/common/template.php" );

$tpl =& templateInit();

$tpl->setVariable( "var", "123" );

$tpl->display( "scrap/delimit.tpl" );


eZDebug::printReport( false, false );

?>
