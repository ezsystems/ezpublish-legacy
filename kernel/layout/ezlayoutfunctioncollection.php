<?php
/**
 * File containing the eZLayoutFunctionCollection class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZLayoutFunctionCollection ezlayoutfunctioncollection.php
  \brief The class eZLayoutFunctionCollection does

*/

class eZLayoutFunctionCollection
{
    /*!
     Constructor
    */
    function eZLayoutFunctionCollection()
    {
    }

    function fetchSitedesignList()
    {
        $contentINI = eZINI::instance( 'content.ini' );
        if ( $contentINI->hasVariable( 'VersionView', 'AvailableSiteDesigns' ) )
        {
            $sitedesignList = $contentINI->variableArray( 'VersionView', 'AvailableSiteDesigns' );
        }
        else if ( $contentINI->hasVariable( 'VersionView', 'AvailableSiteDesignList' ) )
        {
            $sitedesignList = $contentINI->variable( 'VersionView', 'AvailableSiteDesignList' );
        }
        if ( !$sitedesignList )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => eZError::KERNEL_NOT_FOUND ) );
        return array( 'result' => $sitedesignList );
    }

}

?>
