<?php
//
// Definition of eZContentFunctionCollection class
//
// Created on: <06-Oct-2002 16:19:31 amos>
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
        return array( 'result' => $contentVersion );
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

    function &fetchLocaleList()
    {
        include_once( 'lib/ezlocale/classes/ezlocale.php' );
        $localeList =& eZLocale::localeList( true );
        if ( $localeList === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => &$localeList );
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

    function &fetchClass( $classID )
    {
        include_once( 'kernel/classes/ezcontentclass.php' );
        $object =& eZContentClass::fetch( $classID );
        if ( $object === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => &$object );
    }

    function &fetchClassAttributeList( $classID, $versionID )
    {
        include_once( 'kernel/classes/ezcontentclass.php' );
        $objectList =& eZContentClass::fetchAttributes( $classID, true, $versionID );
        if ( $objectList === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => &$objectList );
    }

    function &fetchClassAttribute( $attributeID, $versionID )
    {
        include_once( 'kernel/classes/ezcontentclass.php' );
        $attribute =& eZContentClassAttribute::fetch( $attributeID, true, $versionID );
        if ( $attribute === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => &$attribute );
    }

    function &fetchObjectTree( $parentNodeID, $sortBy, $offset, $limit, $depth, $depthOperator, $classID, $attribute_filter, $extended_attribute_filter,$class_filter_type, $class_filter_array )
    {
        $hash = md5( "$parentNodeID, $sortBy, $offset, $limit, $depth, $classID, $attribute_filter, $class_filter_type, $class_filter_array" );
//         print( "fetch list $parentNodeID $hash<br>" );

        include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
        $treeParameters = array( 'Offset' => $offset,
                                 'Limit' => $limit,
                                 'Limitation' => null,
                                 'SortBy' => $sortBy,
                                 'class_id' => $classID,
                                 'AttributeFilter' => $attribute_filter,
                                 'ExtendedAttributeFilter' => $extended_attribute_filter,
                                 'ClassFilterType' => $class_filter_type,
                                 'ClassFilterArray' => $class_filter_array );
        if ( $depth !== false )
        {
            $treeParameters['Depth'] = $depth;
            $treeParameters['DepthOperator'] = $depthOperator;
        }
        $children =& eZContentObjectTreeNode::subTree( $treeParameters,
                                                       $parentNodeID );
        if ( $children === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );

        /// Fill objects with attributes, speed boost
        eZContentObject::fillNodeListAttributes( $children );

        return array( 'result' => &$children );
    }

    function &fetchObjectTreeCount( $parentNodeID, $class_filter_type, $class_filter_array, $attributeFilter, $depth, $depthOperator )
    {
        include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
        $node =& eZContentObjectTreeNode::fetch( $parentNodeID );
        if ( $node === null )
        {
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        }
        $childrenCount =& $node->subTreeCount( array( 'Limitation' => null,
                                                      'ClassFilterType' => $class_filter_type,
                                                      'ClassFilterArray' => $class_filter_array,
                                                      'AttributeFilter' => $attributeFilter,
                                                      'DepthOperator' => $depthOperator,
                                                      'Depth' => $depth ) );
        if ( $childrenCount === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => &$childrenCount );
    }

    function &fetchContentSearch( $searchText, $subTreeArray, $offset, $limit, $searchTimestamp, $publishDate, $sectionID, $classID, $classAttributeID, $sortArray )
    {
        include_once( "kernel/classes/ezsearch.php" );
        $searchArray =& eZSearch::buildSearchArray();
        $parameters = array();
        if ( $classID !== false )
            $parameters['SearchContentClassID'] = $classID;
        if ( $classAttributeID !== false )
            $parameters['SearchContentClassAttributeID'] = $classAttributeID;
        if ( $sectionID !== false )
            $parameters['SearchSectionID'] = $sectionID;
        if ( $publishDate !== false )
            $parameters['SearchDate'] = $publishDate;
        if ( $sortArray !== false )
            $parameters['SortArray'] = $sortArray;
        $parameters['SearchLimit'] = $limit;
        $parameters['SearchOffset'] = $offset;
        if ( $subTreeArray !== false )
            $parameters['SearchSubTreeArray'] = $subTreeArray;
        if ( $searchTimestamp )
            $parameters['SearchTimestamp'] = $searchTimestamp;
        $searchResult =& eZSearch::search( $searchText,
                                           $parameters,
                                           $searchArray );

        return array( 'result' => &$searchResult );
    }

    function fetchTrashObjectCount()
    {
        $trashObjectList = & eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                                  array(), array( 'status' => EZ_CONTENT_OBJECT_STATUS_ARCHIVED ),
                                                                  array(), null,
                                                                  false,false,
                                                                  array( array( 'operation' => 'count( * )',
                                                                                'name' => 'count' ) ) );
        return array( 'result' => $trashObjectList[0]['count'] );
    }

    function fetchTrashObjectList( $offset, $limit )
    {
        $trashObjectList = & eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                                  null, array( 'status' => EZ_CONTENT_OBJECT_STATUS_ARCHIVED ),
                                                                  null, array( 'length' => $limit, 'offset' => $offset ),
                                                                  true );
        return array( 'result' => &$trashObjectList );
    }

    function fetchDraftVersionList( $offset, $limit )
    {
        $userID = eZUser::currentUserID();
        $draftVersionList = & eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                                   null, array(  'creator_id' => $userID,
                                                                                 'status' => EZ_VERSION_STATUS_DRAFT ),
                                                                   null, array( 'length' => $limit, 'offset' => $offset ),
                                                                   true );
        return array( 'result' => &$draftVersionList );

    }

    function fetchDraftVersionCount()
    {
        $userID = eZUser::currentUserID();
        $draftVersionList = & eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                                   array(), array( 'creator_id' => $userID,
                                                                                   'status' => EZ_VERSION_STATUS_DRAFT ),
                                                                   array(), null,
                                                                   false,false,
                                                                   array( array( 'operation' => 'count( * )',
                                                                                 'name' => 'count' ) ) );
        return array( 'result' => $draftVersionList[0]['count'] );
    }

     function fetchPendingList( $offset, $limit )
    {
        $userID = eZUser::currentUserID();
        $pendingList = & eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                                   null, array(  'creator_id' => $userID,
                                                                                 'status' => EZ_VERSION_STATUS_PENDING ),
                                                                   null, array( 'length' => $limit, 'offset' => $offset ),
                                                                   true );
        return array( 'result' => &$pendingList );

    }

    function fetchPendingCount()
    {
        $userID = eZUser::currentUserID();
        $pendingList = & eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                                   array(), array( 'creator_id' => $userID,
                                                                                   'status' => EZ_VERSION_STATUS_PENDING ),
                                                                   array(), null,
                                                                   false,false,
                                                                   array( array( 'operation' => 'count( * )',
                                                                                 'name' => 'count' ) ) );
        return array( 'result' => $pendingList[0]['count'] );
    }


    function fetchVersionList( $contentObject, $offset, $limit )
    {
        $versionList = & eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                              null, array(  'contentobject_id' => $contentObject->attribute("id") ),
                                                                   null, array( 'length' => $limit, 'offset' => $offset ),
                                                                   true );
        return array( 'result' => &$versionList );

    }

    function fetchVersionCount( $contentObject )
    {
        $versionList = & eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                                   array(), array( 'contentobject_id' => $contentObject->attribute("id") ),
                                                                   array(), null,
                                                                   false,false,
                                                                   array( array( 'operation' => 'count( * )',
                                                                                 'name' => 'count' ) ) );
        return array( 'result' => $versionList[0]['count'] );
    }


    function canInstantiateClassList( $groupID, $parentNode )
    {
        if ( is_object( $parentNode ) )
        {
            $contentObject = $parentNode->attribute( 'object' );
            $canInstantiateClassList =& $contentObject->attribute( 'can_create_class_list' );
        }
        else
        {
            $canInstantiateClassList =& eZContentClass::canInstantiateClassList();
        }
        if ( $groupID > 0 )
        {
            include_once( 'kernel/classes/ezcontentclassclassgroup.php' );
            $classesInGroup = eZContentClassClassGroup::fetchClassList( 0, $groupID, false );
            $classIDListInGroup = array();
            foreach ( $classesInGroup as $class )
            {
                $classIDListInGroup[] = $class['id'];
            }
            $canInstantiateClassFilteredList = array();
            foreach ( array_keys( $canInstantiateClassList ) as $key )
            {
                $class =& $canInstantiateClassList[$key];
                if ( in_array( $class['id'], $classIDListInGroup ) )
                {
                    $canInstantiateClassFilteredList[] =& $class;
                }
            }
            return array( 'result' => $canInstantiateClassFilteredList );
        }
        else
        {
            include_once( 'kernel/classes/ezcontentclass.php' );
            return array( 'result' => $canInstantiateClassList );
        }
    }

    function canInstantiateClasses( $parentNode )
    {
        if ( is_object( $parentNode ) )
        {
            $contentObject = $parentNode->attribute( 'object' );
            return array( 'result' => $contentObject->attribute( 'can_create' ) );
        }
        include_once( 'kernel/classes/ezcontentclass.php' );
        return array( 'result' => eZContentClass::canInstantiateClasses() );
    }

    function contentobjectAttributes( &$version, $languageCode )
    {
        if ( $languageCode == '' )
        {
            return array( 'result' => $version->contentObjectAttributes( ) );
        }
        else
        {
            return array( 'result' => $version->contentObjectAttributes( $languageCode ) );
        }
    }

    function fetchBookmarks()
    {
        $user =& eZUser::currentUser();
        include_once( 'kernel/classes/ezcontentbrowsebookmark.php' );
        return array( 'result' => eZContentBrowseBookmark::fetchListForUser( $user->id() ) );
    }

    function fetchRecent()
    {
        $user =& eZUser::currentUser();
        include_once( 'kernel/classes/ezcontentbrowserecent.php' );
        return array( 'result' => eZContentBrowseRecent::fetchListForUser( $user->id() ) );
    }

    function fetchSectionList()
    {
        include_once( 'kernel/classes/ezsection.php' );
        return array( 'result' => eZSection::fetchList() );
    }
}

?>
