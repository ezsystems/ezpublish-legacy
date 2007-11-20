<?php
//
// Definition of eZClassTemplate class
//
// Created on: <18-Nov-2004 13:03:44 jb>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*! \file ezclasstemplate.php
*/

/*!
  \class eZClassTemplate ezclasstemplate.php
  \brief Replaces or generates blocks of code according to a template file

*/

class eZClassTemplate
{
    /*!
     Constructor
    */
    function eZClassTemplate()
    {
        $this->Templates = array();
        $this->Templates['can-instantiate-class-list'] = array( 'filepath' => 'class_templates/classcreatelist.ctpl' );
    }

    /*!
      Applies template block in the file \a $filePath and writes back the new
      code to the same file.

      \return \c true if it file was successfully made.

      \note It will create a backup file of the original
    */
    function apply( $filePath )
    {
        if ( !file_exists( $filePath ) )
        {
            eZDebug::writeError( "File $filePath does not exists",
                                 'eZClassTemplate::apply' );
            return false;
        }

        $text = file_get_contents( $filePath );
        $tempFile = dirname( $filePath ) . '#' . basename( $filePath ) . '#';
        $fd = fopen( $tempFile, 'wb' );
        if ( !$fd )
        {
            eZDebug::writeError( "Failed to open temporary file $tempFile",
                                 'eZClassTemplate::apply' );
            return false;
        }

        $createTag = 'code-template::create-block:';
        $createTagLen = strlen( $createTag );

        $error = false;

        $ok = true;
        $offset = 0;
        $len = strlen( $text );
        while ( $offset < $len )
        {
            $createPos = strpos( $text, $createTag, $offset );
            if ( $createPos !== false )
            {
                $endPos = strpos( $text, "\n", $createPos + $createTagLen );
                if ( $endPos === false )
                {
                    $createText = substr( $text, $createPos + $createTagLen );
                    $end = $len;
                }
                else
                {
                    $start = $createPos + $createTagLen;
                    $createText = substr( $text, $start, $endPos - $start );
                    $end =  $endPos + 1;
                }

                // Figure out how much the comments should be indented
                // This just makes the code seem more natural
                $indentText = '';
                $subText = substr( $text, $offset, $createPos - $offset );
                $startOfLine = strrpos( $subText, "\n" );
                if ( $startOfLine !== false )
                {
                    if ( preg_match( '#^([ \t]+)#', substr( $subText, $startOfLine + 1 ), $matches ) )
                    {
                        $indentText = $matches[1];
                    }
                }
                unset( $subText );

                // Figure out template name and parameters
                $createText = trim( $createText );
                $elements = explode( ',', $createText );
                if ( count( $elements ) < 1 )
                {
                    eZDebug::writeError( "No template name found in file $filePath at offset $offset",
                                         'eZClassTemplate::apply' );
                    $offset = $end;
                    $error = true;
                    continue;
                }

                $templateName = trim( $elements[0] );

                $templateFile = $this->templateFile( $templateName );
                if ( $templateFile === false )
                {
                    eZDebug::writeError( "No template file for template $templateName used in file $filePath at offset $offset",
                                         'eZClassTemplate::apply' );
                    $offset = $end;
                    $error = true;
                    continue;
                }

                if ( !file_exists( $templateFile ) )
                {
                    eZDebug::writeError( "Template file $templateFile for template $templateName does not exist",
                                         'eZClassTemplate::apply' );
                    $offset = $end;
                    $error = true;
                    continue;
                }

                $parameters = array_splice( $elements, 1 );
                foreach ( $parameters as $key => $parameter )
                {
                    $parameters[$key] = trim( $parameter );
                }

                // Load the template file and split it into the blocks
                // available blocks in the file
                $templateText = file_get_contents( $templateFile );

                $tagSplit = '#(<(?:START|END):code-template::(?:[a-zA-Z]+[a-zA-Z0-9_-]*)>)#';
                $tagRegexp = '#<(START|END):code-template::([a-zA-Z]+[a-zA-Z0-9_-]*)>#';

                $split = preg_split( $tagSplit, $templateText, -1, PREG_SPLIT_DELIM_CAPTURE );

                $currentBlocks = array();
                $currentTag = false;
                for ( $i = 0; $i < count( $split ); ++$i )
                {
                    $part = $split[$i];
                    if ( ( $i % 2 ) == 1 )
                    {
                        // The tag element
                        if ( $currentTag === false )
                        {
                            preg_match( $tagRegexp, trim( $part ), $matches );
                            $currentTag = $matches[2];
                            if ( $matches[1] == 'END' )
                            {
                                eZDebug::writeError( "Tag $currentTag was finished before it was started, skipping it",
                                                     'eZClassTemplate::apply' );
                                $currentTag = false;
                                $error = true;
                            }
                            else
                            {
                                if ( count( $currentBlocks ) > 0 )
                                    $blocks[] = array( 'blocks' => $currentBlocks );
                                $currentBlocks = array();
                            }
                        }
                        else
                        {
                            preg_match( $tagRegexp, trim( $part ), $matches );
                            $tag = $matches[2];
                            if ( $matches[1] == 'END' )
                            {
                                if ( $tag == $currentTag )
                                {
                                    if ( count( $currentBlocks ) > 0 )
                                        $blocks[] = array( 'tag' => $currentTag,
                                                           'blocks' => $currentBlocks );
                                    $currentTag = false;
                                    $currentBlocks = array();
                                }
                                else
                                {
                                    eZDebug::writeError( "End tag $tag does not match start tag $currentTag, skipping it",
                                                         'eZClassTemplate::apply' );
                                    $error = true;
                                }
                            }
                            else
                            {
                                eZDebug::writeError( "Start tag $tag found while $currentTag is active, skipping it",
                                                     'eZClassTemplate::apply' );
                                $error = true;
                            }
                        }
                    }
                    else
                    {
                        // Plain text
                        $currentBlocks[] = $part;
                    }
                }
                if ( $currentTag === false )
                {
                    if ( count( $currentBlocks ) > 0 )
                        $blocks[] = array( 'blocks' => $currentBlocks );
                }
                else
                {
                    if ( count( $currentBlocks ) > 0 )
                        $blocks[] = array( 'tag' => $currentTag,
                                           'blocks' => $currentBlocks );
                }

                // Build new code with blocks to include
                $resultText = '';
                foreach ( $blocks as $block )
                {
                    if ( isset( $block['tag'] ) )
                    {
                        if ( in_array( $block['tag'], $parameters ) )
                        {
                            $resultText .= implode( '', $block['blocks'] );
                        }
                    }
                    else
                    {
                        $resultText .= implode( '', $block['blocks'] );
                    }
                }

                // Remove any end-of-line whitespaces unless keep-whitespace is used
                if ( !in_array( 'keep-whitespace', $parameters ) )
                    $resultText = preg_replace( '#[ \t]+$#m', '', $resultText );

                // Output text before the template-block
                fwrite( $fd, substr( $text, $offset, $createPos - $offset ) );
                fwrite( $fd, substr( $text, $createPos, $end - $createPos ) );

                $offset = $end;

                // Remove any existing auto-generated code
                $autogenRegexp = '#^[ \t]*// code-template::auto-generated:START.+[ \t]*// code-template::auto-generated:END\n#ms';
                $postText = substr( $text, $offset );
                $postText = preg_replace( $autogenRegexp, '', $postText );
                $text = substr( $text, 0, $offset ) . $postText;
                unset( $postText );

                // Output the template code with markers
                fwrite( $fd, ( "$indentText// code-template::auto-generated:START $templateName\n" .
                               "$indentText// This code is automatically generated from $templateFile\n" .
                               "$indentText// DO NOT EDIT THIS CODE DIRECTLY, CHANGE THE TEMPLATE FILE INSTEAD\n" .
                               "\n" ) );

                fwrite( $fd, $resultText );
                fwrite( $fd, ( "\n$indentText// This code is automatically generated from $templateFile\n" .
                               "$indentText// code-template::auto-generated:END\n" ) );

            }
            else
            {
                fwrite( $fd, substr( $text, $offset ) );
                break;
            }
        }

        fclose( $fd );
        if ( !$error )
        {
            $backupFile = $filePath . eZSys::backupFilename();
            // Make a backup and make the temporary file the real one
            if ( file_exists( $backupFile ) )
                unlink( $backupFile );
            rename( $filePath, $backupFile );
            rename( $tempFile, $filePath );
            return true;
        }
        unlink( $tempFile );
        return false;
    }

    /*!
     \return The name of the template file based on the name \a $templateName
             or \c false if no file is defined for the name.
    */
    function templateFile( $templateName )
    {
        if ( isset( $this->Templates[$templateName] ) )
        {
            return $this->Templates[$templateName]['filepath'];
        }
        return false;
    }

    /// \privatesection
    var $Templates;
}

?>
