<?php
//
// Definition of eZInstallScriptPackageInstaller class
//
// Created on: <16-Feb-2006 12:39:59 ks>
//
// Copyright (C) 1999-2006 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezinstallscriptpackageinstaller.php
*/

/*!
  \ingroup package
  \class eZInstallScriptPackageInstaller ezcontentclasspackageinstaller.php
*/

include_once( 'kernel/classes/ezpackageinstallationhandler.php' );

class eZInstallScriptPackageInstaller extends eZPackageInstallationHandler
{
     /*
      Constructor should be implemented in the child class
        and call the constructor of eZPackageInstallationHandler.
     */
    function eZInstallScriptPackageInstaller( &$package, $type, $installItem )
    {
    }
    /*!
     \reimp
     Returns \c 'stable', content class packages are always stable.
    */
    function packageInitialState( &$package, &$persistentData )
    {
        return 'stable';
    }

    function customInstallHandlerInfo( $package, $installItem )
    {
        $return = array();

        $itemPath = $package->path() . '/' . $installItem['sub-directory'];
        $xmlPath = $itemPath . '/' . $installItem['filename'] . '.xml';

        $dom =& $package->fetchDOMFromFile( $xmlPath );
        if ( $dom )
            $mainNode =& $dom->root();

        $return['file-path'] = $itemPath . '/' . $mainNode->getAttribute( 'filename' );
        $return['classname'] = $mainNode->getAttribute( 'classname' );

        return $return;
    }

    function stepTemplate( $package, $installItem, $step )
    {
        $itemPath = $package->path() . '/' . $installItem['sub-directory'];
        $stepTemplatePath = $itemPath . '/step-templates';

        return array( 'name' => $step['template'],
                      'path' => $stepTemplatePath );
    }
}
?>
