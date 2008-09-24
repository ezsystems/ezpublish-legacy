<?php

$Module = $Params['Module'];
$StateID = $Params['StateID'];

$state = eZContentObjectState::fetchByID( $StateID );

$currentAction = $Module->currentAction();

if ( $currentAction =='Edit' )
{
    return $Module->redirectTo( 'state/edit/' . $StateID );
}

require_once 'kernel/common/template.php';

$tpl = templateInit();
$tpl->setVariable( 'state', $state );

$Result = array(
    'path' => array(),
    'content' => $tpl->fetch( 'design:state/view.tpl' )
);

?>