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

/**
 * Custom has access to call that also lets you check that user has access to provided limitation(s)
 * 
 * has_access_to_limitation( string $module, string $function, hash $limitations ):
 * Currently only returns true/false, but will in the future also return array of limitations that
 * did not match (as in limitations you did not ask to check by your provided parameters)
 */

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
              $operatorValue = self::hasAccessToLimitation( $namedParameters['module'], $namedParameters['function'], $namedParameters['limitations'] );
            } break;
        }
    }

    public static function hasAccessToLimitation( $module, $function, $limitations = null )
    {
        // Like fetch(user,has_access_to), but with support for limitations
        $user = eZUser::currentUser();

        if ( $user instanceof eZUser )
        {
            $result = $user->hasAccessTo( $module, $function );
            
            if ( $result['accessWord'] !== 'limited')
            { 
                return $result['accessWord'] === 'yes';
            }
            else
            {
                // User has access unless limitations don't match
                foreach ( $result['policies'] as $limitationArray  )
                {
                    foreach ( $limitationArray as $limitationKey => $limitationValues  )
                    {
                        if ( isset( $limitations[$limitationKey] ) )
                        {
                            if ( !in_array( $limitations[$limitationKey], $limitationValues ) )
                            {
                                return false;
                            }
                        }
                        else
                        {
                            // TODO: build limitation array of unmatched policies
                        }
                    }
                }
                return true;
            }
        }
        return false;
    }
}

?>