<?php
//
// Definition of eZCharTransform class
//
// Created on: <16-Jul-2004 15:54:21 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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
  \class eZCharTransform ezchartransform.php
  \ingroup eZI18N
  \brief Performs rule based transformation of characters in a string

  \sa eZCodeMapper
*/

class eZCharTransform
{
    /// The timestamp for when the format of the cache files were
    /// last changed. This must be updated when the format changes
    /// to invalidate existing cache files.
    /// 1101288452
    /// 30. Jan. 2007 - 1170165730
    /// 24. Apr. 2007 - 1177423380
    const CODE_DATE = 1177423380;

    /*!
     Constructor
    */
    function eZCharTransform()
    {
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
        if ( $text === '' )
        {
            return $text;
        }

        if ( $useCache )
        {
            // CRC32 is used for speed, MD5 would be more unique but is slower
            $key = eZSys::ezcrc32( 'Rule: ' . ( is_array( $rule ) ? implode( ',', $rule ) : $rule ) . '-' . $charset );
            $filepath = $this->cacheFilePath( 'rule-',
                                              '-' . $charsetName,
                                              $key );

            $charsetName = ( $charset === false ? eZTextCodec::internalCharset() : eZCharsetInfo::realCharsetCode( $charset ) );

            // Try to execute code in the cache file, if it succeeds
            // \a $text will/ transformated
            $retText = $this->executeCacheFile( $text, $filepath );
            if ( $retText !== false )
            {
                return $retText;
            }
        }

        // Make sure we have a mapper
        $mapper = new eZCodeMapper();

        $mapper->loadTransformationFiles( $charsetName, false );

        // First generate a unicode based mapping table from the rules
        $unicodeTable = $mapper->generateMappingCode( $rule );
        unset($unicodeTable[0]);
        // Then transform that to a table that works with the current charset
        // Any character not available in the current charset will be removed
        $charsetTable = $mapper->generateCharsetMappingTable( $unicodeTable, $charset );
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
     \param $group Which transformation group to use, of which the rules will be applied.
     \param $charset Which charset to use when transforming, if \c false it will use current charset (i18n.ini).
     \param $useCache If \c true then it will use cache files for the tables,
                      if not it will have to calculate them each time.
    */
    function transformByGroup( $text, $group, $charset = false, $useCache = true )
    {
        if ( $text === '' )
        {
            return $text;
        }
        $charsetName = ( $charset === false ? eZTextCodec::internalCharset() : eZCharsetInfo::realCharsetCode( $charset ) );
        if ( $useCache )
        {
            // CRC32 is used for speed, MD5 would be more unique but is slower
            $keyText = 'Group:' . $group;
            $key = eZSys::ezcrc32( $keyText . '-' . $charset );
            $filepath = $this->cacheFilePath( 'g-' . $group . '-',
                                              '-' . $charsetName,
                                              $key);

            // Try to execute code in the cache file, if it succeeds
            // \a $text will/ transformated
            $retText = $this->executeCacheFile( $text, $filepath );
            if ( $retText !== false )
            {
                return $retText;
            }
        }

        $commands = $this->groupCommands( $group );
        if ( $commands === false )
            return false;

        $mapper = new eZCodeMapper();

        $mapper->loadTransformationFiles( $charsetName, $group );

        $rules = array();
        foreach ( $commands as $command )
        {
            $rules = array_merge( $rules,
                                  $mapper->decodeCommand( $command['command'], $command['parameters'] ) );
        }

        // First generate a unicode based mapping table from the rules
        $unicodeTable = $mapper->generateMappingCode( $rules );
        unset($unicodeTable[0]);
        // Then transform that to a table that works with the current charset
        // Any character not available in the current charset will be removed
        $charsetTable = $mapper->generateCharsetMappingTable( $unicodeTable, $charset );
        $transformationData = array( 'table' => $charsetTable );
        unset( $unicodeTable );

        if ( $useCache )
        {
            $extraCode = '';
            foreach ( $commands as $command )
            {
                $code = $mapper->generateCommandCode( $command, $charsetName );
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
            $mapper->executeCommandCode( $text, $command, $charsetName );
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

        $sys = eZSys::instance();
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

        $ini = eZINI::instance( 'transform.ini' );
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
     Get cache file path.

     \param $prefix
     \param $suffix
     \param $key

     \return cache file path.
    */
    function cacheFilePath( $prefix, $suffix, $key )
    {
        $path = eZCharTransform::cachedTransformationPath();
        if ( !file_exists( $path ) )
        {
            eZDir::mkdir( $path, false, true );
        }
        return $path . '/' . $prefix . sprintf( "%u", $key ) . $suffix . '.ctt.php'; // ctt=charset transform table
    }

    /*!
     \private
     \param $text The text that should be transformed
     \param $filepath The filepath for the cache file
     \param $timestamp A timestamp value which is matched against the cache file,
                       pass for instance the timestamp of the INI file.

     \return The restored transformation data or \c false if there is no cached data.
    */
    protected function executeCacheFile( $text, $filepath, $timestamp = false )
    {
        if ( file_exists( $filepath ) )
        {
            $time = filemtime( $filepath );
            $ini = eZINI::instance( 'transform.ini' );
            if ( $ini->CacheFile && file_exists( $ini->CacheFile ) && $time < filemtime( $ini->CacheFile ) )
            {
                return false;
            }
            if ( $time >= max( self::CODE_DATE, $timestamp ) )
            {
                // Execute the PHP file causing $text will be transformed
                include "$filepath";
                return $text;
            }
        }
        return false;
    }

    /*!
     \private
     Stores the mapping table \a $table in the cache file \a $filepath.
    */
    function storeCacheFile( $filepath, $transformationData,$extraCode, $type, $charsetName )
    {
        $file = basename( $filepath );
        $dir = dirname( $filepath );
        $php = new eZPHPCreator( $dir, $file );

        $php->addComment( "Cached transformation data" );
        $php->addComment( "Type: $type" );
        $php->addComment( "Charset: $charsetName" );
        $php->addComment( "Cached transformation data" );

        $php->addCodePiece( '$data = ' . eZCharTransform::varExport( $transformationData ) . ";\n" );
        $php->addCodePiece( "\$text = strtr( \$text, \$data['table'] );\n" );

        if ( $extraCode )
        {
            $php->addCodePiece( $extraCode );
        }

        return $php->store( true );
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
    */
    static function varExport( $value )
    {
        return var_export( $value, true );
    }

    /*!
     \private
     \static
     Creates a text representation of the value \a $value which can
     be placed in files and be read back by a PHP parser as it was.
     Meant as a replacement for PHP versions with broken var_export.

     \param $column Determines the starting column in which the text will be placed.
                    This is used for expanding arrays and objects which can span multiple lines.
     \param $iteration The current iteration, starts at 0 and increases with 1 for each recursive call
    */
    static function varExportInternal( $value, $column = 0, $iteration = 0 )
    {

        if ( $value === true )
            $text = 'true';
        else if ( $value === false )
            $text = 'false';
        else if ( $value === null )
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
     \static
     Returns the current word separator, if none is found it will read from site.ini URLTranslator/WordSeparator
     \sa setWordSeparator
     */
    static function wordSeparator()
    {
        if ( isset( $GLOBALS['eZCharTransform_wordSeparator'] ) )
        {
            return $GLOBALS['eZCharTransform_wordSeparator'];
        }
        else
        {
            $ini = eZINI::instance();
            $separator = strtolower( $ini->variable( "URLTranslator", "WordSeparator" ) );
            switch ( $separator )
            {
                case 'dash':
                    $separator = '-';
                    break;
                case 'underscore':
                    $separator = '_';
                    break;
                case 'space':
                    $separator = ' ';
                    break;
                default:
                    return '-';
            }
            $GLOBALS['eZCharTransform_wordSeparator'] = $separator;
            return $separator;
        }
    }

    /*!
     Sets the current word separator, set it to \c null to use default value.
     */
    function setWordSeparator( $char )
    {
        $GLOBALS['eZCharTransform_wordSeparator'] = $char;
    }

    static function commandUrlCleanupCompat( $text, $charsetName )
    {
        // Old style of url alias with lowercase only and underscores for separators
        $text = strtolower( $text );
        $text = preg_replace( array( "#[^a-z0-9]+#",
                                     "#^_+|_+$#" ),
                              array( "_",
                                     "" ),
                              $text );
        return $text;
    }

    static function commandUrlCleanup( $text, $charsetName )
    {
        $sep  = eZCharTransform::wordSeparator();
        $sepQ = preg_quote( $sep );
        $text = preg_replace( array( "#[^a-zA-Z0-9_!.-]+#",
                                     "#^[.]+|[!.]+$#", # Remove dots at beginning/end
                                     "#\.\.+#", # Remove double dots
                                     "#[{$sepQ}]+#", # Turn multiple separators into one
                                     "#^[{$sepQ}]+|[{$sepQ}]+$#" ), # Strip separator from beginning/end
                              array( $sep,
                                     $sep,
                                     $sep,
                                     $sep,
                                     "" ),
                              $text );
        return $text;
    }

    static function commandUrlCleanupIRI( $text, $charsetName )
    {
        // With IRI support we keep all characters except some reserved ones,
        // they are space, ampersand, semi-colon, forward slash, colon, equal sign, question mark,
        //          square brackets, parenthesis, plus.
        //
        // Note: Space is turned into a dash to make it easier for people to
        //       paste urls from the system and have the whole url recognized
        //       instead of being broken off
        $sep  = eZCharTransform::wordSeparator();
        $sepQ = preg_quote( $sep );
        $prepost = " ." . $sepQ;
        if ( $sep != "-" )
            $prepost .= "-";
        $text = preg_replace( array( "#[ \\\\%\#&;/:=?\[\]()+]+#",
                                     "#^[.]+|[!.]+$#", # Remove dots at beginning/end
                                     "#\.\.+#", # Remove double dots
                                     "#[{$sepQ}]+#", # Turn multiple separators into one
                                     "#^[{$prepost}]+|[{$prepost}]+$#" ),
                              array( $sep,
                                     $sep,
                                     $sep,
                                     $sep,
                                     "" ),
                              $text );
        return $text;
    }

    /**
     * Returns a shared instance of the eZCharTransform class.
     *
     * @return eZCharTransform
     */
    static function instance()
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
