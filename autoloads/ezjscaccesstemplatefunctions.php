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


class ezjscAccessTemplateFunctions
{
    function ezjscAccessTemplateFunctions()
    {
    }

    function operatorList()
    {
        return array( 'has_access_to_limitation' );
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array( 'has_access_to_limitation' => array( 'module' => array( 'type' => 'string',
                                                'required' => true,
                                                'default' => '' ),
                                              'function' => array( 'type' => 'string',
                                                'required' => true,
                                                'default' => '' ),
                                              'limitations' => array( 'type' => 'array',
                                                'required' => true,
                                                'default' => array() )),
        );
                                              
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        switch ( $operatorName )
        {
            case 'has_access_to_limitation':
            {
                // Line user. has_access_to, but with support for limitations
                $user = eZUser::currentUser();

                if ( $user instanceof eZUser )
                {
                    $result = $user->hasAccessTo( $namedParameters['module'], $namedParameters['function'] );
                    
                    if ( $result['accessWord'] !== 'limited')
                    { 
                        $operatorValue = $result['accessWord'] === 'yes';
                    }
                    else
                    {
                        $operatorValue = true; // User has access unless limitations don't match
                        foreach ( $result['policies'] as $limitationArray  )
                        {
                            foreach ( $limitationArray as $limitationKey => $limitationValues  )
                            {
                                if ( isset( $namedParameters['limitations'][$limitationKey] ) )
                                {
                                    if ( !in_array( $namedParameters['limitations'][$limitationKey], $limitationValues ) )
                                    {
                                        $operatorValue = false;
                                        break 2;
                                    }
                                }
                            }
                        }
                    }
                }
                else
                {
                    $operatorValue = false;
                }
            } break;
        }
    }
}

?>