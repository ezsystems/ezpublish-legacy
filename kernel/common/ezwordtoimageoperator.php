<?php
//
// Definition of eZWordtoimageoperator class
//
// Created on: <27-Mar-2003 13:43:10 oh>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezwordtoimageoperator.php
*/

/*!
  \class eZWordtoimageoperator ezwordtoimageoperator.php
  \brief The class eZWordtoimageoperator does

*/
class eZWordToImageOperator
{
    /*!
     Initializes the object with the name $name, default is "wash".
    */
    function eZWordToImageOperator()
    {
        $this->Operators = array( "wordtoimage", "mimetype_icon", "class_icon", "classgroup_icon", "icon", "flag_icon" );
    }

    /*!
      Returns the template operators.
    */
    function &operatorList()
    {
        return $this->Operators;
    }

    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        // Determine whether we should return only the image URI instead of the whole HTML code.
        if ( isset( $operatorParameters[2] ) )
            $returnURIOnly = $tpl->elementValue( $operatorParameters[2], $rootNamespace, $currentNamespace );
        else
            $returnURIOnly = false;

        switch ( $operatorName )
        {
        case "wordtoimage" :
            {
                include_once( "lib/ezutils/classes/ezini.php" );
                $ini =& eZINI::instance("wordtoimage.ini");
                $iconRoot = $ini->variable( 'WordToImageSettings', 'IconRoot' );

                $replaceText = $ini->variable( 'WordToImageSettings', 'ReplaceText' );
                $replaceIcon = $ini->variable( 'WordToImageSettings', 'ReplaceIcon' );

                $wwwDirPrefix = "";
                if ( strlen( eZSys::wwwDir() ) > 0 )
                    $wwwDirPrefix = eZSys::wwwDir() . "/";
                foreach( $replaceIcon as $icon )
                {
                    $icons[] = '<img src="' . $wwwDirPrefix . $iconRoot .'/' . $icon . '"/>';
                }

                $operatorValue = str_replace( $replaceText, $icons, $operatorValue );
            }break;

        case 'flag_icon' :
            {
                $ini =& eZINI::instance( 'icon.ini' );
                $repository = $ini->variable( 'FlagIcons', 'Repository' );
                $iconFormat = $ini->variable( 'FlagIcons', 'IconFormat' );
                $icon = $operatorValue . "." . $iconFormat;
                $iconPath = '/' . $repository . '/' . $icon;
                if ( !is_readable( $iconPath ) )
                {
                    $defaultIcon = $ini->variable( 'FlagIcons', 'DefaultIcon' );
                    $iconPath = '/' . $repository . '/' . $defaultIcon;
                }
                $wwwDirPrefix = "";
                if ( strlen( eZSys::wwwDir() ) > 0 )
                    $wwwDirPrefix = eZSys::wwwDir();
                $operatorValue = $wwwDirPrefix . $iconPath;
            }break;

        case 'mimetype_icon':
        case 'class_icon':
        case 'classgroup_icon':
        case 'icon':
            {
                $ini =& eZINI::instance( 'icon.ini' );
                $repository = $ini->variable( 'IconSettings', 'Repository' );
                $theme = $ini->variable( 'IconSettings', 'Theme' );
                if ( isset( $operatorParameters[0] ) )
                {
                    $sizeName = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace );
                }
                else
                {
                    $sizeName = $ini->variable( 'IconSettings', 'Size' );
                }
                $sizes = $ini->variable( 'IconSettings', 'Sizes' );
                if ( isset( $sizes[$sizeName] ) )
                {
                    $size = $sizes[$sizeName];
                }
                else
                {
                    $size = $sizes[0];
                }
                $width = false;
                $height = false;
                $xDivider = strpos( $size, 'x' );
                if ( $xDivider !== false )
                {
                    $width = (int)substr( $size, 0, $xDivider );
                    $height = (int)substr( $size, $xDivider + 1 );
                }
                if ( isset( $operatorParameters[1] ) )
                {
                    $altText = $tpl->elementValue( $operatorParameters[1], $rootNamespace, $currentNamespace );
                }
                else
                {
                    $altText = $operatorValue;
                }

                if ( $operatorName == 'mimetype_icon' )
                {
                    $mimeType = strtolower( $operatorValue );
                    $mimeMap = $ini->variable( 'MimeIcons', 'MimeMap' );
                    $icon = false;
                    if ( isset( $mimeMap[$mimeType] ) )
                    {
                        $icon = $mimeMap[$mimeType];
                    }
                    else
                    {
                        $pos = strpos( $mimeType, '/' );
                        if ( $pos !== false )
                        {
                            $mimeGroup = substr( $mimeType, 0, $pos );
                            if ( isset( $mimeMap[$mimeGroup] ) )
                            {
                                $icon = $mimeMap[$mimeGroup];
                            }
                        }
                    }
                    if ( $icon === false )
                    {
                        $icon = $ini->variable( 'MimeIcons', 'Default' );
                    }
                }
                else if ( $operatorName == 'class_icon' )
                {
                    $class = strtolower( $operatorValue );
                    $classMap = $ini->variable( 'ClassIcons', 'ClassMap' );
                    $icon = false;
                    if ( isset( $classMap[$class] ) )
                    {
                        $icon = $classMap[$class];
                    }
                    if ( $icon === false )
                    {
                        $icon = $ini->variable( 'ClassIcons', 'Default' );
                    }
                }
                else if ( $operatorName == 'classgroup_icon' )
                {
                    $classGroup = strtolower( $operatorValue );
                    $classGroupMap = $ini->variable( 'ClassGroupIcons', 'ClassGroupMap' );
                    $icon = false;
                    if ( isset( $classGroupMap[$classGroup] ) )
                    {
                        $icon = $classGroupMap[$classGroup];
                    }
                    if ( $icon === false )
                    {
                        $icon = $ini->variable( 'ClassGroupIcons', 'Default' );
                    }
                }
                else if ( $operatorName == 'icon' )
                {
                    $requestedIcon = strtolower( $operatorValue );
                    $iconMap = $ini->variable( 'Icons', 'IconMap' );
                    $icon = false;
                    if ( isset( $iconMap[$requestedIcon] ) )
                    {
                        $icon = $iconMap[$requestedIcon];
                    }
                    if ( $icon === false )
                    {
                        $icon = $ini->variable( 'Icons', 'Default' );
                    }
                }

                $iconPath = '/' . $repository . '/' . $theme . '/' . $size . '/' . $icon;

                $wwwDirPrefix = "";
                if ( strlen( eZSys::wwwDir() ) > 0 )
                    $wwwDirPrefix = eZSys::wwwDir() . "/";
                $sizeText = '';
                if ( $width !== false and $height !== false )
                {
                    $sizeText = ' width="' . $width . '" height="' . $height . '"';
                }

                if ( $returnURIOnly )
                    $operatorValue = $wwwDirPrefix . $iconPath;
                else
                    $operatorValue = '<img src="' . $wwwDirPrefix . $iconPath . '"' . $sizeText . ' alt="' .  htmlspecialchars( $altText ) . '" title="' . htmlspecialchars( $altText ) . '" />';
            } break;

            default:
            {
                eZDebug::writeError( "Unknown operator: $operatorName", "ezwordtoimageoperator.php" );
            }

        }
    }
    var $Operators;
}
?>
