<?php
set_time_limit( 0 );

$showDebug = false;
$fixErrors = true;

$fixAttribute = true;
$fixURL = true;

include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezutils/classes/ezini.php" );
include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );

// eZDebug::setHandleType( EZ_HANDLE_TO_PHP );
eZDebug::setHandleType( EZ_HANDLE_FROM_PHP );
eZDebug::setLogFileEnabled( false );
eZINI::setIsCacheEnabled( false );

include_once( 'lib/ezutils/classes/ezexecution.php' );

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
    print( "Fatal error: eZ publish did not finish its request\n" );
    print( "The execution of eZ publish was abruptly ended." );
}


eZExecution::addCleanupHandler( 'eZDBCleanup' );
eZExecution::addFatalErrorHandler( 'eZFatalError' );
include_once( 'kernel/classes/ezcontentclassattribute.php' );
include_once( 'kernel/classes/ezcontentobjectattribute.php' );
include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezbinaryfilehandler.php' );
include_once( 'kernel/classes/datatypes/ezbinaryfile/ezbinaryfile.php' );

include_once( 'lib/ezdb/classes/ezdb.php' );

$db =& eZDB::instance();
$db->setIsSQLOutputEnabled( false );


// Get top node
$mainNode =& eZContentObjectTreeNode::fetch( 4132 );


print( "Checking books\n\n" );
// Find all annonser
$subTree =& $mainNode->subTree( array ( 'ClassFilterType' => 'include',
                                        'ClassFilterArray' => array( 10 )
                                        ) );

foreach ( $subTree as $node )
{
    $object =& $node->attribute( 'object' );

    $dataMap =& $object->dataMap();

    $forfatter =& $dataMap['forfatter'];
    $isbn =& $dataMap['isbn'];
    print( $forfatter->attribute('data_text') . " (");
    print( $isbn->attribute('data_text') . ") " );

    // Fetch real forfatter
//    $res = $db->arrayQuery( "SELECT * FROM Book WHERE ISBN='" . $isbn->attribute('data_text') . "'" );
    $res = $db->arrayQuery( "SELECT * FROM Forfatter_Book WHERE Book_Id='" . $isbn->attribute('data_text') . "'" );
    $forfatterNavn = $res[0]['Forfatter_Navn'];

    $forfatter->setAttribute( 'data_text', $forfatterNavn );
    $forfatter->store();
    print( "$forfatterNavn\n" );
}

print( "done\n\n\n\n" );

eZExecution::cleanup();
eZExecution::setCleanExit();

?>
