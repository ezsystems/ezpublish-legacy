<?php

include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezutils/classes/ezinputvalidator.php" );
include_once( "lib/ezutils/classes/ezregexpvalidator.php" );
include_once( "lib/ezutils/classes/ezintegervalidator.php" );

$data_items = array(
//     array( "name" => "lowercase word",
//                             "texts" => array( "abc",
//                                               "200",
//                                               "AbC",
//                                               "200m" ),
//                             "rule" => array( "accepted" => "/^[a-z]+$/",
//                                              "intermediate" => "/(^[a-zA-Z]+$)/e",
//                                              "fixup" => "strtolower('\\1')" ) ),
//                      array( "name" => "identifier",
//                             "texts" => array( "abc",
//                                               "200",
//                                               "AbC",
//                                               "200m",
//                                               "afdas@#_sdfQ#$%324rsdfF" ),
//                             "rule" => array( "accepted" => "/^([a-z0-9]+_?)+$/",
//                                              "intermediate" => "//",
//                                              "fixup" => array( "match" => array( "/^.+$/e",
//                                                                                  "/[^a-z0-9_ ]/" ,
//                                                                                  "/ /",
//                                                                                  "/__+/" ),
//                                                                "replace" => array( "strtolower('\\0')",
//                                                                                    "",
//                                                                                    "_",
//                                                                                    "_" ) ) ) ),
//                      array( "name" => "integer number",
//                             "validator" => new eZIntegerValidator( -200, 200 ),
//                             "texts" => array( "abc",
//                                               "200",
//                                               "-400",
//                                               "524",
//                                               "AbC",
//                                               "200m",
//                                               "abc-234,4def",
//                                               "asdpfokapsd200m" ) ),
                     array( "name" => "integer number",
                            "validator" => new eZIntegerValidator( false, false ),
                            "texts" => array( "abc",
                                              "200",
                                              "-400",
                                              "524",
                                              "AbC",
                                              "200m",
                                              "abc-234,4def",
                                              "asdpfokapsd200m" ) )//,
//                      array( "name" => "float number",
//                             "texts" => array( "abc",
//                                               "200",
//                                               "AbC",
//                                               "200m" ),
//                             "rule" => array( "accepted" => "/^[0-9]+\.[0-9]+$/",
//                                              "intermediate" => "/(^[0-9]+$)/",
//                                              "fixup" => "\\1.0" ) )
                     );
foreach( $data_items as $item )
{
    $name =& $item["name"];
    $texts =& $item["texts"];
    if ( isset( $item["validator"] ) )
    {
        $validator = $item["validator"];
    }
    else
    {
        $rule =& $item["rule"];
        $validator = new eZRegExpValidator( $rule );
    }
    eZDebug::writeNotice( "Name: $name" );
    foreach( $texts as $text )
    {
        $state = $validator->validate( $text );
        if ( $state == EZ_INPUT_VALIDATOR_STATE_INTERMEDIATE )
        {
            $fixup = $validator->fixup( $text );
            eZDebug::writeNotice( "'$text': intermediate, fixup '$fixup'" );
        }
        else if ( $state == EZ_INPUT_VALIDATOR_STATE_INVALID )
        {
            eZDebug::writeNotice( "'$text': invalid" );
        }
        else
        {
            eZDebug::writeNotice( "'$text': accepted" );
        }
    }
}

eZDebug::printReport( false, false );


?>
