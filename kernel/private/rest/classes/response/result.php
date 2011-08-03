<?php
/**
 * File containing ezpRestResult class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * This struct contains the result data to be returned by eZP REST controllers
 */
class ezpRestMvcResult extends ezcMvcResult implements ezcBaseExportable
{
    public $responseGroups;

    /**
     * Returns a new instance of this class with the data specified by $array.
     *
     * $array contains all the data members of this class in the form:
     * array('member_name'=>value).
     *
     * __set_state makes this class exportable with var_export.
     * var_export() generates code, that calls this method when it
     * is parsed with PHP.
     *
     * @param array(string=>mixed) $array
     * @return ezpRestMvcResult
     */
    public static function __set_state( array $array )
    {
        $obj = new self( $array['status'], $array['date'],
                         $array['generator'], $array['cache'], $array['cookies'],
                         $array['content'], $array['variables'] );

        $obj->responseGroups = $array['responseGroups'];
        return $obj;
    }
}
?>
