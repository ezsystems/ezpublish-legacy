<?php
/**
 * File containing the eZContentObjectRegression class
 *
 * @copyright Copyright (C) 1999-2008 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZContentObjectRegression extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZContentObject Regression Tests" );
    }

    /**
     * Test scenario for issue #13552: Broken datamap caching when copying
     * content objects
     * 
     * This test verifies that the content object attributes are fresh and not
     * cached versions of the original object. We can achieve this by comparing
     * the content object attribute ids, and make sure they are not the same.
     *
     */
    public function testDataMapFreshOnCopyObject()
    {
        $folder = new ezpObject( "folder", 2 );
        $folder->name = __FUNCTION__;
        $folder->short_description = "123";
        $folder->publish();

        $attrObj1 = self::compareObjectAttributeIds( $folder->object );

        $newObject = self::copyObject( $folder->object, 2 );
        $attrObjCopy = self::compareObjectAttributeIds( $newObject );

        $res = array_intersect_assoc( $attrObj1, $attrObjCopy );

        // If the intersection of key-valye pairs in the two arrays are empty,
        // all the attribute ids are different in the two objects, and we have
        // fresh copy
        self::assertTrue( empty( $res ), "The copied object does contain fresh content object attributes" );
    }

    /**
     * Create a copy of an object.
     * 
     * The basis for this method is taken from kernel/content/copy.php
     * 
     * @todo Merge this method into kernel wrapper's object class.
     *
     * @param eZContentObject $object
     * @param int $newParentNodeID 
     * @return eZContentObject
     */
    public static function copyObject( $object, $newParentNodeID )
    {
        $newParentNode = eZContentObjectTreeNode::fetch( $newParentNodeID );

        $db = eZDB::instance();
        $db->begin();
        $newObject = $object->copy( true );
        // We should reset section that will be updated in updateSectionID().
        // If sectionID is 0 than the object has been newly created
        $newObject->setAttribute( 'section_id', 0 );
        $newObject->store();

        $curVersion        = $newObject->attribute( 'current_version' );
        $curVersionObject  = $newObject->attribute( 'current' );
        $newObjAssignments = $curVersionObject->attribute( 'node_assignments' );
        unset( $curVersionObject );

        // remove old node assignments
        foreach( $newObjAssignments as $assignment )
        {
            $assignment->purge();
        }

        // and create a new one
        $nodeAssignment = eZNodeAssignment::create( array(
                                                         'contentobject_id' => $newObject->attribute( 'id' ),
                                                         'contentobject_version' => $curVersion,
                                                         'parent_node' => $newParentNodeID,
                                                         'is_main' => 1
                                                         ) );
        $nodeAssignment->store();

        // publish the newly created object
        eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $newObject->attribute( 'id' ),
                                                                  'version'   => $curVersion ) );
        // Update "is_invisible" attribute for the newly created node.
        $newNode = $newObject->attribute( 'main_node' );
        eZContentObjectTreeNode::updateNodeVisibility( $newNode, $newParentNode );

        $db->commit();
        return $newObject;
    }

    public static function compareObjectAttributeIds( $object )
    {
        $dataMap = $object->dataMap();
        $attrIdMap = array();

        foreach( $dataMap as $attr )
        {
            $attrIdMap[$attr->contentClassAttributeIdentifier()] = $attr->attribute( 'id' );
        }
        return $attrIdMap;
    }
}

?>