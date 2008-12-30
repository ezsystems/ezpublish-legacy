<?php
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

/*! \file
*/

function validate( $fields, $type, $spacesAllowed = true )
{
    $validationMessage = '';
    $fieldContainingError = '';
    $validationErrorType = '';
    $hasValidationError = false;
    $fieldNumber = 0;
    foreach( $fields as $fieldName=>$fieldValue )
    {
        if ( $fieldValue == '' )
        {
            $validationErrorType = 'empty';
            $validationMessage = 'Please specify a value';
            $hasValidationError = true;
        }

        if ( $spacesAllowed == false )
        {
            if ( strstr( $fieldValue, " " ) )
            {
                $validationErrorType = 'contain_spaces';
                $validationMessage = 'spaces is not allowed, but field contains spaces';
                $hasValidationError = true;
            }
        }

        if ( !$hasValidationError )
        {
            switch ( $type[$fieldNumber] )
            {
                case 'array':
                    break;
                case 'name':
                    if ( !preg_match( "/^[A-Za-z0-9]*$/", $fieldValue ) )
                    {
                        $validationErrorType = 'not_valid_name';
                        $validationMessage = 'Name contains illegal characters';
                        $hasValidationError = true;
                    }
                    break;
                case 'string':
                    if ( !is_string( $fieldValue ) or
                         ( is_string( $fieldValue ) and is_numeric( $fieldValue ) ) )
                    {
                        $validationErrorType = 'not_string';
                        $validationMessage = 'Field is not a string';
                        $hasValidationError = true;
                    }
                    break;
                case 'numeric':
                    if ( !is_numeric( $fieldValue ) )
                    {
                        $validationErrorType = 'not_numeric';
                        $validationMessage = 'Field is not a numeric';
                        $hasValidationError = true;
                    }
                    break;
            }
        }

        if ( $hasValidationError )
        {
            $fieldContainingError = $fieldName;
            break;
        }
        ++$fieldNumber;
    }
    return array( 'hasValidationError' => $hasValidationError,
                  'fieldContainingError' => $fieldContainingError,
                  'type' => $validationErrorType,
                  'message' => $validationMessage );
}

?>
