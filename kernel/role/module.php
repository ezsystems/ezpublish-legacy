<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

$Module = array( 'name' => 'eZRole' );

$ViewList = array();
$ViewList['list'] = array(
    'script' => 'list.php',
    'default_navigation_part' => 'ezusernavigationpart',
    'post_actions' => array( 'BrowseActionName' ),
    'unordered_params' => array( 'offset' => 'Offset' ),
    'params' => array(  ) );
$ViewList['edit'] = array(
    'script' => 'edit.php',
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezusernavigationpart',
    'params' => array( 'RoleID' ) );
$ViewList['copy'] = array(
    'script' => 'copy.php',
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezusernavigationpart',
    'params' => array( 'RoleID' ) );
$ViewList['policyedit'] = array(
    'script' => 'policyedit.php',
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezusernavigationpart',
    'params' => array( 'PolicyID' ) );
$ViewList['view'] = array(
    'script' => 'view.php',
    'default_navigation_part' => 'ezusernavigationpart',
    'post_actions' => array( 'BrowseActionName' ),
    'params' => array( 'RoleID' ) );
$ViewList['assign'] = array(
    'script' => 'assign.php',
    'default_navigation_part' => 'ezusernavigationpart',
    'params' => array( 'RoleID', 'LimitIdent', 'LimitValue' ) );

?>
