<?php
//
// Definition of eZDiscount class
//
// Created on: <04-Nov-2005 12:26:52 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file ezdiscount.php
*/

class eZDiscount
{
    function eZDiscount()
    {
    }

    /*!
     \static
     params = array( 'contentclass_id' => classID,
                     'contentobject_id' => objectID,
                     'section_id' => sectionID );

    */
    static function discountPercent( $user, $params )
    {
        //include_once( 'lib/ezdb/classes/ezdb.php' );
        //include_once( 'kernel/classes/ezuserdiscountrule.php' );
        //include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );

        $bestMatch = 0.0;

        if ( is_object( $user ) )
        {
            $groups = $user->groups();
            $idArray = array_merge( $groups, array( $user->attribute( 'contentobject_id' ) ) );

            // Fetch discount rules for the current user
            $rules = eZUserDiscountRule::fetchByUserIDArray( $idArray );

            if ( count( $rules ) > 0 )
            {
                $db = eZDB::instance();

                $i = 1;
                $subRuleStr = '';
                foreach ( $rules as $rule )
                {
                    $subRuleStr .= $rule->attribute( 'id' );
                    if ( $i < count( $rules ) )
                        $subRuleStr .= ', ';
                    $i++;
                }

                // Fetch the discount sub rules
                $subRules = $db->arrayQuery( "SELECT * FROM
                                       ezdiscountsubrule
                                       WHERE discountrule_id IN ( $subRuleStr )
                                       ORDER BY discount_percent DESC" );

                // Find the best matching discount rule
                foreach ( $subRules as $subRule )
                {
                    if ( $subRule['discount_percent'] > $bestMatch )
                    {
                        // Rule has better discount, see if it matches
                        if ( $subRule['limitation'] == '*' )
                            $bestMatch = $subRule['discount_percent'];
                        else
                        {
                            // Do limitation check
                            $limitationArray = $db->arrayQuery( "SELECT * FROM
                                       ezdiscountsubrule_value
                                       WHERE discountsubrule_id='" . $subRule['id']. "'" );

                            $hasSectionLimitation = false;
                            $hasClassLimitation = false;
                            $hasObjectLimitation = false;
                            $objectMatch = false;
                            $sectionMatch = false;
                            $classMatch = false;
                            foreach ( $limitationArray as $limitation )
                            {
                                if ( $limitation['issection'] == '1' )
                                {
                                    $hasSectionLimitation = true;

                                    if ( isset( $params['section_id'] ) && $params['section_id'] == $limitation['value'] )
                                        $sectionMatch = true;
                                }
                                elseif ( $limitation['issection'] == '2' )
                                {
                                    $hasObjectLimitation = true;

                                    if ( isset( $params['contentobject_id'] ) && $params['contentobject_id'] == $limitation['value'] )
                                        $objectMatch = true;
                                }
                                else
                                {
                                    $hasClassLimitation = true;
                                    if ( isset( $params['contentclass_id'] ) && $params['contentclass_id'] == $limitation['value'] )
                                        $classMatch = true;
                                }
                            }

                            $match = true;
                            if ( ( $hasClassLimitation == true ) and ( $classMatch == false ) )
                                $match = false;

                            if ( ( $hasSectionLimitation == true ) and ( $sectionMatch == false ) )
                                $match = false;

                            if ( ( $hasObjectLimitation == true ) and ( $objectMatch == false ) )
                                $match = false;

                            if ( $match == true  )
                                $bestMatch = $subRule['discount_percent'];
                        }
                    }
                }
            }
        }
        return $bestMatch;
    }
}

?>
