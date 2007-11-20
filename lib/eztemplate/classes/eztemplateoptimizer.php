<?php
//
// Definition of eZTemplateOptimizer class
//
// Created on: <16-Aug-2004 15:02:51 dr>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*! \file eztemplateoptimizer.php
*/

/*!
  \class eZTemplateOptimizer eztemplateoptimizer.php
  \brief Analyses a compiled template tree and tries to optimize certain parts of it.

*/

require_once( 'lib/ezutils/classes/ezdebug.php' );
//include_once( 'lib/eztemplate/classes/eztemplate.php' );

class eZTemplateOptimizer
{
    /*!
     Optimizes a resource acquisition node and the variable data before it
    */
    static function optimizeResourceAcquisition( $useComments, &$php, $tpl, &$var, &$node, &$resourceData )
    {
        $data = $var[2];
        /* Check if the variable node has the correct format */
        if ( ( $var[1] == 'attributeAccess' ) and
             ( count( $data ) == 5 ) and
             ( $data[0][0] == eZTemplate::TYPE_VARIABLE ) and
             ( $data[0][1][2] == 'node' ) and
             ( $data[1][0] == eZTemplate::TYPE_ATTRIBUTE ) and
             ( $data[1][1][0][1] == 'object' ) and
             ( $data[2][0] == eZTemplate::TYPE_ATTRIBUTE ) and
             ( $data[2][1][0][1] == 'data_map' ) and
             ( $data[3][0] == eZTemplate::TYPE_ATTRIBUTE ) and
             ( $data[4][0] == eZTemplate::TYPE_ATTRIBUTE ) and
             ( $data[4][1][0][1] == 'view_template' ) and
             ( $node[9] == 'attributeAccess' ) and
             ( isset( $resourceData['class-info'] ) ) )
        {
            $attribute = $data[3][1][0][1];
            if ( isset( $resourceData['class-info'][$attribute] ) and
                 isset( $node[2][$resourceData['class-info'][$attribute]] ) )
            {
                $file = $node[2][$resourceData['class-info'][$attribute]];
                $node[0] = eZTemplate::NODE_OPTIMIZED_RESOURCE_ACQUISITION;
                $node[10] = $resourceData['class-info'][$attribute];
                $node[2] = array( $node[10] => $file );

                return true;
            }
            else /* If we can't find it in the lookup table then it's simply
                  * not there, so we can just kill the array. */
            {
                $node[2] = array( 'dummy' => 'foo' );
                return false;
            }
            /* Added as an extra fall back, this point should never be reached,
             * but if it does then we make sure not to mess up the original
             * array in the calling function. */
            return false;
        }
        else
        {
            return false;
        }
    }

    /*!
     Analyses function nodes and tries to optimize them
    */
    static function optimizeFunction( $useComments, &$php, $tpl, &$node, &$resourceData )
    {
        $ret = 0;
        /* Just run the optimizer over all parameters */
        if ( isset( $node[3] ) and is_array( $node[3] ) )
        {
            foreach ( $node[3] as $key => $parameter )
            {
                $ret = eZTemplateOptimizer::optimizeVariable( $useComments, $php, $tpl, $node[3][$key], $resourceData );
            }
        }
        return $ret;
    }

    /*!
     Analyses variables and tries to optimize them
    */
    static function optimizeVariable( $useComments, &$php, $tpl, &$data, &$resourceData )
    {
        $ret = 0;
        /* node.object.data_map optimization */
        if ( ( count( $data ) >= 3 ) and
             ( $data[0][0] == eZTemplate::TYPE_VARIABLE ) and
             ( $data[0][1][2] == 'node' ) and
             ( $data[1][0] == eZTemplate::TYPE_ATTRIBUTE ) and
             ( $data[1][1][0][1] == 'object' ) and
             ( $data[2][0] == eZTemplate::TYPE_ATTRIBUTE ) and
             ( $data[2][1][0][1] == 'data_map' ) )
        {
            /* Modify the next two nodes in the array too as we know for sure
             * what type it is. This fixes the dependency on
             * compiledFetchAttribute */
            if ( ( count( $data ) >= 5 ) and
                 ( $data[3][0] == eZTemplate::TYPE_ATTRIBUTE ) and
                 ( $data[4][0] == eZTemplate::TYPE_ATTRIBUTE ) )
            {
                $data[3][0] = eZTemplate::TYPE_OPTIMIZED_ARRAY_LOOKUP;
                if ( $data[4][1][0][1] == "content")
                {
                    $data[4][0] = eZTemplate::TYPE_OPTIMIZED_CONTENT_CALL;
                }
                else
                {
                    $data[4][0] = eZTemplate::TYPE_OPTIMIZED_ATTRIBUTE_LOOKUP;
                }
            }

            /* Create a new node representing the optimization */
            array_unshift( $data, array( eZTemplate::TYPE_OPTIMIZED_NODE, null, 2 ) );
            $ret = 1;
        }

        /* node.object.data_map optimization through function */
        if ( isset( $data[0] ) and
             $data[0][0] == eZTemplate::NODE_INTERNAL_CODE_PIECE )
        {
            $functionRet = eZTemplateOptimizer::optimizeFunction( $useComments, $php, $tpl, $data[0], $resourceData );
            // Merge settings
            $ret = $ret | $functionRet;
        }
        return $ret;
    }

    /*!
     Runs the optimizer
    */
    static function optimize( $useComments, &$php, $tpl, &$tree, &$resourceData )
    {
        /* If for some reason we don't have elements, simply return */
        if (! is_array( $tree[1] ) )
            return;

        $addNodeInit = false;

        /* Loop through the children of the root */
        foreach ( $tree[1] as $key => $kiddie )
        {
            /* Analyse per node type */
            switch ( $kiddie[0] )
            {
                case eZTemplate::NODE_INTERNAL_OUTPUT_SPACING_INCREASE:
                case eZTemplate::NODE_INTERNAL_SPACING_DECREASE:
                    /* Removing unnecessary whitespace changes */
                    unset( $tree[1][$key] );
                    break;
                case 3: /* Variable */
                    if ( isset( $tree[1][$key + 1] ) and
                         ( $tree[1][$key + 1][0] == eZTemplate::NODE_INTERNAL_RESOURCE_ACQUISITION ) and
                         isset( $resourceData['class-info'] ) )
                    {
                        $ret = eZTemplateOptimizer::optimizeResourceAcquisition(
                            $useComments, $php, $tpl,
                            $tree[1][$key], $tree[1][$key + 1], $resourceData );
                        /* We only unset the tree node when the optimization
                         * function returns false, as that means that the
                         * optimization could not be made. */
                        if ($ret)
                        {
                            unset( $tree[1][$key] );
                        }
                    }
                    else
                    {
                        $ret = eZTemplateOptimizer::optimizeVariable( $useComments, $php, $tpl, $tree[1][$key][2], $resourceData );
                        if ( $ret & 1 )
                            $addNodeInit = true;
                    }
                    break;
            }
        }
        if ( $addNodeInit )
        {
            $initializer = array( eZTemplate::NODE_OPTIMIZED_INIT, null, false );
            array_unshift( $tree[1], $initializer );
        }
    }

    static function fetchClassDeclaration( $classID )
    {
        include_once "kernel/classes/ezcontentclass.php";
        $contentClass = eZContentClass::fetch( $classID );
        $attributeArray = array();
        $attributes = is_object( $contentClass ) ? $contentClass->fetchAttributes() : array();
        foreach ( $attributes as $attribute )
        {
            $attributeArray[ $attribute->Identifier ] = $attribute->DataTypeString;
        }
        return $attributeArray;
    }
}
?>
