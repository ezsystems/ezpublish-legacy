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

$GroupIdentifier = $Params['GroupIdentifier'];
$Module = $Params['Module'];

$group = $GroupIdentifier === null ? new eZContentObjectStateGroup() : eZContentObjectStateGroup::fetchByIdentifier( $GroupIdentifier );

if ( !is_object( $group ) )
{
    return $Module->handleError( eZError::KERNEL_NOT_FOUND, 'kernel' );
}

if ( $group->isInternal() )
{
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}



$tpl = eZTemplate::factory();

$currentAction = $Module->currentAction();

if ( $currentAction == 'Cancel' )
{
    return $Module->redirectTo( 'state/groups' );
}
else if ( $currentAction == 'Store' )
{
    $group->fetchHTTPPersistentVariables();

    $messages = array();
    $isValid = $group->isValid( $messages );

    if ( $isValid )
    {
        $group->store();
        if ( $GroupIdentifier === null )
        {
            return $Module->redirectTo( 'state/group/' . $group->attribute( 'identifier' ) );
        }
        else
        {
            return $Module->redirectTo( 'state/groups' );
        }
    }

    $tpl->setVariable( 'is_valid', $isValid );
    $tpl->setVariable( 'validation_messages', $messages );
}

$tpl->setVariable( 'group', $group );

if ( $GroupIdentifier === null )
{
    $path = array(
        array( 'url' => false, 'text' => ezpI18n::translate( 'kernel/state', 'State' ) ),
        array( 'url' => false, 'text' => ezpI18n::translate( 'kernel/state', 'New group' ) )
    );
}
else
{
    $path = array(
        array( 'url' => false, 'text' => ezpI18n::translate( 'kernel/state', 'State' ) ),
        array( 'url' => false, 'text' => ezpI18n::translate( 'kernel/state', 'Group edit' ) ),
        array( 'url' => false, 'text' => $group->attribute( 'identifier' ) )
    );
}

$Result = array(
    'content' => $tpl->fetch( 'design:state/group_edit.tpl' ),
    'path'    => $path
);

?>