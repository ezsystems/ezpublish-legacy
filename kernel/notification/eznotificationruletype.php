<?php
//
// Definition of eZNotificationRuleType class
//
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
  \class eZNotificationRuleType eznotificationrule.php
  \ingroup eZNotification
  \brief Base class for ez notificaiton rules
*/

include_once( "kernel/classes/ezpersistentobject.php" );

class eZNotificationRuleType
{
    /*!
     Initializes the ruletype with the string id \a $notificationRuleString and
     the name \a $name.
    */
    function eZNotificationRuleType( $notificationRuleString, $name )
    {
        $this->NotificationRuleString = $notificationRuleString;
        $this->Name = $name;
        $this->Attributes = array();
        $this->Attributes["information"] = array( "string" => $this->NotificationRuleString,
                                                  "name" => $this->Name );
    }

    /*!
     \static
     Crates a ruletype instance of the ruletype string id \a $notificationRuleString.
     \note It only creates one instance for each datatype.
    */
    function &create( $notificationRuleString )
    {
        $rules =& $GLOBALS["eZNotificationRules"];
        $def = null;
        if ( isset( $rules[$notificationRuleString] ) )
        {
            $className = $rules[$notificationRuleString];
            $def =& $GLOBALS["eZNotificationRuleObjects"][$notificationRuleString];
            if ( get_class( $def ) != $className )
            {
                $def = new $className();
            }
        }
        return $def;
    }

    /*!
     \static
     \return a list of ruletypes which has been registered.
     \note This will instantiate all ruletypes.
    */
    function &registeredRules()
    {
        $rules =& $GLOBALS["eZNotificationRules"];
        $rule_objects =& $GLOBALS["eZNotificationRuleObjects"];
        foreach ( $rules as $notificationRuleString => $className )
        {
            $def =& $rule_objects[$notificationRuleString];
            if ( get_class( $def ) != $className )
                $def = new $className();
        }
        return $rule_objects;
    }

    /*!
     \static
     Registers the ruletype with string id \a $notificationRuleString and
     class name \a $className. The class name is used for instantiating
     the class and should be in lowercase letters.
    */
    function register( $notificationRuleString, $className )
    {
        $rules =& $GLOBALS["eZNotificationRules"];
        if ( !is_array( $rules ) )
            $rules = array();
        $rules[$notificationRuleString] = $className;
    }

   /*!
     \return the rule type identification string.
    */
    function isA()
    {
        return $this->Attributes["information"]["string"];
    }

    /*!
     \return the attributes for this ruletype.
    */
    function &attributes()
    {
        return array_keys( $this->Attributes );
    }

    /*!
     \return true if the attribute \a $attr exists in this object.
    */
    function hasAttribute( $attr )
    {
        return isset( $this->Attributes[$attr] );
    }

    /*!
     \return the data for the attribute \a $attr or null if it does not exist.
    */
    function &attribute( $attr )
    {
        if ( isset( $this->Attributes[$attr] ) )
            return $this->Attributes[$attr];
        else
            return null;
    }

    /*!
     Check whether or not the content object \a $contentObject satisfies notification rule \a $notificationRule
     \note Default implementation does nothing.
    */
    function match( &$contentObject, &$notificationRule )
    {
        return false;
    }

    var $NotificationRuleString;
    var $Name;
}

$ini =& eZINI::instance();
$availableRules =& $ini->variableArray( "NotificationRuleSettings", "AvailableRules" );

foreach ( $availableRules as $rule )
{
    $includeFile = "kernel/notification/rules/" . $rule ."rule.php";
    if ( file_exists( $includeFile ) )
    {
        include_once( $includeFile );
    }
    else
    {
        eZDebug::writeError( "Rule type: $includeFile not found " );
    }

}
?>
