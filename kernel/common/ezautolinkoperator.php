<?php
//
// Definition of eZAutoLinkOperator class
//
// Created on: <07-Feb-2003 09:39:55 bf>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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

class eZAutoLinkOperator
{
    /*!
     */
    function eZAutoLinkOperator( $name = 'autolink' )
    {
        $this->Operators = array( $name );
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
        return array( 'max_chars' => array( 'type' => 'integer',
                                            'required' => false,
                                            'default' => null ) );
    }

    /*!
     \reimp
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        $ini =& $tpl->ini();
        $max = $ini->variable( 'AutoLinkOperator', 'MaxCharacters' );
        if ( $namedParameters['max_chars'] !== null )
        {
            $max = $namedParameters['max_chars'];
        }
        $maxHalf = (int)($max / 2);

        $methods = $ini->variable( 'AutoLinkOperator', 'Methods' );
        $methodText = implode( '|', $methods );

        // Replace mail
        $operatorValue = preg_replace( "#(([a-zA-Z0-9_-]+\\.)*[a-zA-Z0-9_-]+@([a-zA-Z0-9_-]+\\.)*[a-zA-Z0-9_-]+)#", "<a href='mailto:\\1'>\\1</a>", $operatorValue );

        // Replace http/ftp etc. links
        $elements = preg_split( "#((?:(?:$methodText)://)(?:(?:[a-zA-Z0-9_-]+\\.)*[a-zA-Z0-9_-]+(?:(?:[\/a-zA-Z0-9_+$-]+\\.)*(?:[\/a-zA-Z0-9_+$-])*))(?:\#[a-zA-Z0-9-]+)?\?*(?:[a-zA-Z0-9_-]+=[a-zA-Z0-9_-]+&*)*)#m",
                                $operatorValue,
                                false,
                                PREG_SPLIT_DELIM_CAPTURE );
        $newElements = array();
        $i = 0;
        $lastElement = false;
        foreach ( $elements as $element )
        {
            if ( ( $i % 2 ) == 1 )
            {
                if ( strlen( $lastElement ) > 0 and
                     in_array( $lastElement[strlen( $lastElement ) - 1], array( " ", "\t", "\n", "\r" ) ) )
                {
                    if ( $max )
                    {
                        $text = $element;
                        if ( strlen( $text ) > $max - 3 )
                        {
                            $text = substr( $text, 0, $maxHalf - 3 ) . '...' . substr( $text, strlen( $text ) - $maxHalf );
                        }
                        $element = "<a href=\"$element\" title=\"$element\">$text</a>";
                    }
                    else
                    {
                        $element = "<a href=\"$element\" title=\"$element\">$element</a>";
                    }
                }
            }
            $newElements[] = $element;
            $lastElement = $element;
            ++$i;
        }
        $operatorValue = implode( '', $newElements );
    }

    /// \privatesection
    var $Operators;
};

?>
