<?php
//
// Created on: <28-Mar-2008 00:00:00 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor MCE extension for eZ Publish
// SOFTWARE RELEASE: 1.x
// COPYRIGHT NOTICE: Copyright (C) 2008 eZ Systems AS
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

include_once( 'extension/ezoe/classes/ezajaxcontent.php' );

class eZOEPackerFunctions
{
    public static function i18n( $args, $fileExtension )
    {
        if ( $fileExtension !== '.js' ) return '';
        

        // if we had wanted to always expire the cache we could have done like this
        // $cacheTime++
        // return 'javascript or css content';


        $i18nArray =  array( 'en' => array(
            'common' => array(
                'edit_confirm' => ezi18n( 'design/standard/ezoe', ''),
                'apply' => ezi18n( 'design/standard/ezoe', ''),
                'insert' => ezi18n( 'design/standard/ezoe', ''),
                'update' => ezi18n( 'design/standard/ezoe', ''),
                'cancel' => ezi18n( 'design/standard/ezoe', ''),
                'close' => ezi18n( 'design/standard/ezoe', ''),
                'browse' => ezi18n( 'design/standard/ezoe', ''),
                'class_name' => ezi18n( 'design/standard/ezoe', ''),
                'not_set' => ezi18n( 'design/standard/ezoe', ''),
                'clipboard_msg' => ezi18n( 'design/standard/ezoe', ''),
                'clipboard_no_support' => ezi18n( 'design/standard/ezoe', ''),
                'popup_blocked' => ezi18n( 'design/standard/ezoe', ''),
                'invalid_data' => ezi18n( 'design/standard/ezoe', ''),
                'more_colors' => ezi18n( 'design/standard/ezoe', '')
            ),
        ));
        $i18nString = eZAjaxContent::jsonEncode( $i18nArray );
        
        return 'tinyMCE.addI18n( ' . $i18nString . ' );';
    }

    public static function getCacheTime( $functionName )
    {
        // this data never expires
        return 0;
    }
}

?>