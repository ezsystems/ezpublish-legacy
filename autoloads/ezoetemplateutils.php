<?php
//
// Definition of eZOETemplateUtils class
//
// Created on: <14-May-2008 18:42:08 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor MCE
// SOFTWARE RELEASE: 5.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ systems AS
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
 Some misc template operators to get access to some of the features in eZ Publish API
 
*/

class eZOETemplateUtils
{
    function eZOETemplateUtils()
    {
    }

    function operatorList()
    {
        return array( 'ezoe_ini_section' );
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array( 'ezoe_ini_section' => array( 'section' => array( 'type' => 'string',
                                              'required' => true,
                                              'default' => '' ),
                                                'file' => array( 'type' => 'string',
                                              'required' => false,
                                              'default' => 'site.ini' )
                                           ));
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        $ret = '';

        
        switch ( $operatorName )
        {
            case 'ezoe_ini_section':
                {                    
                    $ini = eZINI::instance( $namedParameters['file'] );
                    $ret = $ini->hasSection( $namedParameters['section'] );
                } break;
        }
        $operatorValue = $ret;
    }
 
}

?>