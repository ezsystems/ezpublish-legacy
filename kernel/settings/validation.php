<?php
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.

//

/*! \file validation.php
*/

function validate( $fields, $type, $spacesAllowed = true )
{
    $validationMessage = '';
    $fieldContainingError = '';
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
