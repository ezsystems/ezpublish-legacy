<?php
/**
 * This file is part of the eZ Publish Legacy package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributd with this source code.
 * @version //autogentag//
 */

/**
 * Makes a DFSBackend handler instantiable using the build static method.
 */
interface eZDFSFileHandlerDFSBackendFactoryInterface
{
    public static function build();
}
