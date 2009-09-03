<?php
//
// Definition of ezjscEncodingTemplateFunctions
//
// Created on: <17-Sep-2007 12:42:08 ar>
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


class ezjscEncodingTemplateFunctions
{
    function ezjscEncodingTemplateFunctions()
    {
    }

    function operatorList()
    {
        return array( 'json_encode',
                      'xml_encode',
                      'node_encode',
                      );
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array( 'json_encode' => array( 'hash' => array( 'type' => 'hash',
                                                'required' => true,
                                                'default' => array() )),
                      'xml_encode' => array( 'hash' => array( 'type' => 'hash',
                                                'required' => true,
                                                'default' => array() )),
                      'node_encode' => array( 'node' => array( 'type' => 'object',
                                                'required' => true,
                                                'default' => array() ),
                                              'params' => array( 'type' => 'hash',
                                                'required' => false,
                                                'default' => array() ),
                                              'type' => array( 'type' => 'string',
                                                'required' => false,
                                                'default' => 'json' )),
        );
                                              
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        switch ( $operatorName )
        {
            case 'json_encode':
            {
                // Lets you use ezjscAjaxContent::jsonEncode from templates
                $operatorValue = json_encode( $namedParameters['hash'] );
            } break;
            case 'xml_encode':
            {
                // Lets you use ezjscAjaxContent::xmlEncode from templates
                $operatorValue = ezjscAjaxContent::xmlEncode( $namedParameters['hash'] );
            } break;
            case 'node_encode':
            {
                // Lets you use ezjscAjaxContent::nodeEncode from templates
                $operatorValue = ezjscAjaxContent::nodeEncode( $namedParameters['node'], $namedParameters['params'], $namedParameters['type'] );
            } break;
        }
    }
}

?>