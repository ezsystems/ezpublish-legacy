<?php
//
// Definition of eZImageManager class
//
// Created on: <01-Mar-2002 14:23:49 amos>
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

/*! \defgroup eZImage Image conversion and scaling */

/*!
  \class eZImageManager ezimagemanager.php
  \ingroup eZImage
  \brief Manages image operations using delegates to do the work

  The manager allows for transparent conversion of one image format
  to another. The conversion may be done in one step if the required
  conversion type is available or it may build a tree of conversion
  rules which is needed to reach the desired end format.

  It's also possible to run operations on images. It's up to each conversion
  rule to report whether or not the operation is supported, the manager will
  then distribute the operations on the available rules which can handle them.
  Examples of operations are scaling and grayscale.

  The scale operation is special and is known to the manager directly while
  the other operations must be recognized by the converter.

  In determing what image rules to be used the manager must first know
  which output types are allowed, this is set with setOutputTypes().
  It takes an array of mimetypes which are allowed.

  The manager must then be fed conversion rules, these tell which conversion
  type is used for converting from the source mimetype to the destination
  mimetype. The rules are set with setRules() which accepts an array of
  rules and a default rule as paremeter. The default rule is used when no
  other mimetype match is found.
  To create a rule you should use the createRule() function, it takes the source
  and destination mimetype as well as the conversion type name. Optionally
  it can specified whether the rule can scale or run operations.

  The last thing that needs to be done is to specify the mimetypes. The manager
  uses mimetypes internally to know what type of image it's working on.
  To go from a filename to a mimetype a set of matches must be setup. The matches
  are created with createMIMEType() which takes the mimetype, regex filename match
  and suffix as parameter. The mimetypes are then registered with setMIMETypes().

  See <a href="http://www.iana.org/">www.iana.org</a> for information on MIME types.

  Now the manager is ready and you can convert images with convert().

  Example:
\code
$img =& eZImageManager::instance();
$img->registerType( "convert", new eZImageShell( '', "convert", array(), array(),
                                                 array( eZImageShell::createRule( "-geometry %wx%h>", // Scale rule
                                                                                  "modify/scale" ),
                                                        eZImageShell::createRule( "-colorspace GRAY", // Grayscale rule
                                                                                  "colorspace/gray" ) ) ) ); // Register shell program convert
$img->registerType( "gd", new eZImageGD() ); // Register PHP converter GD

$img->setOutputTypes( array( "image/jpeg",
                             "image/png" ) ); // We only want jpeg and png, gif is not recommended due to licencing issues.
$rules = array( $img->createRule( "image/jpeg", "image/jpeg", "GD", true, false ), // Required for scaling jpeg images
                $img->createRule( "image/gif", "image/png", "convert", true, false ) ); // Convert GIF to png
$img->setRules( $rules, $img->createRule( "*", "image/png", "convert", true, false ) ); // Convert all other images to PNG with convert

$mime_rules = array( $img->createMIMEType( "image/jpeg", "\.jpe?g$", "jpg" ),
                     $img->createMIMEType( "image/png", "\.png$", "png" ),
                     $img->createMIMEType( "image/gif", "\.gif$", "gif" ) );
$img->setMIMETypes( $mime_rules ); // Register mimetypes

$img1 = $img->convert( "image1.gif", "cache/" ); // Convert GIF and places it in cache dir
$img1 = $img->convert( "image1.png", "cache/", // Scale PNG image and place in cache dir
                       array( "width" => 200, "height" => 200 ), // Scale parameter
                       array( array( "rule-type" => "colorspace/gray" ) ) ); // Gray scale conversion
 \endcode


*/

class eZImageManager
{
    /*!
     Initializes the manager by registering a application/octet-stream mimetype
     which is applied for all unknown files.
    */
    function eZImageManager()
    {
        $this->MIMEOctet =& $this->createMIMEType( "application/octet-stream", "^.+$", "" );
    }

    /*!
     Sets which output types are allowed, this is an array of mimetype names.
    */
    function setOutputTypes( $mimes )
    {
        $this->OutputMIME = $mimes;
        $this->OutputMIMEMap = array();
        foreach ( $mimes as $mime )
        {
            $this->OutputMIMEMap[$mime] = true;
        }
    }

    /*!
     Sets the conversion rules to $rule and the default rule to $defrule.
    */
    function setRules( $rules, $defrule )
    {
        $this->Rules = $rules;
        $this->DefaultRule = $defrule;
        $this->RuleMap = array();
        foreach( $rules as $rule )
        {
            $this->RuleMap[$rule["from"]] = $rule;
        }
    }

    /*!
     Registers a new conversion type which can handle conversion and image operations.
     The name $name represents the conversion name which is used in the conversion rules.
     $type is the conversion object.
    */
    function registerType( $name, &$type )
    {
        $this->Types[$name] =& $type;
    }

    /*!
     Not used for now, may be deleted?
    */
    function ruleFor( $from )
    {
        if ( isset( $this->RuleMap[$from] ) )
            return $this->RuleMap[$from];
        else
            return $this->DefaultRule;
    }

    /*!
     Returns true if the mimetype $type is accepted as an output type.
    */
    function isDisplayType( $type )
    {
        return isset( $this->OutputMIMEMap[$type] );
    }

    /*!
     Creates a MIME type structure.
     $mime is the mimetype name,
     $match is the regex file match,
     $suffix is the filename suffix which is append to the converted filename.
    */
    function &createMIMEType( $mime, $match, $suffix )
    {
        $type = array();
        $type["mime-type"] =& $mime;
        $type["match"] =& $match;
        $type["suffix"] =& $suffix;
        return $type;
    }

    /*!
     Register the mimetypes $types.
    */
    function setMIMETypes( $types )
    {
        $this->MIMETypes = $types;
        $this->MIMEMap = array();
        reset( $this->MIMETypes );
        while ( ( $key = key( $this->MIMETypes ) ) !== null )
        {
            $type =& $this->MIMETypes[$key];
            $this->MIMEMap[$type["mime-type"]] =& $type;
            next( $this->MIMETypes );
        }
    }

    /*!
     Returns the mime info for the mime type $mimeType.
    */
    function mimeInfo( $mimeType )
    {
        if ( isset( $this->MIMEMap[$mimeType] ) )
            return $this->MIMEMap[$mimeType];
        return false;
    }

    /*!
     Returns the mimetype for the file $file.
     If $as_obj is true the whole mimetype structure is returned.
    */
    function mimeTypeFor( $file, $as_obj = false )
    {
        foreach ( $this->MIMETypes as $mime )
        {
            $reg = "/" . $mime["match"] . "/i";
            if ( preg_match( $reg, $file ) )
            {
                if ( $as_obj )
                    return $mime;
                else
                    return $mime["mime-type"];
            }
        }
        $mimeHandler = new eZMimeType();
        $mimeType = $mimeHandler->mimeTypeFor( false, $file );
        if ( $mimeType )
        {
            $suffix = false;
            if ( preg_match( "/\.([^.]+)$/", $file, $matches ) )
                $suffix = $matches[1];
            return array( 'mime-type' => $mimeType,
                          'match' => "\.(.+?)$",
                          'suffix' => $suffix );
        }
        if ( $as_obj )
            return $this->MIMEOctet;
        else
            return $this->MIMEOctet["mime-type"];
    }

    /*!
     Returns the conversion rules which is required for transforming the
     mimetype $from to an output format. If scale is supplied the scaling
     is taking into account when compressing rules.
    */
    function &convertRules( $from, $scale = false )
    {
        $rule = null;
        $rules = array();
        $cur = $from;
        $i = 0;
        $used_types = array();
        if ( $this->isDisplayType( $cur ) and $scale !== false )
        {
            if ( isset( $this->RuleMap[$cur] ) )
                $rule = $this->RuleMap[$cur];
            else
            {
                $rule = $this->DefaultRule;
                $rule["from"] = $cur;
            }
            $rules[] = $rule;
            $used_types[$cur] = true;
            $cur = $rule["to"];
        }
        else
        {
            while ( !$this->isDisplayType( $cur ) )
            {
                if ( isset( $used_types[$cur] ) )
                {
                    return false;
                }
                if ( isset( $this->RuleMap[$cur] ) )
                    $rule = $this->RuleMap[$cur];
                else
                {
                    $rule = $this->DefaultRule;
                    $rule["from"] = $cur;
                }
                $rules[] = $rule;
                $used_types[$cur] = true;
                $cur = $rule["to"];
            }
        }
        $this->compressRules( $rules, $scale );
        return $rules;
    }

    /*!
     Adds the scale rule $scale to the first rule which can perform scaling.
     $rules is usually the rules returned from convertRules().
    */
    function addScaleRule( &$rules, $scale )
    {
        for ( $i = 0; $i < count( $rules ); ++$i )
        {
            $rule =& $rules[$i];
            if ( $rule["canscale"] )
            {
                $rule["scale"] =& $scale;
                break;
            }
        }
    }

    /*!
     Adds the filter rules $filter to the first rule which can perform filtering.
     $rules is usually the rules returned from convertRules().
    */
    function addFilterRule( &$rules, $filter )
    {
        for ( $i = 0; $i < count( $rules ); ++$i )
        {
            $rule =& $rules[$i];
            if ( $rule["canfilter"] )
            {
                $rule["filter"] =& $filter;
                break;
            }
        }
    }

    /*!
     Converst the image file $file to an output format and places it in
     $dest. Scaling is handled with $scale and filters with $filters.
     If $mime is false then the mimetype i fetched from the $file.
    */
    function convert( $file, $dest, $scale = false, $filters = false,
                      $mime = false )
    {
        if ( $mime === false )
            $mime = $this->mimeTypeFor( $file, true );
        $rules =& $this->convertRules( $mime["mime-type"], $scale );
        if ( $scale !== false )
            $this->addScaleRule( $rules, $scale );
        if ( is_array( $filters ) )
            $this->addFilterRule( $rules, true );
        $suffix = $mime["suffix"];
        $dirs = "";
        if ( preg_match( "#(.+/)?(.+)$#", $file, $matches ) )
        {
            $dirs = $matches[1];
            $file = $matches[2];
        }
        $base = $file;
        if ( preg_match( "/(.+)" . $mime["match"] . "/i", $file, $matches ) )
        {
            $base = $matches[1];
        }
        if ( is_dir( $dest ) )
        {
            $dest_dirs = $dest;
            $dest_file = $file;
            $dest_base = $base;
        }
        else
        {
            $dest_dirs = "";
            $dest_file = $dest;
            if ( preg_match( "#(.+/)?(.+)$#", $dest, $matches ) )
            {
                $dest_dirs = $matches[1];
                $dest_file = $matches[2];
            }
            $dest_base = $dest_file;
            if ( preg_match( "/(.+)" . $mime["match"] . "/i", $dest_file, $matches ) )
            {
                $dest_base = $matches[1];
            }
        }
        $from_array = array();
        $to_array = array();
        $from_array["original-filename"] = $file;
        $from_array["dir"] = $dirs;
        $from_array["basename"] = $base;
        $from_array["suffix"] = $suffix;
        $to_array["original-filename"] = $dest_file;
        $to_array["dir"] = $dest_dirs;
        $to_array["basename"] = $dest_base;
        $to_array["suffix"] = $suffix;
        $out_file = $file;
        for ( $i = 0; $i < count( $rules ); ++$i )
        {
            $rule =& $rules[$i];
            $type =& $rule["type"];
            $mime_type =& $this->MIMEMap[$rule["to"]];
            $to_array["suffix"] = $mime_type["suffix"];
            $from_array["mime-type"] = $rule["from"];
            $to_array["mime-type"] = $rule["to"];
            if ( isset( $this->Types[$type] ) )
            {
                $type_obj =& $this->Types[$type];
//                 $str = $type_obj->conversionString( $from_array, $to_array, $to_file );
                $scale_rule = $rule["scale"];
                $filter_rule = $rule["filter"];
                unset( $filter_array );
                $filter_array = false;
                if ( $filter_rule )
                    $filter_array =& $filters;
                if ( $scale_rule !== false )
                    $str = $type_obj->scale( $from_array, $to_array, $to_dir, $to_file,
                                             $filter_array, $scale_rule );
                else
                    $str = $type_obj->convert( $from_array, $to_array, $to_dir, $to_file,
                                               $filter_array );
                $to_array["dir"] = $to_dir;
                $to_array["original-filename"] = $to_file;
                $out_file = $to_file;
                $rule["params"] = $str;
            }
            $from_array["suffix"] = $to_array["suffix"];
            $from_array["basename"] = $to_array["basename"];
            $from_array["original-filename"] = $to_array["original-filename"];
            $from_array["dir"] = $to_array["dir"];
        }

        // Write log message to storage.log
        include_once( 'lib/ezutils/classes/ezlog.php' );
        list( $mimeType, $storedFileName ) = split(":", $to_file );
        eZLog::writeStorageLog( $storedFileName, $dest_dirs );

        return $from_array["dir"] . "/" . $out_file;
    }

    /*!
     \private
     Compresses the rules $rules so that a minimum number of conversion is required.
     The scale $rule is taken into account.
    */
    function compressRules( &$rules, $scale )
    {
        $new_rules = array();
        $last_type = "";
        $last_rule = null;
        for ( $i = 0; $i < count( $rules ); ++$i )
        {
            $rule =& $rules[$i];
            $use_rule = true;
            if ( $last_type == $rule["type"] )
            {
                if ( $scale and isset( $last_rule ) )
                {
                    if ( !$last_rule["canscale"] or !$rule["canscale"] )
                        $use_rule = true;
                    else
                        $use_rule = false;
                }
                else
                    $use_rule = false;
            }
            else
            {
                $use_rule = true;
            }
            if ( $use_rule )
            {
                $new_rules[] =& $rule;
                unset( $last_rule );
                $last_rule =& $rule;
                $last_type = $rule["type"];
            }
            else if ( isset( $last_rule ) )
                $last_rule["to"] = $rule["to"];
        }
        $rules = $new_rules;
    }

    /*!
     Creates a new conversion rule and returns it.
     $from the source mimetype,
     $to the destination mimetype,
     $type the image conversion type must be registered,
     $scale true if the rule can scale the image,
     $filter true if the rule can perform filters.
    */
    function &createRule( $from, $to, $type, $scale, $filter )
    {
        $rule = array();
        $rule["from"] =& $from;
        $rule["to"] =& $to;
        $rule["type"] =& $type;
        $rule["canscale"] =& $scale;
        $rule["canfilter"] =& $filter;
        $rule["scale"] = false;
        $rule["filter"] = false;
        $rule["params"] = "";
        return $rule;
    }

    /*!
     Returns the only instance of the image manager.
    */
    function &instance()
    {
        $instance =& $GLOBALS["eZImageManager"];
        if ( get_class( $instance ) != "ezimagemanager" )
        {
            $instance = new eZImageManager();
        }
        return $instance;
    }

    var $OutputMIME;
    var $OutputMIMEMap;
    var $Rules;
    var $DefaultRule;
    var $RuleMap;
    var $MIMETypes;
    var $Types = array();
}

?>
