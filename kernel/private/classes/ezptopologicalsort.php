<?php
/**
 * File containing the ezpTopologicalSort class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * Sorts a series of dependencies in linear order (topological sort).
 */
class ezpTopologicalSort
{
    protected $nodes = array();

    /**
     * Create a topological sort.
     *
     * <code>
     * <?php
     * $topologicalSort = new ezpTopologicalSort(
     *     array(
     *         'a' => null,             // a has no dependencies
     *         'b' => 'a',              // b depends on a
     *         'd' => 'b',              // d depends on b
     *         'e' => array( 'd', 'c' ) // e depends on d and c
     *     ) );
     * ?>
     * </code>
     *
     * @param array $dependencies Array of dependencies where the keys depends on the values.
     *                            Values can either be a scalar or an array.
     */
    function __construct( $dependencies = array() )
    {
        foreach ( $dependencies as $dependency => $modules ) {
            if (! isset( $this->nodes[$dependency] ) )
                $this->nodes[$dependency] = new ezpTopologicalSortNode( $dependency );
            foreach ( (array) $modules as $module ) {
                if (! isset( $this->nodes[$module] ) )
                    $this->nodes[$module] = new ezpTopologicalSortNode( $module );
                $this->nodes[$module]->registerChild( $this->nodes[$dependency] );
                $this->nodes[$dependency]->registerParent( $this->nodes[$module] );
            }
        }
    }

    /**
     * Performs the topological linear ordering.
     *
     * @return sorted array
     */
    function sort()
    {
        $rootNodes = $this->getRootNodes();

        $sorted = array();
        while ( count( $this->nodes ) > 0 ) {
            // check for circular reference
            if ( count( $rootNodes ) === 0 )
                return false;

            // remove this node from rootNodes and add it to the output
            $current = array_shift( $rootNodes );
            $sorted[] = $current->name;

            // for each of its children queue the new node and remove the original
            while ( $child = $current->popChild() )
            {
                $child->unregisterParent( $this->nodes[$current->name] );
                // if this child has no more parents, add it to the root nodes list
                if ( $child->parentCount() === 0 )
                    $rootNodes[] = $child;
            }

            unset( $this->nodes[$current->name] );
        }
        return $sorted;
    }

    /**
     * Returns a list of node objects that do not have parents.
     *
     * @return array of node objects
     */
    private function getRootNodes()
    {
        $output = array();
        foreach( $this->nodes as $node )
            if (! $node->parentCount() )
                $output[] = $node;
        return $output;
    }
}
?>
