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
        $this->TextChunks = array();
        $this->TemporaryCounter = 0;
    }

    function open()
    {
        if ( !$this->FileResource )
        {
            if ( !file_exists( $this->PHPDir ) )
            {
                include_once( 'lib/ezutils/classes/ezdir.php' );
                $ini =& eZINI::instance();
                $perm = octdec( $ini->variable( 'FileSettings', 'StorageDirPermissions' ) );
                eZDir::mkdir( $this->PHPDir, $perm, true );
            }
            $path = $this->PHPDir . '/' . $this->PHPFile;
            $oldumask = umask( 0 );
            $pathExisted = file_exists( $path );
            $ini =& eZINI::instance();
            $perm = octdec( $ini->variable( 'FileSettings', 'StorageFilePermissions' ) );
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

    function canRestore( $timestamp = false )
    {
        $path = $this->PHPDir . '/' . $this->PHPFile;
        $canRestore = file_exists( $path );
        if ( $timestamp !== false and
             $canRestore )
        {
            $cacheModifierTime = filemtime( $path );
            $canRestore = ( $cacheModifierTime >= $timestamp );
        }
        return $canRestore;
    }

    function &restore( $variableDefinitions )
    {
        $returnVariables = array();
        $path = $this->PHPDir . '/' . $this->PHPFile;
        include( $path );
        foreach ( $variableDefinitions as $variableReturnName => $variableName )
        {
            if ( isset( ${$variableName} ) )
            {
                $returnVariables[$variableReturnName] =& ${$variableName};
            }
            else
                eZDebug::writeError( "Variable '$variableName' is not present in cache '$path'",
                                     'eZPHPCreator::restore' );
        }
        return $returnVariables;
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
                    $this->writeVariable( $element[1], $element[2] );
                }
                else if ( $element[0] == EZ_PHPCREATOR_SPACE )
                {
                    $this->writeSpace( $element );
                }
            }

            $this->write( "?>\n" );

            $this->writeChunks();
            $this->flushChunks();
        }
    }

    function writeChunks()
    {
        $count = count( $this->TextChunks );
        for ( $i = 0; $i < $count; ++$i )
        {
            $text = $this->TextChunks[$i];
            fwrite( $this->FileResource, $text );
        }
    }

    function flushChunks()
    {
        $this->TextChunks = array();
    }

    function write( $text )
    {
//         fwrite( $this->FileResource, $text );
        $this->TextChunks[] = $text;
    }

    function writeSpace( $element )
    {
        $text = str_repeat( "\n", $element[1] );
        $this->write( $text );
    }

    function writeVariable( $variableName, $variableValue )
    {
        $text = '$' . $variableName . ' = ';
        $text .= $this->variableText( $variableValue, strlen( $text ) );
        $text .= ";\n";
        $this->write( $text );
    }

    function variableText( $value, $column, $iteration = 0 )
    {
        if ( is_bool( $value ) )
            $text = ( $value ? 'true' : 'false' );
        else if ( is_null( $value ) )
            $text = 'null';
        else if ( is_string( $value ) )
        {
            $valueText = str_replace( array( "\\",
                                             "\"",
                                             "\n" ),
                                      array( "\\\\",
                                             "\\\"",
                                             "\\n" ),
                                      $value );
            $text = "\"$valueText\"";
        }
        else if ( is_numeric( $value ) )
            $text = $value;
        else if ( is_object( $value ) )
        {
            if ( $iteration > 2 )
            {
                $temporaryVariableName = $this->temporaryVariableName( 'obj' );
                $this->writeVariable( $temporaryVariableName, $value );
                $text = '$' . $temporaryVariableName;
            }
            else
            {
                $text = '';
                if ( method_exists( $value, 'serializedata' ) )
                {
                    $serializeData = $value->serializeData();
                    $className = $serializeData['class_name'];
                    $text = "new $className(";

                    $column += strlen( $text );
                    $parameters = $serializeData['parameters'];
                    $variables = $serializeData['variables'];

                    $i = 0;
                    foreach ( $parameters as $parameter )
                    {
                        if ( $i > 0 )
                        {
                            $text .= ",\n" . str_repeat( ' ', $column );
                        }
                        $variableName = $variables[$parameter];
                        $variableValue = $value->$variableName;
                        $keyText = " ";
                        $text .= $keyText . eZPHPCreator::variableText( $variableValue, $column + strlen( $keyText  ), $iteration + 1 );
                        ++$i;
                    }
                    if ( $i > 0 )
                        $text .= ' ';

                    $text .= ')';
                }
            }
        }
        else if ( is_array( $value ) )
        {
            if ( $iteration > 2 )
            {
                $temporaryVariableName = $this->temporaryVariableName( 'arr' );
                $this->writeVariable( $temporaryVariableName, $value );
                $text = '$' . $temporaryVariableName;
            }
            else
            {
                $text = 'array(';
                $column += strlen( $text );
                $valueKeys = array_keys( $value );
                $isIndexed = true;
                for ( $i = 0; $i < count( $valueKeys ); ++$i )
                {
                    if ( $i !== $valueKeys[$i] )
                    {
                        $isIndexed = false;
                        break;
                    }
                }
                $i = 0;
                foreach ( $valueKeys as $key )
                {
                    if ( $i > 0 )
                    {
                        $text .= ",\n" . str_repeat( ' ', $column );
                    }
                    $element =& $value[$key];
                    $keyText = ' ';
                    if ( !$isIndexed )
                    {
                        if ( is_int( $key ) )
                            $keyText = $key;
                        else
                            $keyText = "\"" . str_replace( array( "\\",
                                                                  "\"",
                                                                  "\n" ),
                                                           array( "\\\\",
                                                                  "\\\"",
                                                                  "\\n" ),
                                                           $key ) . "\"";
                        $keyText = " $keyText => ";
                    }
                    $text .= $keyText . eZPHPCreator::variableText( $element, $column + strlen( $keyText  ), $iteration + 1 );
                    ++$i;
                }
                if ( $i > 0 )
                    $text .= ' ';
                $text .= ')';
            }
        }
        else
            $text = 'null';
        return $text;
    }

    function temporaryVariableName( $prefix )
    {
        $temporaryCounter =& $this->TemporaryCounter;
        $variableName = $prefix . '_' . $temporaryCounter;
        ++$temporaryCounter;
        return $variableName;
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
    var $TextChunks;
}

?>
