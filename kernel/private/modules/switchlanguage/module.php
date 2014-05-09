<?php
/**
 * File containing the switchlanguage module definition
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

$Module = array( "name" => "SwitchLanguage",
                 "var_params" => false );

$ViewList = array();
$ViewList['to'] = array(
    "script" => "to.php",
    "params" => array( "SiteAccess" ),
    );

$FunctionList = array();

?>
