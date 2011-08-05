<?php
/**
 * File containing the ezpTopologicalSortNode class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * Node in a topological sort.
 *
 * @see ezpTopologicalSort
 */
class ezpTopologicalSortNode
{
    public $name;

    protected $children;
    protected $parents;

    /**
     * ezpTopologicalSortNode constructor.
     *
     * @param string $name Name used to define the node
     */
    public function __construct( $name )
    {
        $this->name = $name;
        $this->children = new SplObjectStorage();
        $this->parents = new SplObjectStorage();
    }

    /**
     * Register a child node.
     *
     * @param ezpTopologicalSortNode $childNode Child node to register
     */
    public function registerChild( ezpTopologicalSortNode $childNode )
    {
        if (! $this->children->contains( $childNode ) )
            $this->children->attach( $childNode );
    }

    /**
     * Register a parent node.
     *
     * @param ezpTopologicalSortNode $parentNode Parent node to register
     */
    public function registerParent( ezpTopologicalSortNode $parentNode )
    {
        if (! $this->parents->contains( $parentNode ) )
            $this->parents->attach( $parentNode );
    }

    /**
     * Unregister a parent node.
     *
     * @param ezpTopologicalSortNode $parentNode Parent node to unregister
     */
    public function unregisterParent( ezpTopologicalSortNode $parentNode )
    {
        $this->parents->detach( $parentNode );
    }

    /**
     * Returns the number of registered parents.
     *
     * @return int
     */
    public function parentCount()
    {
        return $this->parents->count();
    }

    /**
     * Pop a child from the list of children.
     *
     * @return ezpTopologicalSortNode|false ezpTopologicalSortNode or false if list is empty
     */
    public function popChild()
    {
        // Rewind if current iterator is not valid
        if (! $this->children->valid() )
            $this->children->rewind();

        // If current iterator is still not valid it means there are no children anymore
        if (! $this->children->valid() )
            return false;

        $child = $this->children->current();
        $this->children->detach( $child );
        return $child;
    }
}
?>
