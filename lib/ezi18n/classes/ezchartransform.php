<?php
//
// Definition of eZCharTransform class
//
// Created on: <16-Jul-2004 15:54:21 amos>
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

/*! \file ezchartransform.php
*/

/*!
  \class eZCharTransform ezchartransform.php
  \ingroup eZI18N
  \brief Performs rule based transformation of characters in a string

  \sa eZCodeMapper
*/

/// The timestamp for when the format of the cache files were
/// last changed. This must be updated when the format changes
/// to invalidate existing cache files.
define( 'EZ_CHARTRANSFORM_CODEDATE', 1101288452 );
include_once( 'lib/ezi18n/classes/eztextcodec.php' );
include_once( 'lib/ezi18n/classes/ezcharsetinfo.php' );

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
     Transforms the text according to the rules defined in \a $rule using character set \a $charset.
     \param $text The text string to be converted, currently Unicode arrays are not supported
     \param $rule Which transformation rule to use, can either be a string identifier or an array with identifiers.
     \param $charset Which charset to use when transforming, if \c false it will use current charset (i18n.ini).
     \param $useCache If \c true then it will use cache files for the mapping,
                      if not it will have to calculate them each time.
    */
    function transform( $text, $rule, $charset = false, $useCache = true )
    {
        if ( $useCache )
        {
            // CRC32 is used for speed, MD5 would be more unique but is slower
            $key = crc32( 'Rule: ' . ( is_array( $rule ) ? implode( ',', $rule ) : $rule ) . '-' . $charset );

            $charsetName = ( $charset === false ? eZTextCodec::internalCharset() : eZCharsetInfo::realCharsetCode( $charset ) );

            // Try to execute code in the cache file, if it succeeds
            // \a $text will/ transformated
            if ( $this->executeCacheFile( $text,
                                          'rule-', '-' . $charsetName,
                                          $key, false, $filepath ) )
            {
//                 eZDebug::writeDebug( 'executed cache file ' . $filepath );
                return $text;
            }
        }

        // Make sure we have a mapper
        if ( $this->Mapper === false )
        {
            include_once( 'lib/ezi18n/classes/ezcodemapper.php' );
            $this->Mapper = new eZCodeMapper();
        }

        $this->Mapper->loadTransformationFiles( $charsetName, false );

        // First generate a unicode based mapping table from the rules
        $unicodeTable = $this->Mapper->generateMappingCode( $rule );
        // Then transform that to a table that works with the current charset
        // Any character not available in the current charset will be removed
        $charsetTable = $this->Mapper->generateCharsetMappingTable( $unicodeTable, $charset );
        $transformationData = array( 'table' => $charsetTable );
        unset( $unicodeTable );

        if ( $useCache )
        {
            $extraCode = '';
            $this->storeCacheFile( $filepath, $transformationData,
                                   $extraCode,
                                   'Rule', $charsetName );
        }

        // Execute transformations
        return strtr( $text, $transformationData['table'] );
    }

    /*!
     Transforms the text according to the rules defined in \a $rule using character set \a $charset.
     \param $text The text string to be converted, currently Unicode arrays are not supported
     \param $rule Which transformation rule to use, can either be a string identifier or an array with identifiers.
     \param $charset Which charset to use when transforming, if \c false it will use current charset (i18n.ini).
     \param $useCache If \c true then it will use cache files for the tables,
                      if not it will have to calculate them each time.
    */
    function transformByGroup( $text, $group, $charset = false, $useCache = true )
    {
        $charsetName = ( $charset === false ? eZTextCodec::internalCharset() : eZCharsetInfo::realCharsetCode( $charset ) );
        if ( $useCache )
        {
            // CRC32 is used for speed, MD5 would be more unique but is slower
            $keyText = 'Group:' . $group;
            $key = crc32( $keyText . '-' . $charset );

            // Try to execute code in the cache file, if it succeeds
            // \a $text will/ transformated
            if ( $this->executeCacheFile( $text,
                                          'g-' . $group . '-', '-' . $charsetName,
                                          $key, false, $filepath ) )
            {
//                 eZDebug::writeDebug( 'executed cache file ' . $filepath );
                return $text;
            }
        }

        $commands = $this->groupCommands( $group );
        if ( $commands === false )
            return false;

        // Make sure we have a mapper
        if ( $this->Mapper === false )
        {
            include_once( 'lib/ezi18n/classes/ezcodemapper.php' );
            $this->Mapper = new eZCodeMapper();
        }

        $this->Mapper->loadTransformationFiles( $charsetName, $group );

        $rules = array();
        foreach ( $commands as $command )
        {
            $rules = array_merge( $rules,
                                  $this->Mapper->decodeCommand( $command['command'], $command['parameters'] ) );
        }

        // First generate a unicode based mapping table from the rules
        $unicodeTable = $this->Mapper->generateMappingCode( $rules );
        // Then transform that to a table that works with the current charset
        // Any character not available in the current charset will be removed
        $charsetTable = $this->Mapper->generateCharsetMappingTable( $unicodeTable, $charset );
        $transformationData = array( 'table' => $charsetTable );
        unset( $unicodeTable );

        if ( $useCache )
        {
            $extraCode = '';
            foreach ( $commands as $command )
            {
                $code = $this->Mapper->generateCommandCode( $command, $charsetName );
                if ( $code !== false )
                {
                    $extraCode .= $code . "\n";
                }
            }
            $this->storeCacheFile( $filepath, $transformationData,
                                   $extraCode,
                                   'Group:' . $group, $charsetName );
        }

        // Execute transformations
        $text = strtr( $text, $transformationData['table'] );

        // Execute custom code
        foreach ( $commands as $command )
        {
            $this->Mapper->executeCommandCode( $text, $command, $charsetName );
        }

        return $text;
    }

    /*!
     \private
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
     \private
     Finds all commands defined for group \a $group.
     The groups and their commands are defined in \c transform.ini.

     \return An array with commands, each entry contains of:
             - command - Name of the command
             - parameters - Array with parameters for command
             - text - Textual representation of the command + parameters
    */
    function groupCommands( $group )
    {
        $rules =& $this->GroupRules[$group];
        if ( isset( $rules ) )
            return $rules;

        $ini =& eZINI::instance( 'transform.ini' );
        $groups = $ini->variable( 'Transformation', 'Groups' );
        if ( !in_array( $group, $groups ) )
        {
            eZDebug::writeError( "Transformation group $group is not part of the active group list Groups in transform.ini",
                                 'eZCharTransform::groupCommands' );
            return false;
        }

        if ( !$ini->hasGroup( $group ) )
        {
            eZDebug::writeError( "Transformation group $group is missing in transform.ini",
                                 'eZCharTransform::groupCommands' );
            return false;
        }

        $rules = array();
        $ruleTexts = $ini->variable( $group, 'Commands' );
        foreach ( $ruleTexts as $ruleText )
        {
            if ( preg_match( "#^([a-zA-Z][a-zA-Z0-9_-]+)(\((.+)\))?$#", $ruleText, $matches ) )
            {
                $command = $matches[1];
                $parameters = array();
                if ( isset( $matches[2] ) )
                {
                    $parameters = explode( ',', $matches[3] );
                }
                $rules[] = array( 'command' => $command,
                                  'parameters' => $parameters );
            }
        }

        return $rules;
    }

    /*!
     \private
     \param $text The text that should be transformed
     \param $key The unique key for the cache, this should be a CRC32 or MD5 of
                 the current rules or commands which are used.
     \param $timestamp A timestamp value which is matched against the cache file,
                       pass for instance the timestamp of the INI file.
     \param[out] $filepath The filepath for the cache file will be generated here,
                           this can be used for the storeCacheFile() method.
     \return The restored transformation data or \c false if there is no cached data.
    */
    function executeCacheFile( &$text, $prefix, $suffix, $key, $timestamp = false, &$filepath )
    {
        $path = eZCharTransform::cachedTransformationPath();
        if ( !file_exists( $path ) )
        {
            include_once( 'lib/ezfile/classes/ezdir.php' );
            eZDir::mkdir( $path, false, true );
        }
        $filepath = $path . '/' . $prefix . sprintf( "%u", $key ) . $suffix . '.ctt.php'; // ctt=charset transform table
        if ( file_exists( $filepath ) )
        {
            $time = filemtime( $filepath );
            if ( $time >= max( EZ_CHARTRANSFORM_CODEDATE, $timestamp ) )
            {
                // Execute the PHP file causing $text will be transformed
                include( $filepath );
                return true;
            }
        }
        return false;
    }

    /*!
     \private
     Stores the mapping table \a $table in the cache file \a $filepath.
    */
    function storeCacheFile( $filepath, $transformationData,
                             $extraCode, $type, $charsetName )
    {
        $fd = @fopen( $filepath, 'wb' );
        if ( $fd )
        {
            @fwrite( $fd, "<?" . "php\n" );
            @fwrite( $fd, "// Cached transformation data\n" );
            @fwrite( $fd, "// Type: $type\n" );
            @fwrite( $fd, "// Charset: $charsetName\n" );
            @fwrite( $fd, "// Cached transformation data\n" );

            // The code that does the transformation
            @fwrite( $fd, '$data = ' . eZCharTransform::varExport( $transformationData ) . ";\n" );
            @fwrite( $fd, "\$text = strtr( \$text, \$data['table'] );\n" );

            if ( $extraCode )
            {
                @fwrite( $fd, $extraCode );
            }

            @fwrite( $fd, "?>" );
            @fclose( $fd );
        }
        else
        {
            eZDebug::writeError( "Failed to store transformation table $filepath" );
        }
    }

    /*!
     \private
     Creates a text representation of the value \a $value which can
     be placed in files and be read back by a PHP parser as it was.
     The type of the values determines the output, it can be one of the following.
     - boolean, becomes \c true or \c false
     - null, becomes \c null
     - string, adds \ (backslash) to backslashes, double quotes, dollar signs and newlines.
               Then wraps the whole string in " (double quotes).
     - numeric, displays the value as-is.
     - array, expands all value recursively using this function
     - object, creates a representation of an object creation if the object has \c serializeData implemented.

     \param $column Determines the starting column in which the text will be placed.
                    This is used for expanding arrays and objects which can span multiple lines.
     \param $iteration The current iteration, starts at 0 and increases with 1 for each recursive call

    */
    function varExport( $value )
    {
        $ver = phpversion();
        // If we the version used is a PHP version with broken var_export
        // we use our own PHP code to export.
        // PHP versions known to have broken var_export are:
        // 4.3.4 and lower
        // 4.3.10
        if ( version_compare( $ver, '4.3.5' ) < 0 or
             ( version_compare( $ver, '4.3.10' ) >= 0 and
               version_compare( $ver, '4.3.11' ) < 0 ) )
        {
            return eZCharTransform::varExportInternal( $value );
        }
        else
        {
            return var_export( $value, true );
        }
    }

    /*!
     \private
     \static
    */
    function varExportInternal( $value, $column = 0, $iteration = 0 )
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
        }
        else if ( is_numeric( $value ) )
            $text = $value;
        else if ( is_object( $value ) )
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
                    $text .= $keyText . eZCharTransform::varExportInternal( $variableValue, $column + strlen( $keyText  ), $iteration + 1 );
                    ++$i;
                }
                if ( $i > 0 )
                    $text .= ' ';

                $text .= ')';
            }
        }
        else if ( is_array( $value ) )
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
                $text .= $keyText . eZCharTransform::varExportInternal( $element, $column + strlen( $keyText  ), $iteration + 1 );
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

    /*!
     \return The unique instance of the character transformer.
    */
    function &instance()
    {
        $instance =& $GLOBALS['eZCharTransformInstance'];
        if ( !isset( $instance ) )
        {
            $instance = new eZCharTransform();
        }
        return $instance;
    }
}

?>
