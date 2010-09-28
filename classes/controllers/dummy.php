<?php
/**
 * File containing dummy test controller.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

class ezpRestDummyController extends ezcMvcController
{
    public function doFoo()
    {
        // Nada
        $res = new ezcMvcResult;
        $res->variables['content'] = "This is the dummy protected content.";
        return $res;
    }
}
?>