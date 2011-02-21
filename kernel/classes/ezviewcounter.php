<?php
/**
 * File containing the eZViewCounter class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

class eZViewCounter extends eZPersistentObject
{
    function eZViewCounter( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( "fields" => array( "node_id" => array( 'name' => "NodeID",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true,
                                                             'foreign_class' => 'eZContentObjectTreeNode',
                                                             'foreign_attribute' => 'node_id',
                                                             'multiplicity' => '1..*' ),
                                         "count" => array( 'name' => "Count",
                                                           'datatype' => 'integer',
                                                           'default' => 0,
                                                           'required' => true ) ),
                      "keys" => array( "node_id" ),
                      'relations' => array( 'node_id' => array( 'class' => 'eZContentObjectTreeNode',
                                                                'field' => 'node_id' ) ),
                      "class_name" => "eZViewCounter",
                      "sort" => array( "count" => "desc" ),
                      "name" => "ezview_counter" );
    }

    static function create( $node_id )
    {
        $row = array("node_id" => $node_id,
                     "count" => 0 );
        return new eZViewCounter( $row );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    public static function removeCounter( $node_id )
    {
        eZPersistentObject::removeObject( eZViewCounter::definition(),
                                          array("node_id" => $node_id ) );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function clear( $node_id )
    {
        $counter = eZViewCounter::fetch( $node_id );
        if ( $counter != null )
        {
            $counter->setAttribute( 'count', 0 );
            $counter->store();
        }
    }

    /**
     * Increase the counter.
     *
     * @param int $count Number of times to increase the counter, by default: 1.
     */
    public function increase( $count = 1 )
    {
        // The attribute is naively incremented, despite possible updates in the DB.
        $this->setAttribute( 'count', $this->attribute( 'count' ) + $count );
        // However, we are not using ->store() here so that we atomatically update
        // the value of the counter in case it has been updated in parallel.
        eZDB::instance()->query(
            "UPDATE ezview_counter " .
            "SET count = count + " . (int)$count . " " .
            "WHERE node_id=" . $this->attribute( "node_id" )
        );
    }

    static function fetch( $node_id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZViewCounter::definition(),
                                                null,
                                                array("node_id" => $node_id ),
                                                $asObject );
    }

    static function fetchTopList( $classID = false, $sectionID = false, $offset = false, $limit = false )
    {
        if ( !$classID && !$sectionID )
        {

            return  eZPersistentObject::fetchObjectList( eZViewCounter::definition(),
                                                         null,
                                                         null,
                                                         null,
                                                         array( 'length' => $limit, 'offset' => $offset ),
                                                         false );
        }

        $queryPart = "";
        if ( $classID != false )
        {
            $classID = (int)$classID;
            $queryPart .= "ezcontentobject.contentclass_id=$classID AND ";
        }

        if ( $sectionID != false )
        {
            $sectionID = (int)$sectionID;
            $queryPart .= "ezcontentobject.section_id=$sectionID AND ";
        }

        $db = eZDB::instance();
        $query = "SELECT ezview_counter.*
                  FROM
                         ezcontentobject_tree,
                         ezcontentobject,
                         ezview_counter
                  WHERE
                         ezview_counter.node_id=ezcontentobject_tree.node_id AND
                         $queryPart
                         ezcontentobject_tree.contentobject_id=ezcontentobject.id
                  ORDER BY ezview_counter.count DESC";

        if ( !$offset && !$limit )
        {
            $countListArray = $db->arrayQuery( $query );
        }
        else
        {
            $countListArray = $db->arrayQuery( $query, array( "offset" => $offset,
                                                               "limit" => $limit ) );
        }
        return $countListArray;
    }

    /// \privatesection
    public $NodeID;
    public $Count;
}

?>
