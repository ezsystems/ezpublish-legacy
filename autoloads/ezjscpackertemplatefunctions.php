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

/*
 Template operators for merging and packing css and javascript files.
 Reduces page load time both in terms of reducing connections from clients
 and bandwidth ( if packing is turned on ).
 
 Packing has 4 levels:
 0 = off
 1 = merge files
 2 = 1 + remove whitespace
 3 = 2 + remove more whitespace  (jsmin is used for scripts)
 Will be forced to 0 when site.ini[TemplateSettings]DevelopmentMode is enabled.
 
 In case of css files, relative image paths will be replaced
 by absolute paths.

 You can also use css / js generators to generate content dynamically.
 This is better explained in ezjscore.ini[Packer_<function>]

 ezscriptfiles and ezcssfiles template operators does not return html, just 
 an array of file urls / content (from generators).
 
 Example of use in pagelayout:
    {ezcss( array('core.css', 'pagelayout.css', 'content.css', ezini( 'StylesheetSettings', 'CSSFileList', 'design.ini' ) ))}
    {ezscript( ezini( 'JavaScriptSettings', 'JavaScriptList', 'design.ini' ) )}
*/

//include_once( 'extension/ezjscore/classes/ezjscorepacker.php' );

class ezjscPackerTemplateFunctions
{
    function ezjscPackerTemplateFunctions()
    {
    }

    function operatorList()
    {
        return array( 'ezscript', 'ezscriptfiles', 'ezcss', 'ezcssfiles'  );
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array( 'ezscript' => array( 'script_array' => array( 'type' => 'array',
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
                                              'default' => 2 )),
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
                                              'default' => 3 ) ));
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        $ret = '';
        // Do not pack files if developmentMode is enabled
        $ezIni = eZINI::instance();
        if ( $ezIni->variable('TemplateSettings', 'DevelopmentMode') === 'enabled' )
        {
            $packLevel = 0;
        }
        else
        {
            $packLevel = (int) $namedParameters['pack_level'];
        }
        
        
        switch ( $operatorName )
        {
            case 'ezscript':
            {                    
                $ret = ezjscPacker::buildJavascriptTag( $namedParameters['script_array'],
                                                     $namedParameters['type'],
                                                     $namedParameters['language'],
                                                     $namedParameters['charset'],
                                                     $packLevel );                    
            } break;
            case 'ezcss':
            {                    
                $ret = ezjscPacker::buildStylesheetTag( $namedParameters['css_array'],
                                                     $namedParameters['media'],
                                                     $namedParameters['type'],
                                                     $namedParameters['rel'],
                                                     $namedParameters['charset'],
                                                     $packLevel );                    
            } break;
            case 'ezscriptfiles':
            {                    
                $ret = ezjscPacker::buildJavascriptFiles( $namedParameters['script_array'], $packLevel );                    
            } break;
            case 'ezcssfiles':
            {                    
                $ret = ezjscPacker::buildStylesheetFiles( $namedParameters['css_array'], $packLevel );                    
            } break;
        }
        $operatorValue = $ret;
    }
}

?>