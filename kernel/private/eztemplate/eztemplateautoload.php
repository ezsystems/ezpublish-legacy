<?php
/**
 * File containing template autoload definitions for private kernel area.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

$eZTemplateOperatorArray = array();
$eZTemplateOperatorArray[] = array( 'script' => 'kernel/private/eztemplate/ezplanguageswitcheroperator.php',
                                    'class' => 'ezpLanguageSwitcherOperator',
                                    'operator_names' => array( 'language_switcher' ) );

?>