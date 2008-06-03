<?php
//
// Definition of eZPDFXMLOutput class
//
// Created on: <25-Sep-2006 15:05:00 ks>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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
*/

//include_once( 'kernel/classes/datatypes/ezxmltext/handlers/output/ezxhtmlxmloutput.php' );

class eZPDFXMLOutput extends eZXHTMLXMLOutput
{

    function eZPDFXMLOutput( &$xmlData, $aliasedType, $contentObjectAttribute = null )
    {
        $this->eZXHTMLXMLOutput( $xmlData, $aliasedType, $contentObjectAttribute );

        $this->OutputTags['table']['initHandler'] = 'initHandlerTable';
        $this->OutputTags['li']['initHandler'] = 'initHandlerLi';
    }

    function initHandlerTable( $element, &$attributes, &$sibilingParams, &$parentParams )
    {
        $ret = array();

        if( !isset( $attributes['width'] ) )
            $attributes['width'] = '100%';

        if( !isset( $attributes['border'] ) )
            $attributes['border'] = 1;

        return $ret;
    }

    function initHandlerLi( $element, &$attributes, &$sibilingParams, &$parentParams )
    {
        if( !isset( $sibilingParams['list_count'] ) )
            $sibilingParams['list_count'] = 1;
        else
            $sibilingParams['list_count']++;

        $ret = array( 'tpl_vars' => array( 'list_count' => $sibilingParams['list_count'],
                                           'tag_name' => $element->parentNode->nodeName ) );
        return $ret;
    }

    public $TemplatesPath = 'design:content/datatype/pdf/ezxmltags/';
}

?>
