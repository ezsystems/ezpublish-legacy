<?php
//
// Definition of eZURLOperator class
//
// Created on: <18-Apr-2002 12:15:07 amos>
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
 \class eZURLOperator ezurloperator.php
 \brief Collection of url modifying operators

*/

define( 'EZ_HTTP_OPERATOR_TYPE_POST', 1 );
define( 'EZ_HTTP_OPERATOR_TYPE_GET', 2 );
define( 'EZ_HTTP_OPERATOR_TYPE_SESSION', 3 );

class eZURLOperator
{
    /*!
     Initializes the image operator with the operator name $name.
    */
    function eZURLOperator( $url_name = 'ezurl',
                            $urlroot_name = 'ezroot',
                            $ezsys_name = 'ezsys',
                            $design_name = 'ezdesign',
                            $image_name = 'ezimage',
                            $ext_name = 'exturl',
                            $httpName = 'ezhttp',
                            $iniName = 'ezini' )
    {
        $this->Operators = array( $url_name, $urlroot_name, $ezsys_name, $design_name, $image_name, $ext_name, $httpName, $iniName );
        $this->URLName = $url_name;
        $this->URLRootName = $urlroot_name;
        $this->SysName = $ezsys_name;
        $this->DesignName = $design_name;
        $this->ImageName = $image_name;
        $this->ExtName = $ext_name;
        $this->HTTPName = $httpName;
        $this->ININame=  $iniName;
        $this->Sys =& eZSys::instance();
    }

    function operatorTemplateHints()
    {
        return array( $this->URLName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => true,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'input-as-parameter' => 'always',
                                               'element-transformation-func' => 'urlTransformation'),
                      $this->URLRootName => array( 'input' => true,
                                                   'output' => true,
                                                   'parameters' => true,
                                                   'element-transformation' => true,
                                                   'transform-parameters' => true,
                                                   'input-as-parameter' => 'always',
                                                   'element-transformation-func' => 'urlTransformation'),
                      $this->SysName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => true,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'input-as-parameter' => 'always',
                                               'element-transformation-func' => 'urlTransformation'),
                      $this->DesignName => array( 'input' => true,
                                                  'output' => true,
                                                  'parameters' => true,
                                                  'element-transformation' => true,
                                                  'transform-parameters' => true,
                                                  'input-as-parameter' => 'always',
                                                  'element-transformation-func' => 'urlTransformation'),
                      $this->ImageName => array( 'input' => true,
                                                 'output' => true,
                                                 'parameters' => true,
                                                 'element-transformation' => true,
                                                 'transform-parameters' => true,
                                                 'input-as-parameter' => 'always',
                                                 'element-transformation-func' => 'urlTransformation'),
                      $this->ExtName => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => true,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'input-as-parameter' => 'always',
                                               'element-transformation-func' => 'urlTransformation'),
                      $this->ININame => array( 'input' => true,
                                               'output' => true,
                                               'parameters' => true,
                                               'element-transformation' => true,
                                               'transform-parameters' => true,
                                               'input-as-parameter' => false,
                                               'element-transformation-func' => 'iniTrans')
                      );
    }

    function iniTrans( $operatorName, &$node, &$tpl, &$resourceData,
                                &$element, &$lastElement, &$elementList, &$elementTree, &$parameters )
    {
        if ( count ( $parameters ) < 2 )
            return false;

        if ( eZTemplateNodeTool::isStaticElement( $parameters[0] ) &&
             eZTemplateNodeTool::isStaticElement( $parameters[1] ) )
        {
            $iniGroup = eZTemplateNodeTool::elementStaticValue( $parameters[0] );
            $iniVariable = eZTemplateNodeTool::elementStaticValue( $parameters[1] );

            $iniName = false;
            if ( count( $parameters ) > 2 )
            {
                $iniName = eZTemplateNodeTool::elementStaticValue( $parameters[2] );
            }

            include_once( 'lib/ezutils/classes/ezini.php' );
            if ( $iniName !== false )
                $ini =& eZINI::instance( $iniName );
            else
                $ini =& eZINI::instance();

            $value = '';
            if ( $ini->hasVariable( $iniGroup, $iniVariable ) )
            {
                $value = $ini->variable( $iniGroup, $iniVariable );
            }
            else
            {
                if ( $iniName === false )
                    $iniName = 'site.ini';
                $tpl->error( $operatorName, "No such variable '$iniVariable' in group '$iniGroup' for $iniName" );
            }
            return array( eZTemplateNodeTool::createStringElement( $value ) );
        }
        else
            return false;
    }

    function urlTransformation( $operatorName, &$node, &$tpl, &$resourceData,
                                &$element, &$lastElement, &$elementList, &$elementTree, &$parameters )
    {
        $newElements = array();
        $values = array();
        $paramCount = 0;
        $tmpCount = 0;
        switch( $operatorName )
        {
            case $this->URLName:
            {
                if ( eZTemplateNodeTool::isStaticElement( $parameters[0] ) )
                {
                    $url = eZTemplateNodeTool::elementStaticValue( $parameters[0] );

                    if ( preg_match( "#^[a-zA-Z0-9]+:#", $url ) or
                         substr( $url, 0, 2 ) == '//' or
                         strlen( $url ) == 0 )
                        $url = '/';
                    else if ( $url[0] == '#' )
                    {
                        $url = htmlspecialchars( $url );
                    }
                    else if ( $url[0] != '/' )
                    {
                        $url = '/' . $url;
                    }

                    $url = $this->Sys->indexDir() . $url;
                    $url = preg_replace( "#(//)#", "/", $url );
                    $url = preg_replace( "#(^.*)(/+)$#", "\$1", $url );
                    $url = htmlspecialchars( $url );

                    $url = $this->applyQuotes( $url, $parameters[1] );

                    return array( eZTemplateNodeTool::createStringElement( $url ) );
                }
                $values[] = $parameters[0];
                $values[] = array( eZTemplateNodeTool::createStringElement( $this->Sys->indexDir() ) );
                $code .= 'if ( preg_match( "#^[a-zA-Z0-9]+:#", %1% ) or' . "\n" .
                     '    substr( %1%, 0, 2 ) == \'//\' or' . "\n" .
                     '    strlen( %1% ) == 0 )' . "\n" .
                     '  %1% = \'/\';' . "\n" .
                     'else if ( %1%[0] == \'#\' )' . "\n" .
                     '{' . "\n" .
                     '  %1% = htmlspecialchars( %1% );' . "\n" .
                     '}' . "\n" .
                     'else if ( %1%[0] != \'/\' )' . "\n" .
                     '{' . "\n" .
                     '%1% = \'/\' . %1%;' . "\n" .
                     '};' . "\n";

                $code .= '%1% = %2% . %1%;' . "\n" .
                     '%1% = preg_replace( "#(//)#", "/", %1% );' . "\n" .
                     '%1% = preg_replace( "#(^.*)(/+)$#", "\$1", %1% );' . "\n" .
                     '%1% = htmlspecialchars( %1% );' . "\n" .
                     'if ( %1% == "" )' . "\n" .
                     '  %1% = "/";' . "\n";

                $paramCount += 2;
            } break;

            case $this->URLRootName:
            {
                if ( eZTemplateNodeTool::isStaticElement( $parameters[0] ) )
                {
                    $url = eZTemplateNodeTool::elementStaticValue( $parameters[0] );

                    if ( preg_match( "#^[a-zA-Z0-9]+:#", $url ) or
                         substr( $url, 0, 2 ) == '//' )
                        $url = '/';
                    else if ( strlen( $url ) > 0 and
                              $url[0] != '/' )
                        $url = '/' . $url;

                    $url = $this->Sys->wwwDir() . $url;
                    $url = htmlspecialchars( $url );

                    $url = $this->applyQuotes( $url, $parameters[1] );

                    return array( eZTemplateNodeTool::createStringElement( $url ) );
                }
                else
                {
                    $code .='if ( preg_match( "#^[a-zA-Z0-9]+:#", %1% ) or' . "\n" .
                         'substr( %1%, 0, 2 ) == \'//\' )' . "\n" .
                         '  %1% = \'/\';' . "\n" .
                         'else if ( strlen( %1% ) > 0 and' . "\n" .
                         '  %1%[0] != \'/\' )' . "\n" .
                         '%1% = \'/\' . %1%;' . "\n";
                    $values[] = $parameters[0];
                }

                $values[] = array( eZTemplateNodeTool::createStringElement( $this->Sys->wwwDir() ) );
                $code .= '%1% = %2% . %1%;' . "\n" .
                     '%1% = htmlspecialchars( %1% );' . "\n";
                $paramCount += 2;
                ++$tmpCount;
            } break;

            case $this->SysName:
            {
                if ( eZTemplateNodeTool::isStaticElement( $parameters[1] ) )
                {
                    $sysAttribute = eZTemplateNodeTool::elementStaticValue( $parameters[1] );

                    return array( eZTemplateNodeTool::createStringElement( $this->Sys->attribute( $sysAttribute ) ) );
                }
                return false;
            } break;

            case $this->DesignName:
            {
                if ( eZTemplateNodeTool::isStaticElement( $parameters[0] ) )
                {
                    $path = eZTemplateNodeTool::elementStaticValue( $parameters[0] );

                    $matches = eZTemplateDesignResource::fileMatchingRules( false, $path );

                    $designResource =& eZTemplateDesignResource::instance();
                    $matchKeys = $designResource->keys();
                    $matchedKeys = array();

                    include_once( 'kernel/common/ezoverride.php' );
                    $match = eZOverride::selectFile( $matches, $matchKeys, $matchedKeys, "#^(.+)/(.+)(\.[a-zA-Z0-9]+)$#" );
                    if ( $match === null )
                    {
                        return false;
                    }

                    $path = $match["file"];
                    $path = $this->Sys->wwwDir() . '/' . $path;
                    $path = htmlspecialchars( $path );

                    $path = $this->applyQuotes( $path, $parameters[1] );

                    return array( eZTemplateNodeTool::createStringElement( $path ) );
                }

                $code .= '%tmp1% =& eZTemplateDesignResource::instance();' . "\n" .
                         'include_once( \'kernel/common/ezoverride.php\' );' . "\n" .
                         '%tmp2% = array();' . "\n" .
                         '%tmp1% = eZOverride::selectFile( eZTemplateDesignResource::fileMatchingRules( false, %1% ), %tmp1%->keys(), %tmp2%, "#^(.+)/(.+)(\.[a-zA-Z0-9]+)$#" );' . "\n" .
                         'if ( %tmp1% === null )' . "\n" .
                         '{' . "\n" .
                         '  %tmp1% = array();' . "\n" .
                         '}' . "\n" .
                         '%1% = %tmp1%["file"];' . "\n" .
                         '%1% = %2% . "/" . %1%;' . "\n" .
                         '%1% = htmlspecialchars( %1% );' . "\n";

                $values[] = $parameters[0];
                $values[] = array( eZTemplateNodeTool::createStringElement( $this->Sys->wwwDir() ) );
                $tmpCount += 2;
                ++$paramCount;
            } break;

            case $this->ImageName:
            {
                if ( eZTemplateNodeTool::isStaticElement( $parameters[0] ) )
                {
                    $path = eZTemplateNodeTool::elementStaticValue( $parameters[0] );
                    $skipSlash = eZTemplateNodeTool::elementStaticValue( $parameters[0] );

                    $ini =& eZINI::instance();
                    $std_base = eZTemplateDesignResource::designSetting( 'standard' );
                    $site_base = eZTemplateDesignResource::designSetting( 'site' );
                    $std_file = "design/$std_base/images/$path";
                    $site_file = "design/$site_base/images/$path";
                    $no_slash_prefix = false;
                    if ( $skipSlash == true && strlen( $this->Sys->wwwDir() ) == 0 )
                        $no_slash_prefix = true;

                    if ( file_exists( $site_file ) )
                    {
                        if ( $no_slash_prefix == true )
                            $path = $site_file;
                        else
                            $path = $this->Sys->wwwDir() . "/$site_file";
                        $path = htmlspecialchars( $path );
                    }
                    else
                    {
                        $additionalSiteDesignList =& $ini->variable( "DesignSettings", "AdditionalSiteDesignList" );

                        $imageFound = false;
                        // Check all additional sitedesigns
                        foreach ( $additionalSiteDesignList as $additionalSiteDesign )
                        {
                            if ( file_exists( "design/$additionalSiteDesign/images/$path" ) )
                            {
                                if ( $no_slash_prefix == true )
                                    $path = "design/$additionalSiteDesign/images/$path";
                                else
                                    $path = $this->Sys->wwwDir() . "/design/$additionalSiteDesign/images/$path";
                                $path = htmlspecialchars( $path );
                                $imageFound = true;
                            }
                        }

                        if ( !$imageFound )
                        {
                            if ( file_exists( $std_file ) )
                            {
                                if ( $no_slash_prefix == true )
                                    $path = $std_file;
                                else
                                    $path = $this->Sys->wwwDir() . "/$std_file";
                                $path = htmlspecialchars( $path );
                            }
                            else
                                $tpl->warning( $this->ImageName, "Image '$path' does not exist in any design" );
                        }
                    }
                }
                else
                    return false; //TODO : generate code for dynamic images.
            } break;

            case $this->ExtName:
            {
                if ( eZTemplateNodeTool::isStaticElement( $parameters[0] ) )
                {
                    $origUrl = eZTemplateNodeTool::elementStaticValue( $parameters[0] );

                    include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );
                    $url =& eZURL::urlByMD5( md5( $origUrl ) );
                    if ( $url == false )
                        eZURL::registerURL( $origUrl );
                    else
                        $origUrl = $url;

                    $origUrl = $this->applyQuotes( $origUrl, $parameters[1] );

                    return array( eZTemplateNodeTool::createStringElement( $origUrl ) );
                }

                $code .= 'include_once( \'kernel/classes/datatypes/ezurl/ezurl.php\' );' . "\n" .
                     '%tmp1% =& eZURL::urlByMD5( md5( %1% ) );' . "\n" .
                     'if ( %tmp1% == false )' . "\n" .
                     '  eZURL::registerURL( %1% );' . "\n" .
                     'else' . "\n" .
                     '  %1% = %tmp1%;' . "\n";
                $values[] = $parameters[0];
                ++$tmpCount;
                ++$paramCount;
            } break;

        }

        include_once( 'lib/ezutils/classes/ezhttptool.php' );
        $http =& eZHTTPTool::instance();

        if ( isset( $http->UseFullUrl ) and $http->UseFullUrl )
        {
            ++$tmpCount;
            $code .= 'include_once( \'lib/ezutils/classes/ezhttptool.php\' );' . "\n" .
                 '%tmp' . $tmpCount . '% =& eZHTTPTool::instance();' . "\n" .
                 'if ( isset( %tmp' . $tmpCount . '%->UseFullUrl ) and %tmp' . $tmpCount . '%->UseFullUrl )' . "\n" .
                 '{' . "\n" .
                 ' %1% = %tmp' . $tmpCount . '%->createRedirectUrl( %1%, array( \'pre_url\' => false ) );' . "\n" .
                 '}' . "\n";
        }

        $quote = '"';
        $val = eZTemplateNodeTool::elementStaticValue( $parameters[$paramCount] );
        ++$paramCount;
        if ( $val == 'single' )
            $quote = "'";
        else if ( $val == 'no' )
            $quote = false;

        if ( $quote !== false )
        {
            $values[] = array( eZTemplateNodeTool::createStringElement( $quote ) );
            $code .= '%1% = %' . $paramCount . '% . %1% . %' . $paramCount . '%;' . "\n";
        }

        $code .= '%output% = %1%;' . "\n";

        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values, false, $tmpCount );

        return $newElements;
    }

    /*!
     Apply quotes to static text

     \param static text
     \param quote parameter

     \return text with quotes
    */
    function applyQuotes( $text, &$parameter )
    {
        $quote = "\"";
        if ( $parameter != null )
        {
            $val = eZTemplateNodeTool::elementStaticValue( $parameter );
            if ( $val == 'single' )
                $quote = "'";
            else if ( $val == 'no' )
                $quote = false;
        }

        include_once( 'lib/ezutils/classes/ezhttptool.php' );
        $http =& eZHTTPTool::instance();

        if ( isset( $http->UseFullUrl ) and $http->UseFullUrl )
        {
            $text = $http->createRedirectUrl( $text, array( 'pre_url' => false ) );
        }
        if ( $quote !== false )
            return $quote . $text . $quote;

        return $text;
    }

    /*!
     Returns the operators in this class.
    */
    function &operatorList()
    {
        return $this->Operators;
    }

    function namedParameterList()
    {
        return array( 'quote_val' => array( 'type' => 'string',
                                            'required' => false,
                                            'default' => 'double' ) );
    }

    /*!
     */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        switch ( $operatorName )
        {
            case $this->ININame:
            {
                if ( count( $operatorParameters ) > 0 )
                {
                    $iniGroup = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace );
                    if ( count( $operatorParameters ) > 1 )
                    {
                        $iniVariable = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );
                        $iniName = false;
                        if ( count( $operatorParameters ) > 2 )
                        {
                            $iniName = $tpl->elementValue( $operatorParameters[2], $rootNamespace, $currentNamespace );
                        }
                        include_once( 'lib/ezutils/classes/ezini.php' );
                        if ( $iniName !== false )
                            $ini =& eZINI::instance( $iniName );
                        else
                            $ini =& eZINI::instance();
                        if ( $ini->hasVariable( $iniGroup, $iniVariable ) )
                        {
                            $operatorValue = $ini->variable( $iniGroup, $iniVariable );
                        }
                        else
                        {
                            if ( $iniName === false )
                                $iniName = 'site.ini';
                            $tpl->error( $operatorValue, "No such variable '$iniVariable' in group '$iniGroup' for $iniName" );
                        }
                        return;
                    }
                    else
                        $tpl->error( $operatorName, "Missing variable name parameter" );
                }
                else
                    $tpl->error( $operatorName, "Missing group name parameter" );
            } break;

            case $this->HTTPName:
            {
                include_once( 'lib/ezutils/classes/ezhttptool.php' );
                $http =& eZHTTPTool::instance();
                if ( count( $operatorParameters ) > 0 )
                {
                    $httpType = EZ_HTTP_OPERATOR_TYPE_POST;
                    $httpName = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace );
                    if ( count( $operatorParameters ) > 1 )
                    {
                        $httpTypeName = strtolower( $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace ) );
                        if ( $httpTypeName == 'post' )
                            $httpType = EZ_HTTP_OPERATOR_TYPE_POST;
                        else if ( $httpTypeName == 'get' )
                            $httpType = EZ_HTTP_OPERATOR_TYPE_GET;
                        else if ( $httpTypeName == 'session' )
                            $httpType = EZ_HTTP_OPERATOR_TYPE_SESSION;
                        else
                            $tpl->warning( $operatorName, "Unknown http type '$httpTypeName'" );
                    }
                    switch( $httpType )
                    {
                        case EZ_HTTP_OPERATOR_TYPE_POST:
                        {
                            if ( $http->hasPostVariable( $httpName ) )
                                $operatorValue = $http->postVariable( $httpName );
                            else
                                $tpl->error( $operatorName, "Unknown post variable '$httpName'" );
                        } break;
                        case EZ_HTTP_OPERATOR_TYPE_GET:
                        {
                            if ( $http->hasGetVariable( $httpName ) )
                                $operatorValue = $http->getVariable( $httpName );
                            else
                                $tpl->error( $operatorName, "Unknown get variable '$httpName'" );
                        } break;
                        case EZ_HTTP_OPERATOR_TYPE_SESSION:
                        {
                            if ( $http->hasSessionVariable( $httpName ) )
                                $operatorValue = $http->sessionVariable( $httpName );
                            else
                                $tpl->error( $operatorName, "Unknown session variable '$httpName'" );
                        } break;
                    }
                }
                else
                {
                    $operatorValue = $http;
                }
                return;
            } break;

            case $this->URLName:
            {
                if ( preg_match( "#^[a-zA-Z0-9]+:#", $operatorValue ) or
                     substr( $operatorValue, 0, 2 ) == '//' )
                     break;
                if ( strlen( $operatorValue ) == 0 )
                    $operatorValue = '/';
                else if ( $operatorValue[0] == '#' )
                {
                    $operatorValue = htmlspecialchars( $operatorValue );
                    break;
                }
                else if ( $operatorValue[0] != '/' )
                {
                    $operatorValue = '/' . $operatorValue;
                }
                $operatorValue = $this->Sys->indexDir() . $operatorValue;
                $operatorValue = preg_replace( "#^(//)#", "/", $operatorValue );
                $operatorValue = preg_replace( "#(^.*)(/+)$#", "\$1", $operatorValue );
                $operatorValue = htmlspecialchars( $operatorValue );

                if ( $operatorValue == "" )
                    $operatorValue = "/";
            } break;

            case $this->URLRootName:
            {
                if ( preg_match( "#^[a-zA-Z0-9]+:#", $operatorValue ) or
                     substr( $operatorValue, 0, 2 ) == '//' )
                     break;
                if ( strlen( $operatorValue ) > 0 and
                     $operatorValue[0] != '/' )
                    $operatorValue = '/' . $operatorValue;
                $operatorValue = $this->Sys->wwwDir() . $operatorValue;
                $operatorValue = htmlspecialchars( $operatorValue );
            } break;

            case $this->SysName:
            {
                if ( count( $operatorParameters ) == 0 )
                    $tpl->warning( 'eZURLOperator' . $operatorName, 'Requires attributename' );
                else
                {
                    $sysAttribute = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace );
                    if ( !$this->Sys->hasAttribute( $sysAttribute ) )
                        $tpl->warning( 'eZURLOperator' . $operatorName, "No such attribute '$sysAttribute' for eZSys" );
                    else
                        $operatorValue = $this->Sys->attribute( $sysAttribute );
                }
                return;
            } break;

            case $this->ImageName:
            {
                $ini =& eZINI::instance();
                $std_base = eZTemplateDesignResource::designSetting( 'standard' );
                $site_base = eZTemplateDesignResource::designSetting( 'site' );
                $std_file = "design/$std_base/images/$operatorValue";
                $site_file = "design/$site_base/images/$operatorValue";
                $no_slash_prefix = false;
                if ( count( $operatorParameters ) == 2 )
                {
                    if ( $operatorParameters[1] == true && strlen( $this->Sys->wwwDir() ) == 0 )
                        $no_slash_prefix = true;
                }

                if ( file_exists( $site_file ) )
                {
                    if ( $no_slash_prefix == true )
                        $operatorValue = $site_file;
                    else
                        $operatorValue = $this->Sys->wwwDir() . "/$site_file";
                    $operatorValue = htmlspecialchars( $operatorValue );
                }
                else
                {
                    $additionalSiteDesignList =& $ini->variable( "DesignSettings", "AdditionalSiteDesignList" );

                    $imageFound = false;
                    // Check all additional sitedesigns
                    foreach ( $additionalSiteDesignList as $additionalSiteDesign )
                    {
                        if ( file_exists( "design/$additionalSiteDesign/images/$operatorValue" ) )
                        {
                            if ( $no_slash_prefix == true )
                                $operatorValue = "design/$additionalSiteDesign/images/$operatorValue";
                            else
                                $operatorValue = $this->Sys->wwwDir() . "/design/$additionalSiteDesign/images/$operatorValue";
                            $operatorValue = htmlspecialchars( $operatorValue );
                            $imageFound = true;
                        }
                    }

                    if ( !$imageFound )
                    {
                        if ( file_exists( $std_file ) )
                        {
                            if ( $no_slash_prefix == true )
                                $operatorValue = $std_file;
                            else
                                $operatorValue = $this->Sys->wwwDir() . "/$std_file";
                            $operatorValue = htmlspecialchars( $operatorValue );
                        }
                        else
                            $tpl->warning( $operatorName, "Image '$operatorValue' does not exist in any design" );
                    }
                }
            } break;

            case $this->ExtName:
            {
                include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );
                $urlMD5 = md5( $operatorValue );
                $url =& eZURL::urlByMD5( $urlMD5 );
                if ( $url === false )
                    eZURL::registerURL( $operatorValue );
                else
                    $operatorValue = $url;
            } break;

            case $this->DesignName:
            {
                $path = $operatorValue;
                $matches = eZTemplateDesignResource::fileMatchingRules( false, $path );

                $designResource =& eZTemplateDesignResource::instance();
                $matchKeys = $designResource->keys();
                $matchedKeys = array();

                include_once( 'kernel/common/ezoverride.php' );
                $match = eZOverride::selectFile( $matches, $matchKeys, $matchedKeys, "#^(.+)/(.+)(\.[a-zA-Z0-9]+)$#" );
                if ( $match === null )
                {
                    $tpl->warning( 'eZURLOperator', "Design element $operatorValue does not exist in any design" );
                    return false;
                }

                $file = $match["file"];
                $operatorValue = $this->Sys->wwwDir() . "/$file";
                $operatorValue = htmlspecialchars( $operatorValue );

            } break;
        }
        $quote = "\"";
        $val = $namedParameters['quote_val'];
        if ( $val == 'single' )
            $quote = "'";
        else if ( $val == 'no' )
            $quote = false;

        include_once( 'lib/ezutils/classes/ezhttptool.php' );
        $http =& eZHTTPTool::instance();

        if ( isset( $http->UseFullUrl ) and $http->UseFullUrl )
        {
            $operatorValue = $http->createRedirectUrl( $operatorValue, array( 'pre_url' => false ) );
        }
        if ( $quote !== false )
            $operatorValue = $quote . $operatorValue . $quote;
    }

    var $Operators;
    var $URLName, $URLRootName, $DesignName, $ImageName;
    var $Sys;
};

?>
