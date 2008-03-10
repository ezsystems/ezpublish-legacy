<?php
//
// Created on: <5-Aug-2007 00:00:00 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor MCE extension for eZ Publish
// SOFTWARE RELEASE: 1.x
// COPYRIGHT NOTICE: Copyright (C) 2008 eZ Systems AS
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

// Simplifying and encoding content objects / nodes to json
// using the php json extension included in php 5.2 and 
// higher or fallback to php version if not present


class eZAjaxContent
{
    protected static $instance         = null;
    protected static $nativeJsonEncode = false;
    
    // protected __construct, so new instance can only be created with getInstance()
    protected function __construct()
    {
    }

    // protected __clone, prevents cloning of object
    protected function __clone()
    {
    }

    /*
     Static Singleton function to get a instance of this class
    */
    public static function getInstance()
    {
        if ( self::$instance === null )
        {
            self::$instance = new eZAjaxContent();
        }
        return self::$instance;
    }
    
    /*
     Function for encoding content object(s) or node(s) to simplified json objects
     TODO: add xml support
    */
    public static function encode( $obj, $params = array(), $type = 'json' )
    {
        if ( is_array( $obj ) )
        {
            $ret = array();
            foreach ( $obj as $ob )
            {
                $ret[] = self::simplify( $ob, $params );
            }
        }
        else
        {
            $ret = self::simplify( $obj, $params );
        }
        return self::jsonEncode( $ret );
    }
    
    /*
      Function for simplifying a content object or node 
    */
    public static function simplify( $obj, $params = array() )
    {
        if ( !$obj ) return array();
        
        $params = array_merge( array(
                            'dataMap' => array(), // collection of identifiers you want to load, load all with array('all')
                            'fetchPath' => false, // fetch node path
                            'fetchChildrenCount' => false,
                            'dataMapType' => array(), //if you want to filter datamap by type
                            'imagePreGenerateSizes' => array('small') //Pre generated images, loading all can be quite time consuming
        ), $params );

        if ( !isset( $params['imageSizes'] ) )// list of available image sizes
        {
            $imageIni = eZINI::instance( 'image.ini' );
            $params['imageSizes'] = $imageIni->variable( 'AliasSettings', 'AliasList' );
        }

        if ( $params['imageSizes'] === null || !isset( $params['imageSizes'][0] ) )
            $params['imageSizes'] = array();
            
        if (  !isset( $params['imageDataTypes'] ) )
            $params['imageDataTypes'] = array('ezimage');
        
        if ( $obj instanceof eZContentObject)
        {
            $node          = $obj->attribute( 'main_node' );
            $contentObject = $obj;
        }
        elseif ( $obj instanceof eZContentObjectTreeNode ) 
        {
            $node          = $obj;
            $contentObject = $obj->attribute( 'object' );
        }
        else
        {
            return ''; // Other passed objects are not supported
        }

        $ret                     = array();
        $attrtibuteArray         = array();
        $ret['name']             = $contentObject->attribute( 'name' );
        $ret['contentobject_id'] = $ret['id'] = (int) $contentObject->attribute( 'id' );
        $ret['main_node_id']     = (int)$contentObject->attribute( 'main_node_id' );
        $ret['modified']         = $contentObject->attribute( 'modified' );
        $ret['published']        = $contentObject->attribute( 'published' );
        $ret['section_id']       = (int) $contentObject->attribute( 'section_id' );
        $ret['current_language'] = $contentObject->attribute( 'current_language' );
        $ret['owner_id']         = (int) $contentObject->attribute( 'owner_id' );
        $ret['class_id']         = (int) $contentObject->attribute( 'contentclass_id' );
        $ret['class_name']       = $contentObject->attribute( 'class_name' );
        
        $class                   = $contentObject->attribute( 'content_class' );
        $ret['class_identifier'] = $class->attribute( 'identifier' );
        $ret['is_container']     = (int) $class->attribute( 'is_container' );

        if ( $node instanceof eZContentObjectTreeNode )
        {
            $ret['node_id']        = (int) $node->attribute( 'node_id' );
            $ret['parent_node_id'] = (int) $node->attribute( 'parent_node_id' );
            $ret['url_alias']      = $node->attribute( 'url_alias' );
            $ret['depth']          = (int) $node->attribute( 'depth' );
            if ( $params['fetchPath'] )
            {
                $ret['path'] = array();
                foreach ( $node->attribute( 'path' ) as $n )
                {
                    $ret['path'][] = self::simplify( $n );
                }
            }
            $ret['children_count'] = 0;
            if ( $ret['is_container'] && $params['fetchChildrenCount'] )
            {
                $ret['children_count'] = (int) $node->attribute( 'children_count' );
            }
        }

        $ret['image_attributes']   = array();
        
        if ( is_array( $params['dataMap'] ) && is_array(  $params['dataMapType'] ) )
        {
            $dataMap = $contentObject->attribute( 'data_map' );
            foreach( $dataMap as $key => $atr )
            {
                $dataTypeString = $atr->attribute( 'data_type_string' );
                
                if ( !in_array( 'all' ,$params['dataMap'], true )
                   && !in_array( $key ,$params['dataMap'], true )
                   && !in_array( $dataTypeString, $params['dataMapType'], true )
                   ) continue;
                $attrtibuteArray[ $key ]['id']         = $atr->attribute( 'id' );
                $attrtibuteArray[ $key ]['type']       = $dataTypeString;
                $attrtibuteArray[ $key ]['identifier'] = $key;
                $attrtibuteArray[ $key ]['content']    = $atr->toString();

                // images
                if ( in_array( $dataTypeString, $params['imageDataTypes'], true) !== false )
                {
                    $content    = $atr->attribute( 'content' );
                    $imageArray = array();
                    foreach( $params['imageSizes'] as $size )
                    {
                        $imageArray[ $size ] = false;
                        if ( in_array( $size, $params['imagePreGenerateSizes'] )
                            && $content->hasAttribute( $size ) )
                            $imageArray[ $size ] = $content->attribute( $size );
                    }
                    
                    $imageArray['original']             = array( 'url' => $attrtibuteArray[ $key ]['content'] );
                    $attrtibuteArray[ $key ]['content'] = $imageArray;
                    $ret['image_attributes'][]          = $key;
                }
            }
        }
        $ret['data_map'] = $attrtibuteArray;
        return $ret;
    }

    /*
     Wrapper function for encoding to json with native or php version
     depending on what the system supports
    */
    public static function jsonEncode( $obj )
    {
        if ( function_exists( 'json_encode' ) === true )
            return json_encode( $obj );

        if ( self::$instance === null )
        {
            self::getInstance();
        }
        return self::$instance->phpJsonEncode( $obj );    
    }
    
    
    /*
     * @author      Michal Migurski <mike-json@teczno.com>
     * @author      Matt Knapp <mdknapp[at]gmail[dot]com>
     * @author      Brett Stimmerman <brettstimmerman[at]gmail[dot]com>
     * @copyright   2005 Michal Migurski
     * @license     http://www.freebsd.org/copyright/freebsd-license.html
     * @link        http://pear.php.net/pepr/pepr-proposal-show.php?id=198
    */
    protected function phpJsonEncode( $var )
    {       
        switch (gettype($var)) {
            case 'boolean':
                return $var ? 'true' : 'false';
            
            case 'NULL':
                return 'null';
            
            case 'integer':
                return sprintf('%d', $var);
                
            case 'double':
            case 'float':
                return sprintf('%f', $var);
                
            case 'string':
                // STRINGS ARE EXPECTED TO BE IN ASCII OR UTF-8 FORMAT
                $ascii = '';
                $strlen_var = strlen($var);

               /*
                * Iterate over every character in the string,
                * escaping with a slash or encoding to UTF-8 where necessary
                */
                for ($c = 0; $c < $strlen_var; ++$c) {
                    
                    $ord_var_c = ord($var{$c});
                    
                    switch ($ord_var_c) {
                        case 0x08:  $ascii .= '\b';  break;
                        case 0x09:  $ascii .= '\t';  break;
                        case 0x0A:  $ascii .= '\n';  break;
                        case 0x0C:  $ascii .= '\f';  break;
                        case 0x0D:  $ascii .= '\r';  break;

                        case 0x22:
                        case 0x2F:
                        case 0x5C:
                            // double quote, slash, slosh
                            $ascii .= '\\'.$var{$c};
                            break;
                            
                        case (($ord_var_c >= 0x20) && ($ord_var_c <= 0x7F)):
                            // characters U-00000000 - U-0000007F (same as ASCII)
                            $ascii .= $var{$c};
                            break;
                        
                        case (($ord_var_c & 0xE0) == 0xC0):
                            // characters U-00000080 - U-000007FF, mask 110XXXXX
                            // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                            $char = pack('C*', $ord_var_c, ord($var{$c+1}));
                            $c+=1;
                            $utf16 = mb_convert_encoding($char, 'UTF-16', 'UTF-8');
                            $ascii .= sprintf('\u%04s', bin2hex($utf16));
                            break;
    
                        case (($ord_var_c & 0xF0) == 0xE0):
                            // characters U-00000800 - U-0000FFFF, mask 1110XXXX
                            // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                            $char = pack('C*', $ord_var_c,
                                         ord($var{$c+1}),
                                         ord($var{$c+2}));
                            $c+=2;
                            $utf16 = mb_convert_encoding($char, 'UTF-16', 'UTF-8');
                            $ascii .= sprintf('\u%04s', bin2hex($utf16));
                            break;
    
                        case (($ord_var_c & 0xF8) == 0xF0):
                            // characters U-00010000 - U-001FFFFF, mask 11110XXX
                            // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                            $char = pack('C*', $ord_var_c,
                                         ord($var{$c+1}),
                                         ord($var{$c+2}),
                                         ord($var{$c+3}));
                            $c+=3;
                            $utf16 = mb_convert_encoding($char, 'UTF-16', 'UTF-8');
                            $ascii .= sprintf('\u%04s', bin2hex($utf16));
                            break;
    
                        case (($ord_var_c & 0xFC) == 0xF8):
                            // characters U-00200000 - U-03FFFFFF, mask 111110XX
                            // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                            $char = pack('C*', $ord_var_c,
                                         ord($var{$c+1}),
                                         ord($var{$c+2}),
                                         ord($var{$c+3}),
                                         ord($var{$c+4}));
                            $c+=4;
                            $utf16 = mb_convert_encoding($char, 'UTF-16', 'UTF-8');
                            $ascii .= sprintf('\u%04s', bin2hex($utf16));
                            break;
    
                        case (($ord_var_c & 0xFE) == 0xFC):
                            // characters U-04000000 - U-7FFFFFFF, mask 1111110X
                            // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                            $char = pack('C*', $ord_var_c,
                                         ord($var{$c+1}),
                                         ord($var{$c+2}),
                                         ord($var{$c+3}),
                                         ord($var{$c+4}),
                                         ord($var{$c+5}));
                            $c+=5;
                            $utf16 = mb_convert_encoding($char, 'UTF-16', 'UTF-8');
                            $ascii .= sprintf('\u%04s', bin2hex($utf16));
                            break;
                    }
                }
                
                return '"'.$ascii.'"';
                
            case 'array':
               /*
                * As per JSON spec if any array key is not an integer
                * we must treat the the whole array as an object. We
                * also try to catch a sparsely populated associative
                * array with numeric keys here because some JS engines
                * will create an array with empty indexes up to
                * max_index which can cause memory issues and because
                * the keys, which may be relevant, will be remapped
                * otherwise.
                * 
                * As per the ECMA and JSON specification an object may
                * have any string as a property. Unfortunately due to
                * a hole in the ECMA specification if the key is a
                * ECMA reserved word or starts with a digit the
                * parameter is only accessible using ECMAScript's
                * bracket notation.
                */
                
                // treat as a JSON object  
                if (is_array($var) && count($var) && (array_keys($var) !== range(0, sizeof($var) - 1))) {
                    return sprintf('{%s}', join(',', array_map(array($this, 'phpJsonEncodeNameValue'),
                                                               array_keys($var),
                                                               array_values($var))));
                }

                // treat it like a regular array
                return sprintf('[%s]', join(',', array_map(array($this, 'phpJsonEncode'), $var)));
                
            case 'object':
                $vars = get_object_vars($var);
                return sprintf('{%s}', join(',', array_map(array($this, 'phpJsonEncodeNameValue'),
                                                           array_keys($vars),
                                                           array_values($vars))));                    

            default:
                return '';
        }
    }
    
   /** function name_value
    * array-walking function for use in generating JSON-formatted name-value pairs
    *
    * @param    string  $name   name of key to use
    * @param    mixed   $value  reference to an array element to be encoded
    *
    * @return   string  JSON-formatted name-value pair, like '"name":value'
    * @access   private
    */
    protected function phpJsonEncodeNameValue($name, $value)
    {
        return (sprintf("%s:%s", $this->phpJsonEncode(strval($name)), $this->phpJsonEncode($value)));
    }
}

?>