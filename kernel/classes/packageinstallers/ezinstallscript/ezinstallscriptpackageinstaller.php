<?php
/**
 * File containing the eZInstallScriptPackageInstaller class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \ingroup package
  \class eZInstallScriptPackageInstaller ezcontentclasspackageinstaller.php
*/

class eZInstallScriptPackageInstaller extends eZPackageInstallationHandler
{
     /*
      Constructor should be implemented in the child class
        and call the constructor of eZPackageInstallationHandler.
     */
    function eZInstallScriptPackageInstaller( $package, $type, $installItem )
    {
    }
    /*!
     Returns \c 'stable', content class packages are always stable.
    */
    function packageInitialState( $package, &$persistentData )
    {
        return 'stable';
    }

    function customInstallHandlerInfo( $package, $installItem )
    {
        $return = array();

        $itemPath = $package->path() . '/' . $installItem['sub-directory'];
        $xmlPath = $itemPath . '/' . $installItem['filename'] . '.xml';

        $dom = $package->fetchDOMFromFile( $xmlPath );
        if ( $dom )
        {
            $mainNode = $dom->documentElement;
            $return['file-path'] = $itemPath . '/' . $mainNode->getAttribute( 'filename' );
            $return['classname'] = $mainNode->getAttribute( 'classname' );
        }

        return $return;
    }

    function stepTemplate( $package, $installItem, $step )
    {
        $itemPath = $package->path() . '/' . $installItem['sub-directory'];
        $stepTemplatePath = $itemPath . '/templates';

        return array( 'name' => $step['template'],
                      'path' => $stepTemplatePath );
    }
}
?>
