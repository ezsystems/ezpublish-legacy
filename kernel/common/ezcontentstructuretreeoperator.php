<?php
//
// Definition of eZContentStructureTreeOperator class
//      
// Created on: <14-Jul-2004 14:18:58 dl>
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
            
/*! \file ezcontentstructuretreeoperator.php 
*/

/*! 
  \class eZContentStructureTreeOperator ezcontentstructuretreeoperator.php 
  \brief  
*/

include_once( 'kernel/common/ezcontentstructuretree.php' );

class eZContentStructureTreeOperator
{
    function eZContentStructureTreeOperator( $name = 'content_structure_tree' )
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
        return array( 'root_node_id'        => array( 'type'       => 'int',
                                                      'required'   => true,
                                                      'default'    => 0 ),

                      'class_filter'        => array( 'type'       => 'array',
                                                      'required'   => false,
                                                      'default'    => false ),

                      'max_depth'           => array( 'type'       => 'int',
                                                      'required'   => false,
                                                      'default'    => 0 ),

                      'max_nodes'           => array( 'type'       => 'int',
                                                      'required'   => false,
                                                      'default'    => 0 ),


                      'sort_by'             => array( 'type'       => 'string',
                                                      'required'   => false,
                                                      'default'    => 'false' ),

                      'fetch_hidden'        => array( 'type'        => 'bool',
                                                      'required'    => false,
                                                      'default'     => 'false' ) );
    }

    /*! 
     \reimp 
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        $sortArray      = false;
        $fetchHidden    = false;

        if ( $namedParameters[ 'sort_by' ] != 'false' )
        {
            $sortingMethod      = explode("/", $namedParameters[ 'sort_by' ]);
            $sortingMethod[1]   = ($sortingMethod[1] == 'ascending') ? '1' : '0';
            
            $sortArray          = array();
            $sortArray[]        =& $sortingMethod;
        }

        if ( $namedParameters[ 'fetch_hidden' ] != 'false' )
        {
            $fetchHidden = true;
        }

        $operatorValue = eZContentStructureTree::getContentStructureTree(  $namedParameters[ 'root_node_id' ],
                                                                           $namedParameters[ 'class_filter' ],
                                                                           $namedParameters[ 'max_depth'    ],
                                                                           $namedParameters[ 'max_nodes'    ],
                                                                           $sortArray,
                                                                           $fetchHidden ); 
    }

    /// \privatesection
    var $Operators;
}

?>
