<?php
// URI:       design:content/datatype/view/ezxmltags/object.tpl
// Filename:  design/standard/templates/content/datatype/view/ezxmltags/object.tpl
// Timestamp: 1062422393 (Mon Sep 1 15:19:53 CEST 2003)
$eZTemplateCompilerCodeDate = 1069426958;
include_once( "var/forum/cache/template/compiled/common.php" );

$vars =& $tpl->Variables;

$text = "";

$textElements = array();
$tpl->processFunction( "content_view_gui", $textElements,
                       false,
                       array( "view" => array( array( 4,
                                                     array( "",
                                                            2,
                                                            "view" ),
                                                     false ) ),
                             "attribute_parameters" => array( array( 4,
                                                                     array( "",
                                                                            2,
                                                                            "object_parameters" ),
                                                                     false ) ),
                             "content_object" => array( array( 4,
                                                               array( "",
                                                                      2,
                                                                      "object" ),
                                                               false ) ) ),
                       array( array( 1,
                                    0,
                                    1 ),
                             array( 1,
                                    90,
                                    91 ),
                             "design/standard/templates/content/datatype/view/ezxmltags/object.tpl" ),
                       $rootNamespace, $currentNamespace );
$text .= implode( '', $textElements );

$text .= "\n\n\n";


?>
