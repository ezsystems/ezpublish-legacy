<?php

$eZTemplateOperatorArray = array();

$eZTemplateOperatorArray[] = array( 'script' => 'extension/ezjscore/autoloads/ezjscpackertemplatefunctions.php',
                                    'class' => 'ezjscPackerTemplateFunctions',
                                    'operator_names' => array( 'ezscript',
                                                               'ezscriptfiles',
                                                               'ezcss',
                                                               'ezcssfiles' ) );


$eZTemplateOperatorArray[] = array( 'script' => 'extension/ezjscore/autoloads/ezjscencodingtemplatefunctions',
                                    'class' => 'ezjscEncodingTemplateFunctions',
                                    'operator_names' => array( 'json_encode',
                                                               'xml_encode',
                                                               'node_encode',
) );
?>
