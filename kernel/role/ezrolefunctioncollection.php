<?php
/**
 * File containing the eZRoleFunctionCollection class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZRoleFunctionCollection ezrolefunctioncollection.php
  \brief The class eZRoleFunctionCollection does

*/

class eZRoleFunctionCollection
{
    function fetchRole( $roleID )
    {
        $role = eZRole::fetch( $roleID );
        return array( 'result' => $role );
    }

}

?>
