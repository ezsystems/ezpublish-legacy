<?php
//
// Definition of eZTemplateStringOperator class
//
// Created on: <17-Jul-2003 13:00:18 bh>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
  \class eZTemplateStringOperator eztemplatestringoperator.php
  \ingroup eZTemplateOperators
\code

\endcode

*/

class eZTemplateStringOperator
{
    /*!
     Constructor.
    */
    function eZTemplateStringOperator( $upcaseName     = 'upcase',
                                       $downcaseName   = 'downcase',
                                       $countwordsName = 'count_words',
                                       $countcharsName = 'count_chars',
                                       $trimName       = 'trim',
                                       $breakName      = 'break',
                                       $wrapName       = 'wrap',
                                       $upfirstName    = 'upfirst',
                                       $upwordName     = 'upword',
                                       $simplifyName   = 'simplify',
                                       $washName       = 'wash',
                                       $chrName        = 'chr',
                                       $ordName        = 'ord',
                                       $shortenName    = 'shorten',
                                       $padName        = 'pad')
    {
        $this->Operators      = array( $upcaseName,
                                       $downcaseName,
                                       $countwordsName,
                                       $countcharsName,
                                       $trimName,
                                       $breakName,
                                       $wrapName,
                                       $upfirstName,
                                       $upwordName,
                                       $simplifyName,
                                       $washName,
                                       $chrName,
                                       $ordName,
                                       $shortenName,
                                       $padName );

        $this->UpcaseName     = $upcaseName;
        $this->DowncaseName   = $downcaseName;
        $this->CountwordsName = $countwordsName;
        $this->CountcharsName = $countcharsName;
        $this->TrimName       = $trimName;
        $this->BreakName      = $breakName;
        $this->WrapName       = $wrapName;
        $this->UpfirstName    = $upfirstName;
        $this->UpwordName     = $upwordName;
        $this->SimplifyName   = $simplifyName;
        $this->WashName       = $washName;
        $this->ChrName        = $chrName;
        $this->OrdName        = $ordName;
        $this->ShortenName    = $shortenName;
        $this->PadName        = $padName;
    }

    /*!
     Returns the template operators.
    */
    function &operatorList()
    {
        return $this->Operators;
    }

    /*!
     \return true to tell the template engine that the parameter list exists per operator type.
    */
    function namedParameterPerOperator()
    {
        return true;
    }
    /*!
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return array( $this->TrimName => array( 'chars_to_remove'  => array( "type" => "string",
                                                                             "required" => false,
                                                                             "default" => false ) ),
                      $this->WrapName => array( 'wrap_at_position' => array( "type" => "integer",
                                                                             "required" => false,
                                                                             "default" => false),
                                                'break_sequence'   => array( "type" => "string",
                                                                             "required" => false,
                                                                             "default" => false ),
                                                'cut'              => array( "type" => "boolean",
                                                                             "required" => false,
                                                                             "default" => false) ),
                      $this->WashName => array( 'type'             => array( "type" => "string",
                                                                             "required" => false,
                                                                             "default" => "xhtml" ) ),
                      $this->ShortenName => array( 'chars_to_keep' => array( "type" => "integer",
                                                                             "required" => false,
                                                                             "default" => 16),
                                                   'str_to_append' => array( "type" => "string",
                                                                             "required" => false,
                                                                             "default" => "..." ) ),
                      $this->PadName => array(  'desired_length'   => array( "type"     => "integer",
                                                                             "required" => false,
                                                                             "default"  => 80),
                                                'pad_sequence'     => array( "type" => "string",
                                                                             "required" => false,
                                                                             "default" => " ") ),
                      $this->SimplifyName => array ( 'char'        => array( "type" => "string",
                                                                             "required" => false,
                                                                             "default" => "\s") ) );
}

/*
 The modify function takes care of the various operations.
*/
function modify( &$tpl,
                 &$operatorName,
                 &$operatorParameters,
                 &$rootNamespace,
                 &$currentNamespace,
                 &$operatorValue,
                 &$namedParameters )
{
    switch ( $operatorName )
    {
        // Convert all alphabetical chars of operatorvalue to uppercase.
        case $this->UpcaseName:
        {
            $operatorValue = strtoupper( $operatorValue );
        } break;

        // Convert all alphabetical chars of operatorvalue to lowercase.
        case $this->DowncaseName:
        {
            $operatorValue = strtolower( $operatorValue );
        } break;

        // Count and return the number of words in operatorvalue.
        case $this->CountwordsName:
        {
            $operatorValue = preg_match_all( "#(\w+)#", $operatorValue, $match_dummy );
        }break;

        // Count and return the number of chars in operatorvalue.
        case $this->CountcharsName:
        {
            $operatorValue = strlen( $operatorValue );
        }break;

        // Insert HTML line breaks before newlines.
        case $this->BreakName:
        {
            $operatorValue = nl2br( $operatorValue );
        }break;

        // Wrap line (insert newlines).
        case $this->WrapName:
        {
            $operatorValue = wordwrap( $operatorValue, $namedParameters['wrap_at_position'], $namedParameters['break_sequence'], $namedParameters['cut']);
        }break;

        // Convert the first character to uppercase.
        case $this->UpfirstName:
        {
            $operatorValue = ucfirst( $operatorValue );
        }break;

        // Simplify / transform multiple consecutive characters into one.
        case $this->SimplifyName:
        {
            $replace_this = "/".$namedParameters['char']."{2,}/";
            $operatorValue = preg_replace( $replace_this, $namedParameters['char'], $operatorValue );
        }break;
        // Convert all first characters [in all words] to uppercase.
        case $this->UpwordName:
        {
            $operatorValue = ucwords( $operatorValue );
        }break;

        // Strip whitespace from the beginning and end of a string.
        case $this->TrimName:
        {
            $operatorValue = trim( $operatorValue, $namedParameters['chars_to_remove']);
        }break;

        // Pad...
        case $this->PadName:
        {
            if (strlen( $operatorValue ) < $namedParameters['desired_length'])
            {
                $operatorValue = str_pad( $operatorValue,
                                          $namedParameters['desired_length'],
                                          $namedParameters['pad_sequence'] );
            }
        }break;

        // Shorten string [default or specified length, length=text+"..."] and add '...'
        case $this->ShortenName:
        {
            if ( strlen( $operatorValue ) > $namedParameters['chars_to_keep'] )
            {
                $chop = $namedParameters['chars_to_keep'] - strlen( $namedParameters['str_to_append'] );
                $operatorLength = strlen( $operatorValue );
                $operatorValue = substr( $operatorValue, 0, $chop );
                $operatorValue = trim( $operatorValue );
                if ( $operatorLength > $chop )
                    $operatorValue = $operatorValue.$namedParameters['str_to_append'];
            }
        }break;

            // Wash (translate strings to non-spammable text):
            case $this->WashName:
            {
                $type = $namedParameters['type'];
                switch ( $type )
                {
                    case "xhtml":
                    {
                        $operatorValue = htmlspecialchars( $operatorValue );
                    } break;
                    case "email":
                    {
                        $ini =& $tpl->ini();
                        $dotText = $ini->variable( 'WashSettings', 'EmailDotText' );
                        $atText = $ini->variable( 'WashSettings', 'EmailAtText' );
                        $operatorValue = str_replace( array( '.',
                                                             '@' ),
                                                      array( $dotText,
                                                             $atText ),
                                                      $operatorValue );
                    } break;
                    case 'pdf':
                    {
                        $operatorValue = str_replace( array( ' ', // use default callback functions in ezpdf library
                                                             "\r\n",
                                                             "\t" ),
                                                      array( '<C:callSpace>',
                                                             '<C:callNewLine>',
                                                             '<C:callTab>' ),
                                                      $operatorValue );
                        $operatorValue = str_replace( "\n", '<C:callNewLine>', $operatorValue );
                    }
                }
            }break;

            // Ord (translate a unicode string to actual unicode id/numbers):
            case $this->OrdName:
            {
                $codec =& eZTextCodec::instance( false, 'unicode' );
                $output = $codec->convertString( $operatorValue );
                $operatorValue = $output;
            }break;

            // Chr (generate unicode characters based on input):
            case $this->ChrName:
            {
                $codec =& eZTextCodec::instance( 'unicode', false );
                $output = $codec->convertString( $operatorValue );
                $operatorValue = $output;
            }break;

            // Default case: something went wrong - unknown things...
            default:
            {
                $tpl->warning( $operatorName, "Unknown string type '$type'" );
            } break;
        }
    }

    /// The array of operators, used for registering operators
    var $Operators;
}

?>
