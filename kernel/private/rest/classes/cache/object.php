<?php
/**
 * File containing ezpRestCacheStorageClusterObject class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * Cache storage implementation for compatible objects (implementing ezcBaseExportable interface).
 * Will also work with arrays and scalar values (int, float, string, bool)
 */
class ezpRestCacheStorageClusterObject extends ezpRestCacheStorageCluster
{
    /**
     * Fetch data from the cache.
     *
     * This method fetches the desired data from the file with $filename from
     * disk. This implementation uses an include statement for fetching. The
     * return value depends on the stored data and might either be an object
     * implementing {@link ezcBaseExportable}, an array or a scalar value.
     *
     * @param string $filename
     * @return mixed
     */
    protected function fetchData( $filename )
    {
        return ( include $filename );
    }

    /**
     * Serialize the data for storing.
     *
     * Serializes the given $data to a executable PHP code representation
     * string. This works with objects implementing {@link ezcBaseExportable},
     * arrays and scalar values (int, bool, float, string). The return value is
     * executable PHP code to be stored to disk. The data can be unserialized
     * using the {@link fetchData()} method.
     *
     * @param mixed $data
     * @return string
     *
     * @throws ezcCacheInvalidDataException
     *         if the $data can not be serialized (e.g. an object that does not
     *         implement ezcBaseExportable, a resource, ...).
     */
    protected function prepareData( $data )
    {
        if ( ( is_object( $data ) && !( $data instanceof ezcBaseExportable ) )
             || is_resource( $data ) )
        {
            throw new ezcCacheInvalidDataException( gettype( $data ), array( 'scalar', 'array', 'ezcBaseExportable' ) );
        }
        return "<?php\nreturn " . var_export( $data, true ) . ";\n?>\n";
    }
}
?>
