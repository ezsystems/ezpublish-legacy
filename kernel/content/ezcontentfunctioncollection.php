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

    function &fetchObjectTree( $parentNodeID, $sortBy, $offset, $limit, $depth, $depthOperator,
                               $classID, $attribute_filter, $extended_attribute_filter, $class_filter_type, $class_filter_array,
                               $groupBy, $mainNodeOnly, $asObject )
    {
        include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
        $treeParameters = array( 'Offset' => $offset,
                                 'Limit' => $limit,
                                 'Limitation' => null,
                                 'SortBy' => $sortBy,
                                 'class_id' => $classID,
                                 'AttributeFilter' => $attribute_filter,
                                 'ExtendedAttributeFilter' => $extended_attribute_filter,
                                 'ClassFilterType' => $class_filter_type,
                                 'ClassFilterArray' => $class_filter_array,
                                 'MainNodeOnly' => $mainNodeOnly );
        if ( is_array( $groupBy ) )
        {
            $groupByHash = array( 'field' => $groupBy[0],
                                  'type' => false );
            if ( isset( $groupBy[1] ) )
                $groupByHash['type'] = $groupBy[1];
            $treeParameters['GroupBy'] = $groupByHash;
        }
        if ( $asObject !== null )
            $treeParameters['AsObject'] = $asObject;
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

        if ( $asObject === null or $asObject )
            eZContentObject::fillNodeListAttributes( $children );

        return array( 'result' => &$children );
    }

    function &fetchObjectTreeCount( $parentNodeID, $class_filter_type, $class_filter_array, $attributeFilter, $depth, $depthOperator, $mainNodeOnly )
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
                                                      'Depth' => $depth,
                                                      'MainNodeOnly' => $mainNodeOnly ) );
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

    function fetchTipafriendTopList( $offset, $limit )
    {
        include_once( 'kernel/classes/eztipafriendcounter.php' );
        include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

        $topList = & eZPersistentObject::fetchObjectList( eZTipafriendCounter::definition(),
                                                       null,
                                                       null,
                                                       null,
                                                       array( 'length' => $limit, 'offset' => $offset ),
                                                       true );

        $contentNodeList = array();
        foreach ( array_keys ( $topList ) as $key )
        {
            $nodeID = $topList[$key]->attribute( 'node_id' );
            $contentNode =& eZContentObjectTreeNode::fetch( $nodeID );
            if ( $contentNode === null )
                return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
            $contentNodeList[] = $contentNode;
        }
        return array( 'result' => $contentNodeList );
    }

    function fetchMostViewedTopList( $classID, $sectionID, $offset, $limit )
    {
        include_once( 'kernel/classes/ezviewcounter.php' );
        include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

        $topList =& eZViewCounter::fetchTopList( $classID, $sectionID, $offset, $limit );
        $contentNodeList = array();
        foreach ( array_keys ( $topList ) as $key )
        {
            $nodeID = $topList[$key]['node_id'];
            $contentNode =& eZContentObjectTreeNode::fetch( $nodeID );
            if ( $contentNode === null )
                return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
            $contentNodeList[] = $contentNode;
        }
        return array( 'result' => $contentNodeList );
    }

    function fetchCollectedInfoCount( $objectAttributeID, $objectID, $value )
    {
        include_once( 'kernel/classes/ezinformationcollection.php' );
        if ( $objectAttributeID )
            $count = eZInformationCollection::fetchCountForAttribute( $objectAttributeID, $value );
        else if ( $objectID )
            $count = eZInformationCollection::fetchCountForObject( $objectID, $value );
        else
            $count = 0;
        return array( 'result' => $count );
    }

    function fetchCollectedInfoCountList( $objectAttributeID )
    {
        include_once( 'kernel/classes/ezinformationcollection.php' );
        $count = eZInformationCollection::fetchCountList( $objectAttributeID );
        return array( 'result' => $count );
    }

    function fetchCollectedInfoCollection( $collectionID, $contentObjectID )
    {
        include_once( 'kernel/classes/ezinformationcollection.php' );
        $collection = false;
        if ( $collectionID )
            $collection =& eZInformationCollection::fetch( $collectionID );
        else if ( $contentObjectID )
        {
            $userIdentifier = eZInformationCollection::currentUserIdentifier();
            $collection =& eZInformationCollection::fetchByUserIdentifier( $userIdentifier, $contentObjectID );
        }
        return array( 'result' => &$collection );
    }

    function &fetchObjectByAttribute( $identifier )
    {
        include_once( 'kernel/classes/ezcontentobjectattribute.php' );
        $contentObjectAttribute =& eZContentObjectAttribute::fetchByIdentifier( $identifier );
        if ( $contentObjectAttribute === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => EZ_ERROR_KERNEL_NOT_FOUND ) );
        return array( 'result' => $contentObjectAttribute->attribute( 'object' ) );
    }

    function &fetchObjectCountByUserID( $classID, $userID )
    {
        include_once( 'kernel/classes/ezcontentobject.php' );
        $objectCount = eZContentObject::fetchObjectCountByUserID( $classID, $userID );
        return array( 'result' => $objectCount );
    }

    function fetchKeywordCount( $alphabet, $classid )
    {
        $limitationList = array();
        $sqlPermissionCheckingString = "";
        $currentUser =& eZUser::currentUser();
        $accessResult = $currentUser->hasAccessTo( 'content', 'read' );
        if ( $accessResult['accessWord'] == 'limited' )
        {
            foreach ( array_keys( $accessResult['policies'] ) as $key )
            {
                $policy =& $accessResult['policies'][$key];
                $limitationList[] =& $policy->attribute( 'limitations' );
            }
        }

        if ( count( $limitationList ) > 0 )
        {
            $sqlParts = array();
            foreach( $limitationList as $limitationArray )
            {
                $sqlPartPart = array();
                $hasNodeLimitation = false;
                foreach ( $limitationArray as $limitation )
                {
                    if ( $limitation->attribute( 'identifier' ) == 'Class' )
                    {
                        $sqlPartPart[] = 'ezcontentobject.contentclass_id in (' . $limitation->attribute( 'values_as_string' ) . ')';
                    }
                    elseif ( $limitation->attribute( 'identifier' ) == 'Section' )
                    {
                        $sqlPartPart[] = 'ezcontentobject.section_id in (' . $limitation->attribute( 'values_as_string' ) . ')';
                    }
                    elseif( $limitation->attribute( 'identifier' ) == 'Owner' )
                    {
                        eZDebug::writeWarning( $limitation, 'System is not configured to check Assigned in objects' );
                    }
                    elseif( $limitation->attribute( 'identifier' ) == 'Node' )
                    {
                        $sqlPartPart[] = 'ezcontentobject_tree.node_id in (' . $limitation->attribute( 'values_as_string' ) . ')';
                        $hasNodeLimitation = true;
                    }
                    elseif( $limitation->attribute( 'identifier' ) == 'Subtree' )
                    {
                        $pathArray = split( ',', $limitation->attribute( 'values_as_string' ) );
                        $sqlPartPartPart = array();
                        foreach ( $pathArray as $limitationPathString )
                        {
                            $sqlPartPartPart[] = "ezcontentobject_tree.path_string like '$limitationPathString%'";
                        }
                        $sqlPartPart[] = implode( ' OR ', $sqlPartPartPart );
                    }
                }
                if ( $hasNodeLimitation )
                    $sqlParts[] = implode( ' OR ', $sqlPartPart );
                else
                    $sqlParts[] = implode( ' AND ', $sqlPartPart );
            }
            $sqlPermissionCheckingString = ' AND ((' . implode( ') or (', $sqlParts ) . ')) ';
        }

        include_once( 'lib/ezdb/classes/ezdb.php' );
        $db =& eZDB::instance();

        if ( $classid != null )
        {
            $query = "SELECT count(*) AS count
                      FROM ezkeyword, ezkeyword_attribute_link,ezcontentobject_tree,ezcontentobject,ezcontentclass, ezcontentobject_attribute
                      WHERE ezkeyword.keyword LIKE '$alphabet%'
                      $sqlPermissionCheckingString
                      AND ezkeyword.class_id='$classid'
                      AND ezcontentclass.version=0
                      AND ezcontentobject.status=".EZ_CONTENT_OBJECT_STATUS_PUBLISHED."
                      AND ezcontentobject_attribute.version=ezcontentobject.current_version
                      AND ezcontentobject_tree.main_node_id=ezcontentobject_tree.node_id
                      AND ezcontentobject_attribute.contentobject_id=ezcontentobject.id
                      AND ezcontentobject_tree.contentobject_id = ezcontentobject.id
                      AND ezcontentclass.id = ezcontentobject.contentclass_id
                      AND ezcontentobject_attribute.id=ezkeyword_attribute_link.objectattribute_id
                      AND ezkeyword_attribute_link.keyword_id = ezkeyword.id";
        }
        else
        {
            $query = "SELECT count(*) AS count
                      FROM ezkeyword, ezkeyword_attribute_link,ezcontentobject_tree,ezcontentobject,ezcontentclass, ezcontentobject_attribute
                      WHERE ezkeyword.keyword LIKE '$alphabet%'
                      $sqlPermissionCheckingString
                      AND ezcontentclass.version=0
                      AND ezcontentobject.status=".EZ_CONTENT_OBJECT_STATUS_PUBLISHED."
                      AND ezcontentobject_attribute.version=ezcontentobject.current_version
                      AND ezcontentobject_tree.main_node_id=ezcontentobject_tree.node_id
                      AND ezcontentobject_attribute.contentobject_id=ezcontentobject.id
                      AND ezcontentobject_tree.contentobject_id = ezcontentobject.id
                      AND ezcontentclass.id = ezcontentobject.contentclass_id
                      AND ezcontentobject_attribute.id=ezkeyword_attribute_link.objectattribute_id
                      AND ezkeyword_attribute_link.keyword_id = ezkeyword.id";
        }

        $keyWords =& $db->arrayQuery( $query );

        return array( 'result' => $keyWords[0]['count'] );
    }

    function fetchKeyword( $alphabet, $classid, $offset, $limit )
    {
        $limitationList = array();
        $sqlPermissionCheckingString = "";
        $currentUser =& eZUser::currentUser();
        $accessResult = $currentUser->hasAccessTo( 'content', 'read' );
        if ( $accessResult['accessWord'] == 'limited' )
        {
            foreach ( array_keys( $accessResult['policies'] ) as $key )
            {
                $policy =& $accessResult['policies'][$key];
                $limitationList[] =& $policy->attribute( 'limitations' );
            }
        }

        if ( count( $limitationList ) > 0 )
        {
            $sqlParts = array();
            foreach( $limitationList as $limitationArray )
            {
                $sqlPartPart = array();
                $hasNodeLimitation = false;
                foreach ( $limitationArray as $limitation )
                {
                    if ( $limitation->attribute( 'identifier' ) == 'Class' )
                    {
                        $sqlPartPart[] = 'ezcontentobject.contentclass_id in (' . $limitation->attribute( 'values_as_string' ) . ')';
                    }
                    elseif ( $limitation->attribute( 'identifier' ) == 'Section' )
                    {
                        $sqlPartPart[] = 'ezcontentobject.section_id in (' . $limitation->attribute( 'values_as_string' ) . ')';
                    }
                    elseif( $limitation->attribute( 'identifier' ) == 'Owner' )
                    {
                        eZDebug::writeWarning( $limitation, 'System is not configured to check Assigned in objects' );
                    }
                    elseif( $limitation->attribute( 'identifier' ) == 'Node' )
                    {
                        $sqlPartPart[] = 'ezcontentobject_tree.node_id in (' . $limitation->attribute( 'values_as_string' ) . ')';
                        $hasNodeLimitation = true;
                    }
                    elseif( $limitation->attribute( 'identifier' ) == 'Subtree' )
                    {
                        $pathArray = split( ',', $limitation->attribute( 'values_as_string' ) );
                        $sqlPartPartPart = array();
                        foreach ( $pathArray as $limitationPathString )
                        {
                            $sqlPartPartPart[] = "ezcontentobject_tree.path_string like '$limitationPathString%'";
                        }
                        $sqlPartPart[] = implode( ' OR ', $sqlPartPartPart );
                    }
                }
                if ( $hasNodeLimitation )
                    $sqlParts[] = implode( ' OR ', $sqlPartPart );
                else
                    $sqlParts[] = implode( ' AND ', $sqlPartPart );
            }
            $sqlPermissionCheckingString = ' AND ((' . implode( ') or (', $sqlParts ) . ')) ';
        }

        $db_params = array();
        $db_params["offset"] = $offset;
        $db_params["limit"] = $limit;

        $keywordNodeArray = array();
        $lastKeyword = "";

        include_once( 'lib/ezdb/classes/ezdb.php' );
        $db =& eZDB::instance();

        if ( $classid != null )
        {
            $query = "SELECT ezkeyword.keyword,ezcontentobject_tree.node_id
                      FROM ezkeyword, ezkeyword_attribute_link,ezcontentobject_tree,ezcontentobject,ezcontentclass, ezcontentobject_attribute
                      WHERE ezkeyword.keyword LIKE '$alphabet%'
                      $sqlPermissionCheckingString
                      AND ezkeyword.class_id='$classid'
                      AND ezcontentclass.version=0
                      AND ezcontentobject.status=".EZ_CONTENT_OBJECT_STATUS_PUBLISHED."
                      AND ezcontentobject_attribute.version=ezcontentobject.current_version
                      AND ezcontentobject_tree.main_node_id=ezcontentobject_tree.node_id
                      AND ezcontentobject_attribute.contentobject_id=ezcontentobject.id
                      AND ezcontentobject_tree.contentobject_id = ezcontentobject.id
                      AND ezcontentclass.id = ezcontentobject.contentclass_id
                      AND ezcontentobject_attribute.id=ezkeyword_attribute_link.objectattribute_id
                      AND ezkeyword_attribute_link.keyword_id = ezkeyword.id ORDER BY ezkeyword.keyword ASC";
        }
        else
        {
            $query = "SELECT ezkeyword.keyword,ezcontentobject_tree.node_id
                      FROM ezkeyword, ezkeyword_attribute_link,ezcontentobject_tree,ezcontentobject,ezcontentclass, ezcontentobject_attribute
                      WHERE ezkeyword.keyword LIKE '$alphabet%'
                      $sqlPermissionCheckingString
                      AND ezcontentclass.version=0
                      AND ezcontentobject.status=".EZ_CONTENT_OBJECT_STATUS_PUBLISHED."
                      AND ezcontentobject_attribute.version=ezcontentobject.current_version
                      AND ezcontentobject_tree.main_node_id=ezcontentobject_tree.node_id
                      AND ezcontentobject_attribute.contentobject_id=ezcontentobject.id
                      AND ezcontentobject_tree.contentobject_id = ezcontentobject.id
                      AND ezcontentclass.id = ezcontentobject.contentclass_id
                      AND ezcontentobject_attribute.id=ezkeyword_attribute_link.objectattribute_id
                      AND ezkeyword_attribute_link.keyword_id = ezkeyword.id ORDER BY ezkeyword.keyword ASC";
        }

        $keyWords =& $db->arrayQuery( $query, $db_params );

        foreach ( array_keys( $keyWords ) as $key )
        {
            $keywordArray =& $keyWords[$key];
            $keyword = $keywordArray['keyword'];
            $nodeID = $keywordArray['node_id'];

            $nodeObject =& eZContentObjectTreeNode::fetch( $nodeID );

            if ( $nodeObject != null )
            {
                if ( strtolower($lastKeyword) == strtolower($keyword) )
                    $keywordNodeArray[] = array( 'keyword' => "", 'link_object' => $nodeObject );
                else
                    $keywordNodeArray[] = array( 'keyword' => $keyword, 'link_object' => $nodeObject );

            }
            $lastKeyword = $keyword;
        }
        return array( 'result' => $keywordNodeArray );
    }

    function fetchSameClassAttributeNodeList( $contentclassattributeID, $value, $datatype )
    {
        if ( $datatype == "int" )
             $type = "data_int";
        else if ( $datatype == "float" )
             $type = "data_float";
        else if ( $datatype == "text" )
             $type = "data_text";
        else
        {
            eZDebug::writeError( "DatatypeString not supported in fetch same_classattribute_node, use int, float or text" );
            return false;
        }
        include_once( 'lib/ezdb/ezdb.php' );
        $db =& eZDB::instance();
        $resultNodeArray = array();
        $nodeList =& $db->arrayQuery( "SELECT ezcontentobject_tree.node_id, ezcontentobject.name, ezcontentobject_tree.parent_node_id
                                            FROM ezcontentobject_tree, ezcontentobject, ezcontentobject_attribute
                                           WHERE ezcontentobject_attribute.$type='$value'
                                             AND ezcontentobject_attribute.contentclassattribute_id='$contentclassattributeID'
                                             AND ezcontentobject_attribute.contentobject_id=ezcontentobject.id
                                             AND ezcontentobject_attribute.version=ezcontentobject.current_version
                                             AND ezcontentobject_tree.contentobject_version=ezcontentobject.current_version
                                             AND ezcontentobject_tree.contentobject_id=ezcontentobject.id
                                        ORDER BY ezcontentobject.name");

        foreach ( array_keys( $nodeList ) as $key )
        {
            $nodeObject =& $nodeList[$key];
            $nodeID = $nodeObject['node_id'];
            $node = eZContentObjectTreeNode::fetch( $nodeID );
            $resultNodeArray[] = $node;
        }
        return array( 'result' => $resultNodeArray );
    }
}

?>
