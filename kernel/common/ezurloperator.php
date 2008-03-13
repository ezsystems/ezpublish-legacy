<?php
//
// Definition of eZURLOperator class
//
// Created on: <18-Apr-2002 12:15:07 amos>
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


/*!
 \class eZURLOperator ezurloperator.php
 \brief Collection of url modifying operators

*/

class eZURLOperator
{
    const HTTP_OPERATOR_TYPE_POST = 1;
    const HTTP_OPERATOR_TYPE_GET = 2;
    const HTTP_OPERATOR_TYPE_SESSION = 3;

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
                            $iniName = 'ezini',
                            $iniNameHasVariable = 'ezini_hasvariable',
                            $httpNameHasVariable = 'ezhttp_hasvariable' )
    {
        $this->Operators = array( $url_name, $urlroot_name, $ezsys_name, $design_name, $image_name, $ext_name, $httpName, $iniName, $iniNameHasVariable, $httpNameHasVariable );
        $this->URLName = $url_name;
        $this->URLRootName = $urlroot_name;
        $this->SysName = $ezsys_name;
        $this->DesignName = $design_name;
        $this->ImageName = $image_name;
        $this->ExtName = $ext_name;
        $this->HTTPName = $httpName;
        $this->ININame = $iniName;
        $this->ININameHasVariable = $iniNameHasVariable;
        $this->HTTPNameHasVariable = $httpNameHasVariable;
        $this->Sys = eZSys::instance();
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
                                               'input-as-parameter' => true,
                                               'element-transformation-func' => 'iniTrans'),
                      $this->ININameHasVariable => array( 'input' => true,
                                                          'output' => true,
                                                          'parameters' => true,
                                                          'element-transformation' => true,
                                                          'transform-parameters' => true,
                                                          'input-as-parameter' => true,
                                                          'element-transformation-func' => 'iniTrans' )
                      );
    }

    function iniTrans( $operatorName, &$node, $tpl, &$resourceData,
                       $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        if ( count ( $parameters ) < 2 )
            return false;

        if ( eZTemplateNodeTool::isStaticElement( $parameters[0] ) &&
             eZTemplateNodeTool::isStaticElement( $parameters[1] ) )
        {
            $iniGroup = eZTemplateNodeTool::elementStaticValue( $parameters[0] );
            $iniVariable = eZTemplateNodeTool::elementStaticValue( $parameters[1] );

            $iniName = isset( $parameters[2] ) ? eZTemplateNodeTool::elementStaticValue( $parameters[2] ) : false;
            $iniPath = isset( $parameters[3] ) ? eZTemplateNodeTool::elementStaticValue( $parameters[3] ) : false;

            // If we should check for existence of variable.
            // You can use like:
            //     ezini( <BlockName>, <SettingName>, <FileName>, <IniPath>, <Dynamic fetch: if 'true()' value of variable will be fetched dynamically>
            //                                                               <Should We Check for existence only: 'hasVariable' or true()> )
            //     ezini_hasvariable( <BlockName>, <SettingName>, <FileName>, <IniPath>... )
            if ( $operatorName == $this->ININameHasVariable )
            {
                $checkExistence = true;
            }
            else
            {
                $checkExistence = isset( $parameters[5] )
                                  ? ( eZTemplateNodeTool::elementStaticValue( $parameters[5] ) === true or
                                      eZTemplateNodeTool::elementStaticValue( $parameters[5] ) == 'hasVariable' ) ? true : false
                                  : false;
            }

            if ( count( $parameters ) > 4 )
            {
                $inCache = eZTemplateNodeTool::elementStaticValue( $parameters[4] );
                // Check if we should put implementaion of parsing ini variable to compiled php file
                if ( $inCache === true )
                {
                    $values = array();
                    $values[] = $parameters[0];
                    $values[] = $parameters[1];

                    $code = "//include_once( 'lib/ezutils/classes/ezini.php' );\n";

                    if ( $iniPath !== false )
                    {
                        $values[] = $parameters[2];
                        $values[] = $parameters[3];
                        $code .= '%tmp1% = eZINI::instance( %3%, %4%, null, null, null, true );' . "\n";
                    }
                    elseif ( $iniName !== false )
                    {
                        $values[] = $parameters[2];
                        $code .= '%tmp1% = eZINI::instance( %3% );' . "\n";
                    }
                    else
                        $code .= '%tmp1% = eZINI::instance();' . "\n";

                    $checkExist = $checkExistence ? 'true' : 'false';

                    $code .= 'if ( %tmp1%->hasVariable( %1%, %2% ) )' . "\n" .
                        '    %output% = ' . "!$checkExist" . ' ? %tmp1%->variable( %1%, %2% ) : true;' . "\n" .
                        "else\n" .
                        "    %output% = $checkExist ? false : '';\n";


                    return array( eZTemplateNodeTool::createCodePieceElement( $code, $values, false, 1 ) );
                }
            }
            //include_once( 'lib/ezutils/classes/ezini.php' );

            if ( $iniPath !== false )
                $ini = eZINI::instance( $iniName, $iniPath, null, null, null, true );
            elseif ( $iniName !== false )
                $ini = eZINI::instance( $iniName );
            else
                $ini = eZINI::instance();

            $value = '';
            if ( $ini->hasVariable( $iniGroup, $iniVariable ) )
            {
                $value = !$checkExistence ? $ini->variable( $iniGroup, $iniVariable ) : true;
            }
            else
            {
                // If we should check for existence then we return only FALSE
                if ( $checkExistence )
                {
                    $value = false;
                }
                else
                {
                    if ( $iniName === false )
                        $iniName = 'site.ini';
                    $tpl->error( $operatorName, "No such variable '$iniVariable' in group '$iniGroup' for $iniName" );
                }
            }
            return array( eZTemplateNodeTool::createStringElement( $value ) );
        }
        else
            return false;
    }

    function urlTransformation( $operatorName, &$node, $tpl, &$resourceData,
                                $element, $lastElement, $elementList, $elementTree, &$parameters )
    {
        $ini = eZINI::instance();
        $shareTemplates = $ini->hasVariable( 'TemplateSettings', 'ShareCompiledTemplates' ) ?
                            $ini->variable( 'TemplateSettings', 'ShareCompiledTemplates' ) == 'enabled' :
                            false;

        $useTmp = false;

        $newElements = array();
        $values = array();
        $paramCount = 0;
        $tmpCount = 0;
        switch( $operatorName )
        {
            case $this->URLName:
            {
                if ( !$shareTemplates && eZTemplateNodeTool::isStaticElement( $parameters[0] ) )
                {
                    $url = eZTemplateNodeTool::elementStaticValue( $parameters[0] );

                    $serverURL = isset( $parameters[2] ) ? eZTemplateNodeTool::elementStaticValue( $parameters[2] ) : 'relative';

                    //include_once( 'lib/ezutils/classes/ezuri.php' );
                    eZURI::transformURI( $url, false, $serverURL );

                    $url = $this->applyQuotes( $url, $parameters[1] );
                    return array( eZTemplateNodeTool::createStringElement( $url ) );
                }
                else if ( $shareTemplates && eZTemplateNodeTool::isStaticElement( $parameters[0] ) )
                {
                    $url = eZTemplateNodeTool::elementStaticValue( $parameters[0] );

                    $values[] = array( eZTemplateNodeTool::createStringElement( $url ) );
                    $values[] = isset( $parameters[2] ) ? $parameters[2] : array( eZTemplateNodeTool::createStringElement( 'relative' ) );

                    $code = <<<CODEPIECE

%tmp1% = %1%;
eZURI::transformURI( %tmp1%, false, %2% );

CODEPIECE;
                    $useTmp = true;
                    ++$tmpCount;

                }
                else
                {
                    $values[] = $parameters[0];
                    $values[] = isset( $parameters[2] ) ? $parameters[2] : array( eZTemplateNodeTool::createStringElement( 'relative' ) );

                    $code = <<<CODEPIECE

//include_once( 'lib/ezutils/classes/ezuri.php' );
eZURI::transformURI( %1%, false, %2% );

CODEPIECE;
                }

                ++$paramCount;
            } break;

            case $this->URLRootName:
            {
                if ( !$shareTemplates && eZTemplateNodeTool::isStaticElement( $parameters[0] ) )
                {
                    $url = eZTemplateNodeTool::elementStaticValue( $parameters[0] );

                    if ( preg_match( "#^[a-zA-Z0-9]+:#", $url ) or
                         substr( $url, 0, 2 ) == '//' )
                        $url = '/';
                    else if ( strlen( $url ) > 0 and
                              $url[0] != '/' )
                        $url = '/' . $url;

                    $serverURL = isset( $parameters[2] ) ? eZTemplateNodeTool::elementStaticValue( $parameters[2] ) : 'relative';

                    // Same as "ezurl" without "index.php" and the siteaccess name in the returned address.
                    //include_once( 'lib/ezutils/classes/ezuri.php' );
                    eZURI::transformURI( $url, true, $serverURL );

                    $url = $this->applyQuotes( $url, $parameters[1] );
                    return array( eZTemplateNodeTool::createStringElement( $url ) );
                }
                else if ( $shareTemplates && eZTemplateNodeTool::isStaticElement( $parameters[0] ) )
                {
                    $url = eZTemplateNodeTool::elementStaticValue( $parameters[0] );

                    $values[] = array( eZTemplateNodeTool::createStringElement( $url ) );
                    $values[] = isset( $parameters[2] ) ? $parameters[2] : array( eZTemplateNodeTool::createStringElement( 'relative' ) );

                    $code = '%tmp1% = %1%;';
                    $code .= 'if ( preg_match( "#^[a-zA-Z0-9]+:#", %tmp1% ) or' . "\n" .
                             'substr( %tmp1%, 0, 2 ) == \'//\' )' . "\n" .
                             '  %tmp1% = \'/\';' . "\n" .
                             'else if ( strlen( %tmp1% ) > 0 and' . "\n" .
                             '  %tmp1%[0] != \'/\' )' . "\n" .
                             '%tmp1% = \'/\' . %tmp1%;' . "\n";

                    $code .= '//include_once( \'lib/ezutils/classes/ezuri.php\' );' . "\n" .
                             'eZURI::transformURI( %tmp1%, true, %2% );' . "\n";

                    $useTmp = true;
                    ++$tmpCount;
                }
                else
                {
                    $values[] = $parameters[0];
                    $values[] = isset( $parameters[2] ) ? $parameters[2] : array( eZTemplateNodeTool::createStringElement( 'relative' ) );

                    $code = 'if ( preg_match( "#^[a-zA-Z0-9]+:#", %1% ) or' . "\n" .
                            'substr( %1%, 0, 2 ) == \'//\' )' . "\n" .
                            '  %1% = \'/\';' . "\n" .
                            'else if ( strlen( %1% ) > 0 and' . "\n" .
                            '  %1%[0] != \'/\' )' . "\n" .
                            '%1% = \'/\' . %1%;' . "\n";
                    $code .= '//include_once( \'lib/ezutils/classes/ezuri.php\' );' . "\n" .
                             'eZURI::transformURI( %1%, true, %2% );' . "\n";

                }

                ++$paramCount;
            } break;

            case $this->SysName:
            {
                if ( !$shareTemplates && eZTemplateNodeTool::isStaticElement( $parameters[1] ) )
                {
                    $sysAttribute = eZTemplateNodeTool::elementStaticValue( $parameters[1] );

                    return array( eZTemplateNodeTool::createStringElement( $this->Sys->attribute( $sysAttribute ) ) );
                }
                return false;
            } break;

            case $this->DesignName:
            {
                if ( !$shareTemplates && eZTemplateNodeTool::isStaticElement( $parameters[0] ) )
                {
                    $path = eZTemplateNodeTool::elementStaticValue( $parameters[0] );

                    $path = $this->eZDesign( $tpl, $path, $operatorName );
                    $path = $this->applyQuotes( $path, $parameters[1] );

                    return array( eZTemplateNodeTool::createStringElement( $path ) );
                }
                else if ( $shareTemplates && eZTemplateNodeTool::isStaticElement( $parameters[0] ) )
                {
                    $path = eZTemplateNodeTool::elementStaticValue( $parameters[0] );

                    $values[] = array( eZTemplateNodeTool::createStringElement( $path ) );
                    $code = ( '%tmp1% = %1%;' . "\n" . '%tmp1% = eZURLOperator::eZDesign( $tpl, %tmp1%, "' . $operatorName . '" );' . "\n" );

                    $useTmp = true;
                    ++$tmpCount;
                }
                else
                {
                    $values[] = $parameters[0];
                    $code = ( '%1% = eZURLOperator::eZDesign( $tpl, %1%, "' . $operatorName . '" );' . "\n" );
                }

                ++$paramCount;
            } break;

            case $this->ImageName:
            {
                $skipSlash = count( $parameters ) > 2 ? eZTemplateNodeTool::elementStaticValue( $parameters[2] ) == true : false;

                if ( !$shareTemplates && eZTemplateNodeTool::isStaticElement( $parameters[0] ) )
                {
                    $path = eZTemplateNodeTool::elementStaticValue( $parameters[0] );

                    $path = eZURLOperator::eZImage( $tpl, $path, $operatorName, $skipSlash );
                    $path = $this->applyQuotes( $path, $parameters[1] );

                    return array( eZTemplateNodeTool::createStringElement( $path ) );
                }
                else if ( $shareTemplates && eZTemplateNodeTool::isStaticElement( $parameters[0] ) )
                {
                    $path = eZTemplateNodeTool::elementStaticValue( $parameters[0] );

                    $values[] = array( eZTemplateNodeTool::createStringElement( $path ) );
                    $values[] = array( eZTemplateNodeTool::createBooleanElement( $skipSlash ) );

                    $code = ( '%tmp1% = %1%;' . "\n" . '%tmp1% = eZURLOperator::eZImage( $tpl, %tmp1%, "' . $operatorName . '", %2% );' . "\n" );

                    $useTmp = true;
                    ++$tmpCount;
                }
                else
                {
                    $values[] = $parameters[0];
                    $values[] = array( eZTemplateNodeTool::createBooleanElement( $skipSlash ) );

                    $code = ( '%1% = eZURLOperator::eZImage( $tpl, %1%, "' . $operatorName . '", %2% );' . "\n" );
                }

                ++$paramCount;
            } break;

            case $this->ExtName:
            {
                if ( !$shareTemplates && eZTemplateNodeTool::isStaticElement( $parameters[0] ) )
                {
                    $origUrl = eZTemplateNodeTool::elementStaticValue( $parameters[0] );

                    //include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );
                    $url = eZURL::urlByMD5( md5( $origUrl ) );
                    if ( $url == false )
                        eZURL::registerURL( $origUrl );
                    else
                        $origUrl = $url;

                    $origUrl = $this->applyQuotes( $origUrl, $parameters[1] );

                    return array( eZTemplateNodeTool::createStringElement( $origUrl ) );
                }
                else if ( $shareTemplates && eZTemplateNodeTool::isStaticElement( $parameters[0] ) )
                {
                    $origUrl = eZTemplateNodeTool::elementStaticValue( $parameters[0] );

                    $values[] = array( eZTemplateNodeTool::createStringElement( $origUrl ) );

                    $code .= '//include_once( \'kernel/classes/datatypes/ezurl/ezurl.php\' );' . "\n" .
                         '%tmp1% = %1%; ' . "\n" .
                         '%tmp2% = eZURL::urlByMD5( md5( %tmp1% ) );' . "\n" .
                         'if ( %tmp2% == false )' . "\n" .
                         '  eZURL::registerURL( %tmp1% );' . "\n" .
                         'else' . "\n" .
                         '  %tmp1% = %tmp2%;' . "\n";

                    ++$tmpCount;
                    $useTmp = true;
                }
                else
                {
                    $values[] = $parameters[0];

                    $code .= '//include_once( \'kernel/classes/datatypes/ezurl/ezurl.php\' );' . "\n" .
                         '%tmp1% = eZURL::urlByMD5( md5( %1% ) );' . "\n" .
                         'if ( %tmp1% == false )' . "\n" .
                         '  eZURL::registerURL( %1% );' . "\n" .
                         'else' . "\n" .
                         '  %1% = %tmp1%;' . "\n";
                }

                ++$tmpCount;
                ++$paramCount;
            } break;

        }

        //include_once( 'lib/ezutils/classes/ezhttptool.php' );
        $http = eZHTTPTool::instance();

        if ( isset( $http->UseFullUrl ) and $http->UseFullUrl )
        {
            ++$tmpCount;
            $code .= '//include_once( \'lib/ezutils/classes/ezhttptool.php\' );' . "\n" .
                 '%tmp' . $tmpCount . '% = eZHTTPTool::instance();' . "\n" .
                 'if ( isset( %tmp' . $tmpCount . '%->UseFullUrl ) and %tmp' . $tmpCount . '%->UseFullUrl ' . "\n" .
                 '                                                 and strncasecmp( %1%, \'/\' , 1 ) === 0 ) // do not prepend the site path if it\'s not a http url%'. "\n" .
                 '{' . "\n" .
                 ' %1% = %tmp' . $tmpCount . '%->createRedirectUrl( %1%, array( \'pre_url\' => false ) );' . "\n" .
                 '}' . "\n";
        }

        if ( count( $parameters ) > $paramCount )
        {
            if ( eZTemplateNodeTool::isStaticElement( $parameters[$paramCount] ) )
            {
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
                    if ( $useTmp )
                    {
                        $code .= '%tmp1% = %' . count( $values ) . '% . %tmp1% . %' . count( $values ) . '%;' . "\n";
                    }
                    else
                    {
                        $code .= '%1% = %' . count( $values ) . '% . %1% . %' . count( $values ) . '%;' . "\n";
                    }
                }
            }
            else
            {
                $values[] = $parameters[$paramCount];
                if ( $useTmp )
                {
                    $code .= 'switch (%' . count( $values ) . '%)'          . "\n" .
                             '{'                                            . "\n" .
                             '    case \'single\':'                         . "\n" .
                             '         %tmp1% = \'\\\'\' . %tmp1% . \'\\\'\' ;'  . "\n" .
                             '         break;'                              . "\n" .
                             '    case \'no\':'                             . "\n" .
                             '         break;'                              . "\n" .
                             '     default:'                                . "\n" .
                             '         %tmp1 = \'"\' . %tmp1% . \'"\' ;'     . "\n" .
                             '}'  . "\n";
                }
                else
                {
                    $code .= 'switch (%' . count( $values ) . '%)'          . "\n" .
                             '{'                                            . "\n" .
                             '    case \'single\':'                         . "\n" .
                             '         %1% = \'\\\'\' . %1% . \'\\\'\' ;'   . "\n" .
                             '         break;'                              . "\n" .
                             '    case \'no\':'                             . "\n" .
                             '         break;'                              . "\n" .
                             '     default:'                                . "\n" .
                             '         %1% = \'"\' . %1% . \'"\' ;'         . "\n" .
                             '}'  . "\n";
                }
            }
        }
        else
        {
            $quote = '"';
            $values[] = array( eZTemplateNodeTool::createStringElement( $quote ) );
            if ( $useTmp )
            {
                $code .= '%tmp1% = %' . count( $values ) . '% . %tmp1% . %' . count( $values ) . '%;' . "\n";
            }
            else
            {
                $code .= '%1% = %' . count( $values ) . '% . %1% . %' . count( $values ) . '%;' . "\n";
            }
        }

        if ( $useTmp )
        {
            $code .= '%output% = %tmp1%;' . "\n";
        }
        else
        {
            $code .= '%output% = %1%;' . "\n";
        }

        $newElements[] = eZTemplateNodeTool::createCodePieceElement( $code, $values, false, $tmpCount );

        return $newElements;
    }

    /*!
     Apply quotes to static text

     \param static text
     \param quote parameter
     \param if set to true, return only quote value

     \return text with quotes
    */
    function applyQuotes( $text, &$parameter, $onlyQuote = false )
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

        if ( $onlyQuote )
        {
            return $quote;
        }

        //include_once( 'lib/ezutils/classes/ezhttptool.php' );
        $http = eZHTTPTool::instance();

        if ( isset( $http->UseFullUrl ) and $http->UseFullUrl
                                        and strncasecmp( $text, '/' , 1 ) === 0 ) // do not prepend the site path if it's not a http url
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
    function operatorList()
    {
        return $this->Operators;
    }

    function namedParameterList()
    {
        return array( 'quote_val' => array( 'type' => 'string',
                                            'required' => false,
                                            'default' => 'double' ),
                      'server_url' => array( 'type' => 'string',
                                             'required' => false,
                                             'default' => 'relative' ) );
    }

    /*!
     */
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        switch ( $operatorName )
        {
            case $this->ININameHasVariable:
            case $this->ININame:
            {
                if ( count( $operatorParameters ) > 0 )
                {
                    $iniGroup = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace );
                    if ( count( $operatorParameters ) == 1 )
                    {
                        $tpl->error( $operatorName, "Missing variable name parameter" );
                        return;
                    }
                    $iniVariable = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );
                    $iniName = isset( $operatorParameters[2] ) ? $tpl->elementValue( $operatorParameters[2], $rootNamespace, $currentNamespace ) : false;
                    $iniPath = isset( $operatorParameters[3] ) ? $tpl->elementValue( $operatorParameters[3], $rootNamespace, $currentNamespace ) : false;

                    // If we should check for existence of variable.
                    // You can use like:
                    //     ezini( <BlockName>, <SettingName>, <FileName>, <IniPath>, _use under template compiling mode_ , <Should We Check for existence: 'hasVariable' or true()> )
                    //     ezini_hasvariable( <BlockName>, <SettingName>, <FileName>, <IniPath>... )
                    if ( $operatorName == $this->ININameHasVariable )
                    {
                        $checkExistence = true;
                    }
                    else
                    {
                        $checkExistence = isset( $operatorParameters[5] )
                                          ? ( $tpl->elementValue( $operatorParameters[5], $rootNamespace, $currentNamespace ) === true or
                                              $tpl->elementValue( $operatorParameters[5], $rootNamespace, $currentNamespace ) == 'hasVariable' ) ? true : false
                                          : false;
                    }
                    //include_once( 'lib/ezutils/classes/ezini.php' );

                    if ( $iniPath !== false )
                        $ini = eZINI::instance( $iniName, $iniPath, null, null, null, true );
                    elseif ( $iniName !== false )
                        $ini = eZINI::instance( $iniName );
                    else
                        $ini = eZINI::instance();

                    if ( $ini->hasVariable( $iniGroup, $iniVariable ) )
                    {
                        $operatorValue = !$checkExistence ? $ini->variable( $iniGroup, $iniVariable ) : true;
                    }
                    else
                    {
                        if ( $checkExistence )
                        {
                            $operatorValue = false;
                            return;
                        }
                        if ( $iniPath !== false )
                        {
                            // Return empty string instead of displaying error when using 'path' parameter
                            // and DirectAccess mode for ezini.
                            $operatorValue = '';
                        }
                        else
                        {
                            if ( $iniName === false )
                                $iniName = 'site.ini';
                            $tpl->error( $operatorName, "!!!No such variable '$iniVariable' in group '$iniGroup' for $iniName" );
                        }
                    }
                    return;
                }
                else
                    $tpl->error( $operatorName, "Missing group name parameter" );
            } break;

            case $this->HTTPNameHasVariable:
            case $this->HTTPName:
            {
                //include_once( 'lib/ezutils/classes/ezhttptool.php' );
                $http = eZHTTPTool::instance();
                if ( count( $operatorParameters ) > 0 )
                {
                    $httpType = eZURLOperator::HTTP_OPERATOR_TYPE_POST;
                    $httpName = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace );
                    if ( count( $operatorParameters ) > 1 )
                    {
                        $httpTypeName = strtolower( $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace ) );
                        if ( $httpTypeName == 'post' )
                            $httpType = eZURLOperator::HTTP_OPERATOR_TYPE_POST;
                        else if ( $httpTypeName == 'get' )
                            $httpType = eZURLOperator::HTTP_OPERATOR_TYPE_GET;
                        else if ( $httpTypeName == 'session' )
                            $httpType = eZURLOperator::HTTP_OPERATOR_TYPE_SESSION;
                        else
                            $tpl->warning( $operatorName, "Unknown http type '$httpTypeName'" );
                    }

                    // If we should check for existence of http variable
                    // You can use like:
                    //     ezhttp( <Variable>, <Method: post, get, session>, <Should We Check for existence: 'hasVariable' or true()> )
                    //     ezhttp_hasvariable( <Variable>, <Method> )
                    if ( $operatorName == $this->HTTPNameHasVariable )
                    {
                        $checkExistence = true;
                    }
                    else
                    {
                        $checkExistence = isset( $operatorParameters[2] )
                                          ? ( $tpl->elementValue( $operatorParameters[2], $rootNamespace, $currentNamespace ) === true or
                                              $tpl->elementValue( $operatorParameters[2], $rootNamespace, $currentNamespace ) == 'hasVariable' ) ? true : false
                                          : false;
                    }
                    switch( $httpType )
                    {
                        case eZURLOperator::HTTP_OPERATOR_TYPE_POST:
                        {
                            if ( $http->hasPostVariable( $httpName ) )
                                $operatorValue = !$checkExistence ? $http->postVariable( $httpName ) : true;
                            else
                            {
                                // If only check for existence - return false
                                if ( $checkExistence )
                                {
                                    $operatorValue = false;
                                    return;
                                }
                                $tpl->error( $operatorName, "Unknown post variable '$httpName'" );
                            }
                        } break;
                        case eZURLOperator::HTTP_OPERATOR_TYPE_GET:
                        {
                            if ( $http->hasGetVariable( $httpName ) )
                                $operatorValue = !$checkExistence ? $http->getVariable( $httpName ) : true;
                            else
                            {
                                if ( $checkExistence )
                                {
                                    $operatorValue = false;
                                    return;
                                }
                                $tpl->error( $operatorName, "Unknown get variable '$httpName'" );
                            }
                        } break;
                        case eZURLOperator::HTTP_OPERATOR_TYPE_SESSION:
                        {
                            if ( $http->hasSessionVariable( $httpName ) )
                                $operatorValue = !$checkExistence ? $http->sessionVariable( $httpName ) : true;
                            else
                            {
                                if ( $checkExistence )
                                {
                                    $operatorValue = false;
                                    return;
                                }
                                $tpl->error( $operatorName, "Unknown session variable '$httpName'" );
                            }
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
                //include_once( 'lib/ezutils/classes/ezuri.php' );
                eZURI::transformURI( $operatorValue, false, $namedParameters['server_url'] );

            } break;

            case $this->URLRootName:
            {
                if ( preg_match( "#^[a-zA-Z0-9]+:#", $operatorValue ) or
                     substr( $operatorValue, 0, 2 ) == '//' )
                     break;
                if ( strlen( $operatorValue ) > 0 and
                     $operatorValue[0] != '/' )
                    $operatorValue = '/' . $operatorValue;

                // Same as "ezurl" without "index.php" and the siteaccess name in the returned address.
                //include_once( 'lib/ezutils/classes/ezuri.php' );
                eZURI::transformURI( $operatorValue, true, $namedParameters['server_url'] );

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
                if ( count( $operatorParameters ) == 2 &&
                     $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace ) == true &&
                     strlen( $this->Sys->wwwDir() ) == 0 )
                {
                    $skipSlash = true;
                }
                else
                {
                    $skipSlash = false;
                }

                $operatorValue = $this->eZImage( $tpl, $operatorValue, $operatorName, $skipSlash );
            } break;

            case $this->ExtName:
            {
                //include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );
                $urlMD5 = md5( $operatorValue );
                $url = eZURL::urlByMD5( $urlMD5 );
                if ( $url === false )
                    eZURL::registerURL( $operatorValue );
                else
                    $operatorValue = $url;
            } break;

            case $this->DesignName:
            {
                $operatorValue = $this->eZDesign( $tpl, $operatorValue, $operatorName );
            } break;
        }
        $quote = "\"";
        $val = $namedParameters['quote_val'];
        if ( $val == 'single' )
            $quote = "'";
        else if ( $val == 'no' )
            $quote = false;

        //include_once( 'lib/ezutils/classes/ezhttptool.php' );
        $http = eZHTTPTool::instance();

        if ( isset( $http->UseFullUrl ) and $http->UseFullUrl
                                        and strncasecmp( $operatorValue, '/' , 1 ) === 0 ) // do not prepend the site path if it's not a http url
        {
            $operatorValue = $http->createRedirectUrl( $operatorValue, array( 'pre_url' => false ) );
        }
        if ( $quote !== false )
            $operatorValue = $quote . $operatorValue . $quote;
    }

    /*!

    */
    static function eZDesign( $tpl, $operatorValue, $operatorName )
    {
        $sys = eZSys::instance();

        $bases = eZTemplateDesignResource::allDesignBases();
        $triedFiles = array();
        $fileInfo = eZTemplateDesignResource::fileMatch( $bases, false, $operatorValue, $triedFiles);

        if ( !$fileInfo )
        {
            $tpl->warning( $operatorName, "Design element $operatorValue does not exist in any design" );
            $tpl->warning( $operatorName, "Tried files: " . implode( ', ', $triedFiles ) );
            $siteDesign = eZTemplateDesignResource::designSetting( 'site' );
            $filePath = "design/$siteDesign/$operatorValue";
        }
        else
        {
            $filePath = $fileInfo['path'];
        }

        $operatorValue = $sys->wwwDir() . '/' . $filePath;
        $operatorValue = htmlspecialchars( $operatorValue );

        return $operatorValue;
    }

    /*!

    */
    static function eZImage( $tpl, $operatorValue, $operatorName, $skipSlash = false )
    {
        $sys = eZSys::instance();
        if ( $skipSlash && strlen( $sys->wwwDir() ) != 0 )
        {
            $skipSlash = false;
        }

        $bases = eZTemplateDesignResource::allDesignBases();
        $triedFiles = array();
        $fileInfo = eZTemplateDesignResource::fileMatch( $bases, 'images', $operatorValue, $triedFiles );

        if ( !$fileInfo )
        {
            $tpl->warning( $operatorName, "Image '$operatorValue' does not exist in any design" );
            $tpl->warning( $operatorName, "Tried files: " . implode( ', ', $triedFiles ) );
            $siteDesign = eZTemplateDesignResource::designSetting( 'site' );
            $imgPath = "design/$siteDesign/images/$operatorValue";
        }
        else
        {
            $imgPath = $fileInfo['path'];
        }

        $operatorValue = $skipSlash ? $imgPath : $sys->wwwDir() . '/' . $imgPath;
        $operatorValue = htmlspecialchars( $operatorValue );

        return $operatorValue;
    }

    public $Operators;
    public $URLName, $URLRootName, $DesignName, $ImageName;
    public $Sys;
};

?>
