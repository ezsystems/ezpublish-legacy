#!/usr/bin/env php
<?php

require 'autoload.php';

$cli = eZCLI::instance();

$scriptSettings = array();
$scriptSettings['description'] = 'Add the ez_lock system state group';
$scriptSettings['use-session'] = true;
$scriptSettings['use-modules'] = true;
$scriptSettings['use-extensions'] = true;

$script = eZScript::instance( $scriptSettings );
$script->startup();

$config = '';
$argumentConfig = '';
$optionHelp = false;
$arguments = false;
$useStandardOptions = true;

$options = $script->getOptions( $config, $argumentConfig, $optionHelp, $arguments, $useStandardOptions );
$script->initialize();

eZContentObjectStateGroup::$allowInternalCUD = true;

$lockGroup = eZContentObjectStateGroup::fetchByIdentifier( 'ez_lock' );
if ( $lockGroup )
{
    $script->shutdown( 1, 'ez_lock state group already exists' );
}

$db = eZDB::instance();
$db->begin();

$locales = eZContentLanguage::fetchLocaleList();

$localeToUse = false;
$localeIDToUse = false;

// this script inserts English names, so preferably use an English locale
$preferredLocales = array( 'eng-GB', 'eng-US' );

foreach( $preferredLocales as $preferredLocale )
{
    if ( in_array( $preferredLocale, $locales ) )
    {
        $localeToUse = $preferredLocale;
        break;
    }
}

// when none of the preferred locales are in use, then use the top priority language
if ( $localeToUse === false )
{
    $prioritizedLanguage = eZContentLanguage::topPriorityLanguage();
    $localeToUse = $prioritizedLanguage->attribute( 'locale' );
}

$lockGroup = new eZContentObjectStateGroup( array( 'identifier' => 'ez_lock' ) );

$trans = $lockGroup->translationByLocale( $localeToUse );
$trans->setAttribute( 'name', 'Lock' );
$trans->setAttribute( 'description', 'Lock group' );
$messages = array();
if ( $lockGroup->isValid( $messages ) )
{
    $cli->output( 'storing state group ez_lock' );
    $lockGroup->store();
}
else
{
    eZDebug::writeDebug( $messages );
    $db->rollback();
    $script->shutdown( 2 );
}

$notLockedState = $lockGroup->newState( 'not_locked' );
$trans = $notLockedState->translationByLocale( $localeToUse );
$trans->setAttribute( 'name', 'Not locked' );
$trans->setAttribute( 'description', 'Not locked state' );
$messages = array();
if ( $notLockedState->isValid( $messages ) )
{
    $cli->output( 'storing state ez_lock/not_locked' );
    $notLockedState->store();
}
else
{
    eZDebug::writeDebug( $messages );
    $db->rollback();
    $script->shutdown( 2 );
}

$lockedState = $lockGroup->newState( 'locked' );
$trans = $lockedState->translationByLocale( $localeToUse );
$trans->setAttribute( 'name', 'Locked' );
$trans->setAttribute( 'description', 'Locked state' );
$messages = array();
if ( $lockedState->isValid( $messages ) )
{
    $cli->output( 'storing state ez_lock/locked' );
    $lockedState->store();
}
else
{
    eZDebug::writeDebug( $messages );
    $db->rollback();
    $script->shutdown( 2 );
}

$db->commit();

$script->shutdown( 0 );

?>
