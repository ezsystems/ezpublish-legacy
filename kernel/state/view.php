<?php
//
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

$Module = $Params['Module'];
$GroupIdentifier = $Params['GroupIdentifier'];
$StateIdentifier = $Params['StateIdentifier'];
$LanguageCode = $Params['Language'];

$group = eZContentObjectStateGroup::fetchByIdentifier( $GroupIdentifier );

if ( !is_object( $group ) )
{
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

$state = $group->stateByIdentifier( $StateIdentifier );

if ( !is_object( $state ) )
{
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

$currentAction = $Module->currentAction();

if ( $currentAction == 'Edit' )
{
    return $Module->redirectTo( "state/edit/$GroupIdentifier/$StateIdentifier" );
}

if ( $LanguageCode )
{
    $state->setCurrentLanguage( $LanguageCode );
}

require_once 'kernel/common/template.php';

$tpl = templateInit();
$tpl->setVariable( 'group', $group );
$tpl->setVariable( 'state', $state );

$Result = array(
    'content' => $tpl->fetch( 'design:state/view.tpl' ),
    'path' => array(
        array( 'url' => false,
               'text' => eZi18n::translate( 'kernel/state', 'State' ) ),
        array( 'url' => 'state/group/' . $group->attribute( 'identifier' ),
               'text' => $group->attribute( 'identifier' ) ),
        array( 'url' => false,
               'text' => $state->attribute( 'identifier' ) )
    )
);

?>