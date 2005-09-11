#!/usr/bin/env php
<?php
//
// Created on: <06-Apr-2004 14:26:28 amos>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
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
include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "eZ publish Setup Node Creator\n\n" .
                                                         "This script will create the setup top level node if it does not exist,\n" .
                                                         "\n" .
                                                         "updatetoplevel.php" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[class-identifier:]", "",
                                array( 'class-identifier' => "Which class to create top level nodes from, default is 'folder'" ) );
$script->initialize();

$db =& eZDB::instance();

$contentINI =& eZINI::instance( 'content.ini' );

include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

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

include_once( 'kernel/classes/ezsection.php' );
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

        $contentObject =& $class->instantiate( $userID, $sectionID );

        if ( is_object( $contentObject ) )
        {
            $contentVersion =& $contentObject->version( $contentObject->attribute( 'current_version' ) );
            $contentObjectAttributes =& $contentVersion->contentObjectAttributes();
            foreach ( array_keys( $contentObjectAttributes ) as $contentObjectAttributeKey )
            {
                $contentObjectAttribute =& $contentObjectAttributes[$contentObjectAttributeKey];
                $contentClassAttribute =& $contentObjectAttribute->attribute( 'contentclass_attribute' );
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

            include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
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
