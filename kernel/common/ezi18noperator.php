<?php
//
// Definition of eZi18nOperator class
//
// Created on: <18-Apr-2002 12:15:07 amos>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

//!! eZKernel
//! The class eZi18nOperator does
/*!

*/

include_once( "kernel/common/i18n.php" );

function make_seed() {
    list($usec, $sec) = explode(' ', microtime());
    return (float) $sec + ((float) $usec * 100000);
}

class eZi18nOperator
{
    /*!
    */
    function eZi18nOperator( $name = "i18n" )
    {
        $this->Operators = array( $name );
        $this->TranslatorManager =& eZTranslatorManager::instance();
    }

    /*!
     Returns the operators in this class.
    */
    function &operatorList()
    {
        return $this->Operators;
    }

    /*!
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return array( "file" => array( "type" => "string",
                                       "required" => false,
                                       "default" => "" ),
                      "comment" => array( "type" => "string",
                                          "required" => false,
                                          "default" => "" ),
                      "context" => array( "type" => "string",
                                          "required" => false,
                                          "default" => false ) );
    }

    /*!
     */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$value, &$namedParameters )
    {
        $file = $namedParameters["file"];
        $context = $namedParameters["context"];
        $comment = $namedParameters["comment"];
//         if ( $context == "" )
//         {
//             $context = $element->templateNameRelation();
//             if ( preg_match( "/^(.+)(\.tpl)$/", $context, $regs ) )
//                 $context = $regs[1];
//         }
        $value = ezi18n( $file, $context, $value, $comment );
        /*
        srand(make_seed());
        $num = rand( 0, 1 );
        if ( $num == 0 )
        {
            $num = rand( 0, 3 );
            for ( $i = 0; $i < $num; ++$i )
            {
                $len = strlen( $value );
                $offs = rand( 0, $len - 1 );
                if ( $offs == 0 )
                {
                    $tmp = $value[$offs];
                    $value[$offs] = $value[$len - 1];
                    $value[$len] = $tmp;
                }
                else
                {
                    $delta = -1;
                    if ( $value[$offs+$delta] == " " and
                         $offs + 1 < $len )
                        $delta = 1;
                    $tmp = $value[$offs];
                    $value[$offs] = $value[$offs+$delta];
                    $value[$offs+$delta] = $tmp;
                }
            }
        }
        else
        {
            $value = preg_replace( "/to/", "2", $value );
            $value = preg_replace( "/for/", "4", $value );
            $value = preg_replace( "/ate/", "8", $value );
            $value = preg_replace( array( "/l/",
                                          "/e/",
                                          "/o/",
                                          "/a/",
                                          "/t/" ),
                                   array( "1",
                                          "3",
                                          "0",
                                          "4",
                                          "7" ), $value );
        }
        */
    }

    var $Operators;
    var $TranslatorManager;
};

?>
