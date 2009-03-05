<?php
//
// Definition of eZMimeType class
//
// Created on: <15-Aug-2002 12:44:57 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

/*! \file
*/

/*!
  \class eZMimeType ezmimetype.php
  \ingroup eZUtils
  \brief Detection and management of MIME types.

  The MIME type structure is an array with the following items:
  - name     - The name of MIME type, eg. image/jpeg
  - suffixes - An array with possible suffixes for the filename, the first entry can be used as when creating filenames of the type.
               If no known suffixes exists this value is \a false
  - prefixes - An array with possible prefixes for the filename, the first entry can be used as when creating filenames of the type.
               If no known prefixes exists this value is \a false
  - is_valid - Boolean which tells whether this MIME type is valid or not, usually this is set to \c false if no
               match for the file was found in which case the name will also be application/octet-stream
  - url      - The original url or file supplied to the matching system, can be \c false if buffer matching was used.
  - filename - Just the filename part of the url
  - dirpath  - The directory path part of the url
  - basename - The basename of the filename without suffix or prefix
  - suffix   - The suffix of the file or \c false if none
  - prefix   - The prefix of the file or \c false if none

*/

class eZMimeType
{
    /*!
     Constructor
    */
    function eZMimeType()
    {
        $this->SuffixList = array();
        $this->PrefixList = array();
        $this->MIMEList = array();

        $ini = eZINI::instance( 'mime.ini' );
        $this->QuickMIMETypes = array();
        foreach ( $ini->groups() as $extension => $blockValues )
        {
            foreach ( $blockValues['Types'] as $type )
            {
                $this->QuickMIMETypes[] = array( $extension, $type );
            }
        }

        foreach ( $this->QuickMIMETypes as $quickMIMEType )
        {
            $mimeEntry =& $this->MIMEList[$quickMIMEType[1]];
            if ( !isset( $mimeEntry ) )
                $mimeEntry = array( 'suffixes' => array(),
                                    'prefixes' => false );
            $mimeEntry['suffixes'][] = $quickMIMEType[0];
        }

        eZMimeType::prepareSuffixList( $this->SuffixList, $this->MIMEList );
        eZMimeType::preparePrefixList( $this->PrefixList, $this->MIMEList );
    }

    /*!
     \static
     \return the default MIME-Type, this is used for all files that are not recognized.
    */
    static function defaultMimeType()
    {
        return array( 'name' => 'application/octet-stream',
                      'url' => false,
                      'filename' => false,
                      'dirpath' => false,
                      'basename' => false,
                      'suffix' => false,
                      'prefix' => false,
                      'suffixes' => false,
                      'prefixes' => false,
                      'is_valid' => true );
    }

    /*!
     \static
     \return the defaultMimeType if \a $returnDefault is \c true, otherwise returns \c false.
    */
    static function defaultValue( $url, $returnDefault )
    {
        if ( $returnDefault )
        {
            $mime = eZMimeType::defaultMimeType();
            $mime['url'] = $url;
            $suffixPos = strpos( $url, '.' );

            if ( $suffixPos !== false )
            {
                $mime['suffix'] = substr( $url, $suffixPos + 1 );
                $mime['dirpath'] = dirname( $url );
                $mime['basename'] = basename( $url, '.' . $mime['suffix'] );
                $mime['filename'] = $mime['basename'] . '.' . $mime['suffix'];
            }
            else
            {
                $mime['basename'] = $url;
                $mime['suffix'] = false;
            }
            $mime['is_valid'] = false;
            return $mime;
        }
        else
            return false;
    }

    /*!
     Changes the MIME type attribute for the MIME info structure \a $mimeInfo to \a $mimetype,
     and recreates all the affected fields.
    */
    static function changeMIMEType( &$mimeInfo, $mimetype )
    {
        $mimeInfo['name'] = $mimetype;
        $newMimeInfo = eZMimeType::findByName( $mimetype );
        $mimeInfo['suffixes'] = $newMimeInfo['suffixes'];
        $mimeInfo['prefixes'] = $newMimeInfo['prefixes'];
        $mimeInfo['suffix'] = $newMimeInfo['suffix'];
        $mimeInfo['prefix'] = $newMimeInfo['prefix'];
        $filename = $mimeInfo['filename'];
        $dotPosition = strrpos( $filename, '.' );
        $basename = $filename;
        if ( $dotPosition !== false )
            $basename = substr( $filename, 0, $dotPosition );
        $mimeInfo['filename'] = $basename . '.' . $mimeInfo['suffix'];
        if ( $mimeInfo['dirpath'] )
            $mimeInfo['url'] = $mimeInfo['dirpath'] . '/' . $mimeInfo['filename'];
        else
            $mimeInfo['url'] = $mimeInfo['filename'];
    }

    /*!
     Changes the basename attribute for the MIME info structure \a $mimeInfo to \a $basename,
     and recreates all the affected fields.
    */
    static function changeBasename( &$mimeInfo, $basename )
    {
        $mimeInfo['basename'] = $basename;
        $mimeInfo['filename'] = $basename . '.' . $mimeInfo['suffix'];
        if ( $mimeInfo['dirpath'] )
            $mimeInfo['url'] = $mimeInfo['dirpath'] . '/' . $mimeInfo['filename'];
        else
            $mimeInfo['url'] = $mimeInfo['filename'];
    }

    /*!
     Changes the basename attribute for the MIME info structure \a $mimeInfo to \a $basename,
     and recreates all the affected fields.
    */
    static function changeDirectoryPath( &$mimeInfo, $dirpath )
    {
        $mimeInfo['dirpath'] = $dirpath;
        if ( $mimeInfo['dirpath'] )
            $mimeInfo['url'] = $mimeInfo['dirpath'] . '/' . $mimeInfo['filename'];
        else
            $mimeInfo['url'] = $mimeInfo['filename'];
    }

    /*!
     Changes the basename attribute for the MIME info structure \a $mimeInfo to \a $basename,
     and recreates all the affected fields.
    */
    static function changeFileData( &$mimeInfo, $dirpath = false, $basename = false, $suffix = false, $filename = false )
    {
        if ( $basename !== false )
            $mimeInfo['basename'] = $basename;
        if ( $suffix !== false )
            $mimeInfo['suffix'] = $suffix;
        if ( $filename !== false )
        {
            $mimeInfo['filename'] = $filename;
        }
        else
        {
            $mimeInfo['filename'] = $mimeInfo['basename'];
            $mimeInfo['filename'] .= '.' . $mimeInfo['suffix'];
        }
        if ( $dirpath !== false )
            $mimeInfo['dirpath'] = $dirpath;

        if ( $mimeInfo['dirpath'] )
            $mimeInfo['url'] = $mimeInfo['dirpath'] . '/' . $mimeInfo['filename'];
        else
            $mimeInfo['url'] = $mimeInfo['filename'];
    }

    /*!
     \return the MIME structure for the MIME type \a $mimeName.
     If \a $returnDefault is set to \c true then it will always return a MIME structure,
     if not it will return \c false if none were found.
    */
    static function findByName( $mimeName, $returnDefault = true )
    {
        $instance = eZMimeType::instance();
        $mimeList =& $instance->MIMEList;
        if ( isset( $mimeList[$mimeName] ) )
        {
            $mime = $mimeList[$mimeName];
            $mime['name'] = $mimeName;
            $mime['url'] = false;
            $mime['filename'] = false;
            $mime['dirpath'] = false;
            $mime['basename'] = false;
            $mime['suffix'] = false;
            if ( isset( $mime['suffixes'][0] ) )
                $mime['suffix'] = $mime['suffixes'][0];
            $mime['prefix'] = false;
            if ( isset( $mime['prefixes'][0] ) )
                $mime['prefix'] = $mime['prefixes'][0];
            $mime['is_valid'] = true;
            return $mime;
        }
        return eZMimeType::defaultValue( false, $returnDefault );
    }

    /*!
     \static
     Finds the MIME type for the url \a $url by examining the url itself (not the content) and returns it.
     If \a $returnDefault is set to \c true then it will always return a MIME structure,
     if not it will return \c false if none were found.
    */
    static function findByURL( $url, $returnDefault = true )
    {
        $instance = eZMimeType::instance();

        $file = $url;
        $dirPosition = strrpos( $url, '/' );
        if ( $dirPosition !== false )
            $file = substr( $url, $dirPosition + 1 );
        $suffixPosition = strrpos( $file, '.' );
        $suffix = false;
        $prefix = false;
        $mimeName = false;
        if ( $suffixPosition !== false )
        {
            $suffix = strtolower( substr( $file, $suffixPosition + 1 ) );
            if ( $suffix )
            {
                $subURL = substr( $file, 0, $suffixPosition );
                $suffixList =& $instance->SuffixList;
                if ( array_key_exists( $suffix, $suffixList ) )
                {
                    $mimeName = $suffixList[$suffix];
                }
            }
            if ( !$mimeName )
            {
                $prefixPosition = strpos( $file, '.' );
                if ( $prefixPosition !== false )
                {
                    $prefix = strtolower( substr( $file, 0, $prefixPosition ) );
                }
                if ( $prefix )
                {
                    $subURL = substr( $file, $suffixPosition + 1 );
                    $prefixList =& $instance->PrefixList;
                    if ( array_key_exists( $prefix, $prefixList ) )
                    {
                        $mimeName = $prefixList[$prefix];
                    }
                }
            }
            if ( $mimeName )
            {
                $mimeList =& $instance->MIMEList;
                if ( array_key_exists( $mimeName, $mimeList ) )
                {
                    $lastDirPosition = strrpos( $url, '/' );
                    $filename = $url;
                    $dirpath = false;
                    if ( $lastDirPosition !== false )
                    {
                        $filename = substr( $url, $lastDirPosition + 1 );
                        $dirpath = substr( $url, 0, $lastDirPosition );
                    }
                    $lastDirPosition = strrpos( $subURL, '/' );
                    $basename = $subURL;
                    if ( $lastDirPosition !== false )
                        $basename = substr( $subURL, $lastDirPosition + 1 );
                    $mime = $mimeList[$mimeName];
                    $mime['name'] = $mimeName;
                    $mime['url'] = $url;
                    $mime['filename'] = $filename;
                    $mime['dirpath'] = $dirpath;
                    $mime['basename'] = $basename;
                    $mime['suffix'] = $suffix;
                    $mime['prefix'] = $prefix;
                    $mime['is_valid'] = true;
                    return $mime;
                }
            }
        }
        return eZMimeType::defaultValue( $url, $returnDefault );
    }

    /*!
     \static
     Finds the MIME type for the url \a $url by examining the contents of the url and returns it.
     If \a $returnDefault is set to \c true then it will always return a MIME structure,
     if not it will return \c false if none were found.
     \note Currently it only calls findByURL()
    */
    static function findByFileContents( $url, $returnDefault = true )
    {
        return eZMimeType::findByURL( $url, $returnDefault );
    }

    /*!
     \static
     Finds the MIME type for the buffer \a $buffer by examining the contents and returns it.
     If \a $returnDefault is set to \c true then it will always return a MIME structure,
     if not it will return \c false if none were found.
     \param $length If specified it will limit how far the \a $buffer is examined
     \param $offset If specified it will set the starting point for the \a $buffer examination
     \param $url If specified the url will be used for MIME determination if buffer examination gives no results.
     \note Currently it only calls findByURL()
    */
    static function findByBuffer( $buffer, $length = false, $offset = false, $url = false, $returnDefault = true )
    {
        return eZMimeType::findByURL( $url, $returnDefault );
    }

    /*!
     \static
     \return the unique instance of the eZMimeType class.
    */
    static function instance()
    {
        $instance =& $GLOBALS['eZMIMETypeInstance'];
        if ( !isset( $instance ) )
        {
            $instance = new eZMimeType();
        }
        return $instance;
    }

    /*!
     \deprecated
     \static
     \return the MIME-Type name for the file \a $file.
    */
    static function mimeTypeFor( $path, $file )
    {
        eZDebug::writeWarning( 'eZMimeType::mimeTypeFor() is deprecated, use eZMimeType::findByURL() instead',
                               'DEPRECATED FUNCTION eZMimeType::mimeTypeFor' );
        $url = $path;
        if ( $url )
            $url .= '/' . $file;
        else
            $url = $file;
        $match = eZMimeType::findByURL( $url, false );
        if ( $match )
            return $match['name'];
        else
            return false;
    }

    /*!
     \private
     Goes trough the mime list and creates a reference to the mime entry using the primary suffix.
    */
    static function prepareSuffixList( &$suffixList, $mimeList )
    {
        foreach ( $mimeList as $mimeName => $mimeData )
        {
            if ( is_array( $mimeData['suffixes'] ) )
            {
                foreach ( $mimeData['suffixes'] as $suffix )
                {
                    if ( !isset( $suffixList[$suffix] ) )
                        $suffixList[$suffix] = $mimeName;
                }
            }
        }
    }

    /*!
     \private
     Goes trough the mime list and creates a reference to the mime entry using the primary prefix.
    */
    static function preparePrefixList( &$prefixList, $mimeList )
    {
        foreach ( $mimeList as $mimeName => $mimeData )
        {
            if ( is_array( $mimeData['prefixes'] ) )
            {
                foreach ( $mimeData['prefixes'] as $prefix )
                {
                    if ( !isset( $prefixList[$prefix] ) )
                        $prefixList[$prefix] = $mimeName;
                }
            }
        }
    }

    /// \privatesection

    /// An associative array which maps from suffix name to MIME type name.
    public $SuffixList;
    /// An associative array which maps from prefix name to MIME type name.
    public $PrefixList;
    /// An associative array which maps MIME type name to MIME structure.
    public $MIMEList;

    public $QuickContentMatch = array(
        array( array( 0, 'string', 'GIF87a', 'image/gif' ),
               array( 0, 'string', 'GIF89a', 'image/gif' ) )
        );

    /// A list of suffixes and their MIME types, this is used to quickly initialize the system.
    public $QuickMIMETypes = array();
}
?>
