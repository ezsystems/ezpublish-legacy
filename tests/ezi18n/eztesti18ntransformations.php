<?php
//
// Definition of eZTestI18NTransformations class
//
// Created on: <22-Nov-2004 13:48:38 jb>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

/*! \file eztesti18ntransformations.php
*/

/*!
  \class eZTestI18NTransformations eztesti18ntransformations.php
  \brief The class eZTestI18NTransformations does

*/

class eZTestI18NTransformations extends eZTestCase
{
    /*!
     Constructor
    */
    function eZTestI18NTransformations( $name = false )
    {
        $this->eZTestCase( $name );
        include_once( 'lib/ezi18n/classes/ezchartransform.php' );

        $charsets = array( 'latin1' );

        foreach ( $charsets as $charset )
        {
            foreach ( glob( 'tests/ezi18n/trans/latin1/*.src' ) as $file )
            {
                $name = str_replace( 'tests/ezi18n/trans/' . $charset . '/', '', $file );
                $name = str_replace( '.src', '', $name );
                $name = ucwords( $charset ) . '-' . ucwords( $name );
                $this->addTest( 'testTransformation', $name, array( 'file' => $file,
                                                                    'charset' => $charset ) );
            }
        }
    }

    /*!
    */
    function testTransformation( &$tr, $params )
    {
        $file = $params['file'];
        $charset = $params['charset'];

        if ( !file_exists( $file ) )
        {
            $tr->assert( false, 'Missing source test file ' . $file );
            return;
        }

        $expectedFileName = str_replace( '.src', '.exp', $file );
        if ( !file_exists( $file ) )
        {
            $tr->assert( false, 'Missing expected test file ' . $file );
            return;
        }

        $source = file_get_contents( $file );
        $expected = file_get_contents( $expectedFileName );

        if ( !preg_match( "#^((g:([a-zA-Z0-9_-]+))|(r:([a-zA-Z0-9_,-]+)))#", $source, $matches ) )
        {
            $tr->assert( false, 'No group or rule defined in transformation file ' . $file );
            return;
        }
        $pos = strpos( $source, "\n" );
        if ( $pos !== false )
            $source = substr( $source, $pos + 1 );

        $ct =& eZCharTransform::instance();
        if ( isset( $matches[2] ) )
        {
            $group = $matches[3];
            $actual = $ct->transformByGroup( $source, $group, $charset );
        }
        else if ( isset( $matches[4] ) )
        {
            $rules = explode( ',', $matches[5] );
            $actual = $ct->transform( $source, $rules, $charset );
        }

        $actualFileName = str_replace( '.src', '.out', $file );
        $fp = fopen( $actualFileName, 'w' );
        fwrite( $fp, $actual );
        fclose( $fp );

        $tr->assert( strcmp( $actual, $expected ) == 0, 'String compare of transformed text' );
    }

}

?>
