<?php
//
// Definition of eZPHPCreator class
//
// Created on: <28-Nov-2002 08:28:23 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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
// http://ez.no/products/licences/professional/. For pricing of this licence
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
define( 'EZ_PHPCREATOR_TEXT', 3 );
define( 'EZ_PHPCREATOR_METHOD_CALL', 4 );
define( 'EZ_PHPCREATOR_CODE_PIECE', 5 );
define( 'EZ_PHPCREATOR_EOL_COMMENT', 6 );
define( 'EZ_PHPCREATOR_INCLUDE', 7 );
define( 'EZ_PHPCREATOR_VARIABLE_UNSET', 8 );

define( 'EZ_PHPCREATOR_VARIABLE_ASSIGNMENT', 1 );
define( 'EZ_PHPCREATOR_VARIABLE_APPEND_TEXT', 2 );
define( 'EZ_PHPCREATOR_VARIABLE_APPEND_ELEMENT', 3 );

define( 'EZ_PHPCREATOR_INCLUDE_ONCE', 1 );
define( 'EZ_PHPCREATOR_INCLUDE_ALWAYS', 2 );

define( 'EZ_PPCREATOR_METHOD_CALL_PARAMETER_VALUE', 1 );
define( 'EZ_PHPCREATOR_METHOD_CALL_PARAMETER_VARIABLE', 2 );

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
            $this->FileResource = @fopen( $path, "w" );
            if ( !$this->FileResource )
                eZDebug::writeError( "Could not open file '$path' for writing, perhaps wrong permissions" );
            if ( $this->FileResource and
                 !$pathExisted )
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

    function exists()
    {
        $path = $this->PHPDir . '/' . $this->PHPFile;
        return file_exists( $path );
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
            $variableRequired = true;
            $variableDefault = false;
            if ( is_array( $variableName ) )
            {
                $variableDefinition = $variableName;
                $variableName = $variableDefinition['name'];
                $variableRequired = $variableDefinition['required'];
                if ( isset( $variableDefinition['default'] ) )
                    $variableDefault = $variableDefinition['default'];
            }
            if ( isset( ${$variableName} ) )
            {
                $returnVariables[$variableReturnName] =& ${$variableName};
            }
            else if ( $variableRequired )
                eZDebug::writeError( "Variable '$variableName' is not present in cache '$path'",
                                     'eZPHPCreator::restore' );
            else
                $returnVariables[$variableReturnName] = $variableDefault;
        }
        return $returnVariables;
    }

    /*!
     Stores the PHP cache, returns false if the cache file could not be created.
    */
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
                    $this->writeVariable( $element[1], $element[2], $element[3], $element[4] );
                }
                else if ( $element[0] == EZ_PHPCREATOR_VARIABLE_UNSET )
                {
                    $this->writeVariableUnset( $element[1], $element[2] );
                }
                else if ( $element[0] == EZ_PHPCREATOR_SPACE )
                {
                    $this->writeSpace( $element );
                }
                else if ( $element[0] == EZ_PHPCREATOR_TEXT )
                {
                    $this->writeText( $element );
                }
                else if ( $element[0] == EZ_PHPCREATOR_METHOD_CALL )
                {
                    $this->writeMethodCall( $element );
                }
                else if ( $element[0] == EZ_PHPCREATOR_CODE_PIECE )
                {
                    $this->writeCodePiece( $element );
                }
                else if ( $element[0] == EZ_PHPCREATOR_EOL_COMMENT )
                {
                    $this->writeComment( $element );
                }
                else if ( $element[0] == EZ_PHPCREATOR_INCLUDE )
                {
                    $this->writeInclude( $element );
                }
            }

            $this->write( "?>\n" );

            $this->writeChunks();
            $this->flushChunks();
            $this->close();

            // Write log message to storage.log
            include_once( 'lib/ezutils/classes/ezlog.php' );
            eZLog::writeStorageLog( $this->PHPFile, $this->PHPDir . '/' );
            return true;
        }
        else
        {
            eZDebug::writeError( "Failed to open file '" . $this->PHPDir . '/' . $this->PHPFile . "'",
                                 'eZPHPCreator::store' );
            return false;
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

    function writeInclude( $element )
    {
        $includeFile = $element[1];
        $includeType = $element[2];
        if ( $includeType == EZ_PHPCREATOR_INCLUDE_ONCE )
            $includeName = 'include_once';
        else if ( $includeType == EZ_PHPCREATOR_INCLUDE_ALWAYS )
            $includeName = 'include';
        $includeFileText = $this->variableText( $includeFile, 0 );
        $text = "$includeName( $includeFileText );\n";
        $this->write( $text );
    }

    function writeComment( $element )
    {
        $elementAttributes = $element[2];
        $whitespaceHandling = $elementAttributes['whitespace-handling'];
        $eol = $elementAttributes['eol'];
        $commentArray = explode( "\n", $element[1] );
        $text = '';
        foreach ( $commentArray as $comment )
        {
            $textLine = '// ' . $comment;
            if ( $whitespaceHandling )
            {
                $textLine = rtrim( $textLine );
                $textLine = str_replace( "\t", '    ', $textLine );
            }
            $text .= $textLine . "\n";
        }
        $this->write( $text );
    }

    function writeSpace( $element )
    {
        $text = str_repeat( "\n", $element[1] );
        $this->write( $text );
    }

    function writeCodePiece( $element )
    {
        $code = $element[1];
        $this->write( $code );
    }

    function writeText( $element )
    {
        $text = $element[1];
        $this->write( "\n?>" );
        $this->write( $text );
        $this->write( "<?php\n" );
    }

    function writeMethodCall( $element )
    {
        $objectName = $element[1];
        $methodName = $element[2];
        $parameters = $element[3];
        $returnValue = $element[4];
        $text = '';
        if ( is_array( $returnValue ) )
        {
            $variableName = $returnValue[0];
            $assignmentType = EZ_PHPCREATOR_VARIABLE_ASSIGNMENT;
            if ( isset( $variableValue[1] ) )
                $assignmentType = $variableValue[1];
            $text = $this->variableNameText( $variableName, $assignmentType );
        }
        $text .= '$' . $objectName . '->' . $methodName . '(';
        $column = strlen( $text );
        $i = 0;
        foreach ( $parameters as $parameterData )
        {
            if ( $i > 0 )
                $text .= ",\n" . str_repeat( ' ', $column );
            $parameterType = EZ_PHPCREATOR_METHOD_CALL_PARAMETER_VALUE;
            $parameterValue = $parameterData[0];
            if ( isset( $parameterData[1] ) )
                $parameterType = $parameterData[1];
            if ( $parameterType == EZ_PHPCREATOR_METHOD_CALL_PARAMETER_VALUE )
                 $text .= ' ' . $this->variableText( $parameterValue, $column + 1 );
            else if ( $parameterType == EZ_PHPCREATOR_METHOD_CALL_PARAMETER_VARIABLE )
                $text .= ' $' . $parameterValue;
            ++$i;
        }
        if ( $i > 0 )
            $text .= ' ';
        $text .= ");\n";
        $this->write( $text );
    }

    function writeVariableUnset( $variableName,
                                 $variableParameters = array() )
    {
        $text = "unset( \$$variableName );\n";
        $this->write( $text );
    }

    function writeVariable( $variableName, $variableValue, $assignmentType = EZ_PHPCREATOR_VARIABLE_ASSIGNMENT,
                            $variableParameters = array() )
    {
        $variableParameters = array_merge( array( 'full-tree' => false ),
                                           $variableParameters );
        $fullTree = $variableParameters['full-tree'];
        $text = $this->variableNameText( $variableName, $assignmentType, $variableParameters );
        $maxIterations = 2;
        if ( $fullTree )
            $maxIterations = false;
        $text .= $this->variableText( $variableValue, strlen( $text ), 0, $maxIterations );
        $text .= ";\n";
        $this->write( $text );
    }

    function variableNameText( $variableName, $assignmentType, $variableParameters = array() )
    {
        $variableParameters = array_merge( array( 'is-reference' => false ),
                                           $variableParameters );
        $isReference = $variableParameters['is-reference'];
        $text = '$' . $variableName;
        if ( $assignmentType == EZ_PHPCREATOR_VARIABLE_ASSIGNMENT )
        {
            if ( $isReference )
                $text .= ' =& ';
            else
                $text .= ' = ';
        }
        else if ( $assignmentType == EZ_PHPCREATOR_VARIABLE_APPEND_TEXT )
        {
            $text .= ' .= ';
        }
        else if ( $assignmentType == EZ_PHPCREATOR_VARIABLE_APPEND_ELEMENT )
        {
            if ( $isReference )
                $text .= '[] =& ';
            else
                $text .= '[] = ';
        }
        return $text;
    }

    function variableText( $value, $column, $iteration = 0, $maxIterations = 2 )
    {
        if ( is_bool( $value ) )
            $text = ( $value ? 'true' : 'false' );
        else if ( is_null( $value ) )
            $text = 'null';
        else if ( is_string( $value ) )
        {
            $valueText = str_replace( array( "\\",
                                             "\"",
                                             "\$",
                                             "\n" ),
                                      array( "\\\\",
                                             "\\\"",
                                             "\\$",
                                             "\\n" ),
                                      $value );
            $text = "\"$valueText\"";
//             $valueText = str_replace( array( "'" ),
//                                       array( "\\'" ),
//                                       $value );
//             $text = "'$valueText'";
        }
        else if ( is_numeric( $value ) )
            $text = $value;
        else if ( is_object( $value ) )
        {
            if ( $maxIterations !== false and
                 $iteration > $maxIterations )
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
                        $text .= $keyText . eZPHPCreator::variableText( $variableValue, $column + strlen( $keyText  ), $iteration + 1, $maxIterations );
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
            if ( $maxIterations !== false and
                 $iteration > $maxIterations )
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
                    $text .= $keyText . eZPHPCreator::variableText( $element, $column + strlen( $keyText  ), $iteration + 1, $maxIterations );
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

    function addVariable( $name, $value, $assignmentType = EZ_PHPCREATOR_VARIABLE_ASSIGNMENT,
                          $parameters = array() )
    {
        $element = array( EZ_PHPCREATOR_VARIABLE,
                          $name,
                          $value,
                          $assignmentType,
                          $parameters );
        $this->Elements[] = $element;
    }

    function addVariableUnset( $name,
                               $parameters = array() )
    {
        $element = array( EZ_PHPCREATOR_VARIABLE_UNSET,
                          $name,
                          $parameters );
        $this->Elements[] = $element;
    }

    function addSpace( $lines = 1 )
    {
        $element = array( EZ_PHPCREATOR_SPACE,
                          $lines );
        $this->Elements[] = $element;
    }

    function addText( $text )
    {
        $element = array( EZ_PHPCREATOR_TEXT,
                          $text );
        $this->Elements[] = $element;
    }

    function addMethodCall( $objectName, $methodName, $parameters, $returnValue = false )
    {
        $element = array( EZ_PHPCREATOR_METHOD_CALL,
                          $objectName,
                          $methodName,
                          $parameters,
                          $returnValue );
        $this->Elements[] = $element;
    }

    function addCodePiece( $code )
    {
        $element = array( EZ_PHPCREATOR_CODE_PIECE,
                          $code );
        $this->Elements[] = $element;
    }

    function addComment( $comment, $eol = true, $whitespaceHandling = true )
    {
        $element = array( EZ_PHPCREATOR_EOL_COMMENT,
                          $comment,
                          array( 'eol' => $eol,
                                 'whitespace-handling' => $whitespaceHandling ) );
        $this->Elements[] = $element;
    }

    function addInclude( $file, $type = EZ_PHPCREATOR_INCLUDE_ONCE )
    {
        $element = array( EZ_PHPCREATOR_INCLUDE,
                          $file,
                          $type );
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
