<?php
//
// Definition of eZPHPCreator class
//
// Created on: <28-Nov-2002 08:28:23 amos>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezphpcreator.php
*/

/*!
  \class eZPHPCreator ezphpcreator.php
  \brief The class eZPHPCreator does

*/

define( 'EZ_PHPCREATOR_VARIABLE', 1 );
define( 'EZ_PHPCREATOR_SPACE', 2 );

class eZPHPCreator
{
    /*!
     Constructor
    */
    function eZPHPCreator( $dir, $file )
    {
        $this->PHPDir = $dir;
        $this->PHPFile = $file;
        $this->FileResource = false;
        $this->Elements = array();
    }

    function open()
    {
        if ( !$this->FileResource )
        {
            if ( !file_exists( $this->PHPDir ) )
            {
                include_once( 'lib/ezutils/classes/ezdir.php' );
                $ini =& eZINI::instance();
                $perm = $ini->variable( 'FileSettings', 'StorageDirPermissions' );
                eZDir::mkdir( $this->PHPDir, $perm, true );
            }
            $path = $this->PHPDir . '/' . $this->PHPFile;
            $oldumask = umask( 0 );
            $pathExisted = file_exists( $path );
            $ini =& eZINI::instance();
            $perm = $ini->variable( 'FileSettings', 'StorageFilePermissions' );
            $this->FileResource = fopen( $path, "w" );
            if ( !$pathExisted )
                chmod( $path, $perm );
            umask( $oldumask );
        }
        return $this->FileResource;
    }

    function close()
    {
        if ( $this->FileResource )
        {
            fclose( $this->FileResource );
            $this->FileResource = false;
        }
    }

    function store()
    {
        if ( $this->open() )
        {
            $this->write( "<?php\n" );

            $count = count( $this->Elements );
            for ( $i = 0; $i < $count; ++$i )
            {
                $element =& $this->Elements[$i];
                if ( $element[0] == EZ_PHPCREATOR_VARIABLE )
                {
                    $this->writeVariable( $element );
                }
                else if ( $element[0] == EZ_PHPCREATOR_SPACE )
                {
                    $this->writeSpace( $element );
                }
            }

            $this->write( "?>\n" );
        }
    }

    function write( $text )
    {
        fwrite( $this->FileResource, $text );
    }

    function writeSpace( $element )
    {
        $text = str_repeat( "\n", $element[1] );
        $this->write( $text );
    }

    function writeVariable( $element )
    {
        $text = '$' . $element[1] . ' = ';
        $value = $element[2];
        $text .= $this->variableText( $value, strlen( $text ) );
        $text .= ";\n";
        $this->write( $text );
    }

    function variableText( $value, $column )
    {
        if ( is_bool( $value ) )
            $text = ( $value ? 'true' : 'false' );
        else if ( is_null( $value ) )
            $text = 'null';
        else if ( is_string( $value ) )
        {
            $valueText = str_replace( array( "\"",
                                             "\n" ),
                                      array( "\\\"",
                                             "\\n" ),
                                      $value );
            $text = "\"$valueText\"";
        }
        else if ( is_numeric( $value ) )
            $text = $value;
        else if ( is_array( $value ) )
        {
            $text = 'array(';
            $column += strlen( $text );
            $i = 0;
            foreach ( array_keys( $value ) as $key )
            {
                if ( $i > 0 )
                {
                    $text .= ",\n" . str_repeat( ' ', $column );
                }
                $element =& $value[$key];
                if ( is_int( $key ) )
                    $keyText = $key;
                else
                    $keyText = "\"" . str_replace( array( "\"",
                                                          "\n" ),
                                                   array( "\\\"",
                                                          "\\n" ),
                                                   $key ) . "\"";
                $keyText = " $keyText => ";
                $text .= $keyText . $this->variableText( $element, $column + strlen( $keyText  ) );
                ++$i;
            }
            if ( $i > 0 )
                $text .= ' ';
            $text .= ')';
        }
        else
            $text = 'null';
        return $text;
    }

    function addVariable( $name, $value )
    {
        $element = array( EZ_PHPCREATOR_VARIABLE,
                          $name,
                          $value );
        $this->Elements[] = $element;
    }

    function addSpace( $lines = 1 )
    {
        $element = array( EZ_PHPCREATOR_SPACE,
                          $lines );
        $this->Elements[] = $element;
    }

    /// \privatesection
    var $PHPDir;
    var $PHPFile;
    var $FileResource;
    var $Elements;
}

?>
