<?php
//
// Created on: <04-Feb-2004 21:56:50 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

$Module = $Params['Module'];


define( 'MD5_SUM_LIST_FILE', 'share/filelist.md5' );

$tpl = eZTemplate::factory();

$tpl->setVariable( 'md5_result', false );
$tpl->setVariable( 'upgrade_sql', false );

if ( $Module->isCurrentAction( 'MD5Check' ) )
{
    if ( !file_exists( MD5_SUM_LIST_FILE ) )
    {
        $tpl->setVariable( 'md5_result', 'failed' );
        $tpl->setVariable( 'failure_reason',
                           ezpI18n::tr( 'kernel/setup', 'File %1 does not exist. '.
                                    'You should copy it from the recent eZ Publish distribution.',
                                    null, array( MD5_SUM_LIST_FILE ) ) );
    }
    else
    {
        $checkResult = eZMD5::checkMD5Sums( 'share/filelist.md5' );

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
    foreach( eZextension::activeExtensions() as $activeextension )
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