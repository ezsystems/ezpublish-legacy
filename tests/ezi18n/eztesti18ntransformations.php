<?php
//
// Definition of eZTestI18NTransformations class
//
// Created on: <22-Nov-2004 13:48:38 jb>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
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
