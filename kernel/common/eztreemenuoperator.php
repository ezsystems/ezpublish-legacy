<?php
//
// Definition of eZTreeMenuOperator class
//
// Created on: <12-Feb-2003 09:17:07 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.6.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

include_once( "kernel/classes/ezcontentobjecttreenode.php" );

class eZTreeMenuOperator
{
    /*!
     */
    function eZTreeMenuOperator( $name = 'treemenu' )
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
        return array( 'path' => array( 'type' => 'array',
                                       'required' => true,
                                       'default' => false ),
                      'node_id' => array( 'type' => 'int',
                                          'required' => true,
                                          'default' => false ),
                      'class_filter' => array( 'type' => 'array',
                                               'required' => false,
                                               'default' => false ),
                      'depth_skip' => array( 'type' => 'int',
                                             'required' => false,
                                             'default' => false ),
                      'max_level' => array( 'type' => 'int',
                                            'required' => false,
                                            'default' => false ),
                      'is_selected_method' => array( 'type' => 'string',
                                                     'required' => false,
                                                     'default' => 'tree' ) );
    }

    /*!
     \reimp
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        $level = 0;
        $done = false;
        $i = 0;
        $pathArray = array();
        $tmpModulePath = $namedParameters['path'];
        $classFilter = $namedParameters['class_filter'];

        if( $classFilter == 'false' )
        {
            $classFilter = array();
        }
        else if ( count( $classFilter ) == 0 )
        {
            $classFilter = array( 1 );
        }
        if ( !$tmpModulePath[count($tmpModulePath)-1]['url'] and isset( $tmpModulePath[count($tmpModulePath)-1]['node_id'] ) )
            $tmpModulePath[count($tmpModulePath)-1]['url'] = "/content/view/full/" . $namedParameters['node_id'];

        $depthSkip = $namedParameters['depth_skip'];

        $maxLevel = $namedParameters['max_level'];
        $isSelectedMethod = $namedParameters['is_selected_method'];
        if ( $maxLevel === false )
            $maxLevel = 2;

        while ( !$done && isset( $tmpModulePath[$i+$depthSkip] ) )
        {
            // get node id
            $elements = explode( "/", $tmpModulePath[$i+$depthSkip]['url'] );
            $nodeID = false;
            if ( isset( $elements[4] ) )
                $nodeID = $elements[4];

            $excludeNode = false;

            if ( isset( $elements[1] ) )
            if ( $elements[1] == 'content' and $elements[2] == 'view' and is_numeric( $nodeID ) and $excludeNode == false and $level < $maxLevel )
            {
                $node =& eZContentObjectTreeNode::fetch( $nodeID );
                if ( !isset( $node ) ) { $operatorValue = $pathArray; return; }
                if ( isset( $tmpModulePath[$i+$depthSkip+1] ) )
                {
                    $nextElements = explode( "/", $tmpModulePath[$i+$depthSkip+1]['url'] );
                    if ( isset( $nextElements[4] ) )
                         $nextNodeID = $nextElements[4];
                }
                else
                    $nextNodeID = false;

                $menuChildren =& eZContentObjectTreeNode::subTree( array( 'Depth' => 1,
                                                                          'Offset' => 0,
                                                                          'SortBy' => $node->sortArray(),
                                                                          'ClassFilterType' => 'include',
                                                                          'ClassFilterArray' => $classFilter
                                                                          ),
                                                                   $nodeID );

                /// Fill objects with attributes, speed boost
                eZContentObject::fillNodeListAttributes( $menuChildren );

                $tmpPathArray = array();
                foreach ( $menuChildren as $child )
                {
                    $name = $child->attribute( 'name' );
                    $tmpNodeID = $child->attribute( 'node_id' );

                    $url = "/content/view/full/$tmpNodeID/";
                    $urlAlias = "/" . $child->attribute( 'url_alias' );

					$isSelected = false;
                    $nextNextElements = ( $isSelectedMethod == 'node' and isset( $tmpModulePath[$i+$depthSkip+2]['url'] ) ) ? explode( "/", $tmpModulePath[$i+$depthSkip+2]['url'] ) : null;
                    if ( $nextNodeID === $tmpNodeID and !isset( $nextNextElements[4] ) )
                    {
                        $isSelected = true;
                    }

                    $tmpPathArray[] = array( 'id' => $tmpNodeID,
                                             'level' => $i,
                                             'url_alias' => $urlAlias,
                                             'url' => $url,
                                             'text' => $name,
											 'is_selected' => $isSelected );
                }

                // find insert pos
                $j = 0;
                $insertPos = 0;
                foreach ( $pathArray as $path )
                {
                    if ( $path['id'] == $nodeID )
                        $insertPos = $j;
                    $j++;
                }
                $restArray = array_splice( $pathArray, $insertPos + 1 );

                $pathArray = array_merge( $pathArray, $tmpPathArray );
                $pathArray = array_merge( $pathArray, $restArray );
            }
            else
            {
                if ( $level == 0 )
                {
                    $node = eZContentObjectTreeNode::fetch( 2 );
                    if ( !isset( $node ) ) { $operatorValue = $pathArray; return; }
                    $menuChildren =& eZContentObjectTreeNode::subTree( array( 'Depth' => 1,
                                                                              'Offset' => 0,
                                                                              'SortBy' => $node->sortArray(),
                                                                              'ClassFilterType' => 'include',
                                                                              'ClassFilterArray' => $classFilter
                                                                              ),
                                                                       2 );

                    /// Fill objects with attributes, speed boost
                    eZContentObject::fillNodeListAttributes( $menuChildren );

                    $pathArray = array();
                    foreach ( $menuChildren as $child )
                    {
                        $name = $child->attribute( 'name' );
                        $tmpNodeID = $child->attribute( 'node_id' );

                        $url = "/content/view/full/$tmpNodeID/";
                        $urlAlias = "/" . $child->attribute( 'url_alias' );

                        $pathArray[] = array( 'id' => $tmpNodeID,
                                              'level' => $i,
                                              'url_alias' => $urlAlias,
                                              'url' => $url,
                                              'text' => $name,
                                              'is_selected' => false );
                    }
                }
                $done = true;
            }
            ++$level;
            ++$i;
        }

        $operatorValue = $pathArray;
    }

    /// \privatesection
    var $Operators;
};

?>
