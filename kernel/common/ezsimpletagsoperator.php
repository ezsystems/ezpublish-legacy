<?php
//
// Definition of eZSimpleTagsOperator class
//
// Created on: <07-Feb-2003 09:39:55 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

class eZSimpleTagsOperator
{
    /*!
     */
    function eZSimpleTagsOperator( $name = 'simpletags' )
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
        return array( 'listname' => array( 'type' => 'string',
                                           'required' => false,
                                           'default' => false ) );
    }

    /*!
     \reimp
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        $elements = preg_split( "#(</?[a-zA-Z]+>)#",
                                $operatorValue,
                                false,
                                PREG_SPLIT_DELIM_CAPTURE );
        $newElements = array();
        $i = 0;
        foreach ( $elements as $element )
        {
            if ( ( $i % 2 ) == 1 )
            {
                $tagText = $element;
                if ( preg_match( "#<(/?)([a-zA-Z]+)>#", $tagText, $matches ) )
                {
                    $isEndTag = false;
                    if ( $matches[1] )
                        $isEndTag = true;
                    $tag = $matches[2];
                    $element = array( $tag, $isEndTag, $tagText );
                }
            }
            $newElements[] = $element;
            ++$i;
        }

        $tagListName = 'TagList';
        if ( $namedParameters['listname'] )
            $tagListName .= '_' . $namedParameters['listname'];

        $tagMap = array();
        $ini =& eZINI::instance( 'template.ini' );
        $tagList = $ini->variable( 'SimpleTagsOperator', $tagListName );
        foreach ( $tagList as $tag => $tagItem )
        {
            $elements = explode( ';', $tagItem );
            $pre = $elements[0];
            $post = $elements[1];
            $phpFunctions = array();
            if ( isset( $elements[2] ) )
            {
                $phpFunctionList = explode( ',', $elements[2] );
                $phpFunctions = array();
                foreach ( $phpFunctionList as $phpFunction )
                {
                    if ( function_exists( $phpFunction ) )
                        $phpFunctions[] = $phpFunction;
                }
            }
            $tagMap[$tag] = array( 'pre' => $pre,
                                   'post' => $post,
                                   'phpfunctions' => $phpFunctions );
        }

        $textPHPFunctions = array( 'htmlspecialchars' );
        $textPre = false;
        $textPost = false;
        if ( isset( $tagMap['text']['pre'] ) )
            $textPre = $tagMap['text']['pre'];
        if ( isset( $tagMap['text']['post'] ) )
            $textPost = $tagMap['text']['post'];
        if ( isset( $tagMap['text']['phpfunctions'] ) )
            $textPHPFunctions = $tagMap['text']['phpfunctions'];
        $textElements = array();
        for ( $i = 0; $i < count( $newElements ); ++$i )
        {
            $element = $newElements[$i];
            if ( is_string( $element ) )
            {
                $text = $element;
                foreach ( $textPHPFunctions as $textPHPFunction )
                {
                    $text = $textPHPFunction( $text );
                }
                $textElements[] = $textPre . $text . $textPost;
            }
            else if ( is_array( $element ) )
            {
                $tag = $element[0];
                $isEndTag = $element[1];
                $originalText = $element[2];
                if ( isset( $tagMap[$tag] ) )
                {
                    $tagOptions = $tagMap[$tag];
                    $phpFunctions = $tagOptions['phpfunctions'];
                    if ( !$isEndTag )
                    {
                        $tagElements = array();
                        for ( $j = $i + 1; $j < count( $newElements ); ++$j )
                        {
                            $tagElement = $newElements[$j];
                            if ( is_string( $tagElement ) )
                            {
                                $tagElements[] = $tagElement;
                            }
                            else if ( is_array( $tagElement ) )
                            {
                                if ( $tagElement[0] == $tag and
                                     $tagElement[1] )
                                {
                                    break;
                                }
                                $text = $tagElement[2];
                                $tagElements[] = $text;
                            }
                        }
                        $i = $j;
                        $textElements[] = $tagOptions['pre'];
                        $text = implode( '', $tagElements );
                        foreach ( $phpFunctions as $phpFunction )
                        {
                            $text = $phpFunction( $text );
                        }
                        $textElements[] = $text;
                        $textElements[] = $tagOptions['post'];
                    }
                }
                else
                {
                    $text = $originalText;
                    foreach ( $textPHPFunctions as $textPHPFunction )
                    {
                        $text = $textPHPFunction( $text );
                    }
                    $textElements[] = $textPre . $text . $textPost;
                }
            }
        }

        $operatorValue = implode( '', $textElements );
    }

    /// \privatesection
    var $Operators;
};

?>
