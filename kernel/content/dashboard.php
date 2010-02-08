<?php 
//
// Created on: <15-Jan-2010 13:06:01 ls>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.3.x
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

require_once( 'kernel/common/template.php' );

$ini = eZINI::instance( 'dashboard.ini' );
$currentUser = eZUser::currentUser();

$orderedBlocks = array();

$dashboardBlocks = $ini->variable( 'DashboardSettings', 'DashboardBlocks' );

foreach( $dashboardBlocks as $blockIdentifier )
{
    $blockGroupName = 'DashboardBlock_' . $blockIdentifier;
    if ( !$ini->hasGroup( $blockGroupName ) )
        continue;

    $hasAccess = true;
    if ( $ini->hasVariable( $blockGroupName, 'PolicyList' ) )
    {
        $policyList = $ini->variable( $blockGroupName, 'PolicyList' );
        foreach( $policyList as $policy )
        {
            // Value is either "<node_id>" or "<module>/<function>"
            if ( strpos( $policy, '/' ) !== false )
            {
                list( $module, $function ) = explode( '/', $policy );
                    $result = $currentUser->hasAccessTo( $module, $function );

                if ( $result['accessWord'] === 'no' )
                {
                    $hasAccess = false;
                    break;
                }
            }
            else
            {
                $node = eZContentObjectTreeNode::fetch( $policy );
                if ( !$node instanceof eZContentObjectTreeNode || !$node->attribute('can_read') )
                {
                    $hasAccess = false;
                    break;
                }
            }
        }
    }

    if ( $hasAccess === false )
        continue;

    $priority = 0;
    if ( $ini->hasVariable( $blockGroupName, 'Priority' ) )
        $priority = $ini->variable( $blockGroupName, 'Priority' );

    $numberOfItems = null;
    if ( $ini->hasVariable( $blockGroupName, 'NumberOfItems' ) )
        $numberOfItems = $ini->variable( $blockGroupName, 'NumberOfItems' );

    $template = null;
    if ( $ini->hasVariable( $blockGroupName, 'Template' ) )
        $template = $ini->variable( $blockGroupName, 'Template' );
    
    while( isset( $orderedBlocks[$priority]  ) )
        $priority++;
        
    $orderedBlocks[$priority] = array( 'identifier' => $blockIdentifier,
                                       'template' => $template,
                                       'number_of_items' => $numberOfItems );
}

// Sort $orderedBlocks by key, starting from the lowest priority
ksort( $orderedBlocks );

$contentInfoArray = array();

$tpl = templateInit();

$tpl->setVariable( 'blocks', $orderedBlocks );
$tpl->setVariable( 'user', $currentUser );
$tpl->setVariable( 'persistent_variable', false );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:content/dashboard.tpl' );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/content', 'Dashboard' ),
                                'url' => false ) );

$contentInfoArray['persistent_variable'] = false;
if ( $tpl->variable( 'persistent_variable' ) !== false )
    $contentInfoArray['persistent_variable'] = $tpl->variable( 'persistent_variable' );

$Result['content_info'] = $contentInfoArray;

?>