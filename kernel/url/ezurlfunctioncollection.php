<?php
/**
 * File containing the eZURLFunctionCollection class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZURLFunctionCollection ezurlfunctioncollection.php
  \brief The class eZURLFunctionCollection does

*/

class eZURLFunctionCollection
{
    function fetchList( $isValid, $offset, $limit, $onlyPublished )
    {
        $parameters = array( 'is_valid' => $isValid,
                             'offset' => $offset,
                             'limit' => $limit,
                             'only_published' => $onlyPublished );
        $list = eZURL::fetchList( $parameters );
        if ( $list === null )
            $result = array( 'error' => array( 'error_type' => 'kernel',
                                               'error_code' => eZError::KERNEL_NOT_FOUND ) );
        else
            $result = array( 'result' => $list );
        return $result;
    }

    function fetchListCount( $isValid, $onlyPublished )
    {
        $parameters = array( 'is_valid' => $isValid,
                             'only_published' => $onlyPublished );
        $listCount = eZURL::fetchListCount( $parameters );
        if ( $listCount === null )
            $result = array( 'error' => array( 'error_type' => 'kernel',
                                               'error_code' => eZError::KERNEL_NOT_FOUND ) );
        else
            $result = array( 'result' => $listCount );
        return $result;
    }

}

?>
