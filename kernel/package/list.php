<?php
//
// Created on: <11-Aug-2003 18:12:39 amos>
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

require_once( "kernel/common/template.php" );
$module = $Params['Module'];
$offset = (int)$Params['Offset'];

$repositoryID = 'local';
if ( $Params['RepositoryID'] )
    $repositoryID = $Params['RepositoryID'];

if ( $module->isCurrentAction( 'InstallPackage' ) )
{
    return $module->redirectToView( 'upload' );
}

$removeList = array();
if ( $module->isCurrentAction( 'RemovePackage' ) or
     $module->isCurrentAction( 'ConfirmRemovePackage' ) )
{
    if ( $module->hasActionParameter( 'PackageSelection' ) )
    {
        $removeConfirmation = $module->isCurrentAction( 'ConfirmRemovePackage' );
        $packageSelection = $module->actionParameter( 'PackageSelection' );
        foreach ( $packageSelection as $packageID )
        {
            $package = eZPackage::fetch( $packageID );
            if ( $package )
            {
                if ( $removeConfirmation )
                {
                    $package->remove();
                }
                else
                {
                    $removeList[] = $package;
                }
            }
        }
        if ( $removeConfirmation )
            return $module->redirectToView( 'list' );
    }
}

if ( $module->isCurrentAction( 'ChangeRepository' ) )
{
    $repositoryID = $module->actionParameter( 'RepositoryID' );
}

if ( $module->isCurrentAction( 'CreatePackage' ) )
{
    return $module->redirectToView( 'create' );
}

$tpl = templateInit();

$viewParameters = array( 'offset' => $offset );

$tpl->setVariable( 'module_action', $module->currentAction() );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'remove_list', $removeList );
$tpl->setVariable( 'repository_id', $repositoryID );

$Result = array();
$Result['content'] = $tpl->fetch( "design:package/list.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => eZi18n::translate( 'kernel/package', 'Packages' ) ) );

?>
