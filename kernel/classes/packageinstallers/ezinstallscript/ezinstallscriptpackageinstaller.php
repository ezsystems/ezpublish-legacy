<?php
/**
 * File containing the eZInstallScriptPackageInstaller class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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
