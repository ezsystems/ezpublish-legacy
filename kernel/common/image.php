<?php
//
// Definition of Image class
//
// Created on: <08-May-2002 10:15:05 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

function imageInit()
{
    //include_once( 'lib/ezimage/classes/ezimagemanager.php' );
    //include_once( 'lib/ezimage/classes/ezimageanalyzer.php' );

    if ( isset( $GLOBALS['eZPublishImageManager'] ) )
    {
        return $GLOBALS['eZPublishImageManager'];
    }

    $GLOBALS['eZPublishImageManager'] = eZImageManager::instance();

    $GLOBALS['eZPublishImageManager']->readINISettings();

    eZImageAnalyzer::readAnalyzerSettingsFromINI();

    return $GLOBALS['eZPublishImageManager'];
}

?>
