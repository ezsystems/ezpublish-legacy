<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];


$tpl = eZTemplate::factory();

$tpl->setVariable( 'md5_result', false );
$tpl->setVariable( 'upgrade_sql', false );

if ( $Module->isCurrentAction( 'MD5Check' ) )
{
    if ( !file_exists( eZMD5::CHECK_SUM_LIST_FILE ) )
    {
        $tpl->setVariable( 'md5_result', 'failed' );
        $tpl->setVariable( 'failure_reason',
                           ezpI18n::tr( 'kernel/setup', 'File %1 does not exist. '.
                                    'You should copy it from the recent eZ Publish distribution.',
                                    null, array( eZMD5::CHECK_SUM_LIST_FILE ) ) );
    }
    else
    {
        $checkResult = eZMD5::checkMD5Sums( eZMD5::CHECK_SUM_LIST_FILE );

        $extensionsDir = eZExtension::baseDirectory();
        foreach( eZextension::activeExtensions() as $activeExtension )
        {
            $extensionPath = "$extensionsDir/$activeExtension/";
            if ( file_exists( $extensionPath . eZMD5::CHECK_SUM_LIST_FILE ) )
            {
                $checkResult = array_merge( $checkResult, eZMD5::checkMD5Sums( $extensionPath . eZMD5::CHECK_SUM_LIST_FILE, $extensionPath ) );
            }
        }

        if ( count( $checkResult ) == 0 )
        {
            $tpl->setVariable( 'md5_result', 'ok' );
        }
        else
        {
            $tpl->setVariable( 'md5_result', $checkResult );
        }
    }
}

if ( $Module->isCurrentAction( 'DBCheck' ) )
{
    $db = eZDB::instance();
    $dbSchema = eZDbSchema::instance();
    // read original schema from dba file
    $originalSchema = eZDbSchema::read( 'share/db_schema.dba' );

    // merge schemas from all active extensions that declare some db schema
    $extensionsdir = eZExtension::baseDirectory();
    foreach( eZExtension::activeExtensions() as $activeextension )
    {
        if ( file_exists( $extensionsdir . '/' . $activeextension . '/share/db_schema.dba' ) )
        {
            if ( $extensionschema = eZDbSchema::read( $extensionsdir . '/' . $activeextension . '/share/db_schema.dba' ) )
            {
                $originalSchema = eZDbSchema::merge( $originalSchema, $extensionschema );
            }
        }
    }

    // transform schema to 'localized' version for current db
    // (we might as well convert $dbSchema to generic format and diff in generic format,
    // but eZDbSchemaChecker::diff does not know how to re-localize the generated sql
    $dbSchema->transformSchema( $originalSchema, true );
    $differences = eZDbSchemaChecker::diff( $dbSchema->schema( array( 'format' => 'local', 'force_autoincrement_rebuild' => true ) ), $originalSchema );
    $sqlDiff = $dbSchema->generateUpgradeFile( $differences );

    if ( strlen( $sqlDiff ) == 0 )
    {
        $tpl->setVariable( 'upgrade_sql', 'ok' );
    }
    else
    {
        $tpl->setVariable( 'upgrade_sql', $sqlDiff );
    }
}

$Result = array();
$Result['content'] = $tpl->fetch( "design:setup/systemupgrade.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/setup', 'System Upgrade' ) ) );
?>
