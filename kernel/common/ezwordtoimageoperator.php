<?php
//
// Definition of eZWordtoimageoperator class
//
// Created on: <27-Mar-2003 13:43:10 oh>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

/*! \file
*/

/*!
  \class eZWordToImageOperator ezwordtoimageoperator.php
  \brief The class eZWordToImageOperator does

*/
class eZWordToImageOperator
{
    /*!
     Initializes the object with the name $name, default is "wash".
    */
    function eZWordToImageOperator()
    {
        $this->Operators = array( "wordtoimage",
                                  "mimetype_icon", "class_icon", "classgroup_icon", "action_icon", "icon",
                                  "flag_icon", "icon_info" );
        $this->IconInfo = array();
    }

    /*!
      Returns the template operators.
    */
    function operatorList()
    {
        return $this->Operators;
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        switch ( $operatorName )
        {
            case "wordtoimage":
            {
                $ini = eZINI::instance("wordtoimage.ini");
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
            } break;

            // icon_info( <type> ) => array() containing:
            // - repository - Repository path
            // - theme - Theme name
            // - theme_path - Theme path
            // - size_path_list - Associative array of size paths
            // - size_info_list - Associative array of size info (width and height)
            // - icons - Array of icon files, relative to theme and size path
            // - default - Default icon file, relative to theme and size path
            case 'icon_info':
            {
                if ( !isset( $operatorParameters[0] ) )
                {
                    $tpl->missingParameter( $operatorName, 'type' );
                    return;
                }
                $type = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace );

                // Check if we have it cached
                if ( isset( $this->IconInfo[$type] ) )
                {
                    $operatorValue = $this->IconInfo[$type];
                    return;
                }

                $ini = eZINI::instance( 'icon.ini' );
                $repository = $ini->variable( 'IconSettings', 'Repository' );
                $theme = $ini->variable( 'IconSettings', 'Theme' );
                $groups = array( 'mimetype' => 'MimeIcons',
                                 'class' => 'ClassIcons',
                                 'classgroup' => 'ClassGroupIcons',
                                 'action' => 'ActionIcons',
                                 'icon' => 'Icons' );
                $configGroup = $groups[$type];
                $mapNames = array( 'mimetype' => 'MimeMap',
                                   'class' => 'ClassMap',
                                   'classgroup' => 'ClassGroupMap',
                                   'action' => 'ActionMap',
                                   'icon' => 'IconMap' );
                $mapName = $mapNames[$type];

                // Check if the specific icon type has a theme setting
                if ( $ini->hasVariable( $configGroup, 'Theme' ) )
                {
                    $theme = $ini->variable( $configGroup, 'Theme' );
                }

                // Load icon settings from the theme
                $themeINI = eZINI::instance( 'icon.ini', $repository . '/' . $theme );

                $sizes = $themeINI->variable( 'IconSettings', 'Sizes' );
                if ( $ini->hasVariable( 'IconSettings', 'Sizes' ) )
                {
                    $sizes = array_merge( $sizes,
                                          $ini->variable( 'IconSettings', 'Sizes' ) );
                }

                $sizePathList = array();
                $sizeInfoList = array();

                if ( is_array( $sizes ) )
                {
                    foreach ( $sizes as $key => $size )
                    {
                        $pathDivider = strpos( $size, ';' );
                        if ( $pathDivider !== false )
                        {
                            $sizePath = substr( $size, $pathDivider + 1 );
                            $size = substr( $size, 0, $pathDivider );
                        }
                        else
                        {
                            $sizePath = $size;
                        }

                        $width = false;
                        $height = false;
                        $xDivider = strpos( $size, 'x' );
                        if ( $xDivider !== false )
                        {
                            $width = (int)substr( $size, 0, $xDivider );
                            $height = (int)substr( $size, $xDivider + 1 );
                        }
                        $sizePathList[$key] = $sizePath;
                        $sizeInfoList[$key] = array( $width, $height );
                    }
                }

                $map = array();

                // Load mapping from theme
                if ( $themeINI->hasVariable( $configGroup, $mapName ) )
                {
                    $map = array_merge( $map,
                                        $themeINI->variable( $configGroup, $mapName ) );
                }
                // Load override mappings if they exist
                if ( $ini->hasVariable( $configGroup, $mapName ) )
                {
                    $map = array_merge( $map,
                                        $ini->variable( $configGroup, $mapName ) );
                }

                $default = false;
                if ( $themeINI->hasVariable( $configGroup, 'Default' ) )
                    $default = $themeINI->variable( $configGroup, 'Default' );
                if ( $ini->hasVariable( $configGroup, 'Default' ) )
                    $default = $ini->variable( $configGroup, 'Default' );

                // Build return value
                $iconInfo = array( 'repository' => $repository,
                                   'theme' => $theme,
                                   'theme_path' => $repository . '/' . $theme,
                                   'size_path_list' => $sizePathList,
                                   'size_info_list' => $sizeInfoList,
                                   'icons' => $map,
                                   'default' => $default );

                $this->IconInfo[$type] = $iconInfo;
                $operatorValue = $iconInfo;
            } break;

            case 'flag_icon':
            {
                $ini = eZINI::instance( 'icon.ini' );
                $repository = $ini->variable( 'FlagIcons', 'Repository' );
                $theme = $ini->variable( 'FlagIcons', 'Theme' );

                // Load icon settings from the theme
                $themeINI = eZINI::instance( 'icon.ini', $repository . '/' . $theme );

                $iconFormat = $themeINI->variable( 'FlagIcons', 'IconFormat' );
                if ( $ini->hasVariable( 'FlagIcons', 'IconFormat' ) )
                {
                    $iconFormat = $ini->variable( 'FlagIcons', 'IconFormat' );
                }

                $icon = $operatorValue . '.' . $iconFormat;
                $iconPath = $repository . '/' . $theme . '/' . $icon;
                if ( !is_readable( $iconPath ) )
                {
                    $defaultIcon = $themeINI->variable( 'FlagIcons', 'DefaultIcon' );
                    $iconPath = $repository . '/' . $theme . '/' . $defaultIcon . '.' . $iconFormat;
                }
                if ( strlen( eZSys::wwwDir() ) > 0 )
                    $wwwDirPrefix = eZSys::wwwDir() . '/';
                else
                    $wwwDirPrefix = '/';
                $operatorValue = $wwwDirPrefix . $iconPath;
            } break;

            case 'mimetype_icon':
            case 'class_icon':
            case 'classgroup_icon':
            case 'action_icon':
            case 'icon':
            {
                // Determine whether we should return only the image URI instead of the whole HTML code.
                if ( isset( $operatorParameters[2] ) )
                    $returnURIOnly = $tpl->elementValue( $operatorParameters[2], $rootNamespace, $currentNamespace );
                else
                    $returnURIOnly = false;

                $ini = eZINI::instance( 'icon.ini' );
                $repository = $ini->variable( 'IconSettings', 'Repository' );
                $theme = $ini->variable( 'IconSettings', 'Theme' );
                $groups = array( 'mimetype_icon' => 'MimeIcons',
                                 'class_icon' => 'ClassIcons',
                                 'classgroup_icon' => 'ClassGroupIcons',
                                 'action_icon' => 'ActionIcons',
                                 'icon' => 'Icons' );
                $configGroup = $groups[$operatorName];

                // Check if the specific icon type has a theme setting
                if ( $ini->hasVariable( $configGroup, 'Theme' ) )
                {
                    $theme = $ini->variable( $configGroup, 'Theme' );
                }

                // Load icon settings from the theme
                $themeINI = eZINI::instance( 'icon.ini', $repository . '/' . $theme );

                if ( isset( $operatorParameters[0] ) )
                {
                    $sizeName = $tpl->elementValue( $operatorParameters[0], $rootNamespace, $currentNamespace );
                }
                else
                {
                    $sizeName = $ini->variable( 'IconSettings', 'Size' );
                    // Check if the specific icon type has a size setting
                    if ( $ini->hasVariable( $configGroup, 'Size' ) )
                    {
                        $theme = $ini->variable( $configGroup, 'Size' );
                    }
                }

                $sizes = $themeINI->variable( 'IconSettings', 'Sizes' );
                if ( $ini->hasVariable( 'IconSettings', 'Sizes' ) )
                {
                    $sizes = array_merge( $sizes,
                                          $ini->variable( 'IconSettings', 'Sizes' ) );
                }

                if ( isset( $sizes[$sizeName] ) )
                {
                    $size = $sizes[$sizeName];
                }
                else
                {
                    $size = $sizes[0];
                }

                $pathDivider = strpos( $size, ';' );
                if ( $pathDivider !== false )
                {
                    $sizePath = substr( $size, $pathDivider + 1 );
                    $size = substr( $size, 0, $pathDivider );
                }
                else
                {
                    $sizePath = $size;
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
                    $icon = $this->iconGroupMapping( $ini, $themeINI,
                                                     'MimeIcons', 'MimeMap',
                                                     strtolower( $operatorValue ) );
                }
                else if ( $operatorName == 'class_icon' )
                {
                    $icon = $this->iconDirectMapping( $ini, $themeINI,
                                                      'ClassIcons', 'ClassMap',
                                                      strtolower( $operatorValue ) );
                }
                else if ( $operatorName == 'classgroup_icon' )
                {
                    $icon = $this->iconDirectMapping( $ini, $themeINI,
                                                      'ClassGroupIcons', 'ClassGroupMap',
                                                      strtolower( $operatorValue ) );
                }
                else if ( $operatorName == 'action_icon' )
                {
                    $icon = $this->iconDirectMapping( $ini, $themeINI,
                                                      'ActionIcons', 'ActionMap',
                                                      strtolower( $operatorValue ) );
                }
                else if ( $operatorName == 'icon' )
                {
                    $icon = $this->iconDirectMapping( $ini, $themeINI,
                                                      'Icons', 'IconMap',
                                                      strtolower( $operatorValue ) );
                }

                $iconPath = '/' . $repository . '/' . $theme;
                $iconPath .= '/' . $sizePath;
                $iconPath .= '/' . $icon;

                $wwwDirPrefix = "";
                if ( strlen( eZSys::wwwDir() ) > 0 )
                    $wwwDirPrefix = eZSys::wwwDir();
                $sizeText = '';
                if ( $width !== false and $height !== false )
                {
                    $sizeText = ' width="' . $width . '" height="' . $height . '"';
                }

                // The class will be detected by ezpngfix.js, which will force alpha blending in IE.
                if ( ( !isset( $sizeName ) || $sizeName == 'normal' || $sizeName == 'original' ) && strstr( strtolower( $iconPath ), ".png" ) )
                {
                    $class = 'class="transparent-png-icon" ';
                }
                else
                {
                    $class = '';
                }

                if ( $returnURIOnly )
                    $operatorValue = $wwwDirPrefix . $iconPath;
                else
                    $operatorValue = '<img ' . $class . 'src="' . $wwwDirPrefix . $iconPath . '"' . $sizeText . ' alt="' .  htmlspecialchars( $altText ) . '" title="' . htmlspecialchars( $altText ) . '" />';
            } break;

            default:
            {
                eZDebug::writeError( "Unknown operator: $operatorName", "ezwordtoimageoperator.php" );
            }

        }

    }

    /*!
     \private
     Tries to find icon file by considering \a $matchItem as a single value.

     It will first try to match the whole \a $matchItem value in the mapping table.

     \return The relative path to the icon file.

     Example
     \code
     $icon = $this->iconDirectMapping( $ini, $themeINI, 'ClassIcons', 'ClassMap', 'Folder' );
     \endcode

     \sa iconGroupMapping
    */
    function iconDirectMapping( &$ini, &$themeINI, $iniGroup, $mapName, $matchItem )
    {
        $map = array();

        // Load mapping from theme
        if ( $themeINI->hasVariable( $iniGroup, $mapName ) )
        {
            $map = array_merge( $map,
                                $themeINI->variable( $iniGroup, $mapName ) );
        }
        // Load override mappings if they exist
        if ( $ini->hasVariable( $iniGroup, $mapName ) )
        {
            $map = array_merge( $map,
                                $ini->variable( $iniGroup, $mapName ) );
        }

        $icon = false;
        if ( isset( $map[$matchItem] ) )
        {
            $icon = $map[$matchItem];
        }
        if ( $icon === false )
        {
            if ( $themeINI->hasVariable( $iniGroup, 'Default' ) )
                $icon = $themeINI->variable( $iniGroup, 'Default' );
            if ( $ini->hasVariable( $iniGroup, 'Default' ) )
                $icon = $ini->variable( $iniGroup, 'Default' );
        }
        return $icon;
    }

    /*!
     \private
     Tries to find icon file by considering \a $matchItem as a group,
     split into two parts and separated by a slash.

     It will first try to match the whole \a $matchItem value and then
     the group name.

     \return The relative path to the icon file.

     Example
     \code
     $icon = $this->iconGroupMapping( $ini, $themeINI, 'MimeIcons', 'MimeMap', 'image/jpeg' );
     \endcode

     \sa iconDirectMapping
    */
    function iconGroupMapping( &$ini, &$themeINI, $iniGroup, $mapName, $matchItem )
    {
        $map = array();

        // Load mapping from theme
        if ( $themeINI->hasVariable( $iniGroup, $mapName ) )
        {
            $map = array_merge( $map,
                                $themeINI->variable( $iniGroup, $mapName ) );
        }
        // Load override mappings if they exist
        if ( $ini->hasVariable( $iniGroup, $mapName ) )
        {
            $map = array_merge( $map,
                                $ini->variable( $iniGroup, $mapName ) );
        }

        $icon = false;
        // See if we have a match for the whole match item
        if ( isset( $map[$matchItem] ) )
        {
            $icon = $map[$matchItem];
        }
        else
        {
            // If not we have to check the group (first part)
            $pos = strpos( $matchItem, '/' );
            if ( $pos !== false )
            {
                $mimeGroup = substr( $matchItem, 0, $pos );
                if ( isset( $map[$mimeGroup] ) )
                {
                    $icon = $map[$mimeGroup];
                }
            }
        }

        // No icon? If so use default
        if ( $icon === false )
        {
            if ( $themeINI->hasVariable( $iniGroup, 'Default' ) )
                $icon = $themeINI->variable( $iniGroup, 'Default' );
            if ( $ini->hasVariable( $iniGroup, 'Default' ) )
                $icon = $ini->variable( $iniGroup, 'Default' );
        }
        return $icon;
    }

    /// \privatesection
    public $Operators;
    public $IconInfo;
}
?>
