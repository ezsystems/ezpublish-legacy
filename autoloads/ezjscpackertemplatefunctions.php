<?php
//
// Definition of ezjscPackerTemplateFunctions class
//
// Created on: <1-Jul-2008 12:42:08 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ JSCore extension for eZ Publish
// SOFTWARE RELEASE: 1.x
// COPYRIGHT NOTICE: Copyright (C) 2009 eZ Systems AS
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

/**
 * ezjsPacker related template operators
 * For merging and packing javascript or stylesheet files together to reduce size and
 * number of files(as in reduces client connections).
 * 
 * ezscript[_require|_load]( array|string $scripts
 *                                   [, string $type='text/javascript'
 *                                   [, string $language='javascript'
 *                                   [, string $charset='utf-8'
 *                                   [, int $pack_level=2]]]])
 *                                   
 * ezcss[_require|_load]( array|string $css_files
 *                                   [, string $media='all'
 *                                   [, string $type='text/css'
 *                                   [, string $rel='stylesheet'
 *                                   [, string $charset='utf-8'
 *                                   [, int $pack_level=3]]]]])
 *
 * ezscriptfiles( array|string $scripts[, int $pack_level=2[, bool $ignore_loaded=false]] )
 * ezcssfiles( array|string $css_files[, int $pack_level=3[, bool $ignore_loaded=false]] )
 * 
 * These are alternatives to ezscript and ezcss that return array of files to be included
 * instead of generating the (x)html for them.
 * 
 * Genal note:
 * Packing has 4 levels:
 *  0 = off
 *  1 = merge files
 *  2 = 1 + remove whitespace
 *  3 = 2 + remove more whitespace  (jsmin is used for scripts)
 *  !Will be forced to 0 when site.ini[TemplateSettings]DevelopmentMode is enabled.
 *
 * In case of css files, relative image paths will be replaced by absolute paths.
 *
 * You can also use css / js generators to generate content dynamically.
 * This is better explained in ezjscore.ini[Packer_<function>]
 * 
 * Brief (ezscript|ezcss)_require + (ezscript|ezcss)_load:
 * Lets you do on demand loading of javscript and css files instead of loading
 * them on every page using JavaScriptList & CSSFileList witch tends to also
 * load files no matter what design you use.
 * 
 * (ezscript|ezcss)_require : Stores list of required files in persistent_variable hash
 * values (js|css)_files or if not defined (on non content pages) on protected variable
 * ezjscPackerTemplateFunctions::$persistentVariable so they can be loaded later 
 * by (ezscript|ezcss)_load. If already loaded, then executed right away just like
 * calling (ezscript|ezcss) operators.
 * 
 * (ezscript|ezcss)_load : Packs the files you (optionally) pass to it + the files marked
 * to be loaded by (ezscript|ezcss)_require.
 */

//include_once( 'extension/ezjscore/classes/ezjscorepacker.php' );

class ezjscPackerTemplateFunctions
{
    function ezjscPackerTemplateFunctions()
    {
    }

    function operatorList()
    {
        return array( 'ezscript', 'ezscript_require', 'ezscript_load', 'ezscriptfiles', 'ezcss', 'ezcss_require', 'ezcss_load', 'ezcssfiles'  );
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        static $def = null;
        if ( $def === null )
        {
            $def = array( 'ezscript' => array( 'script_array' => array( 'type' => 'array',
                                                  'required' => true,
                                                  'default' => array() ),
                                               'type' => array( 'type' => 'string',
                                                  'required' => false,
                                                  'default' => 'text/javascript' ),
                                               'language' => array( 'type' => 'string',
                                                  'required' => false,
                                                  'default' => 'javascript' ),
                                               'charset' => array( 'type' => 'string',
                                                  'required' => false,
                                                  'default' => 'utf-8' ),
                                               'pack_level' => array( 'type' => 'integer',
                                                  'required' => false,
                                                  'default' => 2 )),
                          'ezscriptfiles' => array( 'script_array' => array( 'type' => 'array',
                                                  'required' => true,
                                                  'default' => array() ),
                                               'pack_level' => array( 'type' => 'integer',
                                                  'required' => false,
                                                  'default' => 2 ),
                                            'ignore_loaded' => array( 'type' => 'bool',
                                                  'required' => false,
                                                  'default' => false )),
                          'ezcss' => array( 'css_array' => array( 'type' => 'array',
                                                  'required' => true,
                                                  'default' => array() ),
                                            'media' => array( 'type' => 'string',
                                                  'required' => false,
                                                  'default' => 'all' ),
                                            'type' => array( 'type' => 'string',
                                                  'required' => false,
                                                  'default' => 'text/css' ),
                                            'rel' => array( 'type' => 'string',
                                                  'required' => false,
                                                  'default' => 'stylesheet' ),
                                            'charset' => array( 'type' => 'string',
                                                  'required' => false,
                                                  'default' => 'utf-8' ),
                                            'pack_level' => array( 'type' => 'integer',
                                                  'required' => false,
                                                  'default' => 3 ) ),
                          'ezcssfiles' => array( 'css_array' => array( 'type' => 'array',
                                                  'required' => true,
                                                  'default' => array() ),
                                            'pack_level' => array( 'type' => 'integer',
                                                  'required' => false,
                                                  'default' => 3 ),
                                            'ignore_loaded' => array( 'type' => 'bool',
                                                  'required' => false,
                                                  'default' => false ) ));

            // Definition for _require and _load is the same as main functons, so copy to keep code size down
            $def['ezscript_require'] = $def['ezscript'];
            $def['ezscript_load'] = $def['ezscript'];
            $def['ezscript_load']['script_array']['required'] = false;

            $def['ezcss_require'] = $def['ezcss'];
            $def['ezcss_load'] = $def['ezcss'];
            $def['ezcss_load']['css_array']['required'] = false;
        }
        return $def;
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        $ret = '';
        switch ( $operatorName )
        {
            case 'ezscript_load':
            {
                if ( !isset( self::$loaded['js_files'] ) )
                {
                    $depend = self::setPersistentArray( 'js_files', $namedParameters['script_array'], $tpl, false, true );
                    $ret = ezjscPacker::buildJavascriptTag( $depend,
                                                         $namedParameters['type'],
                                                         $namedParameters['language'],
                                                         $namedParameters['charset'],
                                                         $namedParameters['pack_level'] );
                    self::$loaded['js_files'] = true;
                    break;
                }// let 'ezscript' handle loaded calls
                else
                {
                    $namedParameters['script_array'] = self::setPersistentArray( 'js_files', $namedParameters['script_array'], $tpl, true, true, true );
                }
            }
            case 'ezscript_require':
            {
                if ( !isset( self::$loaded['js_files'] ) )
                {
                    self::setPersistentArray( 'js_files', $namedParameters['script_array'], $tpl, true );
                    break;
                }// let 'ezscript' handle loaded calls
                else
                {
                    $namedParameters['script_array'] = self::setPersistentArray( 'js_files', $namedParameters['script_array'], $tpl, true, true, true );
                }
            }
            case 'ezscript':
            {
                $ret = ezjscPacker::buildJavascriptTag( $namedParameters['script_array'],
                                                     $namedParameters['type'],
                                                     $namedParameters['language'],
                                                     $namedParameters['charset'],
                                                     $namedParameters['pack_level'] );
            } break;
            case 'ezscriptfiles':
            {
                if ( $namedParameters['ignore_loaded'] )
                {
                    $ret = ezjscPacker::buildStylesheetFiles( $namedParameters['script_array'], $namedParameters['pack_level'] );
                }
                else
                {
                    $diff = self::setPersistentArray( 'js_files', $namedParameters['script_array'], $tpl, true, true, true );
                    $ret = ezjscPacker::buildStylesheetFiles( $diff, $namedParameters['pack_level'] );
                }
            } break;
            case 'ezcss_load':
            {
                if ( !isset( self::$loaded['css_files'] ) )
                {
                    $depend = self::setPersistentArray( 'css_files', $namedParameters['css_array'], $tpl, false, true );
                    $ret = ezjscPacker::buildStylesheetTag( $depend,
                                                         $namedParameters['media'],
                                                         $namedParameters['type'],
                                                         $namedParameters['rel'],
                                                         $namedParameters['charset'],
                                                         $namedParameters['pack_level'] );
                    self::$loaded['css_files'] = true;
                    break;
                }// let 'ezcss' handle loaded calls
                else
                {
                    $namedParameters['css_array'] = self::setPersistentArray( 'css_files', $namedParameters['css_array'], $tpl, true, true, true );
                }
            }
            case 'ezcss_require':
            {
                if ( !isset( self::$loaded['css_files'] ) )
                {
                    self::setPersistentArray( 'css_files', $namedParameters['css_array'], $tpl, true );
                    break;
                }// let 'ezcss' handle loaded calls
                else
                {
                    $namedParameters['css_array'] = self::setPersistentArray( 'css_files', $namedParameters['css_array'], $tpl, true, true, true );
                }
            }
            case 'ezcss':
            {
                $ret = ezjscPacker::buildStylesheetTag( $namedParameters['css_array'],
                                                     $namedParameters['media'],
                                                     $namedParameters['type'],
                                                     $namedParameters['rel'],
                                                     $namedParameters['charset'],
                                                     $namedParameters['pack_level'] );
            } break;
            case 'ezcssfiles':
            {
                if ( $namedParameters['ignore_loaded'] )
                {
                    $ret = ezjscPacker::buildStylesheetFiles( $namedParameters['css_array'], $namedParameters['pack_level'] );
                }
                else
                {
                    $diff = self::setPersistentArray( 'css_files', $namedParameters['css_array'], $tpl, true, true, true );
                    $ret = ezjscPacker::buildStylesheetFiles( $diff, $namedParameters['pack_level'] );
                }
            } break;
        }
        $operatorValue = $ret;
    }

    /**
     * Function for setting values to deal with persistent_variable either from
     * template or internally on {@link self::$persistentVariable}
     *
     * @param string $key Key to store values on
     * @param string|array $value Value(s) to store
     * @param object $tpl Template object to get values from
     * @param bool $append Append or prepend value?
     * @param bool $arrayUnique Make sure array is unique to remove duplicates
     * @param bool $returnArrayDiff Return diff against existing values instead of resulting array
     * $param bool $override Override/Wipe out values or merge?
     * @return array
     * 
     */
    static public function setPersistentArray( $key, $value, $tpl, $append = true, $arrayUnique = false, $returnArrayDiff = false, $override = false )
    {
        $isPageLayout = false;
        $persistentVariable = array();
        if ( $tpl->hasVariable('module_result') )
        {
            $isPageLayout = true;
            $moduleResult = $tpl->variable('module_result');
        }

        if ( isset( $moduleResult['content_info']['persistent_variable'] ) )
        {
            $persistentVariable = $moduleResult['content_info']['persistent_variable'];
        }
        else if ( $tpl->hasVariable('persistent_variable') )
        {
           $persistentVariable = $tpl->variable('persistent_variable');
        }
        else if ( self::$persistentVariable !== null )
        {
            $persistentVariable = self::$persistentVariable;
        }

        if ( !is_array( $persistentVariable ) )
        {
            // Give warning if value is not array as we depend on it
            if ( !$isPageLayout && $persistentVariable )
            {
                eZDebug::writeError( 'persistent_variable was not an array and where cleared, see ezjscore requriments!', __METHOD__ );
            }
            $persistentVariable = array();
        }

        // make a copy in case we need to diff value in the end
        $persistentVariableCopy = $persistentVariable;

        if ( !$override )
        {
            if ( isset( $persistentVariable[ $key ] ) && is_array( $persistentVariable[ $key ] ) )
            {
                if ( is_array( $value ) )
                    $persistentVariable[ $key ] = $append ? array_merge( $persistentVariable[ $key ], $value ) : array_merge( $value, $persistentVariable[ $key ] );
                else if ( $append )
                    $persistentVariable[ $key ][] = $value;
                else
                    $persistentVariable[ $key ] = array_merge( array( $value ), $persistentVariable[ $key ] );
            }
            else
            {
                if ( is_array( $value ) )
                    $persistentVariable[ $key ] = $value;
                else
                    $persistentVariable[ $key ] = array( $value );
            }
        }
        else
        {
            $persistentVariable[ $key ] = $value;
        }

        if ( $arrayUnique && isset( $persistentVariable[$key][1] ) )
        {
            $persistentVariable[$key] = array_unique( $persistentVariable[$key] );
        }

        // set the finnished array in the template
        if ( $isPageLayout )
        {
            if ( isset( $moduleResult['content_info'] ) )
                $moduleResult['content_info']['persistent_variable'] = $persistentVariable;
            else
                $moduleResult['content_info'] = array('persistent_variable' => $persistentVariable);

            $tpl->setVariable('module_result', $moduleResult );
        }
        else
        {
            $tpl->setVariable('persistent_variable', $persistentVariable );
        }
    
        // storing the value internally as well in case this is not a view that supports persistent_variable (ezpagedata will look for it)
        self::$persistentVariable = $persistentVariable;

        if ( $returnArrayDiff && isset( $persistentVariableCopy[ $key ][0] ) )
            return array_diff( $persistentVariable[ $key ], $persistentVariableCopy[ $key ] );

        return $persistentVariable[$key];
    }
    
    // reusable function for getting internal persistent_variable
    static public function getPersistentVariable( $key = null )
    {
        if ( $key !== null )
        {
            if ( isset( self::$persistentVariable[ $key ] ) )
                return self::$persistentVariable[ $key ];
            return null;
        }
        return self::$persistentVariable;
    }

    // Internal version of the $persistent_variable used on view that don't support it
    static protected $persistentVariable = null;
    
    // Internal flag for already loaded types
    static protected $loaded = array();
}

?>