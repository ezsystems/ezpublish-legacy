<?php
//
// Definition of eZCharTransform class
//
// Created on: <16-Jul-2004 15:54:21 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

/*! \file ezchartransform.php
*/

/*!
  \class eZCharTransform ezchartransform.php
  \brief Performs rule based transformation of characters in a string

  \sa eZCodeMapper
*/

define( 'EZ_CHARTRANSFORM_CODEDATE', 1089984686 );

class eZCharTransform
{
    /*!
     Constructor
    */
    function eZCharTransform()
    {
        $this->Mapper = false;
    }

    /*!
     \static
     Transforms the text according to the rules defined in \a $rule using character set \a $charset.
     \param $text The text string to be converted, currently Unicode arrays are not supported
     \param $rule Which transformation rule to use, can either be a string identifier or an array with identifiers.
     \param $charset Which charset to use when transforming, if \c false it will use current charset (i18n.ini).
    */
    function transform( $text, $rule, $charset = false )
    {
        return strtr( $text, $this->charsetTable( $rule, $charset ) );
    }

    /*!
     \static
     \return the path of the cached transformation tables.
    */
    function cachedTransformationPath()
    {
        $dir =& $GLOBALS['eZCodeMapperCachePath'];
        if ( isset( $dir ) )
            return $dir;

        include_once( 'lib/ezutils/classes/ezsys.php' );
        $sys =& eZSys::instance();
        $dir = $sys->cacheDirectory() . '/trans';
        return $dir;
    }

    /*!
     \static
     Returns the charset transformation table for rule \a $rule using charset \a $charset.

     It will try to restore the table from a cache file if possible, if not it will recreate it
     and store it on disk then return it.
    */
    function charsetTable( $rule, $charset = false )
    {
        // CRC32 is used for speed, MD5 would be more unique but is slower
        $key = crc32( ( is_array( $rule ) ? implode( ',', $rule ) : $rule ) . '-' . $charset );

        $path = eZCharTransform::cachedTransformationPath();
        if ( !file_exists( $path ) )
        {
            include_once( 'lib/ezfile/classes/ezdir.php' );
            eZDir::mkdir( $path, false, true );
        }
        $filepath = $path . '/' . sprintf( "%u", $key ) . '.ctt'; // ctt=charset transform table
        if ( file_exists( $filepath ) )
        {
            $time = filemtime( $filepath );
            if ( $time >= EZ_CHARTRANSFORM_CODEDATE )
            {
                eZDebug::writeDebug( "restore $filepath" );
                $data = unserialize( file_get_contents( $filepath ) );
                return $data['table'];
            }
        }

        if ( $this->Mapper === false )
        {
            include_once( 'lib/ezi18n/classes/ezcodemapper.php' );
            $this->Mapper = new eZCodeMapper();
        }

        $unicodeTable = $this->Mapper->generateMappingCode( $rule );
        $charsetTable = $this->Mapper->generateCharsetMappingTable( $unicodeTable, $charset );
        unset( $unicodeTable );

        $fd = @fopen( $filepath, 'wb' );
        if ( $fd )
        {
            eZDebug::writeDebug( "store $filepath" );
            @fwrite( $fd, serialize( array( 'table' => $charsetTable ) ) );
            @fclose( $fd );
        }
        else
        {
            eZDebug::writeError( "Failed to store transformation table $filepath" );
        }

        return $charsetTable;
    }

}

?>
