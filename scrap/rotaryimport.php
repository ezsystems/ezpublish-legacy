#!/usr/local/php/bin/php
<?php

set_time_limit( 0 );

print( "Starting Rotary import\n" );
include_once( "lib/ezutils/classes/ezdebug.php" );

//eZDebug::setHandleType( EZ_HANDLE_FROM_PHP );

include_once( "lib/ezutils/classes/ezmodule.php" );
eZModule::setGlobalPathList( array( "kernel" ) );
include_once( 'lib/ezutils/classes/ezexecution.php' );

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
    print( "The execution of eZ publish was abruptly ended." );
}

eZExecution::addCleanupHandler( 'eZDBCleanup' );
eZExecution::addFatalErrorHandler( 'eZFatalError' );

$db =& eZDB::instance();
$db->setIsSQLOutputEnabled( false );

//$lines =& file( 'rotaryimport.csv' );

$klubbFolder =& eZContentObjectTreeNode::fetch( 16 );

$handle = fopen ( $argv[1], "r");
while ( !feof( $handle ) )
{
    $line = fgets( $handle, 4096 );
    $columns = explode( ';', $line );
    // Check that the line contains enough elements
    $columnCount = count( $columns );
    // Get klubb
    $klubb = $columns[1];

    $klubbNode =& createKlubb( $klubb, $columns[0] );
    createMedlem( $klubbNode, $columns );
    flush();
}
fclose( $handle );

print("\ndone!\n\n" );
eZExecution::cleanup();
eZExecution::setCleanExit();

function &createKlubb( $klubb, $distrikt )
{
    // Check if klubb exists
    global $klubbFolder;

    $existingKlubb =& $klubbFolder->childrenByName( $klubb );

    if ( count( $existingKlubb ) == 0 )
    {
        print( "Klubb does not exist, creating $klubb\n" );
        // Create Arkiv node
        $class =& eZContentClass::fetch( 6 );
        // Create object by user 14 in section 1
        $contentObject =& $class->instantiate( 14, 1 );
        $nodeAssignment =& eZNodeAssignment::create( array(
                                                         'contentobject_id' => $contentObject->attribute( 'id' ),
                                                         'contentobject_version' => $contentObject->attribute( 'current_version' ),
                                                         'parent_node' => 16,
                                                         'is_main' => 1
                                                         )
                                                     );

        $version =& $contentObject->version( 1 );
        $contentObjectAttributes =& $version->contentObjectAttributes();

        $contentObjectAttributes[0]->setAttribute( 'data_text', $klubb );
        $contentObjectAttributes[0]->store();

        $contentObjectAttributes[1]->setAttribute( 'data_text', $distrikt );
        $contentObjectAttributes[1]->store();

        $version->setAttribute( 'modified', eZDateTime::currentTimeStamp() );
        $version->setAttribute( 'status', EZ_VERSION_STATUS_DRAFT );

        $version->store();

        $nodeAssignment->store();
        include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
        $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObject->attribute( 'id' ),
                                                                                     'version' => 1 ) );
        $klubbObject =& eZContentObject::fetch( $contentObject->attribute( 'id' ) );
        $klubbNode = $klubbObject->attribute( 'main_node' );
    }
    else
    {
        $klubbNode = $existingKlubb[0];
    }
    return $klubbNode;
}


function createMedlem( &$klubbNode, $columns )
{
    print( '.' );
    // Create Arkiv node
    $class =& eZContentClass::fetch( 7 );
    // Create object by user 14 in section 1
    $contentObject =& $class->instantiate( 14, 1 );
    $nodeAssignment =& eZNodeAssignment::create( array(
                                                     'contentobject_id' => $contentObject->attribute( 'id' ),
                                                     'contentobject_version' => $contentObject->attribute( 'current_version' ),
                                                     'parent_node' => $klubbNode->attribute( 'node_id' ),
                                                     'is_main' => 1
                                                     )
                                                 );

    $version =& $contentObject->version( 1 );
    $contentObjectAttributes =& $version->contentObjectAttributes();

    $contentObjectAttributes[0]->setAttribute( 'data_text', $columns[2] );
    $contentObjectAttributes[0]->store();

    $contentObjectAttributes[1]->setAttribute( 'data_text', $columns[3]  );
    $contentObjectAttributes[1]->store();

    // Kjønn
    $contentObjectAttributes[2]->setAttribute( 'data_text', $columns[4]  );
    $contentObjectAttributes[2]->store();

    // Tittel
    $contentObjectAttributes[3]->setAttribute( 'data_text', $columns[5]  );
    $contentObjectAttributes[3]->store();

    // Født
    $contentObjectAttributes[4]->setAttribute( 'data_text', $columns[6]  );
    $contentObjectAttributes[4]->store();

    // Priv adr
    $contentObjectAttributes[5]->setAttribute( 'data_text', $columns[7]  );
    $contentObjectAttributes[5]->store();

    // Priv postadr
    $contentObjectAttributes[6]->setAttribute( 'data_text', $columns[8]  );
    $contentObjectAttributes[6]->store();

    // Priv postnr
    $contentObjectAttributes[7]->setAttribute( 'data_text', $columns[9]  );
    $contentObjectAttributes[7]->store();

    // Priv poststed
    $contentObjectAttributes[8]->setAttribute( 'data_text', $columns[10]  );
    $contentObjectAttributes[8]->store();

    // Priv tlf
    $contentObjectAttributes[9]->setAttribute( 'data_text', $columns[11]  );
    $contentObjectAttributes[9]->store();

    // Opptaksår
    $contentObjectAttributes[10]->setAttribute( 'data_int', $columns[12] );
    $contentObjectAttributes[10]->store();

    // Første opptak
    $contentObjectAttributes[11]->setAttribute( 'data_text', $columns[13]  );
    $contentObjectAttributes[11]->store();

    // Opptaksklubb
    $contentObjectAttributes[12]->setAttribute( 'data_text', $columns[14]  );
    $contentObjectAttributes[12]->store();

    // Klassifikasjon
    $contentObjectAttributes[13]->setAttribute( 'data_text', $columns[15]  );
    $contentObjectAttributes[13]->store();

    // Honorary
    $contentObjectAttributes[14]->setAttribute( 'data_text', $columns[16]  );
    $contentObjectAttributes[14]->store();

    // Phf år
    $contentObjectAttributes[15]->setAttribute( 'data_text', $columns[17]  );
    $contentObjectAttributes[15]->store();

    // Verv i år
    $contentObjectAttributes[16]->setAttribute( 'data_text', $columns[18]  );
    $contentObjectAttributes[16]->store();

    // Firma
    $contentObjectAttributes[17]->setAttribute( 'data_text', $columns[19]  );
    $contentObjectAttributes[17]->store();

    // Firma Adr
    $contentObjectAttributes[18]->setAttribute( 'data_text', $columns[20]  );
    $contentObjectAttributes[18]->store();

    // Firma postnr
    $contentObjectAttributes[19]->setAttribute( 'data_text', $columns[21]  );
    $contentObjectAttributes[19]->store();

    // Firma poststed
    $contentObjectAttributes[20]->setAttribute( 'data_text', $columns[22]  );
    $contentObjectAttributes[20]->store();

    // Firma tlf
    $contentObjectAttributes[21]->setAttribute( 'data_text', $columns[23]  );
    $contentObjectAttributes[21]->store();

    // Møtetsted
    $contentObjectAttributes[22]->setAttribute( 'data_text', $columns[24]  );
    $contentObjectAttributes[22]->store();

    $version->setAttribute( 'modified', eZDateTime::currentTimeStamp() );
    $version->setAttribute( 'status', EZ_VERSION_STATUS_DRAFT );

    $version->store();

    $nodeAssignment->store();
    include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
    $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObject->attribute( 'id' ),
                                                                                 'version' => 1 ) );
}
?>
