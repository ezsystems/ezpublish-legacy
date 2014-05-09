<?php
/**
 * File containing the eZDBTool class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZDBTool ezdbtool.php
  \brief The class eZDBTool does

*/

class eZDBTool
{
    /*!
     \return true if the database does not contain any relation objects.
     \note If db is not specified it will use eZDB::instance()
    */
    static function isEmpty( $db )
    {
        if ( $db === null )
            $db = eZDB::instance();
        $relationTypeMask = $db->supportedRelationTypeMask();
        $count = $db->relationCounts( $relationTypeMask );
        return $count == 0;
    }

    /*!
     Tries to remove all relation types from the database.
     \note If db is not specified it will use eZDB::instance()
    */
    static function cleanup( $db )
    {
        if ( $db === null )
            $db = eZDB::instance();
        $relationTypes = $db->supportedRelationTypes();
        $result = true;
        $defaultRegexp = "#^ez|tmp_notification_rule_s#";
        foreach ( $relationTypes as $relationType )
        {
            $relationItems = $db->relationList( $relationType );
            // This is the default regexp, unless the db driver provides one
            $matchRegexp = null;
            if ( method_exists( $db, 'relationMatchRegexp' ) )
            {
                $matchRegexp = $db->relationMatchRegexp( $relationType );
            }
            if ( $matchRegexp === null )
                $matchRegexp = $defaultRegexp;
            foreach ( $relationItems as $relationItem )
            {
                // skip relations that shouldn't be touched
                if ( $matchRegexp !== false and
                     !preg_match( $matchRegexp, $relationItem ) )
                    continue;

                if ( !$db->removeRelation( $relationItem, $relationType ) )
                {
                    $result = false;
                    break;
                }
            }
            if ( !$result )
                break;
        }
        return $result;
    }
}

?>
