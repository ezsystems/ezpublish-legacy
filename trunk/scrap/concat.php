<?php

function concat_delimited()
{
    $numargs = func_num_args();
    $argList = func_get_args();
    $text = null;
    if ( $numargs > 1 )
    {
        $delimit = $argList[0];
        $text = implode( $delimit, array_flatten( array_splice( $argList, 1 ) ) );
    }
    return $text;
}

function concat()
{
    $numargs = func_num_args();
    $argList = func_get_args();
    $text = null;
    if ( $numargs > 0 )
    {
        $text = implode( '', array_flatten( $argList ) );
    }
    return $text;
}

function array_flatten_helper( $flattened, $input )
{
    if ( is_array( $input ) )
        return array_merge( $flattened, array_reduce( $input, "array_flatten_helper", array() ) );
    array_push( $flattened, $input );
    return $flattened;
}

function array_flatten( $array )
{
    return array_reduce( $array, "array_flatten_helper", array() );
}

print( concat() . "\n" );
print( concat( 1, 2, 5 ) . "\n" );
print( concat( 1, array( 2, 5 ), array( 3, array( 4, 8 ), 9 ) ) . "\n" );
print( concat_delimited( ', ', 1, array( 2, 5 ), array( 3, array( 4, 8 ), 9 ) ) . "\n" );
$of = 'of';
$power = 'power';
print( concat( 'a test ', $of, ' concat ', $power ) . "\n" );


// include_once( "lib/ezutils/classes/eztexttool.php" );

// $array1 = array( "id" => array( 1, 4, 5 ),
//                  "version" => 0 );
// $array1_1 = array( array( "id" => 1,
//                           "id" => 4,
//                           "id" => 5 ),
//                    array( "version" => 0 ) );
// $array1_2 = array( '(', 'ID', "='", 1, "'", ' OR ', 'ID', "='", 2, "'", ')',
//                    ' AND ',
//                    'version', "='", 0, "'" );
// $array2 = array( "is_enabled" => 0,
//                  "version" => 3 );
// $array2_1 = array( array( '', "is_enabled", "='", 0, "'" ),
//                    array( '', "version", "='", 3, "'" ) );
// $array2_2 = array( array( '', "is_enabled", "='", 0, "'" ),
//                    ', ',
//                    array( '', "version", "='", 3, "'" ) );

// // print( eZTextTool::concatDelimited( ', ', eZTextTool::arrayKeyValueWrap( $array2, '', "='", "'" ) ) . "\n" );
// print_r( eZTextTool::arrayAddDelimiter( eZTextTool::arrayElevateKeys( $array2, '', "='", "'" ), ', ' ) );
// print_r( eZTextTool::arrayAddDelimiter( $array1['id'], ', ', "'", "'" ) );

// print( concat( $array2_2 ) . "\n" );


// UPDATE ez... SET is_enabled='1' WHERE (ID='1' OR ID='2') AND version='0';
// UPDATE ez... SET is_enabled='1' WHERE ID IN ('1', '2') AND version='0';
// ( array( array( '', '', ' AND ' ),
//          array( '(', ')', ' OR ' ),
//          array( '', "='", "'" ) ) );
// ( array( '', '', ' AND ',
//          array( '(', ')', ' OR ',
//                 array( '', "='", "'" ) ) ) );

// include_once( "kernel/classes/ezworkflow.php" );
// include_once( "kernel/classes/ezpersistentobject.php" );

// function getmicrotime()
// {
//     list($usec, $sec) = explode(" ",microtime());
//     return ((float)$usec + (float)$sec);
// }

// $query = eZPersistentObject::updateObjectList3( array(
//                                                     "definition" => eZWorkflowEvent::definition(),
//                                                     "update_fields" => array( "is_enabled" => 0 ),
//                                                     "conditions" => array( "id" => range( 1, 100 ),
//                                                                            "version" => 0 )
//                                                     ) );
// print( $query.  "\n" );

// $start_t = getmicrotime();

// for ( $i = 0; $i < 40; ++$i )
// {
//     $query = eZPersistentObject::updateObjectList( array(
//                                                        "definition" => eZWorkflowEvent::definition(),
//                                                        "update_fields" => array( "is_enabled" => 0 ),
//                                                        "conditions" => array( "id" => range( 1, 100 ),
//                                                                               "version" => 0 )
//                                                        ) );
// }

// $end_t = getmicrotime();
// $diff_t = $end_t - $start_t;

// print( "updateObjectList took $diff_t secs\n" );

// $start_t = getmicrotime();

// for ( $i = 0; $i < 40; ++$i )
// {
//     $query = eZPersistentObject::updateObjectList2( array(
//                                                         "definition" => eZWorkflowEvent::definition(),
//                                                         "update_fields" => array( "is_enabled" => 0 ),
//                                                         "conditions" => array( "id" => range( 1, 100 ),
//                                                                                "version" => 0 )
//                                                         ) );
// }

// $end_t = getmicrotime();
// $diff_t = $end_t - $start_t;

// print( "updateObjectList2 took $diff_t secs\n" );

// $start_t = getmicrotime();

// for ( $i = 0; $i < 40; ++$i )
// {
//     $query = eZPersistentObject::updateObjectList3( array(
//                                                         "definition" => eZWorkflowEvent::definition(),
//                                                         "update_fields" => array( "is_enabled" => 0 ),
//                                                         "conditions" => array( "id" => range( 1, 100 ),
//                                                                                "version" => 0 )
//                                                         ) );
// }

// $end_t = getmicrotime();
// $diff_t = $end_t - $start_t;

// print( "updateObjectList3 took $diff_t secs\n" );

// print( $query . "\n" );

?>
