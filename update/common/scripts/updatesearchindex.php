<?php
//
// Created on: <28-Nov-2002 12:45:40 bf>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

set_time_limit( 0 );

$ezpublishRoot = realpath( "." );
$i = 1;

$startNode = 1;

while ( $i < count( $argv ) )
{
    $arg =& $argv[$i];
    $increase = true;
    if ( $arg == '--root' )
    {
        if ( isset( $arg[$i+1] ) )
        {
            $ezpublishRoot = $argv[$i+1];
            chdir( $ezpublishRoot );
            $increase = false;
            $i += 2;
        }
        else
            print( "Missing --root path\n" );
    }
    else if ( $arg == '--node' )
    {
        if ( isset( $argv[$i+1] ) )
        {
            $startNode = $argv[$i+1];
        }
        else
            print( "Missing --node id\n" );
    }
    if ( $increase )
        ++$i;
}
print( realpath( "." ) . "\n" );

//eZDebug::setHandleType( EZ_HANDLE_FROM_PHP );

include_once( "lib/ezutils/classes/ezmodule.php" );
eZModule::setGlobalPathList( array( "kernel" ) );
include_once( 'lib/ezutils/classes/ezexecution.php' );
include_once( "lib/ezutils/classes/ezdebug.php" );

include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

eZDebug::setHandleType( EZ_HANDLE_TO_PHP );
eZDebug::setLogFileEnabled( false );
eZINI::setIsCacheEnabled( false );

function eZDBCleanup()
{
    if ( class_exists( 'ezdb' )
         and eZDB::hasInstance() )
    {
        $db =& eZDB::instance();
        $db->setIsSQLOutputEnabled( false );
    }
//     session_write_close();
}

function eZFatalError()
{
    eZDebug::setHandleType( EZ_HANDLE_NONE );
    print( "Fatal error: eZ publish did not finish it's request\n" );
    print( "The execution of eZ publish was abruptly ended.\n" );
}

eZExecution::addCleanupHandler( 'eZDBCleanup' );
eZExecution::addFatalErrorHandler( 'eZFatalError' );

$db =& eZDB::instance();
$db->setIsSQLOutputEnabled( false );


// Get top node
$node =& eZContentObjectTreeNode::fetch( $startNode );

$endl = "\n";
// $endl = "<br/>";

$subTreeCount =& $node->subTreeCount();
print( "Number of objects to index: $subTreeCount $endl" );
$subTree =& $node->subTree();

$updateCount = $subTreeCount / 100;
if ( $updateCount < 1 )
    $updateCount = 1;

$columnSize = 50;

$nodeCount = 0;
$updateCounter = 0;
$columnCounter = 0;
foreach ( $subTree as $node )
{
    $object = $node->attribute( 'object' );
    eZSearch::removeObject( $object );
    eZSearch::addObject( $object );
    ++$nodeCount;
    ++$updateCounter;
    print( "." );
    ++$columnCounter;
    if ( $columnCounter > $columnSize )
    {
        $percent = ($nodeCount*100.0) / $subTreeCount;
        $percentText = number_format( $percent, 2 );
        print( " " . $percentText . "%\n" );
        $columnCounter = 0;
        ob_end_flush();
    }
//     if ( $updateCounter > $updateCount )
//     {
//         $updateCounter = 0;
//         $percent = ($nodeCount*100.0) / $subTreeCount;
//         print( $percent . "%" . $endl );
//     }
}
if ( $columnCounter < $columnSize )
{
    print( str_repeat( ' ', $columnSize - $columnCounter + 1 ) );
    print( " 100.00%\n" );
}

print( $endl . "done" . $endl );

?>
