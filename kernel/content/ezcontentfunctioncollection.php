<?php
//
// Definition of eZContentFunctionCollection class
//
// Created on: <06-Oct-2002 16:19:31 amos>
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

/*! \file ezcontentfunctioncollection.php
*/

/*!
  \class eZContentFunctionCollection ezcontentfunctioncollection.php
  \brief The class eZContentFunctionCollection does

*/

include_once( 'kernel/error/errors.php' );

class eZContentFunctionCollection
{
    /*!
     Constructor
    */
    function eZContentFunctionCollection()
    {
    }

    function &fetchContentObject( $objectID )
    {
        include_once( 'kernel/classes/ezcontentobject.php' );
        $contentObject =& eZContentObject::fetch( $objectID );
        if ( $contentObject === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => $contentObject );
    }

    function &fetchContentVersion( $objectID, $versionID )
    {
        include_once( 'kernel/classes/ezcontentobjectversion.php' );
        $contentVersion =& eZContentObjectVersion::fetchVersion( $versionID, $objectID );
        if ( $contentVersion === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => $contentObject );
    }

    function &fetchContentNode( $nodeID )
    {
        include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
        $contentNode =& eZContentObjectTreeNode::fetch( $nodeID );
        if ( $contentNode === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => $contentNode );
    }

    function &fetchNonTranslationList( $objectID, $version )
    {
        include_once( 'kernel/classes/ezcontentobject.php' );
        include_once( 'kernel/classes/ezcontentobjectversion.php' );
        $version =& eZContentObjectVersion::fetchVersion( $version, $objectID );
        if ( $version === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );

        $nonTranslationList =& $version->nonTranslationList();
        if ( $nonTranslationList === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => &$nonTranslationList );
    }

    function &fetchTranslationList()
    {
        include_once( 'kernel/classes/ezcontentobject.php' );
        $translationList =& eZContentObject::translationList();
        if ( $translationList === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => &$translationList );
    }

    function &fetchObject( $objectID )
    {
        include_once( 'kernel/classes/ezcontentobject.php' );
        $object =& eZContentObject::fetch( $objectID );
        if ( $object === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => &$object );
    }

    function &fetchObjectTree( $parentNodeID, $sortBy, $offset, $limit, $depth, $classID, $class_filter_type, $class_filter_array   )
    {
        include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
        $treeParameters = array( 'Offset' => $offset,
                                 'Limit' => $limit,
                                 'Limitation' => null,
                                 'SortBy' => $sortBy,
                                 'class_id' => $classID,
                                 'ClassFilterType' => $class_filter_type,
                                 'ClassFilterArray' => $class_filter_array );
        if ( $depth !== false )
            $treeParameters['Depth'] = $depth;

        $children =& eZContentObjectTreeNode::subTree( $treeParameters,
                                                       $parentNodeID );
        if ( $children === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => &$children );
    }

    function &fetchObjectTreeCount( $parentNodeID, $class_filter_type, $class_filter_array, $depth )
    {
        include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
        $node =& eZContentObjectTreeNode::fetch( $parentNodeID );
        $childrenCount =& $node->subTreeCount( array( 'Limitation' => null,
                                                      'ClassFilterType' => $class_filter_type,
                                                      'ClassFilterArray' => $class_filter_array,
                                                      'Depth' => $depth ) );
        if ( $childrenCount === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => &$childrenCount );
    }
}

?>
