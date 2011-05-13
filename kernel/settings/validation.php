<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
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
