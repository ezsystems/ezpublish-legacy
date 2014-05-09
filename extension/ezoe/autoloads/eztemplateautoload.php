<?php
/**
 * Template autoload definition for eZ Online Editor
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 */

$eZTemplateOperatorArray = array();

$eZTemplateOperatorArray[] = array( 'script' => 'extension/ezoe/autoloads/ezoetemplateutils.php',
                                    'class' => 'eZOETemplateUtils',
                                    'operator_names' => array( 'ezoe_ini_section' ) );


?>