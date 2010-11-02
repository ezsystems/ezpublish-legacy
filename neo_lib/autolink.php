<?php
//
// Definition of eZAutoLinkOperator class
//
// Created on: <07-Feb-2003 09:39:55 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

class AutoLink
{
    public static function formatUri( $url, $max )
    {
        $text = $url;
        if (strlen($text) > $max)
        {
            $text = substr($text, 0, ($max / 2) - 3). '...'. substr($text, strlen($text) - ($max / 2));
        }
        return "<a href=\"$url\" title=\"$url\">$text</a>";
    }

    public static function addURILinks( $text, $max, $methods = 'http|https|ftp' )
    {
        return preg_replace( "!($methods):\/\/[\w]+(.[\w]+)([\w\-\.,@?^=%&:\/~\+#;*\(\)\!]*[\w\-\@?^=%&\/~\+#;*\(\)\!])?!e",
            "AutoLink::formatUri('$0', $max )", $text);
    }


    public static function getLink($input, $maxChars = null)
    {
        $ini = eZINI::instance('template.ini');
        $max = ($maxChars !== null ? $maxChars : $ini->variable( 'AutoLinkOperator', 'MaxCharacters' ) );

        $methods = $ini->variable( 'AutoLinkOperator', 'Methods' );
        $methodText = implode( '|', $methods );

        // Replace mail
        $input = preg_replace( "#(([a-zA-Z0-9_-]+\\.)*[a-zA-Z0-9_-]+@([a-zA-Z0-9_-]+\\.)*[a-zA-Z0-9_-]+)#", "<a href='mailto:\\1'>\\1</a>", $input );

        // Replace http/ftp etc. links
        $input = AutoLink::addURILinks($input, $max, $methodText);
        return $input;
    }
};

?>
