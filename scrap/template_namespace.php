<?php

include_once( "lib/ezutils/classes/ezini.php" );

eZINI::setIsCacheEnabled( false );
eZINI::setIsDebugEnabled( true );

include_once( "kernel/common/template.php" );

$tpl =& templateInit();

$tpl->setVariable( "var", "123" );
$tpl->setVariable( "var", "456", "Room1" );
$tpl->setVariable( "var", "789", "Room2" );
$tpl->setVariable( "var", "!@#", "Room3" );
$tpl->setVariable( "var", "$%^", "Room4" );
$tpl->setVariable( "var", "&*(", "Room5" );
$tpl->setVariable( "var", ")_+", "Room6" );

$tpl->display( "scrap/root.tpl" );


eZDebug::printReport( false, false );

?>
