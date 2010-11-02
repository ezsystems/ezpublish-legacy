<?php
//
// Definition of eZWordtoimageoperator class
//
// Created on: <27-Mar-2003 13:43:10 oh>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
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

/*! \file ezwordtoimageoperator.php
*/

/*!
  \class eZWordToImageOperator ezwordtoimageoperator.php
  \brief The class eZWordToImageOperator does

*/
class Image
{
    private static $IconInfo = array();

    public static function getMimeTypeIcon($input, $sizeName = false, $altText = false, $returnURIOnly = false)
    {
        return self::getIconImpl("MimeIcons", "MimeMap", true, $input, $sizeName, $altText, $returnURIOnly);
    }

    public static function getClassIcon($input, $sizeName = false, $altText = false, $returnURIOnly = false)
    {
        return self::getIconImpl("ClassIcons", "ClassMap", false, $input, $sizeName, $altText, $returnURIOnly);
    }

    public static function getClassGroupIcon($input, $sizeName = false, $altText = false, $returnURIOnly = false)
    {
        return self::getIconImpl("ClassGroupIcons", "ClassGroupMap", false, $input, $sizeName, $altText, $returnURIOnly);
    }

    public static function getActionIcon($input, $sizeName = false, $altText = false, $returnURIOnly = false)
    {
        return self::getIconImpl("ActionIcons", "ActionMap", false, $input, $sizeName, $altText, $returnURIOnly);
    }

    public static function getIcon($input, $sizeName = false, $altText = false, $returnURIOnly = false)
    {
        return self::getIconImpl("Icons", "IconMap", false, $input, $sizeName, $altText, $returnURIOnly);
    }

    // XXX Possible candidate for optimization?
    private static function getIconImpl($configGroup, $configMap, $checkGroup, $input, $sizeName = false, $altText = false, $returnURIOnly = false)
    {
        $ini = eZINI::instance( 'icon.ini' );
        $repository = $ini->variable( 'IconSettings', 'Repository' );
        $theme = $ini->variable( 'IconSettings', 'Theme' );
        

        // Check if the specific icon type has a theme setting
        if ( $ini->hasVariable( $configGroup, 'Theme' ) )
        {
            $theme = $ini->variable( $configGroup, 'Theme' );
        }

        // Load icon settings from the theme
        $themeINI = eZINI::instance( 'icon.ini', $repository . '/' . $theme );

        if( !$sizeName)
        {
            $sizeName = $ini->variable( 'IconSettings', 'Size' );
            // Check if the specific icon type has a size setting
            if ( $ini->hasVariable( $configGroup, 'Size' ) )
            {
                // XXX Is this correct?
                $theme = $ini->variable( $configGroup, 'Size' );
            }
        }

        $sizes = $themeINI->variable( 'IconSettings', 'Sizes' );
        if ( $ini->hasVariable( 'IconSettings', 'Sizes' ) )
        {
            $sizes = array_merge( $sizes, $ini->variable( 'IconSettings', 'Sizes' ) );
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

        if (!$altText)
        {
            $altText = $input;
        }

        $icon = self::iconMapping( $ini, $themeINI,
                                          $configGroup, $configMap, $checkGroup, 
                                          strtolower( $input ) );


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
        {
            return $wwwDirPrefix . $iconPath;
        }
        else
        {
            return '<img ' . $class . 'src="' . $wwwDirPrefix . $iconPath . '"' . $sizeText . ' alt="' .  htmlspecialchars( $altText ) . '" title="' . htmlspecialchars( $altText ) . '" />';
        }
    }

    public static function getWordToImage($input)
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

        return str_replace( $replaceText, $icons, $input );
    }


    /**
     * icon_info( <type> ) => array() containing:
     *
     * - repository - Repository path
     * - theme - Theme name
     * - theme_path - Theme path
     * - size_path_list - Associative array of size paths
     * - size_info_list - Associative array of size info (width and height)
     * - icons - Array of icon files, relative to theme and size path
     * - default - Default icon file, relative to theme and size path      
     */
    public static function getIconInfo($type)
    {
        // Check if we have it cached
        if ( isset( self::$IconInfo[$type] ) )
        {
            return self::$IconInfo[$type];
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

        self::$IconInfo[$type] = $iconInfo;
        return $iconInfo;
    }

    public static function getFlagIcon($language)
    {
        $ini = eZINI::instance( 'icon.ini' );
        $repository = $ini->variable( 'IconSettings', 'Repository' );
        $theme = $ini->variable( 'FlagIcons', 'Theme' );

        // Load icon settings from the theme
        $themeINI = eZINI::instance( 'icon.ini', $repository . '/' . $theme );

        $iconFormat = $themeINI->variable( 'FlagIcons', 'IconFormat' );
        if ( $ini->hasVariable( 'FlagIcons', 'IconFormat' ) )
        {
            $iconFormat = $ini->variable( 'FlagIcons', 'IconFormat' );
        }

        $icon = $language . '.' . $iconFormat;
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
        return $wwwDirPrefix . $iconPath;
    }

    /*!
     \private
     Tries to find icon file by considering \a $matchItem as a single value.

     It will first try to match the whole \a $matchItem value in the mapping table.

     \return The relative path to the icon file.

     Example
     \code
     $icon = $this->iconDirectMapping( $ini, $themeINI, 'ClassIcons', 'ClassMap', 'Folder' );
     \encode

     \sa iconGroupMapping
    */
    public static function iconMapping( &$ini, &$themeINI, $iniGroup, $mapName, $checkGroup, $matchItem )
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
        elseif( $checkGroup )
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

        if ( $icon === false )
        {
            if ( $themeINI->hasVariable( $iniGroup, 'Default' ) )
                $icon = $themeINI->variable( $iniGroup, 'Default' );
            if ( $ini->hasVariable( $iniGroup, 'Default' ) )
                $icon = $ini->variable( $iniGroup, 'Default' );
        }
        return $icon;
    }
}
?>
