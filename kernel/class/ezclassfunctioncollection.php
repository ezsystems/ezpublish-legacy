<?php
/**
 * File containing the eZClassFunctionCollection class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZClassFunctionCollection ezclassfunctioncollection.php
  \brief The class eZClassFunctionCollection does

*/

class eZClassFunctionCollection
{
    /*!
     Constructor
    */
    function eZClassFunctionCollection()
    {
    }

    function fetchClassListByGroups( $groupFilter, $groupFilterType = 'include' )
    {
        $notIn = ( $groupFilterType == 'exclude' );

        if ( is_array( $groupFilter ) && count( $groupFilter ) > 0 )
        {
            $db = eZDB::instance();
            $groupFilter = $db->generateSQLINStatement( $groupFilter, 'ccg.group_id', $notIn );

            $classNameFilter = eZContentClassName::sqlFilter( 'cc' );
            $version = eZContentClass::VERSION_STATUS_DEFINED;

            $sql = "SELECT DISTINCT cc.*, $classNameFilter[nameField] " .
                   "FROM ezcontentclass cc, ezcontentclass_classgroup ccg, $classNameFilter[from] " .
                   "WHERE cc.version = $version" .
                   "      AND cc.id = ccg.contentclass_id" .
                   "      AND $groupFilter" .
                   "      AND $classNameFilter[where] " .
                   "ORDER BY $classNameFilter[nameField] ASC";

            $rows = $db->arrayQuery( $sql );
            $classes = eZPersistentObject::handleRows( $rows, 'eZContentClass', true );
        }
        else
        {
            $classes = eZContentClass::fetchList( eZContentClass::VERSION_STATUS_DEFINED, true, false, array( 'name' => 'asc' ) );
        }

        return array( 'result' => $classes );
    }

    function fetchClassList( $classFilter, $sortBy )
    {
        $sorts = null;
        if ( $sortBy &&
             is_array( $sortBy ) &&
             count( $sortBy ) == 2 &&
             in_array( $sortBy[0], array( 'id', 'name' ) ) )
        {
            $sorts = array( $sortBy[0] => ( $sortBy[1] )? 'asc': 'desc' );
        }
        $contentClassList = array();
        if ( is_array( $classFilter ) and count( $classFilter ) == 0)
        {
            $classFilter = false;
        }
        if ( !is_array( $classFilter ) or
             count( $classFilter ) > 0 )
        {
            $contentClassList = eZContentClass::fetchList( 0, true, false,
                                                            $sorts, null,
                                                            $classFilter );
        }
        if ( $contentClassList === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => eZError::KERNEL_NOT_FOUND ) );
        return array( 'result' => $contentClassList );
    }

    function fetchLatestClassList( $offset, $limit )
    {
        $contentClassList = array();
        $limitData = null;
        if ( $limit )
            $limitData = array( 'offset' => $offset,
                                'length' => $limit );
        $contentClassList = eZContentClass::fetchList( 0, true, false,
                                                        array( 'modified' => 'desc' ), null,
                                                        false, $limitData );
        return array( 'result' => $contentClassList );
    }

    function fetchClassAttributeList( $classID )
    {
        $contentClassAttributeList = array();
        if ( $contentClass = eZContentClass::fetch( $classID ) )
        {
            $contentClassAttributeList = $contentClass->fetchAttributes();
        }
        if ( $contentClassAttributeList === null )
            return array( 'error' => array( 'error_type' => 'kernel',
                                            'error_code' => eZError::KERNEL_NOT_FOUND ) );
        return array( 'result' => $contentClassAttributeList );
    }

    function fetchOverrideTemplateList( $classID )
    {
        $class = eZContentClass::fetch( $classID );
        $classIdentifier = $class->attribute( 'identifier' );

        $result = array ();

        $ini = eZINI::instance();

        $siteAccessArray = $ini->variable('SiteAccessSettings', 'AvailableSiteAccessList' );

        foreach ( $siteAccessArray as $siteAccess )
        {
            $overrides = eZTemplateDesignResource::overrideArray( $siteAccess );

            foreach( $overrides as $override )
            {
                if ( isset( $override['custom_match'] ) )
                {
                    foreach( $override['custom_match'] as $customMatch )
                    {
                        if( isset( $customMatch['conditions']['class_identifier'] ) &&
                            $customMatch['conditions']['class_identifier'] == $classIdentifier )
                        {
                            $result[] = array( 'siteaccess' => $siteAccess,
                                               'block'      => $customMatch['override_name'],
                                               'source'     => $override['template'],
                                               'target'     => $customMatch['match_file'] );
                        }

                        if( isset( $customMatch['conditions']['class'] ) &&
                            $customMatch['conditions']['class'] == $classID )
                        {

                            $result[] = array( 'siteaccess' => $siteAccess,
                                               'block'      => $customMatch['override_name'],
                                               'source'     => $override['template'],
                                               'target'     => $customMatch['match_file'] );
                        }
                    }
                }
            }

        }

        return array( 'result' => $result );
    }

}

?>
