<?php
//
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

include_once( "kernel/classes/ezcontentclass.php" );
include_once( "lib/ezutils/classes/ezhttppersistence.php" );
include_once( "kernel/classes/ezcontentclassclassgroup.php" );
include_once( "lib/ezutils/classes/ezini.php" );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

$Module =& $Params["Module"];
$GroupID = null;
if ( isset( $Params["GroupID"] ) )
    $GroupID =& $Params["GroupID"];
$http =& eZHTTPTool::instance();
$deleteIDArray = $http->sessionVariable( "DeleteClassIDArray" );
$DeleteResult = array();
$alreadyRemoved = array();
$systemClassArray = array();


// Find system node and their content class ID.
$systemNodeArray = array();
$db =& eZDB::instance();
$systemNodeArray =& $db->arrayQuery( "SELECT node_id from ezcontentobject_tree WHERE depth = 1" );
foreach ( array_keys( $systemNodeArray ) as $key )
{
    $systemNodeID = $systemNodeArray[$key]['node_id'];
    $systemNode =& eZContentObjectTreeNode::fetch( $systemNodeID );
    if ( $systemNode != null )
    {
        $nodeObject = $systemNode->attribute( 'object' );
        $systemClassID = $nodeObject->attribute( 'contentclass_id' );
        if ( !in_array( $systemClassID, $systemClassArray ) )
        {
            $systemClassArray[] = $systemClassID;
        }
    }
}

if ( !$http->hasPostVariable( 'ConfirmButton' ) && !$http->hasPostVariable( 'CancelButton' ) && $GroupID != null )
{
    // we will remove class - group relations rather than classes if they belongs to more than 1 group:
    $updateDeleteIDArray = true;
    foreach ( array_keys( $deleteIDArray ) as $key )
    {
        $classID = $deleteIDArray[$key];
        // for each classes tagged for deleting:
        $class =& eZContentClass::fetch( $classID );
        if ( $class )
        {
            // find out to how many groups the class belongs:
            $classInGroups = $class->attribute( 'ingroup_list' );
            if ( count( $classInGroups ) != 1 )
            {
                // remove class - group relation:
                include_once( "kernel/class/ezclassfunctions.php" );
                eZClassFunctions::removeGroup( $classID, null, array( $GroupID ) );
                $alreadyRemoved[] = array( 'id' => $classID,
                                           'name' => $class->attribute( 'name' ) );
                $updateDeleteIDArray = true;
                unset( $deleteIDArray[$key] );
            }
        }
    }
    if ( $updateDeleteIDArray )
    {
        // we aren't going to remove classes already processed:
        $http->setSessionVariable( 'DeleteClassIDArray', $deleteIDArray );
    }
    if ( count( $deleteIDArray ) == 0 )
    {
        // we don't need anything to confirm:
        return $Module->redirectTo( '/class/classlist/' . $GroupID );
    }
}

if ( $http->hasPostVariable( "ConfirmButton" ) )
{
    foreach ( $deleteIDArray as $deleteID )
    {
        if ( !in_array( $deleteID, $systemClassArray ) )
        {
            eZContentClassClassGroup::removeClassMembers( $deleteID, 0 );
            eZContentClassClassGroup::removeClassMembers( $deleteID, 1 );

            // Fetch real version and remove it
            $deleteClass =& eZContentClass::fetch( $deleteID );
            if ( $deleteClass != null )
                $deleteClass->remove( true );

            // Fetch temp version and remove it
            $tempDeleteClass =& eZContentClass::fetch( $deleteID, true, 1 );
            if ( $tempDeleteClass != null )
                $tempDeleteClass->remove( true, 1 );

            //Remove all object from thrash
            $db =& eZDB::instance();
            $deleteID = $db->escapeString( $deleteID ); //security thing
            while ( true )
            {
                $resArray =& $db->arrayQuery( "SELECT ezcontentobject.id FROM ezcontentobject WHERE ezcontentobject.contentclass_id='$deleteID'", array( 'length' => 50 ) );
                if( !$resArray || count( $resArray ) == 0 )
                {
                    break;
                }
                foreach( $resArray as $row )
                {
                    $object =& eZContentObject::fetch( $row['id'] );
                    $object->purge();
                }
            }
        }
    }
    return $Module->redirectTo( '/class/classlist/' . $GroupID );
}
if ( $http->hasPostVariable( "CancelButton" ) )
{
    return $Module->redirectTo( '/class/classlist/' . $GroupID );
}

foreach ( $deleteIDArray as $deleteID )
{
    $ClassObjectsCount = 0;
    $class =& eZContentClass::fetch( $deleteID );
    if ( $class != null )
    {
        $class =& eZContentClass::fetch( $deleteID );
        $ClassID = $class->attribute( 'id' );
        $ClassName = $class->attribute( 'name' );
        if ( !in_array( $deleteID, $systemClassArray ) )
        {
            $classObjects =& eZContentObject::fetchSameClassList( $ClassID );
            $ClassObjectsCount = count( $classObjects );
            if ( $ClassObjectsCount == 1 )
                $ClassObjectsCount .= ezi18n( 'kernel/class', ' object' );
            else
                $ClassObjectsCount .= ezi18n( 'kernel/class', ' objects' );
            $item = array( "className" => $ClassName,
                           "objectCount" => $ClassObjectsCount );
            $DeleteResult[] = $item;
        }
        else
        {
            $item = array( "className" => $ClassName,
                           "objectCount" => -1 );
            $DeleteResult[] = $item;
        }
    }
}

$Module->setTitle( ezi18n( 'kernel/class', 'Remove classes' ) . ' ' . $ClassID );
include_once( "kernel/common/template.php" );
$tpl =& templateInit();

$tpl->setVariable( 'module', $Module );
$tpl->setVariable( 'GroupID', $GroupID );
$tpl->setVariable( 'DeleteResult', $DeleteResult );
$tpl->setVariable( 'already_removed', $alreadyRemoved );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:class/removeclass.tpl" );
$Result['path'] = array( array( 'url' => '/class/removeclass/',
                                'text' => ezi18n( 'kernel/class', 'Remove classes' ) ) );
?>
