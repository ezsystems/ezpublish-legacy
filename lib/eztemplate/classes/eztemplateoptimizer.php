<?php
//
// Definition of eZTemplateOptimizer class
//
// Created on: <16-Aug-2004 15:02:51 dr>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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

/*! \file eztemplateoptimizer.php
*/

/*!
  \class eZTemplateOptimizer eztemplateoptimizer.php
  \brief Analyses a compiled template tree and tries to optimize certain parts of it.

*/

include_once( 'lib/ezutils/classes/ezdebug.php' );
include_once( 'lib/eztemplate/classes/eztemplate.php' );

class eZTemplateOptimizer
{
    /*!
     Constructor
    */
    function eZTemplateOptimizer()
    {
    }

    /*!
     Optimizes a resource acquisition node and the variable data before it
    */
    function optimizeResourceAcquisition( $useComments, &$php, &$tpl, &$var, &$node, &$resourceData )
    {
        $data = $var[2];
        /* Check if the variable node has the correct format */
        if ( ( $var[1] == 'attributeAccess' ) and
             ( count( $data ) == 5 ) and
             ( $data[0][0] == EZ_TEMPLATE_TYPE_VARIABLE ) and
             ( $data[0][1][2] == 'node' ) and
             ( $data[1][0] == EZ_TEMPLATE_TYPE_ATTRIBUTE ) and
             ( $data[1][1][0][1] == 'object' ) and
             ( $data[2][0] == EZ_TEMPLATE_TYPE_ATTRIBUTE ) and
             ( $data[2][1][0][1] == 'data_map' ) and
             ( $data[3][0] == EZ_TEMPLATE_TYPE_ATTRIBUTE ) and
             ( $data[4][0] == EZ_TEMPLATE_TYPE_ATTRIBUTE ) and
             ( $data[4][1][0][1] == 'view_template' ) and
             ( $node[9] == 'attributeAccess' ) and
             ( isset( $resourceData['class-info'] ) ) )
        {
            $attribute = $data[3][1][0][1];
            if ( isset( $resourceData['class-info'][$attribute] ) and
                 isset( $node[2][$resourceData['class-info'][$attribute]] ) )
            {
                $file = $node[2][$resourceData['class-info'][$attribute]];
                $node[0] = EZ_TEMPLATE_NODE_OPTIMIZED_RESOURCE_ACQUISITION;
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
    function optimizeFunction( $useComments, &$php, &$tpl, &$node, &$resourceData )
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
    function optimizeVariable( $useComments, &$php, &$tpl, &$data, &$resourceData )
    {
        $ret = 0;
        /* node.object.data_map optimization */
        if ( ( count( $data ) >= 3 ) and
             ( $data[0][0] == EZ_TEMPLATE_TYPE_VARIABLE ) and
             ( $data[0][1][2] == 'node' ) and
             ( $data[1][0] == EZ_TEMPLATE_TYPE_ATTRIBUTE ) and
             ( $data[1][1][0][1] == 'object' ) and
             ( $data[2][0] == EZ_TEMPLATE_TYPE_ATTRIBUTE ) and
             ( $data[2][1][0][1] == 'data_map' ) )
        {
            /* Remove the nodes there are optimized-away */
            unset($data[1], $data[2]);
            /* Create a new node representing the optimization */
            $data[0] = array( EZ_TEMPLATE_TYPE_OPTIMIZED_NODE, null, 2 );

            /* Modify the next two nodes in the array too as we know for sure
             * what type it is. This fixes the dependency on
             * compiledFetchAttribute */
            if ( ( count( $data ) >= 3 ) and
                 ( $data[3][0] == EZ_TEMPLATE_TYPE_ATTRIBUTE ) and
                 ( $data[4][0] == EZ_TEMPLATE_TYPE_ATTRIBUTE ) )
            {
                $data[3][0] = EZ_TEMPLATE_TYPE_OPTIMIZED_ARRAY_LOOKUP;
                if ( $data[4][1][0][1] == "content")
                {
                    $data[4][0] = EZ_TEMPLATE_TYPE_OPTIMIZED_CONTENT_CALL;
                }
                else
                {
                    $data[4][0] = EZ_TEMPLATE_TYPE_OPTIMIZED_ATTRIBUTE_LOOKUP;
                }
            }
            $ret = 1;
        }

        /* node.object.data_map optimization through function */
        if ( isset( $data[0] ) and
             $data[0][0] == 101 )
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
    function optimize( $useComments, &$php, &$tpl, &$tree, &$resourceData )
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
                case EZ_TEMPLATE_NODE_INTERNAL_SPACING_INCREASE:
                case EZ_TEMPLATE_NODE_INTERNAL_SPACING_DECREASE:
                    /* Removing unnecessary whitespace changes */
                    unset( $tree[1][$key] );
                    break;
                case 3: /* Variable */
                    if ( isset( $tree[1][$key + 1] ) and
                         ( $tree[1][$key + 1][0] == 140 ) and
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
            $initializer = array( EZ_TEMPLATE_NODE_OPTIMIZED_INIT, null, false );
            array_unshift( $tree[1], $initializer );
        }
    }

    function fetchClassDeclaration( $classID )
    {
        include_once "kernel/classes/ezcontentclass.php";
        $attributes = eZContentClass::fetchAttributes( $classID );
        $attributeArray = array();
        foreach ( $attributes as $attribute )
        {
            $attributeArray[ $attribute->Identifier ] = $attribute->DataTypeString;
        }
        return $attributeArray;
    }
}
?>
