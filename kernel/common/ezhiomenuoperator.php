<?php
//
// Definition of eZHiOMenuOperator class
//
// Created on: <08-Jan-2003 13:24:47 bf>
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

class eZHiOMenuOperator
{
    /*!
     */
    function eZHiOMenuOperator( $name = 'hiomenu' )
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
                      'section_id' => array( 'type' => 'int',
                                          'required' => true,
                                          'default' => false ) );
    }

    /*!
     \reimp
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        include_once( 'lib/ezutils/classes/ezphpcreator.php' );
        $php = new eZPHPCreator( "var/cache/menu/", "menu_" . $namedParameters['node_id'] . ".php" );

        if ( $php->canRestore( mktime() - 60*5 ) )
        {
            $cacheExpired = false;
        }
        else
        {
            $cacheExpired = true;
        }

        if ( $cacheExpired == true )
        {
            $level = 0;
            $done = false;
            $i = 0;
            $pathArray = array();
            $tmpModulePath = $namedParameters['path'];

            $tmpModulePath[count($tmpModulePath)-1]['url'] = "/content/view/full/" . $namedParameters['node_id'];

            $offset = 0;
            $sessionIDs = array ( 2,  // Sykepleierutdanning
                                  4,  // Estetiske fag
                                  5,  // Helsefag
                                  6,  // Ingeniørutdanning
                                  7,  // Journalistikk, bibliotek- og informasjonsfag
                                  8,  // Lærerutdanning
                                  9,  // Økonomi, kommunal- og sosialfag
                                  10, // Profesjonsstudier
                                  11, // Internasjonalt og flerkulturelt arbeid
                                  12, // Kompetanseutvikling i den flerkulturelle skolen
                                  13, // Voldsofferarbeid
                                  14, // Pedagogisk utviklingssenter
                                  15, // Høgskolebiblioteket
                                  16,  // Administrasjonen16
                                  21  // Sevu
                                  );

            if ( in_array( $namedParameters['section_id'], $sessionIDs ) )
                $offset = 2;
            else if ( $namedParameters['section_id']==19 )  // English pages
                $offset = 1;

            $node =& eZContentObjectTreeNode::fetch( $namedParameters['node_id']  );

            if ( is_object( $node ) )
            {
                if ( $node->attribute( 'main_node_id' ) != $namedParameters['node_id'] )
                {
                    $offset = 0;
                }
            }

            while ( !$done )
            {
                // get node id
                $elements = explode( "/", $tmpModulePath[$i+$offset]['url'] );
                $nodeID = $elements[4];

                $excludeNode = false;
                $node =& eZContentObjectTreeNode::fetch( $nodeID );

                if ( $node )
                {
                    $obj = $node->attribute('object');
                    $dataMap = $obj->dataMap();
                    if ( $obj->attribute( 'contentclass_id' ) == 1 )
                    {
                        if ( get_class( $dataMap['liste'] ) == 'ezcontentobjectattribute' )
                            if ( $dataMap['liste']->attribute('data_int' ) == 1 )
                            {
                                $excludeNode = true;
                            }
                    }
                }

                if ( $elements[1] == 'content' and $elements[2] == 'view' and is_numeric( $nodeID ) and $excludeNode == false )
                {
                    $menuChildren =& eZContentObjectTreeNode::subTree( array( 'Depth' => 1,
                                                                              'Offset' => 0,
                                                                              'SortBy' => array( array('priority') ),
                                                                              'ClassFilterType' => 'include',
                                                                              'ClassFilterArray' => array( 6, 25 )
                                                                              ),
                                                                       $nodeID );

                    /// Fill objects with attributes, speed boost
                    eZContentObject::fillNodeListAttributes( $menuChildren );

                    $tmpPathArray = array();
                    foreach ( $menuChildren as $child )
                    {
                        $name = $child->attribute( 'name' );

                        $strLimit = 17;
                        if ( strlen( $name ) > $strLimit )
                        {
                            $name = substr( $name, 0, $strLimit ) . "...";
                        }
                        unset( $tmpObj );
                        $tmpNodeID = $child->attribute( 'node_id' );
                        $tmpObj = $child->attribute( 'object' );
                        $className = $tmpObj->attribute( 'class_name' );

                        unset( $map );
                        $addToMenu = true;
                        if ( $className == "Vevside" )
                        {
                            $map = $tmpObj->attribute( "data_map" );
                            $type = $map['type'];

                            $value = null;
                            $addToMenu = false;
                            if ( get_class( $type ) == "ezcontentobjectattribute" )
                            {
                                $enum = $type->content();
                                $values = $enum->attribute( "enumobject_list" );
                                $value = $values[0];
                                if ( get_class( $value ) == 'ezenumobjectvalue' and  $value->attribute( 'enumvalue' )  == 2 )
                                    $addToMenu = true;
                            }
                        }

                        if ( $className == "Link" )
                        {
                            $map = $tmpObj->attribute( "data_map" );
                            $tmpURL = $map['url']->content();
                            $url = "$tmpURL";
                            $urlAlias = $tmpURL;

                            $addToMenu = false;
                            $enum = $map['type']->content();
                            $value = null;
                            if ( get_class( $enum ) == "ezenum" )
                            {
                                unset( $values );
                                $values = $enum->attribute( "enumobject_list" );
                                $value = $values[0];
                            }

                            if ( get_class( $value ) == 'ezenumobjectvalue' and  $value->attribute( 'enumvalue' ) == 2 )
                            {
                                $addToMenu = true;
                            }
                        }
                        else
                        {
                            $url = "/content/view/full/$tmpNodeID/";
                            $urlAlias = "/" . $child->attribute( 'url_alias' );
                        }

                        if ( $addToMenu == true )
                            $tmpPathArray[] = array( 'id' => $tmpNodeID,
                                                     'level' => $i,
                                                     'url_alias' => $urlAlias,
                                                     'url' => $url,
                                                     'text' => $name );
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
                        $menuChildren =& eZContentObjectTreeNode::subTree( array( 'Depth' => 1,
                                                                                  'Offset' => 0,
                                                                                  'SortBy' => array( array('priority') ),
                                                                                  'ClassFilterType' => 'include',
                                                                                  'ClassFilterArray' => array( 6,25 )
                                                                                  ),
                                                                           2 );

                        /// Fill objects with attributes, speed boost
                        eZContentObject::fillNodeListAttributes( $menuChildren );

                        $pathArray = array();
                        foreach ( $menuChildren as $child )
                        {
                            $name = $child->attribute( 'name' );

                            $strLimit = 17;
                            if ( strlen( $name ) > $strLimit )
                            {
                                $name = substr( $name, 0, $strLimit ) . "...";
                            }
                            $tmpNodeID = $child->attribute( 'node_id' );
                            $tmpObj = $child->attribute( 'object' );
                            $className = $tmpObj->attribute( 'class_name' );

                            $addToMenu = true;
                            if ( $className == "Vevside" )
                            {
                                $map = $tmpObj->attribute( "data_map" );
                                $enum = $map['type']->content();
                                $values = $enum->attribute( "enumobject_list" );
                                $value = $values[0];
                                if ( get_class( $value ) == 'ezenumobjectvalue' and  $value->attribute( 'enumvalue' ) <> 2 )
                                    $addToMenu = false;
                            }

                            if ( $className == "Link" )
                            {
                                $map = $tmpObj->attribute( "data_map" );
                                $tmpURL = $map['url']->content();
                                $url = "$tmpURL";
                                $urlAlias = $url;
                            }
                            else
                            {
                                $url = "/content/view/full/$tmpNodeID/";
                                $urlAlias = "/" . $child->attribute( 'url_alias' );
                            }

                            if ( $addToMenu == true  )
                                $pathArray[] = array( 'id' => $tmpNodeID,
                                                      'level' => $i,
                                                      'url_alias' => $urlAlias,
                                                      'url' => $url,
                                                      'text' => $name );
                        }
                    }
                    $done = true;
                }
                ++$level;
                ++$i;
            }

            $php->addVariable( "menu_array", $pathArray );
            $php->store();
        }
        else
        {
            $values =& $php->restore( array( 'menu_array' => 'menu_array' ) );
            $pathArray =& $values['menu_array'];
        }

        $operatorValue = $pathArray;
    }

    /// \privatesection
    var $Operators;
};

?>
