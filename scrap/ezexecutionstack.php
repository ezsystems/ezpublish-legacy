<?php
//
// Definition of eZExecutionStack class
//
// Created on: <04-Jul-2002 13:55:55 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

/*! \file ezexecutionstack.php
*/

/*!
  \class eZExecutionStack ezexecutionstack.php
  \brief Handles advanced URI execution by storing visited executions

*/

define( "EZ_EXECUTION_STACK_NAME", "eZExecutionStack" );

class eZExecutionStack
{
    /*!
     Constructor
    */
    function eZExecutionStack()
    {
        $this->HTTPTool =& eZHTTPTool::instance();
        if ( !$this->HTTPTool->hasSessionVariable( EZ_EXECUTION_STACK_NAME ) )
            $this->HTTPTool->setSessionVariable( EZ_EXECUTION_STACK_NAME, array() );
        $this->HTTPVariable =& $this->HTTPTool->sessionVariable( EZ_EXECUTION_STACK_NAME );
    }

    /*!
     Removes all execution entries.
    */
    function clear()
    {
        $this->HTTPVariable = array();
    }

    /*!
     Pushes the parameters \a $uri, \a $module and \a $function onto to the stack
     making it the current stack item.
     \sa replace
    */
    function push( $uri, $module, $function )
    {
        $count = count( $this->HTTPVariable );
        $this->HTTPVariable[$count] = array( "uri" => $uri,
                                             "module" => $module,
                                             "function" => $function );
    }

    /*!
     Replaces the current stack item with the parameters \a $uri, \a $module and \a $function.
     \sa push
    */
    function replace( $uri, $module, $function )
    {
        $count = count( $this->HTTPVariable );
        if ( $count > 0 )
        {
            $this->HTTPVariable[$count-1] = array( "uri" => $uri,
                                                   "module" => $module,
                                                   "function" => $function );
        }
    }

    /*!
     Inspects the current stack entry by using isEqualTo, if the
     entry does not match \a $module and \a $function it will push
     the paramter values.
    */
    function addEntry( $uri, $module, $function )
    {
        if ( !$this->isEqualTo( $module, $function ) )
            $this->push( $uri, $module, $function );
    }

    /*!
     \return true if the current stack item has the same module and function
     as passed in \a $module and \a $function.
    */
    function isEqualTo( $module, $function )
    {
        $data = $this->peek();
        return ( $data["module"] == $module and
                 $data["function"] == $function );
    }

    /*!
     Pops the current stack item off the stack and returns it,
     returns null if no stack item is available.
     \sa peek
    */
    function &pop( $entry = false )
    {
        $count = count( $this->HTTPVariable );
        $value = null;
        if ( $count > 0 )
        {
            $value = array_pop( $this->HTTPVariable );
        }
        if ( $entry !== false and
             isset( $value[$entry] ) )
            $value = $value[$entry];
        return $value;
    }

    /*!
     \return the current stack item.
     \sa pop
    */
    function peek( $entry = false )
    {
        $count = count( $this->HTTPVariable );
        $value = null;
        if ( $count > 0 )
        {
            $value = $this->HTTPVariable[$count-1];
        }
        if ( $entry !== false and
             isset( $value[$entry] ) )
            $value = $value[$entry];
        return $value;
    }

    /*!
     Returns all the stack entries as an array.
    */
    function entries()
    {
        return $this->HTTPVariable;
    }

    /*!
     \return the number of stack items.
    */
    function entryCount()
    {
        return count( $this->HTTPVariable );
    }

    /*!
     Returns true if there are no execution entries available.
    */
    function isEmpty()
    {
        return $this->entryCount() == 0;
    }

    /*!
     \return the unique instance of the execution stack
    */
    function &instance()
    {
        $instance =& $GLOBALS["eZExecutionStackInstance"];
        if ( get_class( $instance ) != "ezexecutionstack" )
        {
            $instance = new eZExecutionStack();
        }
        return $instance;
    }

    /// \privatesection
    var $HTTPTool;
    var $HTTPVariable;
}

?>
