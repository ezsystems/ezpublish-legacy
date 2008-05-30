<?php

$eZTemplateOperatorArray = array();

$eZTemplateOperatorArray[] = array( 'script' => 'extension/ezoe/autoloads/ezoepacker.php',
                                    'class' => 'eZOEPacker',
                                    'operator_names' => array( 'ezoescript', 'ezoecss' ) );

$eZTemplateOperatorArray[] = array( 'script' => 'extension/ezoe/autoloads/ezoetemplateutils.php',
                                    'class' => 'eZOETemplateUtils',
                                    'operator_names' => array( 'ezoe_ini_section' ) );


?>