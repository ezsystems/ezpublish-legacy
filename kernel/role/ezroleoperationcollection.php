<?php

//
// Definition of eZUserOperationCollection class
//
// Created on: <27-Apr-2009 13:51:17 rp/ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/* ! \file
 */

/* !
  \class eZRoleOperationCollection ezroleoperationcollection.php
  \brief The class eZRoleOperationCollection does

 */

class eZRoleOperationCollection {
    /* !
      Constructor
     */

    function eZRoleOperationCollection() {
        
    }

    /**
     * Assign role to object
     *
     * @param int $roleID
     * @param string $limitIdent
     * @param string $limitValue
     *
     */
    static public function assignRole($roleID, $limitIdent = '', $limitValue = '') {
        $selectedObjectIDArray = $http->postVariable('SelectedObjectIDArray');
        $role = eZRole::fetch($roleID);

        $db = eZDB::instance();
        $db->begin();
        foreach ($selectedObjectIDArray as $objectID) {
            $role->assignToUser($objectID, $limitIdent, $limitValue);
        }
        // Clear role caches.
        eZRole::expireCache();

        $db->commit();
        if (count($selectedObjectIDArray) > 0) {
            eZContentCacheManager::clearAllContentCache();
        }

        /* Clean up policy cache */
        eZUser::cleanupCache();
    }

}

?>
