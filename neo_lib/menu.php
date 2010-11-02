<?php
//
// Definition of eZTemplateMenuFunction class
//
// Created on: <10-Mar-2004 15:34:50 wy>
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

class Menu
{
    public static function getMenu($menuName)
    {
        print $menuName;
        $menuIni = eZINI::instance( "menu.ini" );
        if ( !$menuIni->hasVariable( 'SelectedMenu', $menuName ) )
        {
            throw new ezcTemplateRuntimeException("menu.ini has no settings for 'SelectedMenu::$menuName'");
        }

        $menuTemplate = $menuIni->variable( "SelectedMenu", $menuName );
        if ( $menuTemplate === null )
        {
            //throw new ezcTemplateRuntimeException("menu.ini has no settings for 'SelectedMenu::$menuName'");
            return "";
        }

        $tpl = templateInit();
        $tc = ezcTemplateConfiguration::getInstance();
        $tc->templatePath = getcwd() . "/new_templates";
        $uri = "design:menu/$menuTemplate.tpl";
        return $tpl->fetch($uri);
    }
}

?>
