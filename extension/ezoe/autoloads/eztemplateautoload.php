<?php
/**
 * Template autoload definition for eZ Online Editor
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

$eZTemplateOperatorArray = array();

$eZTemplateOperatorArray[] = array( 'script' => 'extension/ezoe/autoloads/ezoetemplateutils.php',
                                    'class' => 'eZOETemplateUtils',
                                    'operator_names' => array( 'ezoe_ini_section' ) );


?>