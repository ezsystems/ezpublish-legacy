<?php

$StateID = $Params['StateID'];

$state = eZContentObjectState::fetchByID( $StateID );

require_once 'kernel/common/template.php';

$tpl = templateInit();
$tpl->setVariable( 'state', $state );

$Result = array(
    'path' => array(),
    'content' => $tpl->fetch( 'design:state/view.tpl' )
);

?>