<?php
//
// Definition of EZStepInstaller class
//
// Created on: <08-Aug-2003 14:46:44 kk>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

/*! \file ezstep_class_definition.php
*/

/*!
  \class EZStepInstaller ezstep_class_definition.ph
  \brief The class EZStepInstaller provide a framework for eZStep installer classes

*/

class eZStepInstaller
{
    /*!
     Default constructor for eZ publish installer classes

    \param template
    \param http object
    \param ini settings object
    \param persistencelist, all previous posted data
    */
    function eZStepInstaller( &$tpl, &$http, &$ini, &$persistenceList )
    {
        $this->Tpl =& $tpl;
        $this->Http =& $http;
        $this->Ini =& $ini;
        $this->PersistenceList =& $persistenceList;
    }

    /*!
     \virtual

     Processespost data from this class.
     \return  true if post data accepted, or false if post data is rejected.
    */
    function processPostData()
    {
    }

    /*!
     \virtual

    Performs test needed by this class.

    This class may access class variables to store data needed for viewing if output failed
    \return true if all tests passed and continue with next default step,
            number of next step if all tests passed and next step is "hard coded",
           false if tests failed
    */
    function init()
    {
    }

    /*!
    \virtual

    Display information and forms needed to pass this step.
    \return result to use in template
    */
    function &display()
    {
        return null;
    }

    function findAppropriateCharset( &$primaryLanguage, &$allLanguages, $canUseUnicode )
    {
        include_once( 'lib/ezi18n/classes/ezcharsetinfo.php' );
        $allCharsets = array();
        for ( $i = 0; $i < count( $allLanguages ); ++$i )
        {
            $language =& $allLanguages[$i];
            $charsets = $language->allowedCharsets();
            foreach ( $charsets as $charset )
            {
                $charset = eZCharsetInfo::realCharsetCode( $charset );
                $allCharsets[] = $charset;
            }
        }
        $allCharsets = array_unique( $allCharsets );
//         eZDebug::writeDebug( $allCharsets, 'allCharsets' );
        $commonCharsets = $allCharsets;
        for ( $i = 0; $i < count( $allLanguages ); ++$i )
        {
            $language =& $allLanguages[$i];
            $charsets = $language->allowedCharsets();
            $realCharsets = array();
            foreach ( $charsets as $charset )
            {
                $charset = eZCharsetInfo::realCharsetCode( $charset );
                $realCharsets[] = $charset;
            }
            $realCharsets = array_unique( $realCharsets );
            $commonCharsets = array_intersect( $commonCharsets, $realCharsets );
        }
        $usableCharsets = array_values( $commonCharsets );
//         eZDebug::writeDebug( $usableCharsets, 'usableCharsets' );
        $charset = false;
        if ( count( $usableCharsets ) > 0 )
        {
            if ( in_array( $primaryLanguage->charset(), $usableCharsets ) )
                $charset = $primaryLanguage->charset();
            else // Pick the first charset
                $charset = $usableCharsets[0];
        }
        else
        {
            if ( $canUseUnicode )
            {
                $charset = eZCharsetInfo::realCharsetCode( 'utf-8' );
            }
//             else
//             {
//                 // Pick preferred primary language
//                 $charset = $primaryLanguage->charset();
//             }
        }
//         eZDebug::writeDebug( $charset, 'charset' );
        return $charset;
    }

    function availableSiteTypes()
    {
        include_once( 'kernel/setup/ezsetuptypes.php' );
        $siteTypes = eZSetupTypes();
        return $siteTypes;
    }

    function extraDataList()
    {
        return array( 'title', 'url', 'database',
                      'access_type', 'access_type_value', 'admin_access_type_value',
                      'existing_database' );
    }

    function chosenSiteTypes()
    {
        include_once( 'kernel/setup/ezsetuptypes.php' );
        $siteTypes = eZSetupTypes();
        $chosenSiteTypes = array();
        $extraList = $this->extraDataList();
        foreach ( $this->PersistenceList['chosen_site_types'] as $siteTypeIdentifier )
        {
            if ( isset( $siteTypes[$siteTypeIdentifier] ) )
            {
                $chosenSiteType = $siteTypes[$siteTypeIdentifier];
                foreach ( $extraList as $extraItem )
                {
                    if ( isset( $this->PersistenceList['site_extra_data_' . $extraItem][$siteTypeIdentifier] ) )
                    {
                        $chosenSiteType[$extraItem] = $this->PersistenceList['site_extra_data_' . $extraItem][$siteTypeIdentifier];
                    }
                }
                $chosenSiteTypes[] = $chosenSiteType;
            }
        }
        return $chosenSiteTypes;
    }

    function selectSiteTypes( $chosenSiteTypes )
    {
        include_once( 'kernel/setup/ezsetuptypes.php' );
        $siteTypes = eZSetupTypes();
        $siteIdentifiers = array();
        $chosenList = array();
        foreach ( $siteTypes as $siteTypeItem )
        {
            $siteIdentifier = $siteTypeItem['identifier'];
            if ( in_array( $siteIdentifier, $chosenSiteTypes ) )
                $chosenList[] = $siteIdentifier;
        }
        if ( count( $chosenList ) == 0 )
        {
            return false;
        }
        $this->PersistenceList['chosen_site_types'] = $chosenList;
        return true;
    }

    function storeSiteTypes( $siteTypes )
    {
        $extraList = $this->extraDataList();
        $siteList = array();
        foreach ( $siteTypes as $siteTypeItem )
        {
            $siteIdentifier = $siteTypeItem['identifier'];
            foreach ( $extraList as $extraItem )
            {
                if ( isset( $siteTypeItem[$extraItem] ) )
                {
                    $this->PersistenceList['site_extra_data_' . $extraItem][$siteIdentifier] = $siteTypeItem[$extraItem];
                }
            }
            $siteList[] = $siteIdentifier;
        }
        $this->PersistenceList['chosen_site_types'] = $siteList;
    }

    function storeExtraSiteData( $siteIdentifier, $dataIdentifier, $value )
    {
        if ( !isset( $this->PersistenceList['site_extra_data_' . $dataIdentifier] ) )
            $this->PersistenceList['site_extra_data_' . $dataIdentifier] = array();
        $this->PersistenceList['site_extra_data_' . $dataIdentifier][$siteIdentifier] = $value;
    }

    function extraData( $dataIdentifier )
    {
        if ( isset( $this->PersistenceList['site_extra_data_' . $dataIdentifier] ) )
            return $this->PersistenceList['site_extra_data_' . $dataIdentifier];
        return false;
    }

    function extraSiteData( $siteIdentifier, $dataIdentifier )
    {
        if ( isset( $this->PersistenceList['site_extra_data_' . $dataIdentifier][$siteIdentifier] ) )
            return $this->PersistenceList['site_extra_data_' . $dataIdentifier][$siteIdentifier];
        return false;
    }

    var $Tpl;
    var $Http;
    var $Ini;
    var $PersistenceList;
}

?>
