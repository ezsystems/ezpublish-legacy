<?php
/**
 * File containing the eZWorkflowFunctionCollection class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZWorkflowFunctionCollection ezworkflowfunctioncollection.php
  \brief The class eZWorkflowFunctionCollection does

*/

class eZWorkflowFunctionCollection
{
    /*!
     Constructor
    */
    function eZWorkflowFunctionCollection()
    {
    }


    function fetchWorkflowStatuses()
    {
        return array( 'result' => eZWorkflow::statusNameMap() );
    }

    function fetchWorkflowTypeStatuses()
    {
        return array( 'result' => eZWorkflowType::statusNameMap() );
    }

}

?>
