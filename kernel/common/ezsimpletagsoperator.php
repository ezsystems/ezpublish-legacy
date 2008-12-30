<?php
//
// Definition of eZSimpleTagsOperator class
//
// Created on: <07-Feb-2003 09:39:55 bf>
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

class eZSimpleTagsOperator
{
    function eZSimpleTagsOperator( $name = 'simpletags' )
    {
        $this->Operators = array( $name );
    }

    /*!
     Returns the operators in this class.
    */
    function operatorList()
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
     \private

     Makes sure extra includes are loaded (include_once) so extra functions can be used.
     \note This function will only run one time, if called multiple times it will simple return
    */
    function initializeIncludes()
    {
        // If we have this global variable we shouldn't do any processing
        if ( !empty( $GLOBALS['eZSimpleTagsInit'] ) )
            return;

        $GLOBALS['eZSimpleTagsInit'] = true;
        $ini = eZINI::instance( 'template.ini' );
        $extensions = $ini->variable( 'SimpleTagsOperator', 'Extensions' );
        $pathList = eZExtension::expandedPathList( $extensions, 'simpletags' );
        $includeList = $ini->variable( 'SimpleTagsOperator', 'IncludeList' );

        foreach ( $includeList as $includeFile )
        {
            foreach ( $pathList as $path )
            {
                $file = $path . '/' . $includeFile;
                if ( file_exists( $file ) )
                {
                    include_once( $file );
                }
            }
        }
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        $elements = preg_split( "#(</?[a-zA-Z0-9_-]+>)#",
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
                if ( preg_match( "#<(/?)([a-zA-Z0-9_-]+)>#", $tagText, $matches ) )
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

        $this->initializeIncludes();

        $tagMap = array();
        $ini = eZINI::instance( 'template.ini' );
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
    public $Operators;
};

?>
