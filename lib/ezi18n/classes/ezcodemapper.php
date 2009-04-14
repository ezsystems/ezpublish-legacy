<?php
//
// Definition of eZCodeMapper class
//
// Created on: <18-Jun-2004 14:56:15 amos>
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
  \class eZCodeMapper ezcodemapper.php
  \ingroup eZI18N
  \brief Handles mapping of character codes

*/

class eZCodeMapper
{
    const TYPE_DIRECT = 1;
    const TYPE_RANGE = 2;
    const TYPE_REPLACE = 3;

    /*!
     Constructor
    */
    function eZCodeMapper()
    {
        $this->TransformationTables = array();
        $this->TransformationFiles = array();
    }

    /*!
     \return The mapping table for identifier \a $identifier or \c false if it is not found.
    */
    function mappingTable( $identifier )
    {
        if ( isset( $this->TransformationTables[$identifier] ) )
            return $this->TransformationTables[$identifier];
        return false;
    }

    /*!
     \return An array with the names of rules which are currently available.
    */
    function ruleNames()
    {
        return array_keys( $this->TransformationTables );
    }

    /*!
     Outputs error \a $text found in parsed file at position \a $position.
    */
    function error( $text, $position = false )
    {
        if ( $position )
        {
            $str = $position['file'] . ':' . $position['from'][0] . ' C' . $position['from'][1];
            if ( isset( $position['to'] ) )
                $str .= ' -> L' . $position['to'][0] . ' C' . $position['to'][1];
            $str .= ':';
        }
        $str .= $text;
        if ( class_exists( 'ezcli' ) )
        {
            $cli = eZCLI::instance();
            $cli->error( $str );
        }
        else
        {
            eZDebug::writeError( $str, 'eZCodeMapper::error' );
        }
    }

    /*!
     Outputs warning \a $text found in parsed file at position \a $position.
    */
    function warning( $text, $position = false )
    {
        if ( $position )
        {
            $str = $position['file'] . ':' . $position['from'][0] . ' C' . $position['from'][1];
            if ( isset( $position['to'] ) )
                $str .= ' -> L' . $position['to'][0] . ' C' . $position['to'][1];
            $str .= ':';
        }
        $str .= $text;
        if ( class_exists( 'ezcli' ) )
        {
            $cli = eZCLI::instance();
            $cli->warning( $str );
        }
        else
        {
            eZDebug::writeWarning( $str, 'eZCodeMapper::warning' );
        }
    }

    /*!
     \return \c true if the transformation file is already loaded.
    */
    function isTranformationLoaded( $name )
    {
        return in_array( $name, $this->TransformationFiles );
    }

    /*!
     Loads all transformation files defined in \c transform.ini to the current
     mapper. It will also load any transformations found in extensions.

     \param $currentCharset The name of the current charset in use. The caller must
                            make sure this is not an alias by using eZCharsetInfo::realCharsetCode()
     \param $transformationGroup The transformation group which is currently used or \c false for none.
    */
    function loadTransformationFiles( $currentCharset, $transformationGroup )
    {
        $ini = eZINI::instance( 'transform.ini' );
        $repositoryList = array( $ini->variable( 'Transformation', 'Repository' ) );
        $files = $ini->variable( 'Transformation', 'Files' );
        $extensions = $ini->variable( 'Transformation', 'Extensions' );
        $repositoryList = array_merge( $repositoryList,
                                       eZExtension::expandedPathList( $extensions, 'transformations' ) );

        // Check if the current charset maps to a unicode group
        // If it does it can trigger loading of additional files
        $unicodeGroups = array();
        $charsets = $ini->variable( 'Transformation', 'Charsets' );
        foreach ( $charsets as $entry )
        {
            list ( $charset, $group ) = explode( ';', $entry, 2 );
            $charset = eZCharsetInfo::realCharsetCode( $charset );
            if ( $charset == $currentCharset )
            {
                if ( !in_array( $group, $unicodeGroups ) )
                    $unicodeGroups[] = $group;
            }
        }

        // If we are using transformation groups then add that as
        // a unicode group. This causes it load transformation files
        // specific to that group.
        if ( $transformationGroup !== false )
            $unicodeGroups[] = $transformationGroup;

        // Add any extra files from the unicode groups
        foreach ( $unicodeGroups as $unicodeGroup )
        {
            if ( $ini->hasGroup( $unicodeGroup ) )
            {
                $files = array_merge( $files, $ini->variable( $unicodeGroup, 'Files' ) );
                $extensions = $ini->variable( $unicodeGroup, 'Extensions' );
                $repositoryList = array_merge( $repositoryList,
                                               eZExtension::expandedPathList( $extensions, 'transformations' ) );
            }
        }

        foreach ( $files as $file )
        {
            // Only load files that are not currently loaded
            if ( $this->isTranformationLoaded( $file ) )
                continue;

            foreach ( $repositoryList as $repository )
            {
                $trFile = $repository . '/' . $file;
                if ( file_exists( $trFile ) )
                {
                    $this->parseTransformationFile( $trFile, $file );
                }
            }
        }
    }

    /*!
     Parses the transformation file \a $filename and appends any rules it finds
     to the current rule list.
     \param $name The name of transformation file as it was requested, ie. without a path
    */
    function parseTransformationFile( $filename, $name )
    {
//         eZDebug::writeDebug( "Parsing file $filename" );
        $tbl = array();

        $fd = fopen( $filename, "rb" );
        if ( !$fd )
        {
            $this->error( "Failed opening $filename" );
            return false;
        }

        $this->TransformationFiles[] = $name;

        $this->ISOUnicodeCodec = eZTextCodec::instance( 'iso-8859-1', 'unicode' );

        $buffer = '';
        $lineNum = 1;
        $i = 0;
        $hexValues = "0123456789abcdefABCDEF";
        $identifier = false;

        // The big funky parser starts here
        // It starts by reading a chunk of data from the file
        // then splits everything into an array with lines.
        // Then it traverses one line at a time looking for
        // identifiers and rules. Comments will be removed before the
        // line is parsed for identifiers and rules.

        while ( !feof( $fd ) or strlen( $buffer ) > 0 )
        {
            $lines = array();
            $len = strlen( $buffer );
            // Check if we have data in the buffer yet
            // Note: The actual buffer reading is done at the end of this while loop
            if ( $len > 0 )
            {
                $endPos = false;
                $eolPos = 0;
                // Look for complete lines and append to $lines
                while ( $eolPos !== false and $eolPos < $len )
                {
                    $eolPos = strpos( $buffer, "\n", $endPos );
                    if ( $eolPos !== false )
                    {
                        $line = substr( $buffer, $endPos, $eolPos - $endPos );
                        $lines[] = array( 'text' => $line,
                                          'line' => $lineNum );
                        ++$lineNum;
                        $endPos = $eolPos + 1;
                    }
                }

                // If we have leftover data place that back in $buffer
                if ( $endPos !== false )
                {
                    $buffer = substr( $buffer, $endPos );
                }
            }

            // Once we have some lines start parsing them one at a time
            foreach ( $lines as $lineData )
            {
                $line = $lineData['text'];
                $lineOrg = $line;
                $linePos = $lineData['line'];
                $commentPos = strpos( $line, '#' );
                $origLine = $line;
                // Get rid of any comments before we check the line
                if ( $commentPos !== false )
                {
                    $line = substr( $line, 0, $commentPos );
                }
                $trimLine = trim( $line );
                // Skip empty lines
                if ( strlen( $trimLine ) == 0 )
                    continue;

//                 print( "Line: '$line'\n" );

                $unicodeData = false;

                $sourceValue = false;
                $sourceEndValue = false;
                $destinationValues = false;
                $transposeValue = false;
                $transposeAdd = true;
                $moduloValue = 1;
                // source, marker, range_input, range_marker, map_input, transpose_input, replace_input
                $state = 'source';
                // map, transpose, replace
                $type = false;

                $len = strlen( $line );
                if ( preg_match( '#^(.+):[ \t]*$#', $line, $matches ) )
                {
                    $identifier = $matches[1];
                    if ( !preg_match( '#^[a-zA-Z_-][a-zA-Z0-9_-]*$#', $identifier ) )
                    {
                        $this->warning( "Invalid identifier '$identifier', can only contain a-z, a-Z - and _",
                                      array( 'file' => $filename, 'from' => array( $linePos, strlen( $identifier ) ) ) );
                        $identifier = false;
                        continue;
                    }
//                     print( "identifier '$identifier'\n" );
                    continue;
                }
                else if ( $identifier === false )
                {
                    $this->warning( "No identifier defined yet, skipping: '" . $line . "'",
                                    array( 'file' => $filename, 'from' => array( $linePos, 0 ) ) );
                    continue;
                }
                else
                {
                    $pos = 0;
                    $col = 0;
                    $failed = false;
                    while ( $pos < $len )
                    {
                        while ( $pos < $len and
                                ( $line[$pos] == ' ' or
                                  $line[$pos] == "\t" ) )
                        {
                            ++$pos;
                        }
                        if ( $pos >= $len )
                            break;

                        $char = $line[$pos];
                        $unicodeData = false;
                        if ( $char == '"' )
                        {
                            $delimiterPos = $pos;
                            while ( $delimiterPos < $len )
                            {
                                $delimiterPos = strpos( $line, '"', $delimiterPos + 1 );
                                if ( $delimiterPos === false or
                                     $delimiterPos <= $pos + 1 or
                                     $line[$delimiterPos - 1] != "\\" )
                                    break;
                            }
                            if ( $delimiterPos === false )
                            {
                                $this->warning( "No end-quote found for line, skipping: '$line'",
                                                array( 'file' => $filename,
                                                       'from' => array( $linePos, $pos ),
                                                       'to' => array( $linePos, strlen( $line ) ) ) );
                                $pos = $len;
                                $failed = true;
                                break;
                            }
                            $str = str_replace( array( "\\\"", "\\\\" ),
                                                array( "\"", "\\" ),
                                                substr( $line, $pos + 1, $delimiterPos - $pos - 1 ) );
//                             print( "string '$str'\n" );
                            $pos = $delimiterPos + 1;
                            $unicodeData = array( 'value' => $str,
                                                  'type' => 'string' );
                        }
                        else if ( $char == 'U' and
                             $pos + 1 < $len and
                             $line[$pos + 1] == '+' )
                        {
                            $hexPos = $pos + 2;
                            if ( $hexPos + 4 > $len )
                            {
                                $col = $hexPos;
                                $this->warning( "Found U+ value with " . ( 4 - ( $len - $hexPos ) ) . " missing hex numbers",
                                                array( 'file' => $filename,
                                                       'from' => array( $linePos, $hexPos ) ) );
                                $failed = true;
                                $pos = $hexPos;
                                break;
                            }
                            $hasHexValues = true;
                            for ( $offset = 0; $offset < 4; ++$offset )
                            {
                                $hexChar = $line[$hexPos + $offset];
                                if ( $hexChar == ' ' or
                                     $hexChar == "\t" )
                                {
                                    $col = $hexPos + $offset;
                                    $hasHexValues = false;
                                    $this->warning( "Found U+ value with " . ( 4 - $offset ) . " missing hex numbers",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $hexPos ),
                                                           'to' => array( $linePos, $hexPos + $offset ) ) );
                                    $failed = true;
                                    $pos = $hexPos + $offset;
                                    break;
                                }
                                if ( strpos( $hexValues, $hexChar ) === false )
                                {
                                    $col = $hexPos + $offset;
                                    $hasHexValues = false;
                                    $this->warning( "Found U+ value with invalid hex numbers ($hexChar)",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $hexPos ),
                                                           'to' => array( $linePos, $hexPos + $offset ) ) );
                                    $pos = $hexPos + $offset;
                                    $failed = true;
                                    break;
                                }
                            }
                            if ( $failed )
                                break;
                            if ( $hasHexValues )
                            {
                                $unicodeValue = hexdec( substr( $line, $hexPos, 4 ) );
                                $unicodeData = array( 'value' => $unicodeValue,
                                                      'type' => 'unicode' );
//                                 print( "unicode U+ '$unicodeValue'\n" );
                            }
                            $pos = $hexPos + 4;
                        }
                        else if ( strpos( $hexValues, $char ) !== false and
                                  $pos + 1 < $len and
                                  strpos( $hexValues, $line[$pos + 1] ) !== false )
                        {
                            $hexPos = $pos;
                            if ( $hexPos + 2 > $len )
                            {
                                $col = $len;
                                $this->warning( "Found ASCII value with " . ( 2 - ( $len - $hexPos ) ) . " missing hex numbers",
                                                array( 'file' => $filename,
                                                       'from' => array( $linePos, $hexPos ) ) );
                                $pos = $hexPos;
                                $failed = true;
                                break;
                            }
                            $hasHexValues = true;
                            for ( $offset = 0; $offset < 2; ++$offset )
                            {
                                $hexChar = $line[$hexPos + $offset];
                                if ( $hexChar == ' ' or
                                     $hexChar == "\t" )
                                {
                                    $col = $hexPos + $offset;
                                    $hasHexValues = false;
                                    $this->warning( "Found ASCII value with " . ( 2 - $offset ) . " missing hex numbers",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $hexPos ),
                                                           'to' => array( $linePos, $hexPos + $offset ) ) );
                                    $pos = $hexPos + $offset;
                                    $failed = true;
                                    break;
                                }
                                if ( strpos( $hexValues, $hexChar ) === false )
                                {
                                    $col = $hexPos + $offset;
                                    $hasHexValues = false;
                                    $this->warning( "Found ASCII value with invalid hex numbers ($hexChar)",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $hexPos ),
                                                           'to' => array( $linePos, $hexPos + $offset ) ) );
                                    $pos = $hexPos + $offset;
                                    $failed = true;
                                    break;
                                }
                            }
                            if ( $failed )
                                break;
                            if ( $hasHexValues )
                            {
                                $asciiValue = hexdec( substr( $line, $hexPos, 4 ) );
//                                 print( "unicode ASCII '$asciiValue'\n" );
                                $unicodeData = array( 'value' => $asciiValue,
                                                      'type' => 'ascii' );
                            }
                            $pos = $hexPos + 2;
                        }
                        else if ( substr( $line, $pos, 6 ) == 'remove' )
                        {
//                             print( "remove character\n" );
                            $unicodeData = array( 'value' => false,
                                                  'type' => 'remove' );
                            $pos += 6;
                        }
                        else if ( substr( $line, $pos, 4 ) == 'keep' )
                        {
//                             print( "keep character\n" );
                            $unicodeData = array( 'value' => true,
                                                  'type' => 'keep' );
                            $pos += 4;
                        }

                        if ( $unicodeData )
                        {
//                             print( "data state: $state\n" );
                            // source, marker, range_input, range_marker, map_input, transpose_input, replace_input, transpose_modulo
                            if ( $state == 'source' )
                            {
                                if ( $unicodeData['type'] == 'string' and
                                     strlen( $unicodeData['value'] ) > 1 )
                                {
                                    $this->warning( "Text string with more than one character cannot be used as input value '" . $unicodeData['value'] . "'",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $pos ) ) );
                                    $failed = true;
                                    break;
                                }
                                $sourceValue = $this->extractUnicodeValue( $unicodeData );
                                $state = 'marker';
                            }
                            else if ( $state == 'marker' )
                            {
                                $this->warning( "Source value not expected, a source value has already been extracted at $line" . "[$pos]",
                                                array( 'file' => $filename,
                                                       'from' => array( $linePos, $pos ) ) );
                                $failed = true;
                                break;
                            }
                            else if ( $state == 'range_input' )
                            {
                                if ( $unicodeData['type'] == 'string' and
                                     strlen( $unicodeData['value'] ) > 1 )
                                {
                                    $this->warning( "Text string with more than one character cannot be used as range end value '" . $unicodeData['value'] . "'",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $pos ) ) );
                                    $failed = true;
                                    break;
                                }
                                $sourceEndValue = $this->extractUnicodeValue( $unicodeData );
                                $state = 'range_marker_or_modulo';
                            }
                            else if ( $state == 'range_marker_or_modulo' or
                                      $state == 'range_marker' )
                            {
                                $this->warning( "Range value not expected, a range value has already been extracted at $line" . "[$pos]",
                                                array( 'file' => $filename,
                                                       'from' => array( $linePos, $pos ) ) );
                                $failed = true;
                                break;
                            }
                            else if ( $state == 'map_input' )
                            {
                                if ( !is_array( $destinationValues ) )
                                    $destinationValues = array();
                                $destinationValues = array_merge( $destinationValues,
                                                                  $this->extractUnicodeValues( $unicodeData ) );
                                $type = 'map';
                            }
                            else if ( $state == 'replace_input' )
                            {
                                if ( !is_array( $destinationValues ) )
                                    $destinationValues = array();
                                $destinationValues = array_merge( $destinationValues,
                                                                  $this->extractUnicodeValues( $unicodeData ) );
                                $type = 'replace';
                            }
                            else if ( $state == 'transpose_input' )
                            {
                                if ( $unicodeData['type'] == 'string' and
                                     strlen( $unicodeData['value'] ) > 1 )
                                {
                                    $this->warning( "Text string with more than one character cannot be used as transpose value '" . $unicodeData['value'] . "'",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $pos ) ) );
                                    $failed = true;
                                    break;
                                }
                                $transposeValue = $this->extractUnicodeValue( $unicodeData );
                                $type = 'transpose';
                            }
                            else if ( $state == 'transpose_modulo' )
                            {
                                if ( $unicodeData['type'] == 'string' and
                                     strlen( $unicodeData['value'] ) > 1 )
                                {
                                    $this->warning( "Text string with more than one character cannot be used as transpose modulo value '" . $unicodeData['value'] . "'",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $pos ) ) );
                                    $failed = true;
                                    break;
                                }
                                $moduloValue = $this->extractUnicodeValue( $unicodeData );
                                if ( $moduloValue == 0 )
                                {
                                    $this->error( "Modulo value of 0 is not allowed, 1 will be used instead",
                                                  array( 'file' => $filename,
                                                         'from' => array( $linePos, $pos ) ) );
                                    // Note: There is another 0 check in generateSimpleMappingTable()
                                }
//                                 print( "modulo value=$moduloValue\n" );
                                $state = 'range_marker';
                            }
                        }
                        else if ( !$failed )
                        {
//                             print( "command state: $state\n" );
                            // source, marker, range_input, range_marker, map_input, transpose_input, replace_input
                            if ( $state == 'source' )
                            {
                                if ( $char == '=' )
                                {
                                    $this->warning( "Cannot use map marker $char without prior character value",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $pos ) ) );
                                    $failed = true;
                                    break;
                                }
                                else if ( $char == '+' or
                                          $char == '-' )
                                {
                                    $this->warning( "Cannot use range marker $char without prior character value",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $pos ) ) );
                                    $failed = true;
                                    break;
                                }
                                else
                                {
                                    $this->warning( "Unknown character '$char', expecting input value",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $pos ) ) );
                                    $failed = true;
                                    break;
                                }
                            }
                            else if ( $state == 'marker' )
                            {
                                if ( $char == '=' )
                                {
                                    $state = 'map_input';
                                    ++$pos;
                                }
                                else if ( $char == '-' )
                                {
                                    $state = 'range_input';
                                    ++$pos;
                                }
                                else if ( $char == '+' )
                                {
                                    $this->warning( "Cannot use range marker $char without prior character value",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $pos ) ) );
                                    $failed = true;
                                    break;
                                }
                                else
                                {
                                    $this->warning( "Unknown character '$char', expecting marker",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $pos ) ) );
                                    $failed = true;
                                    break;
                                }
                            }
                            else if ( $state == 'range_marker_or_modulo' or
                                      $state == 'range_marker' )
                            {
                                if ( $state == 'range_marker_or_modulo' and
                                     $char == '%' )
                                {
//                                     print( "found modulo marker\n" );
                                    // Look for modulo value
                                    $state = 'transpose_modulo';
                                    ++$pos;
                                }
                                else if ( $char == '=' )
                                {
                                    $state = 'replace_input';
                                    ++$pos;
                                }
                                else if ( $char == '-' or
                                          $char == '+' )
                                {
                                    $transposeAdd = ( $char == '+' ? true : false );
                                    $state = 'transpose_input';
                                    ++$pos;
                                }
                                else
                                {
                                    $this->warning( "Unknown character '$char', expecting range end value",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $pos ) ) );
                                    $failed = true;
                                    break;
                                }
                            }
                            else if ( $state == 'map_input' )
                            {
                                if ( $char == '=' )
                                {
                                    $this->warning( "Duplicate mapping marker $char",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $pos ) ) );
                                    $failed = true;
                                    break;
                                }
                                else if ( $char == '-' or
                                          $char == '+' )
                                {
                                    $this->warning( "Already mapping values, cannot use range/transpose marker $char",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $pos ) ) );
                                    $failed = true;
                                    break;
                                }
                                else
                                {
                                    $this->warning( "Unknown character '$char', expecting output values",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $pos ) ) );
                                    $failed = true;
                                    break;
                                }
                            }
                            else if ( $state == 'transpose_modulo' )
                            {
                                if ( $char == '%' )
                                {
                                    $this->warning( "Modulo marker already used, cannot use $char",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $pos ) ) );
                                    $failed = true;
                                    break;
                                }
                                else if ( $char == '-' or
                                          $char == '+' )
                                {
                                    $this->warning( "Transpose marker $char used, but no modulo value has been found yet",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $pos ) ) );
                                    $failed = true;
                                    break;
                                }
                                else
                                {
                                    $this->warning( "Unknown character '$char', expecting modulo value",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $pos ) ) );
                                    $failed = true;
                                    break;
                                }
                            }
                            else if ( $state == 'transpose_input' )
                            {
                                if ( $char == '=' )
                                {
                                    $this->warning( "Already transposing, cannot use mapping marker $char",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $pos ) ) );
                                    $failed = true;
                                    break;
                                }
                                else if ( $char == '-' or
                                          $char == '+' )
                                {
                                    $this->warning( "Duplicate transpose marker $char",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $pos ) ) );
                                    $failed = true;
                                    break;
                                }
                                else
                                {
                                    $this->warning( "Unknown character '$char', expecting transpose value",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $pos ) ) );
                                    $failed = true;
                                    break;
                                }
                            }
                            else if ( $state == 'replace_input' )
                            {
                                if ( $char == '=' )
                                {
                                    $this->warning( "Already replacing, cannot use mapping marker $char",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $pos ) ) );
                                    $failed = true;
                                    break;
                                }
                                else if ( $char == '-' or
                                          $char == '+' )
                                {
                                    $this->warning( "Already replacing, cannot use transpose marker $char",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $pos ) ) );
                                    $failed = true;
                                    break;
                                }
                                else
                                {
                                    $this->warning( "Unknown character '$char', expecting replace value",
                                                    array( 'file' => $filename,
                                                           'from' => array( $linePos, $pos ) ) );
                                    $failed = true;
                                    break;
                                }
                            }
                        }
                    }
                    if ( !$failed )
                    {
                        if ( $identifier )
                        {
//                             print( "\nGot type '$type'\n" );
//                            if ( is_array( $destinationValues ) )
//                                $destinationValues = array_diff( $destinationValues, array( '' ) );

                            if ( !isset( $tbl[$identifier] ) )
                                $tbl[$identifier] = array();

                            if ( $type == 'map' )
                            {
//                                 print( "***mapping***:\n" . $sourceValue . ' => ' . implode( ', ', $destinationValues ) . "\n\n" );
                                $this->appendDirectMapping( $tbl[$identifier], $identifier, $sourceValue, $destinationValues );
                            }
                            else if ( $type == 'replace' )
                            {
//                                 print( "***replacing***:\n" . $sourceValue . ' - ' . $sourceEndValue . ' => ' . implode( ', ', $destinationValues ) . "\n\n" );
                                $this->appendReplaceMapping( $tbl[$identifier], $identifier, $sourceValue, $sourceEndValue, $destinationValues );
                            }
                            else if ( $type == 'transpose' )
                            {
//                                 print( "***transposing***:\n" . $sourceValue . ' - ' . $sourceEndValue . ' % ' . $moduloValue . ' + ' . $transposeValue . "\n\n" );
                                $this->appendTransposeMapping( $tbl[$identifier], $identifier, $sourceValue, $sourceEndValue, $transposeValue, $transposeAdd, $moduloValue );
                            }
                        }
//                         else
//                         {
//                             print( "No identifier found yet, skipping entry!!!!!!!!!!\n" );
//                         }
                    }
                    else
                    {
//                         $this->warning( "Failed adding mapper",
//                                         array( 'file' => $filename,
//                                                'from' => array( $linePos, $pos ) ) );
                    }
                }
            }

            // Here we read more data from the file, appending to
            // the $buffer variable
            if ( !feof( $fd ) )
            {
                $buffer .= fread( $fd, 4096 );

                // Make sure we have Unix endline characters
                $buffer = preg_replace( "#(\r\n|\r|\n)#", "\n", $buffer );
            }
            ++$i;
        }

        fclose( $fd );

        $this->TransformationTables = array_merge( $this->TransformationTables, $tbl );
    }

    /*!
     \private
     Appends a mapping from one value to another.
     \param $block Current block it is working on
     \param $identifier The current identifier it is working on
     \param $sourceValue The original value
     \param $destinationValues The value it should be mapped to
    */
    function appendDirectMapping( &$block, $identifier, $sourceValue, $destinationValues )
    {
        $count = count( $block );
        if ( count( $destinationValues ) == 1 )
            $destinationValues = array_pop( $destinationValues );
        if ( isset( $block[$count - 1] ) and
             $block[$count - 1][0] == self::TYPE_DIRECT and
             $block[$count - 1][2] == $identifier )
        {
            $block[$count - 1][1][$sourceValue] = $destinationValues;
        }
        else
        {
            $block[] = array( self::TYPE_DIRECT,
                              array( $sourceValue => $destinationValues ),
                              $identifier );

        }
    }

    /*!
     \private
     Appends a mapping for a range of values into a specific value
     \param $block Current block it is working on
     \param $identifier The current identifier it is working on
     \param $sourceValue The start of the original value
     \param $sourceEndValue The ned of the original value
     \param $destinationValues The value it should be mapped to
    */
    function appendReplaceMapping( &$block, $identifier, $sourceValue, $sourceEndValue, $destinationValues )
    {
        $count = count( $block );
        if ( count( $destinationValues ) == 1 )
            $destinationValues = array_pop( $destinationValues );
        if ( isset( $block[$count - 1] ) and
             $block[$count - 1][0] == self::TYPE_REPLACE and
             $block[$count - 1][2] == $identifier )
        {
            $block[$count - 1][1][] = array( $sourceValue, $sourceEndValue, $destinationValues );
        }
        else
        {
            $block[] = array( self::TYPE_REPLACE,
                              array( array( $sourceValue, $sourceEndValue, $destinationValues ) ),
                              $identifier );

        }
    }

    /*!
     \private
     Appends a mapping for characters by transposing them up or down.
     \param $block Current block it is working on
     \param $identifier The current identifier it is working on
     \param $sourceValue The start of the original value
     \param $sourceEndValue The ned of the original value
     \param $transposeValue How much to transpose the values
     \param $addValue If \c true the $transposeValue is added to the range if not it is subtracted.
    */
    function appendTransposeMapping( &$block, $identifier, $sourceValue, $sourceEndValue, $transposeValue, $addValue, $moduloValue )
    {
        $count = count( $block );
        if ( isset( $block[$count - 1] ) and
             $block[$count - 1][0] == self::TYPE_RANGE and
             $block[$count - 1][2] == $identifier )
        {
            $block[$count - 1][1][] = array( $sourceValue, $sourceEndValue, $addValue ? $transposeValue : -$transposeValue, $moduloValue );
        }
        else
        {
            $block[] = array( self::TYPE_RANGE,
                              array( array( $sourceValue, $sourceEndValue, $addValue ? $transposeValue : -$transposeValue, $moduloValue ) ),
                              $identifier );

        }
    }

    /*!
     \private
     \return The first unicod value for the data entry \a $data.
    */
    function extractUnicodeValue( $data )
    {
        $type = $data['type'];
        if ( $type == 'string' )
        {
            $list = $this->ISOUnicodeCodec->convertString( $data['value'][0] );
            return $list[0];
        }
        else if ( $type == 'ascii' )
        {
            return $data['value'];
        }
        else if ( $type == 'unicode' )
        {
            return $data['value'];
        }
        else if ( $type == 'remove' )
        {
            return false;
        }
        else if ( $type == 'keep' )
        {
            return true;
        }
        return null;
    }

    /*!
     \private
     \return The unicode values for the data entry \a $data.
    */
    function extractUnicodeValues( $data )
    {
        $type = $data['type'];
        if ( $type == 'string' )
        {
            return $this->ISOUnicodeCodec->convertString( $data['value'] );
        }
        else if ( $type == 'ascii' )
        {
            return array( $data['value'] );
        }
        else if ( $type == 'unicode' )
        {
            return array( $data['value'] );
        }
        else if ( $type == 'remove' )
        {
            return array( false );
        }
        else if ( $type == 'keep' )
        {
            return array( true );
        }
        return array();
    }

    /*!
     \private
     Goes trough all entries in \a $table and if it finds identifier references
     it will fetch the table for that identifier and merge in the current one.
     \return The expanded table.
    */
    function expandInheritance( $table )
    {
        $newTable = array();
        foreach ( $table as $tableItem )
        {
            if ( is_string( $tableItem ) )
            {
                $identifier = $tableItem;
                $subTable = $this->mappingTable( $identifier );
                if ( !$subTable )
                {
                    eZDebug::writeError( "Failed to fetch mapping table for identifier: '$identifier'" );
                }
                else
                {
                    $subTable = $this->expandInheritance( $subTable );
                    $newTable = array_merge( $newTable, $subTable );
                }
            }
            else
            {
                $newTable[] = $tableItem;
            }
        }
        return $newTable;
    }

    /*!
     Turns the character list $list into an array with ordinal values
     \param $list Can be on of these types:
                  - String - each character is turned into an ordinal value
                  - Numeric - the numeric is used as ordinal value
                  - Boolean - means no character
                  - Array - each element is turned into an ordinal value by recursion
    */
    function ordinalValues( $table, $list )
    {
        $ordinals = array();
        if ( is_string( $list ) )
        {
            $len = strlen( $list );
            for ( $offset = 0; $offset < $len; ++$offset )
            {
                $ordinals[] = ord( $list[$offset] );
            }
        }
        else if ( is_numeric( $list ) )
        {
            $ordinals[] = $list;
        }
        else if ( is_array( $list ) )
        {
            foreach ( $list as $item )
            {
                $ordinals = array_merge( $ordinals, eZCodeMapper::ordinalValues( $table, $item ) );
            }
        }
        $ordinals = eZCodeMapper::mapOrdinals( $table, $ordinals );
        return $ordinals;
    }

    /*!
     Goes trough each ordinal in \a $ordinals and sees if there is mapping for it.
     If it is the mapping is applied and used as the new ordinal, if the mapping refers to
     an array it will be mapped recursively.
    */
    function mapOrdinals( $table, $ordinals )
    {
        $mappedOrdinals = array();
        foreach ( $ordinals as $ordinal )
        {
            while ( !is_array( $ordinal ) and isset( $table[$ordinal] ) )
            {
                $ordinal = $table[$ordinal];
                if ( is_array( $ordinal ) )
                {
                    $ordinal = eZCodeMapper::mapOrdinals( $table, $ordinal );
                }
            }
            if ( is_array( $ordinal ) )
                $mappedOrdinals = array_merge( $mappedOrdinals, $ordinal );
            else
                $mappedOrdinals[] = $ordinal;
        }
        return $mappedOrdinals;
    }

    /*!
     Goes trough all to codes in the mapping table \a $unicodeMap and maps
     those that match \a $fromCode into \a $toCode.

     \return \a $unicodeMap
    */
    protected function mapExistingCodes( $unicodeMap, $fromCode, $toCode )
    {
        foreach ( $unicodeMap as $from => $to )
        {
            if ( is_array( $to ) )
            {
                $newTo = array();
                foreach ( $to as $ordinal )
                {
                    if ( $ordinal == $fromCode )
                    {
                        $newTo = array_merge( $newTo, array( $toCode ) );
                    }
                    else
                    {
                        $newTo[] = $ordinal;
                    }
                }
                $unicodeMap[$from] = $newTo;
            }
            else if ( $to == $fromCode )
            {
                $unicodeMap[$from] = $toCode;
            }
        }
        return $unicodeMap;
    }

    /*!
     Goes trough the mapping rules in the table \a $table and generates a simple
     mapping table which maps from one Unicode value to another (or array of values).

     The generation uses backward and forward propagation of the defined mappings
     to get the proper end result of a given value.

     \note This method can take a while if lots of rules are used
    */
    function generateSimpleMappingTable( $table, $allowedRanges )
    {
        if ( !is_array( $table ) )
            return false;
        $unicodeMap = array();
        foreach ( $table as $tableItem )
        {
            $type = $tableItem[0];
            $item = $tableItem[1];
            if ( isset( $tableItem[2] ) )
            {
                $identifier = $tableItem[2];
//                print( "identifier: $identifier\n" );
            }
            if ( $type == self::TYPE_DIRECT )
            {
                foreach ( $item as $fromCode => $toCode )
                {
//                    print( "from: $fromCode, to: $toCode\n" );
//                     if ( $fromCode == 1026 )
//                     {
//                         print( "<pre>oldcode<br/>" ); var_dump( $toCode ); print( "</pre>" );
//                     }
                    $toCode = eZCodeMapper::ordinalValues( $unicodeMap, $toCode );
//                     if ( $fromCode == 1026 )
//                     {
//                         print( "<pre>newcode<br/>" ); var_dump( $toCode ); print( "</pre>" );
//                     }
                    if ( count( $allowedRanges ) == 0 )
                    {
                        if ( count( $toCode ) == 1 )
                            $toCode = $toCode[0];
                        // If the mapping already exists we skip it
                        if ( isset( $unicodeMap[$fromCode] ) )
                            continue;

                        $unicodeMap[$fromCode] = $toCode;
                        $unicodeMap = eZCodeMapper::mapExistingCodes( $unicodeMap, $fromCode, $toCode );
                    }
                    else
                    {
                        $allowed = false;
                        foreach ( $allowedRanges as $allowedRange )
                        {
                            if ( $fromCode >= $allowedRange[0] and
                                 $fromCode <= $allowedRange[1] )
                            {
                                $allowed = true;
                                break;
                            }
                        }
                        if ( !$allowed )
                            continue;

                        $toCodeList = $toCode;
                        $newToCodeList = array();
                        foreach ( $toCodeList as $toCode )
                        {
                            if ( is_bool( $toCode ) )
                            {
                                $newToCodeList[] = $toCode;
                                continue;
                            }
                            foreach ( $allowedRanges as $allowedRange )
                            {
                                if ( $toCode >= $allowedRange[0] and
                                     $toCode <= $allowedRange[1] )
                                {
                                    break;
                                }
                            }
                            if ( $allowed )
                            {
                                $newToCodeList[] = $toCode;
                            }
                        }
                        $toCode = $newToCodeList;
                        if ( count( $toCode ) > 0 )
                        {
                            if ( count( $toCode ) == 1 )
                                $toCode = $toCode[0];

                            // If the mapping already exists we skip it
                            if ( isset( $unicodeMap[$fromCode] ) )
                                continue;

                            $unicodeMap = eZCodeMapper::mapExistingCodes( $unicodeMap, $fromCode, $toCode );

                            $unicodeMap[$fromCode] = $toCode;
                        }
                    }
                }
            }
            else if ( $type == self::TYPE_RANGE )
            {
                foreach ( $item as $rangeItem )
                {
                    $start = $rangeItem[0];
                    $stop = $rangeItem[1];
                    if ( $start > $stop )
                    {
                        $tmp = $stop;
                        $stop = $start;
                        $start = $tmp;
                    }
                    $add = $rangeItem[2];
                    $modulo = $rangeItem[3];
                    // Sanity-check, to avoid infinite loops
                    if ( $modulo == 0 )
                        $modulo = 1;
                    for ( $i = $start; $i <= $stop; $i += $modulo )
                    {
                        if ( count( $allowedRanges ) == 0 )
                        {
                            $allowed = true;
                        }
                        else
                        {
                            $allowed = false;
                            foreach ( $allowedRanges as $allowedRange )
                            {
                                if ( $i >= $allowedRange[0] and
                                     $i <= $allowedRange[1] )
                                {
                                    $allowed = true;
                                    break;
                                }
                            }
                            if ( !$allowed )
                                continue;
                        }

                        $replace = $i + $add;
                        $replace = eZCodeMapper::ordinalValues( $unicodeMap, $replace );
                        if ( count( $allowedRanges ) == 0 )
                        {
                            if ( count( $replace ) == 0 )
                                $replace = false;
                            else if ( count( $replace ) == 1 )
                                $replace = $replace[0];
                            $unicodeMap = eZCodeMapper::mapExistingCodes( $unicodeMap, $i, $replace );

                            // If the mapping already exists we skip it
                            if ( isset( $unicodeMap[$i] ) )
                                continue;

                            $unicodeMap[$i] = $replace;
                        }
                        else
                        {
                            $newReplace = array();
                            foreach ( $allowedRanges as $allowedRange )
                            {
                                foreach ( $replace as $replaceOrdinal )
                                {
                                    if ( $replaceOrdinal >= $allowedRange[0] and
                                         $replaceOrdinal <= $allowedRange[1] )
                                    {
                                        $newReplace[] = $replaceOrdinal;
                                    }
                                }
                            }
                            if ( count( $newReplace ) == 0 )
                                $replace = false;
                            else if ( count( $newReplace ) == 1 )
                                $replace = $newReplace[0];
                            else
                                $replace = $newReplace;

                            // If the mapping already exists we skip it
                            if ( isset( $unicodeMap[$i] ) )
                                continue;

                            $unicodeMap = eZCodeMapper::mapExistingCodes( $unicodeMap, $i, $replace );
                            $unicodeMap[$i] = $replace;
                        }
                    }
                }
            }
            else if ( $type == self::TYPE_REPLACE )
            {
                foreach ( $item as $rangeItem )
                {
                    $start = $rangeItem[0];
                    $stop = $rangeItem[1];
                    if ( $start > $stop )
                    {
                        $tmp = $stop;
                        $stop = $start;
                        $start = $tmp;
                    }
                    $replace = $rangeItem[2];
                    $replace = eZCodeMapper::ordinalValues( $unicodeMap, $replace );
                    if ( count( $allowedRanges ) == 0 )
                    {
                        if ( count( $replace ) == 0 )
                            $replace = false;
                        else if ( count( $replace ) == 1 )
                            $replace = $replace[0];
                        for ( $i = $start; $i <= $stop; ++$i )
                        {
                            // If the mapping already exists we skip it
                            if ( isset( $unicodeMap[$i] ) )
                                continue;

                            $unicodeMap = eZCodeMapper::mapExistingCodes( $unicodeMap, $i, $replace );
                            $unicodeMap[$i] = $replace;
                        }
                    }
                    else
                    {
                        $newReplace = array();
                        foreach ( $allowedRanges as $allowedRange )
                        {
                            foreach ( $replace as $replaceOrdinal )
                            {
                                if ( $replaceOrdinal >= $allowedRange[0] and
                                     $replaceOrdinal <= $allowedRange[1] )
                                {
                                    $newReplace[] = $replaceOrdinal;
                                }
                            }
                        }
                        if ( count( $newReplace ) == 0 )
                            $replace = false;
                        else if ( count( $newReplace ) == 1 )
                            $replace = $newReplace[0];
                        else
                            $replace = $newReplace;
                        for ( $i = $start; $i <= $stop; ++$i )
                        {
                            $allowed = false;
                            foreach ( $allowedRanges as $allowedRange )
                            {
                                if ( $i >= $allowedRange[0] and
                                     $i <= $allowedRange[1] )
                                {
                                    $allowed = true;
                                    break;
                                }
                            }
                            if ( $allowed )
                            {
                                // If the mapping already exists we skip it
                                if ( isset( $unicodeMap[$i] ) )
                                    continue;

                                $unicodeMap = eZCodeMapper::mapExistingCodes( $unicodeMap, $i, $replace );
                                $unicodeMap[$i] = $replace;
                            }
                        }
                    }
                }
            }
        }
        return $unicodeMap;
    }

    /*!
     Generates a unicode mapping table for idenfier \a $idenfier.

     \param $identifier Is either a single identifier string or a
                        an array with identifiers.
     \return The unicode mapping table for all defined identifiers
    */
    function generateMappingCode( $identifier )
    {
        if ( !is_array( $identifier ) )
            $identifier = array( $identifier );
        $table = $this->expandInheritance( $identifier );

        // We allow all characters for now
        $allowedRanges = array();
        $simpleTable = $this->generateSimpleMappingTable( $table, $allowedRanges );
        ksort( $simpleTable );
        return $simpleTable;
    }

    /*!
     Generates a mapping table for the character set $charset.
     This will mapping table will only work for that character set but will be much faster
     and be fed directly to the strtr() PHP function.
     \return the table or \c false if something failed.
    */
    function generateCharsetMappingTable( $unicodeTable, $charset )
    {
        $codec = eZTextCodec::instance( 'unicode', $charset );
        if ( !$codec )
        {
            eZDebug::writeError( "Failed to create textcodec for charset '$charset'" );
            return false;
        }

        $charsetTable = array();
        foreach ( $unicodeTable as $match => $replacement )
        {
            $matchLocal = $codec->convertString( array( $match ) );
            if ( is_array( $replacement ) )
            {
                $replacementLocal = $codec->convertString( $replacement );
            }
            else
            {
                $replacementLocal = $codec->convertString( array( $replacement ) );
            }
            $charsetTable[$matchLocal] = $replacementLocal;
        }

        // Make sure longer string entries are placed before the shorter ones
        // This is very important when working with utf8 which have
        // variable length for characters
        krsort( $charsetTable );
        return $charsetTable;
    }

    /*!
     Decodes a command into transformation rules.
     \param $name Name of the command
     \param $parameters Array of parameters for the command
     \return An array with transformation rules.
    */
    function decodeCommand( $name, $parameters )
    {
        $names = $this->ruleNames();
        $rules = array();
        switch ( $name )
        {
            // Special code handlers
            case 'url_cleanup_iri':
            case 'url_cleanup':
            case 'url_cleanup_compat':
            case 'identifier_cleanup':
            {
            } break;

            case 'normalize':
            case 'search_normalize':
            case 'decompose':
            case 'diacritical':
            case 'lowercase':
            case 'uppercase':
            case 'search_cleanup':
            {
                if ( count( $parameters ) == 0 )
                {
                    // Include all normalize rules
                    foreach ( $names as $rule )
                    {
                        if ( preg_match( '#_'. $name . '$#', $rule ) )
                            $rules[] = $rule;
                    }
                }
                else
                {
                    foreach ( $parameters as $parameter )
                    {
                        $rule = $parameter . '_' . $name;
                        if ( in_array( $rule, $names ) )
                            $rules[] = $rule;
                    }
                }
            } break;

            case 'transform':
            case 'transliterate':
            {
                $dividers = array( 'transform' => '_to_',
                                   'transliterate' => '_transliterate_' );
                $divider = $dividers[$name];
                if ( count( $parameters ) == 0 )
                {
                    // Include all transformation rules
                    foreach ( $names as $rule )
                    {
                        if ( preg_match( '#^[a-zA-Z][a-zA-Z0-9-]+'. $divider . '[a-zA-Z][a-zA-Z0-9-]+$#', $rule ) )
                            $rules[] = $rule;
                    }
                }
                else if ( count( $parameters ) == 2 )
                {
                    $rule = $parameters[0] . $divider . $parameters[1];
                    if ( in_array( $rule, $names ) )
                        $rules[] = $rule;
                }
            } break;

            default:
            {
                $ini = eZINI::instance( 'transform.ini' );
                $commands = $ini->variable( 'Extensions', 'Commands' );
                if ( isset( $commands[$name] ) )
                {
                    break;
                }
                eZDebug::writeError( "Unknown command '$name'",
                                     'eZCharTransform::decodeCommand' );
            } break;
        }
        return $rules;
    }

    /*!
     Generates PHP code for the command \a $command.
     \param $charsetName The name of the charset the text will be in,
                         this can be used to generate different code for different charsets.
     \return A string containing PHP code or \c false if not supported.
    */
    function generateCommandCode( $command, $charsetName )
    {
        if ( $command['command'] == 'url_cleanup_iri' )
        {
            $charsetNameTxt = var_export( $charsetName, true );
            $code = "\$text = eZCharTransform::commandUrlCleanupIRI( \$text, $charsetNameTxt );\n";
            return $code;
        }
        else if ( $command['command'] == 'url_cleanup' )
        {
            $charsetNameTxt = var_export( $charsetName, true );
            $code = "\$text = eZCharTransform::commandUrlCleanup( \$text, $charsetNameTxt );\n";
            return $code;
        }
        else if ( $command['command'] == 'url_cleanup_compat' )
        {
            $charsetNameTxt = var_export( $charsetName, true );
            $code = "\$text = eZCharTransform::commandUrlCleanupCompat( \$text, $charsetNameTxt );\n";
            return $code;
        }
        else if ( $command['command'] == 'identifier_cleanup' )
        {
            $code = ( "\$text = strtolower( \$text );\n" .
                      "\$text = preg_replace( array( \"#[^a-z0-9_ ]#\",\n" .
                      "                             \"/ /\",\n" .
                      "                             \"/__+/\",\n" .
                      "                             \"/^_|_$/\" ),\n" .
                      "                      array( \" \",\n" .
                      "                             \"_\",\n" .
                      "                             \"_\",\n" .
                      "                             \"\" ),\n" .
                      "                      \$text );\n" );
            return $code;
        }
        else if ( $command['command'] == 'search_cleanup' )
        {
            $code = '';
            $nonCJKCharsets = $this->nonCJKCharsets();
            if ( !in_array( $charsetName, $nonCJKCharsets ) )
            {
                $code .= ( '// add N-Gram(N=2)  chinese / japanese / korean multibyte characters' . "\n" .
                           '//include_once( \'lib/ezi18n/classes/eztextcodec.php\' );' . "\n" .
                           '$codec = eZTextCodec::instance( false, \'unicode\' );' . "\n" .
                           "\n" .
                           '$unicodeValueArray = $codec->convertString( $text );' . "\n" .
                           "\n" .
                           '$normalizedTextArray = array();' . "\n" .
                           '$bFlag = false;' . "\n" .
                          'foreach ( array_keys( $unicodeValueArray ) as $valueKey )' . "\n" .
                           '{' . "\n" .
                           '    // Check for word characters that should be broken up for search' . "\n" .
                           '    if ( ( $unicodeValueArray[$valueKey] >= 12289 and' . "\n" .
                           '           $unicodeValueArray[$valueKey] <= 12542 ) or' . "\n" .
                           '         ( $unicodeValueArray[$valueKey] >= 13312 and' . "\n" .
                           '           $unicodeValueArray[$valueKey] <= 40863 ) or' . "\n" .
                           '         ( $unicodeValueArray[$valueKey] >= 44032 and' . "\n" .
                           '           $unicodeValueArray[$valueKey] <= 55203 ) )' . "\n" .
                           '    {' . "\n" .
                           '        if ( $bFlag )' . "\n" .
                           '        {' . "\n" .
                           '            $normalizedTextArray[] = $unicodeValueArray[$valueKey];' . "\n" .
                           '        }' . "\n" .
                           '        $normalizedTextArray[] = 32; // A space' . "\n" .
                           '        $normalizedTextArray[] = $unicodeValueArray[$valueKey];' . "\n" .
                           '        $bFlag = true;' . "\n" .
                           '    }' . "\n" .
                           '    else' . "\n" .
                           '    {' . "\n" .
                           '        if ( $bFlag )' . "\n" .
                           '        {' . "\n" .
                           '            $normalizedTextArray[] = 32; // A space' . "\n" .
                           '        }' . "\n" .
                           '        $normalizedTextArray[] = $unicodeValueArray[$valueKey];' . "\n" .
                           '        $bFlag = false;' . "\n" .
                           '    }' . "\n" .
                           '}' . "\n" .
                           'if ( $bFlag )' . "\n" .
                           '{' . "\n" .
                           '    $normalizedTextArray[count($normalizedTextArray)-1]=32;' . "\n" .
                           '}' . "\n" .
                           '$revCodec = eZTextCodec::instance( \'unicode\', false ); // false means use internal charset' . "\n" .
                           '$text = $revCodec->convertString( $normalizedTextArray );' . "\n" );
            }
            $code .= ( '$text = preg_replace( array( "#(\.){2,}#",' . "\n" .
                       '                             "#^\.#",' . "\n" .
                       '                             "#\s\.#",' . "\n" .
                       '                             "#\.\s#",' . "\n" .
                       '                             "#\.$#",' . "\n" .
                       '                             "#([^0-9])%#" ),' . "\n" .
                       '                      array( " ",' . "\n" .
                       '                             " ",' . "\n" .
                       '                             " ",' . "\n" .
                       '                             " ",' . "\n" .
                       '                             " ",' . "\n" .
                       '                             " " ),' . "\n" .
                       '                      $text );' . "\n" .
                       '$ini = eZINI::instance();' . "\n" .
                       'if ( $ini->variable( \'SearchSettings\', \'EnableWildcard\' ) != \'true\' )' . "\n" .
                       '{' . "\n" .
                       '    $text = str_replace( "*", " ", $text );' . "\n" .
                       '}' . "\n" .
                       '$charset = eZTextCodec::internalCharset();' . "\n" .
                       '$hasUTF8 = ( $charset == "utf-8" );' . "\n" .
                       "\n" .
                       'if ( $hasUTF8 )' . "\n" .
                       '{' . "\n" .
                       '    $text = preg_replace( "#(\s+)#u", " ", $text );' . "\n" .
                       '}' . "\n" .
                       'else' . "\n" .
                       '{' . "\n" .
                       '    $text = preg_replace( "#(\s+)#", " ", $text );' . "\n" .
                       '}' );

            return $code;
        }
        else
        {
            $ini = eZINI::instance( 'transform.ini' );
            $commands = $ini->variable( 'Extensions', 'Commands' );
            if ( isset( $commands[$command['command']] ) )
            {
                list( $path, $className ) = explode( ':', $commands[$command['command']], 2 );
                if ( file_exists( $path ) )
                {
                    $charsetNameTxt = var_export( $charsetName, true );
                    $commandTxt     = var_export( $command['command'], true );
                    $pathTxt        = var_export( $path, true );
                    $code = "include_once( $pathTxt );\n\$text = $className::executeCommand( \$text, $commandTxt, $charsetNameTxt );\n";
                    return $code;
                }
                else
                {
                    eZDebug::writeError( "Could not locate include file '$path' for transformation '" . $command['command'] . "'" );
                }
            }
        }
        return false;
    }

    /*!
     Executes custom PHP code for the command \a $command.
     \param $charsetName The name of the charset the text will be in,
                         this can be used to execute different code for different charsets.
     \return \c true if the command is supported, \c false otherwise.
    */
    function executeCommandCode( &$text, $command, $charsetName )
    {
        if ( $command['command'] == 'url_cleanup_iri' )
        {
            $text = eZCharTransform::commandUrlCleanupIRI( $text, $charsetName );
            return true;
        }
        else if ( $command['command'] == 'url_cleanup' )
        {
            $text = eZCharTransform::commandUrlCleanup( $text, $charsetName );
            return true;
        }
        else if ( $command['command'] == 'url_cleanup_compat' )
        {
            $text = eZCharTransform::commandUrlCleanupCompat( $text, $charsetName );
            return true;
        }
        else if ( $command['command'] == 'identifier_cleanup' )
        {
            $text = strtolower( $text );
            $text = preg_replace( array( "#[^a-z0-9_ ]#",
                                         "/ /",
                                         "/__+/",
                                         "/^_|_$/" ),
                                  array( " ",
                                         "_",
                                         "_",
                                         "" ),
                                  $text );
            return true;
        }
        else if ( $command['command'] == 'search_cleanup' )
        {
            $nonCJKCharsets = $this->nonCJKCharsets();
            if ( !in_array( $charsetName, $nonCJKCharsets ) )
            {
                // 4 Add spaces after chinese / japanese / korean multibyte characters
                $codec = eZTextCodec::instance( false, 'unicode' );

                $unicodeValueArray = $codec->convertString( $text );

                $normalizedTextArray = array();
                $bFlag = false;
                foreach ( array_keys( $unicodeValueArray ) as $valueKey )
                {
                    // Check for word characters that should be broken up for search
                    if ( ( $unicodeValueArray[$valueKey] >= 12289 and
                           $unicodeValueArray[$valueKey] <= 12542 ) or
                         ( $unicodeValueArray[$valueKey] >= 13312 and
                           $unicodeValueArray[$valueKey] <= 40863 ) or
                         ( $unicodeValueArray[$valueKey] >= 44032 and
                           $unicodeValueArray[$valueKey] <= 55203 ) )
                    {
                        if ( $bFlag )
                        {
                            $normalizedTextArray[] = $unicodeValueArray[$valueKey];
                        }
                        $normalizedTextArray[] = 32; // A space
                        $normalizedTextArray[] = $unicodeValueArray[$valueKey];
                        $bFlag = true;
                    }
                    else
                    {
                        if ( $bFlag )
                        {
                            $normalizedTextArray[] = 32; // A space
                        }
                        $normalizedTextArray[] = $unicodeValueArray[$valueKey];
                        $bFlag = false;
                    }
                }

                if ( $bFlag )
                {
                    $normalizedTextArray[ count( $normalizedTextArray ) - 1 ] = 32;
                }

                $revCodec = eZTextCodec::instance( 'unicode', false ); // false means use internal charset
                $text = $revCodec->convertString( $normalizedTextArray );
            }

            // Make sure dots inside words/numbers are kept, the rest is turned into space
            $text = preg_replace( array( "#(\.){2,}#",
                                         "#^\.#",
                                         "#\s\.#",
                                         "#\.\s#",
                                         "#\.$#",
                                         "#([^0-9])%#" ), // Keep only % after a number
                                  array( " ",
                                         " ",
                                         " ",
                                         " ",
                                         " ",
                                         "$1 " ),
                                  $text );
            $ini = eZINI::instance();
            if ( $ini->variable( 'SearchSettings', 'EnableWildcard' ) != 'true' )
            {
                $text = str_replace( "*", " ", $text );
            }
            $charset = eZTextCodec::internalCharset();
            $hasUTF8 = ( $charset == "utf-8" );

            if ( $hasUTF8 )
            {
                $text = preg_replace( "#(\s+)#u", " ", $text );
            }
            else
            {
                $text = preg_replace( "#(\s+)#", " ", $text );
            }

            return true;
        }
        else
        {
            $ini = eZINI::instance( 'transform.ini' );
            $commands = $ini->variable( 'Extensions', 'Commands' );
            if ( isset( $commands[$command['command']] ) )
            {
                list( $path, $className ) = explode( ':', $commands[$command['command']], 2 );
                if ( file_exists( $path ) )
                {
                    include_once( $path );
                    $text = call_user_func_array( array( $className, 'executeCommand' ),
                                                  array( $text, $command['command'], $charsetName ) );
                    return true;
                }
                else
                {
                    eZDebug::writeError( "Could not locate include file '$path' for transformation '" . $command['command'] . "'" );
                }
            }
        }
        return false;
    }

    /*!
     \return An array with charsets that are certain to not contain CJK characters.
    */
    function nonCJKCharsets()
    {
        return array( 'adobe-standard-encoding',
                      'cp437', 'cp737', 'cp775', 'cp850', 'cp852', 'cp855', 'cp857',
                      'cp860', 'cp861', 'cp862', 'cp863', 'cp864', 'cp865', 'cp866',
                      'cp869', 'cp874',
                      'dec-mcs', 'hp-roman8',
                      'iso-8859-1', 'iso-8859-2', 'iso-8859-3', 'iso-8859-4', 'iso-8859-5',
                      'iso-8859-6', 'iso-8859-7', 'iso-8859-8', 'iso-8859-9', 'iso-8859-10',
                      'iso-8859-11', 'iso-8859-13', 'iso-8859-14', 'iso-8859-15',
                      'koi8-r', 'koi8-u', 'macintosh', 'next', 'us-ascii',
                      'windows-1250', 'windows-1251', 'windows-1252', 'windows-1253',
                      'windows-1254', 'windows-1255', 'windows-1256', 'windows-1257',
                      'windows-1258' );
    }

    /// \privatesection
    public $TransformationTables;
    public $TransformationFiles;
    public $ISOUnicodeCodec;
}

?>
