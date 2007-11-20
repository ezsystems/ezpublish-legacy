#!/usr/bin/env php
<?php
//
// Created on: <06-Apr-2004 14:26:28 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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
function upgradeMenuINI( $menuGroup, $newNodeID )
{
    $cli = eZCLI::instance();
    $menuINI = eZINI::instance( 'menu.ini' );

    if ( !$menuINI->hasVariable( "Topmenu_$menuGroup", 'URL' ) )
        return;
    $oldURLList = $menuINI->variable( "Topmenu_$menuGroup", 'URL' );
    $changed = false;
    foreach ( $oldURLList as $context => $url )
    {
        if ( is_numeric( $newNodeID ) )
        {
            $url = preg_replace( "/\/(\d+)(\/?)$/", '/'. $newNodeID . "\\2", $url );
            $oldURLList[$context] = $url;
            $changed = true;
        }
    }
    if ( $changed )
    {
        $menuINI->setVariable( "Topmenu_$menuGroup", 'URL',  $oldURLList );

        $cli->output();
        $cli->output( "Writing back changes to menu.ini" );
        $menuINI->save( false, false, 'append', true );

    }
}
//include_once( 'lib/ezutils/classes/ezcli.php' );
//include_once( 'kernel/classes/ezscript.php' );

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish Top Level Nodes Creator\n\n" .
                                                        "This script will create the top level nodes that are missing,\n" .
                                                        "\n" .
                                                        "updatetoplevel.php" ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[class-identifier:]", "",
                                array( 'class-identifier' => "Which class to create top level nodes from, default is 'folder'" ) );
$script->initialize();

$db = eZDB::instance();

$contentINI = eZINI::instance( 'content.ini' );

//include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

$checkNodes = array( array( 'Content', 'RootNode',       '',       'Standard section', 'ezcontentnavigationpart', false ),
                     array( 'Users',   'UserRootNode',   'users',  'Users',            'ezusernavigationpart',    true  ),
                     array( 'Media',   'MediaRootNode',  'media',  'Media',            'ezmedianavigationpart',   true  ),
                     array( 'Design',  'DesignRootNode', 'design', 'Design',           'ezvisualnavigationpart',  true  ),
                     array( 'Setup',   'SetupRootNode',  'setup',  'Setup',            'ezsetupnavigationpart',   true  ) );

$contentClassIdentifier = 'folder';
if ( $options['class-identifier'] )
    $contentClassIdentifier = $options['class-identifier'];

$sectionID = 1;
$userID = 14;
$class = eZContentClass::fetchByIdentifier( $contentClassIdentifier );
if ( !is_object( $class ) )
    $script->shutdown( 1, "Failed to load content class for identifier '$contentClassIdentifier'" );

//include_once( 'kernel/classes/ezsection.php' );
$sections = eZSection::fetchList();

$storeContentINI = false;
$i = 0;
foreach ( $checkNodes as $checkNode )
{
    if ( $i > 0 )
        $cli->output();
    $name = $checkNode[0];
    $iniVariable = $checkNode[1];
    $nodeURLName = $checkNode[2];
    $nodeSectionName = strtolower( $checkNode[3] );
    $nodeNavigationPartIdentifier = $checkNode[4];
    $createNewNode = $checkNode[5];
    $rootNodeID = $contentINI->variable( 'NodeSettings', $iniVariable );

    $cli->output( "Checking node " . $cli->stylize( 'highlight', $name ) . " (" . $cli->stylize( 'emphasize', $rootNodeID ) . ")",
                  false );

    $rootNode = eZContentObjectTreeNode::fetch( $rootNodeID );

    $createNode = false;
    if ( is_object( $rootNode ) )
    {
        $parentNodeID = $rootNode->attribute( 'parent_node_id' );
        if ( $parentNodeID != 1 )
        {
            $cli->output( $cli->gotoColumn( 50 ) . $cli->stylize( 'failure', "[ Failed ]" ) );
            $cli->output( "This is not a top level node, a new one will be created" );
            $createNode = true;
        }
        else
        {
            $cli->output( $cli->gotoColumn( 50 ) . $cli->stylize( 'success', "[   OK   ]" ) );
        }
    }
    else
    {
        $rootNode = eZContentObjectTreeNode::fetchByURLPath( $nodeURLName );
        if ( is_object( $rootNode ) )
        {
            $cli->output( $cli->gotoColumn( 50 ) . $cli->stylize( 'success', "[   OK   ]" ) );
            $cli->output( "The actual node ID is " . $cli->stylize( 'emphasize', $rootNode->attribute( 'node_id' ) ) . ", will update content.ini with this" );
            $contentINI->setVariable( 'NodeSettings', $iniVariable, $rootNode->attribute( 'node_id' ) );
            upgradeMenuINI( $nodeURLName, $rootNode->attribute( 'node_id' ) );
            $cli->output( "Updated content.ini with new ID " );
            $storeContentINI = true;
        }
        else
        {
            $cli->output( $cli->gotoColumn( 50 ) . $cli->stylize( 'failure', "[ Failed ]" ) );
            $cli->output( "The node does not exist, a new one will be created" );
            $createNode = true;
        }
    }

    if ( $createNode )
    {
        $cli->output( 'Creating', false );

        $sectionID = false;
        foreach ( $sections as $section )
        {
            $sectionName = strtolower( $section->attribute( 'name' ) );
            $sectionNavigationPartIdentifier = $section->attribute( 'navigation_part_identifier' );
            if ( $sectionName == $nodeSectionName and
                 $sectionNavigationPartIdentifier == $nodeNavigationPartIdentifier )
            {
                $sectionID = $section->attribute( 'id' );
                break;
            }
        }
        if ( !$sectionID )
        {
            if ( $createNewNode )
            {
                $row = array( 'id' => false,
                              'name' => $name,
                              'navigation_part_identifier' => $nodeNavigationPartIdentifier );
                $section = new eZSection( $row );
                $section->store();
                $sectionID = $section->attribute( 'id' );
                $sections[] = $section;
            }
            else
            {
                $sectionID = 1;
            }
        }

        $contentObject = $class->instantiate( $userID, $sectionID );

        if ( is_object( $contentObject ) )
        {
            $contentVersion =& $contentObject->version( $contentObject->attribute( 'current_version' ) );
            $contentObjectAttributes =& $contentVersion->contentObjectAttributes();
            foreach ( array_keys( $contentObjectAttributes ) as $contentObjectAttributeKey )
            {
                $contentObjectAttribute =& $contentObjectAttributes[$contentObjectAttributeKey];
                $contentClassAttribute = $contentObjectAttribute->attribute( 'contentclass_attribute' );
                $contentClassAttributeIdentifier = $contentClassAttribute->attribute( 'identifier' );
                $contentObjectAttributeType = $contentObjectAttribute->attribute( 'data_type_string' );
                if ( ( $contentObjectAttributeType == 'ezstring' or
                       $contentObjectAttributeType == 'eztext' ) and
                     ( $contentClassAttributeIdentifier == 'name' or
                       $contentClassAttributeIdentifier == 'title' ) )
                {
                    $contentObjectAttribute->setAttribute( 'data_text', $name );
                    $contentObjectAttribute->store();
                    break;
                }
            }

            $nodeAssignment = eZNodeAssignment::create( array( 'contentobject_id' => $contentObject->attribute( 'id' ),
                                                                'contentobject_version' => $contentObject->attribute( 'current_version' ),
                                                                'parent_node' => 1,
                                                                'is_main' => 1 ) );
            $nodeAssignment->store();

            //include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
            $operationResult = eZOperationHandler::execute( 'content', 'publish',
                                                            array( 'object_id' => $contentObject->attribute( 'id' ),
                                                                   'version' => $contentObject->attribute( 'current_version' ) ) );
            $cli->output( $cli->gotoColumn( 50 ) . $cli->stylize( 'success', " [   OK   ]" ) );

            $contentObject = eZContentObject::fetch( $contentObject->attribute( 'id' ) );
            $node = $contentObject->mainNode();
            if ( is_object( $node ) )
            {
                $cli->output( "New root node ID: " . $cli->stylize( 'emphasize', $node->attribute( 'node_id' ) ) );
                if ( $rootNodeID != $node->attribute( 'node_id' ) )
                {
                    $contentINI->setVariable( 'NodeSettings', $iniVariable, $node->attribute( 'node_id' ) );
                    upgradeMenuINI( $nodeURLName, $node->attribute( 'node_id' ) );
                    $cli->output( "Updated content.ini with new ID " );
                    $storeContentINI = true;
                }
            }

            if ( $name == 'Design' )
            {
                $cli->output( "Moving node /setup/ez_publish to /design", false );
                $setupEzPublushNode = eZContentObjectTreeNode::fetchByURLPath( 'setup/ez_publish' );
                if ( !is_object( $setupEzPublushNode ) )
                {

                    $cli->output( '' ); // \n
                    $cli->output( "Node not found.", false );
                    $cli->output( $cli->gotoColumn( 50 ) . $cli->stylize( 'failure', "[ Failed ]" ) );
                }
                else
                {
                    $setupEzPublushNode->move( $node->attribute( 'node_id' ) );
                    $cli->output( $cli->gotoColumn( 50 ) . $cli->stylize( 'success', "[   OK   ]" ) );
                }
                unset( $setupEzPublushNode );
            }
        }
        else
        {
            $cli->output( $cli->gotoColumn( 50 ) . $cli->stylize( 'failure', " [ Failed ]" ) );
        }
    }

    ++$i;
}

if ( $storeContentINI )
{
    $cli->output();
    $cli->output( "Writing back changes to content.ini" );
    $contentINI->save( false, false, 'append', true );
}

$script->shutdown();

?>
