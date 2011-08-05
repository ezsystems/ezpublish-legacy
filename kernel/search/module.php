<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = array( "name" => "eZSearch",
                 "variable_params" => true );

$ViewList = array();

$ViewList["stats"] = array(
    "script" => "stats.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    'single_post_actions' => array( 'ResetSearchStatsButton' => 'ResetSearchStats' ),
    "params" => array( ),
    "unordered_params" => array( "offset" => "Offset" ) );

?>
