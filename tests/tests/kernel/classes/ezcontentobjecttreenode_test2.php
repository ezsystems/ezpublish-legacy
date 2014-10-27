<?php
/**
 * File containing the eZContentObjectTreeNodeTest2 class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
 */

class eZContentObjectTreeNodeTest2 extends ezpDatabaseTestCase
{
    public function providerForTestIssue23528()
    {
        return array(
            // integer
            array( 'published',        "                            ( ezcontentobject.published IN (1,3,0,1,0)  ) AND " ),
            array( 'modified',         "                            ( ezcontentobject.modified IN (1,3,0,1,0)  ) AND " ),
            array( 'modified_subnode', "                            ( modified_subnode IN (1,3,0,1,0)  ) AND " ),
            array( 'node_id',          "                            ( ezcontentobject_tree.node_id IN (1,3,0,1,0)  ) AND " ),
            array( 'contentobject_id', "                            ( ezcontentobject_tree.contentobject_id IN (1,3,0,1,0)  ) AND " ),
            array( 'section',          "                            ( ezcontentobject.section_id IN (1,3,0,1,0)  ) AND " ),
            array( 'state',            "                            ( ezcontentobject.id IN (SELECT contentobject_id FROM ezcobj_state_link WHERE contentobject_state_id IN ( 1, 3, 0, 1, 0 )) ) AND " ),
            array( 'depth',            "                            ( depth IN (1,3,0,1,0)  ) AND " ),
            array( 'priority',         "                            ( ezcontentobject_tree.priority IN (1,3,0,1,0)  ) AND " ),
            array( 'owner',            "                            ( ezcontentobject.owner_id IN (1,3,0,1,0)  ) AND " ),
            array( 'visibility',       "                            ( ezcontentobject_tree.is_invisible IN (1,3,0,1,0)  ) AND " ),
            // string
            array( 'path',             "                            ( path_string IN ('1','3','foo','1foo','foo_1')  ) AND " ),
            array( 'class_identifier', "                            ( ezcontentclass.identifier IN ('1','3','foo','1foo','foo_1')  ) AND " ),
            array( 'class_name',       "                            ( ezcontentclass_name.name IN ('1','3','foo','1foo','foo_1')  ) AND " ),
            array( 'name',             "                            ( ezcontentobject_name.name IN ('1','3','foo','1foo','foo_1')  ) AND " ),
        );
    }

    /**
     * eZContentObjectTreeNode::createAttributeFilterSQLStrings() returns
     * invalid 'in'/'not in' SQL statements
     *
     * @link http://issues.ez.no/23528
     * @dataProvider providerForTestIssue23528
     */
    public function testIssue23528( $name, $expected )
    {
        $params = array( 1, '3', 'foo', '1foo', 'foo_1' );
        $attributeFilterParams = array( array( $name, 'in', $params ) );
        $attributeFilter = eZContentObjectTreeNode::createAttributeFilterSQLStrings( $attributeFilterParams );
        $this->assertEquals( $expected, $attributeFilter['where'] );
    }
}
