#!/usr/bin/env php
<?php
//
// Created on: <19-Mar-2004 09:51:56 amos>
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

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "eZ publish PHP tag checker\n\n" .
                                                         "Checks for characters before the PHP start tag and after the PHP end tag\n" .
                                                         "and sets exit code based on the result\n" .
                                                         "PATH can either be a file or a directory\n"
                                                         "\n" .
                                                         "ezcheckphptag.php lib" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[no-print]",
                                "[path+]",
                                array( 'no-print' => "Do not print path for bad files"
                                       ) );
$script->initialize();

if ( count( $options['arguments'] ) < 1 )
{
    $script->shutdown( 1, "No files to check" );
}

$print = true;
if ( $options['no-print'] )
    $print = false;

$ini =& eZINI::instance();

$pathList = $options['arguments'];
$error = false;
$badFiles = array();

$startTag = '<?php';
$shortStartTag = '<?';
$endTag = '?>';
$endNewlineTag = "?>\n";

foreach ( $pathList as $path )
{
    $files = array();
    if ( is_dir( $path ) )
    {
        $files = eZDir::recursiveFindRelative( false, $path, '.php' );
    }
    else if ( is_file( $path ) )
    {
        $files[] = $path;
    }
    else
    {
    }
    foreach ( $files as $file )
    {
        $fd = fopen( $file, 'r' );
        if ( $fd )
        {
            $startText = fread( $fd, 5 );
            $hasCorrectStart = false;
            $hasCorrectEnd = false;
            $errorText = array();
            if ( $startText == $startTag )
            {
                $hasCorrectStart = true;
            }
            else if ( substr( $startText, 0, 2 ) == $shortStartTag )
            {
                $errorText[] = "short start tag used";
            }
            else
            {
                $errorText[] = "does not start with PHP tag";
            }
            fseek( $fd, filesize( $file ) - 4, SEEK_SET );
            $endText = fread( $fd, 4 );
            $endText = preg_replace( "/\r\n|\r|\n/", "\n", $endText );
            $endText = substr( $endText, strlen( $endText ) - 3, 3 );
            if ( substr( $endText, 1 ) == $endTag or $endText == $endNewlineTag )
            {
                $hasCorrectEnd = true;
            }
            else
            {
                $errorText[] = "does not end with PHP tag";
            }
            fclose( $fd );
            if ( !$hasCorrectStart or !$hasCorrectEnd )
            {
                if ( $print )
                {
                    $text = $cli->stylize( 'file', $file );
                    if ( count( $errorText ) > 0 )
                        $text .= ": " . implode( ", ", $errorText );
                    $cli->output( $text );
                }
                $badFiles[] = $file;
            }
        }
        else
        {
            if ( $print )
                $cli->output( $cli->stylize( 'file', $file ) . ": could not open file" );
            $error = true;
        }
    }
}

if ( count( $badFiles ) > 0 or $error )
    $script->setExitCode( 1 );

$script->shutdown();

?>
