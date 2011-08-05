<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = array( "name" => "eZContentClass" );

$ViewList = array();
$ViewList["edit"] = array(
    "script" => "edit.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "ClassID", "GroupID", "GroupName" ),
    'unordered_params' => array( 'language' => 'Language' )
    );
$ViewList["view"] = array(
    "script" => "view.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "ClassID" ),
    'unordered_params' => array( 'language' => 'Language',
                                 'scriptid' => 'ScheduledScriptID' ) );
$ViewList["copy"] = array(
    "script" => "copy.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "ClassID" ) );
$ViewList["down"] = array(
    "script" => "edit.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "ClassID", "AttributeID" ) );
$ViewList["up"] = array(
    "script" => "edit.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "ClassID", "AttributeID" ) );
$ViewList["removeclass"] = array(
    "script" => "removeclass.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "GroupID" ) );
$ViewList["removegroup"] = array(
    "script" => "removegroup.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array() );
$ViewList["classlist"] = array(
    "script" => "classlist.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "GroupID" ) );
$ViewList["grouplist"] = array(
    "script" => "grouplist.php",
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array() );
$ViewList["groupedit"] = array(
    "script" => "groupedit.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezsetupnavigationpart',
    "params" => array( "GroupID" ) );
$ViewList['translation'] = array(
    'script' => 'translation.php',
    'default_navigation_part' => 'ezsetupnavigationpart',
    'ui_context' => 'edit',
    'params' => array(  ),
    'single_post_actions' => array( 'CancelButton' => 'Cancel',
                                    'UpdateInitialLanguageButton' => 'UpdateInitialLanguage',
                                    'RemoveTranslationButton' => 'RemoveTranslation' ),
    'post_action_parameters' => array( 'Cancel' => array( 'ClassID' => 'ContentClassID',
                                                          'LanguageCode' => 'ContentClassLanguageCode' ),
                                       'UpdateInitialLanguage' => array( 'ClassID' => 'ContentClassID',
                                                                         'LanguageCode' => 'ContentClassLanguageCode',
                                                                         'InitialLanguageID' => 'InitialLanguageID' ),
                                       'RemoveTranslation' => array( 'ClassID' => 'ContentClassID',
                                                                     'LanguageCode' => 'ContentClassLanguageCode',
                                                                     'LanguageID' => 'LanguageID',
                                                                     'ConfirmRemoval' => 'ConfirmRemoval' ) ) );

?>
