<?php
//
// Definition of eZImageShell class
//
// Created on: <28-Feb-2002 17:31:42 amos>
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

/*!
  \class eZImageShell ezimageshell.php
  \ingroup eZImage
  \brief Image conversions delegate using shell commands

  This class should handle most the image shell programs out there.
  It builds a shell commandline using pre and post parameters as well
  as scale and operation rules.

   Filename handling is specified in the constructor and can handle on of four cases.
   - EZ_IMAGE_KEEP_SUFFIX - keeps the original suffix
   - EZ_IMAGE_REPLACE_SUFFIX - replaces the suffix with one fitting for the source/dest
   - EZ_IMAGE_APPEND_SUFFIX - appends a fitting suffix to the filename, keeping the original.
   - EZ_IMAGE_PREPEND_SUFFIX_TAG - prepends the filetype to the filename, for instance png:
   - EZ_IMAGE_NO_SUFFIX - removes the suffix

\code
$img->registerType( 'convert', new eZImageShell( '', 'convert', array(), array(),
                                                 array( eZImageShell::createRule( "-geometry %wx%h>",
                                                                                  "modify/scale" ),
                                                        eZImageShell::createRule( "-colorspace GRAY",
                                                                                  "colorspace/gray" ) ) ) );
\endcode

*/

include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezutils/classes/ezini.php" );

define( "EZ_IMAGE_KEEP_SUFFIX", 1 );
define( "EZ_IMAGE_REPLACE_SUFFIX", 2 );
define( "EZ_IMAGE_APPEND_SUFFIX", 3 );
define( "EZ_IMAGE_PREPEND_SUFFIX_TAG", 4 );
define( "EZ_IMAGE_NO_SUFFIX", 5 );
define( "EZ_IMAGE_PREPEND_AND_REPLACE_SUFFIX_TAG", 6 );

define( "EZ_IMAGE_PRE_PARAM", 1 );
define( "EZ_IMAGE_POST_PARAM", 2 );

class eZImageShell
{
    /*!
     Initializes a shell program, $exec contains the executable command,
     $pre_params and $post_params is an array of parameters which are prepended and appended,
     $rules is an array of rules create rules with createRule(),
     $from_type specifies what happens with the source filename and $to_type
     specifies what happens with the destination filename.
    */
    function eZImageShell( $execPath, $exec, $pre_params, $post_params, $rules,
                           $from_type = EZ_IMAGE_KEEP_SUFFIX, $to_type = EZ_IMAGE_REPLACE_SUFFIX )
    {
        $this->ExecPath = $execPath;
        $this->Exec = $exec;
        $this->PreParams = $pre_params;
        $this->PostParams = $post_params;
        $this->Rules = array();
        foreach ( $rules as $rule )
        {
            $this->Rules[$rule["rule-type"]] = $rule;
        }
        $this->FromType = $from_type;
        $this->ToType = $to_type;
    }

    /*!
     \static
    */
    function isAvailable( $type )
    {
        return true;
    }

    /*!
     Creates a new operation rule and returns it.
     $expr is the text parameter which is added to the commandline,
     $rule_type is a string representing the name of the current rule
     which is used for determing if a converter can handle the wanted
     image operation,
     $type is either EZ_IMAGE_PRE_PARAM or EZ_IMAGE_POST_PARAM.
    */
    function &createRule( $expr, $rule_type, $type = EZ_IMAGE_PRE_PARAM )
    {
        $rule = array();
        $rule["expression"] = $expr;
        $rule["rule-type"] = $rule_type;
        $rule["type"] = $type;
        return $rule;
    }

    /*!
     Converts the filename $from according to $type. See class documentation
     or different types you can use.
    */
    function &convertFileName( $from, $type, $as_pure_file = false, $add_dir = true )
    {
        switch ( $type )
        {
            case EZ_IMAGE_KEEP_SUFFIX:
            {
                $str = $from["original-filename"];
                if ( $add_dir )
                    $str = $from["dir"] . "/" . $str;
            } break;

            case EZ_IMAGE_APPEND_SUFFIX:
            {
                $str = $from["original-filename"] . "." . $from["suffix"];
                if ( $add_dir )
                    $str = $from["dir"] . "/" . $str;
            } break;

            case EZ_IMAGE_PREPEND_SUFFIX_TAG:
            {
                $str = "";
                if ( $add_dir )
                    $str = $from["dir"] . "/";
                $str .= $from["original-filename"];
                if ( !$as_pure_file )
                    $str = $from["suffix"] . ":" . $str;
            } break;

            case EZ_IMAGE_NO_SUFFIX:
            {
                $str = $from["basename"];
                if ( $add_dir )
                    $str = $from["dir"] . "/" . $str;
            } break;

            default:
            case EZ_IMAGE_REPLACE_SUFFIX:
            {
                $str = $from["basename"] . "." . $from["suffix"];
                if ( $add_dir )
                    $str = $from["dir"] . "/" . $str;
            } break;

            case EZ_IMAGE_PREPEND_AND_REPLACE_SUFFIX_TAG:
            {
                $str = "";
                if ( $add_dir )
                    $str = $from["dir"] . "/";
                $str .= $from["basename"] . "." . $from["suffix"];
                if ( !$as_pure_file )
                    $str = $from["suffix"] . ":" . $str;
            } break;
        }
        return $str;
    }

    /*!
     \private
     Creates the commandline string which is required for running the shell program.
    */
    function conversionString( &$from, &$to, $pre, $post, &$to_dir, &$to_file, &$dest_str )
    {
        $str = '';
        if ( $this->ExecPath != '' )
            $str = $this->ExecPath . '/';
        if ( strstr( $this->Exec, $this->ExecPath ) === false ) // if false, Exec does not contain full path
            $str .= $this->Exec;
        else
            $str = $this->Exec;

        // Check if convert string contains spaces
        if ( strstr( $str, " " ) !== false )
            $str = "\"".$str."\"";

        $params = array_merge( $this->PreParams, $pre );
        foreach ( $params as $param )
        {
            $str .= ' '.$param;
        }
        $from_str =& $this->convertFileName( $from, $this->FromType );
        $to_str =& $this->convertFileName( $to, $this->ToType );
        $dest_str = $this->convertFileName( $to, $this->ToType, true );
        $to_file = $this->convertFileName( $to, $this->ToType, false, false );
        $to_dir = $to["dir"];
        $str .= " $from_str $to_str";
        $params = array_merge( $this->PostParams, $post );
        foreach ( $params as $param )
        {
            $str .= ' '.$param;
        }

        return $str;
    }

    /*!
     Runs the conversion on the file and returns the result.
    */
    function convert( &$from, &$to, &$to_dir, &$to_file, &$filters )
    {
        $pre = array();
        $post = array();
        return $this->handle( $from, $to, $to_dir, $to_file, $pre, $post, $filters );
    }

    /*!
     Runs the conversion and scale on the file and returns the result.
    */
    function scale( &$from, &$to, &$to_dir, &$to_file, &$filters, $scale )
    {
        $pre = array();
        $post = array();
        if ( isset( $this->Rules["modify/scale"] ) )
        {
            $rule =& $this->Rules["modify/scale"];
            $rule_str = $this->parseScaleRule( $rule, $scale );
            $this->addParameter( $pre, $post, $rule["type"], $rule_str );
        }
        return $this->handle( $from, $to, $to_dir, $to_file, $pre, $post, $filters );
    }

    /*!
     \private
     Converts and/or scales the image.
    */
    function handle( &$from, &$to, &$to_dir, &$to_file, &$pre, &$post, &$filters )
    {
        if ( is_array( $filters ) )
        {
            foreach ( $filters as $item )
            {
                if ( isset( $this->Rules[$item["rule-type"]] ) )
                {
                    $rule =& $this->Rules[$item["rule-type"]];
                    $str = $this->parseRule( $rule, $item );
                    $this->addParameter( $pre, $post, $rule["type"], $str );
                }
            }
        }
        $str = $this->conversionString( $from, $to, $pre, $post, $to_dir, $to_file, $dest_str );
        $this->run( $str, $dest_str );
        return $str;
    }

    /*!
     Adds the parameter $str to either the pre list $pre or the
     post list $post according to the type $type.
    */
    function addParameter( &$pre, &$post, $type, $str )
    {
        if ( $type == EZ_IMAGE_PRE_PARAM )
            array_push( $pre, $str );
        else
            array_push( $post, $str );
    }

    /*!
     \private
     Returns the "expression" part of the rule $rule.
    */
    function parseRule( &$rule, $param )
    {
        return $rule["expression"];
    }

    /*!
     Parses the scale rule $rule with the width and height from $scale and returns the text.
    */
    function parseScaleRule( $rule, $scale )
    {
//         $str = preg_quote( $rule["expression"] );
        $str = $rule["expression"];
        $str = preg_replace( "/%w/", $scale["width"], $str );
        $str = preg_replace( "/%h/", $scale["height"], $str );
        return $str;
    }

    /*!
     Runs the command $execstr.
    */
    function run( &$execstr, $dest_str )
    {
        eZDebugSetting::writeDebug( 'lib-ezimage-shell', "Executing shell command '$execstr'", 'eZImageShell::run' );

        $err = system( $execstr, $ret_code );
        if ( $ret_code == 0 )
        {
            if ( !file_exists( $dest_str ) )
            {
                eZDebug::writeError( "Unkown destination file: $dest_str", "eZImageShell(" . $this->Exec . ")" );
                return false;
            }
            $ini =& eZINI::instance();
            $perm = $ini->variable( "ImageSettings", "NewImagePermissions" );
            $oldmask = umask( 0 );
            chmod( $dest_str, octdec( $perm ) );
            umask( $oldmask );
            $ret = true;
        }
        else
        {
            eZDebug::writeWarning( "Failed executing: $execstr, Err: $err, Ret: $ret_code", 'eZImageShell::run' );
            $ret = false;
        }
        return $ret;
    }

    /// The path to the executable
    var $ExecPath;
    /// The file to execute for image conversion
    var $Exec;
    /// Array of parameters which are added before the files
    var $PreParams;
    /// Array of parameters which are added after the files
    var $PostParams;
    /// The filename operation to run on the source file.
    var $FromType;
    /// The filename operation to run on the destination file.
    var $ToType;
}

?>
