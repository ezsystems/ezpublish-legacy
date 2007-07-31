<?php
//
// Definition of eZOption class
//
// Created on: <29-Jul-2004 15:52:24 gv>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
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

/*!
  \class eZMultioption2 ezmultioption2.php
  \ingroup eZDatatype
  \brief Encapsulates multiple options in one datatype.
*/
include_once( "lib/ezxml/classes/ezxml.php" );

class eZMultioption2
{
    /*!
     Initializes with empty multioption2 list.
    */
    function eZMultioption2( $name, $id = 0, $multioptionIDCounter = 0, $optionCounter = 0, $groupID = 0 )
    {
        $this->Name = $name;
        $this->ID = $id;
        $this->GroupID = $groupID;
        $this->Options = array();
        $this->ChildGroupList = array();
        $this->MultiOptionIDCounter = $multioptionIDCounter;
        $this->OptionCounter = $optionCounter;
        $this->GroupIDCounter = $groupID;
        $this->Rules = array();
    }

    function setGroupIDCounter( $groupIDCounter )
    {
        $this->GroupIDCounter = $groupIDCounter;
    }

    function getGroupIDCounter()
    {
        return $this->GroupIDCounter;
    }
    function setMultiOptionIDCounter( $multioptionIDCounter )
    {
        $this->MultiOptionIDCounter = $multioptionIDCounter;
    }

    function getMultiOptionIDCounter()
    {
        return $this->MultiOptionIDCounter;
    }
    function setOptionCounter( $optionCounter )
    {
        $this->OptionCounter = $optionCounter;
    }

    function getOptionCounter()
    {
        return $this->OptionCounter;
    }

    function addChildGroup( &$group, $multioptionID = false )
    {
        if ( $group->GroupID == 0 )
        {
            $this->GroupIDCounter++;
            $group->GroupIDCounter = $this->GroupIDCounter;
            $group->GroupID = $this->GroupIDCounter;
        }
        else
        {
            $this->initCounters( $group );
        }
        if ( $multioptionID === false )
            $this->ChildGroupList[] =& $group;
        else
        {
            $option =& $this->Options[$multioptionID];
            $option['child_group'] =& $group;
        }
        return count( $this->ChildGroupList );
    }

    /*!
      Adds an Multioption named \a $name
      \param $name contains the name of multioption.
      \param $multiOptionPriority is stored for displaying the array in order.
      \param $defaultValue is stored to display the options by default.
      \return The ID of the multioption that was added.
    */
    function addMultiOption( $name, $multiOptionPriority, $defaultValue, $multiOptionID )
    {
        if ( strlen( $multiOptionID ) == 0 )
        {
            $this->MultiOptionIDCounter++;
            $multiOptionID = $this->MultiOptionIDCounter;
        }
        else
        {
            if ( $this->MultiOptionIDCounter < $multiOptionID )
            {
                $this->MultiOptionIDCounter = $multiOptionID;
            }
        }

        $this->Options[] = array( "id" => count( $this->Options ),
                                  "multioption_id" => $multiOptionID,
                                  "name" => $name,
                                  'priority'=> $multiOptionPriority,
                                  "default_option_id" => $defaultValue,
                                  'imageoption' => false,
                                  'optionlist' => array() );
        return count( $this->Options ) - 1;
    }

    /*!
      Adds an Option to multioption \a $name
      \param $newID is the element key value to which the new option will be added.
      \param $optionValue is the original value to display for users.
      \param $optionAdditionalPrice is a price value that is used to store price of the option values.
    */
    function addOption( $newID, $OptionID, $optionValue, $optionAdditionalPrice, $isSelectable = 1, $objectID = 0 )
    {
        $key = count( $this->Options[$newID]['optionlist'] ) + 1;
        if ( strlen( $OptionID ) == 0 )
        {
            $this->OptionCounter += 1;
            $OptionID = $this->OptionCounter;
        }
        else if ( $OptionID > $this->OptionCounter )
        {
            $this->OptionCounter = $OptionID;
        }
        $this->Options[$newID]['optionlist'][] = array( "id" => $key,
                                                        "option_id" => $OptionID,
                                                        "value" => $optionValue,
                                                        'additional_price' => $optionAdditionalPrice,
                                                        'is_selectable' => $isSelectable );
        if ( $objectID )
        {
            $this->Options[$newID]['optionlist'][count( $this->Options[$newID]['optionlist'])-1]['object'] = $objectID;
            $this->Options[$newID]['imageoption'] = 1;

        }
        return $OptionID;
    }

    function addOptionForMultioptionID( $multioptionID, $OptionID, $optionValue, $optionAdditionalPrice )
    {
        $searchResult = $this->findMultiOption( $multioptionID );
        if ( !$searchResult )
            return null;
        $group =& $searchResult['group'];
        $this->initCounters( $group );
        return $group->addOption( $searchResult['id'], $OptionID, $optionValue, $optionAdditionalPrice );
    }

    function setObjectForOption( $multioptionID, $optionID, $objectID )
    {
        $searchResult = $this->findMultiOption( $multioptionID );
        if ( !$searchResult )
            return null;
        $group =& $searchResult['group'];

        if ( isset( $group->Options[$searchResult['id']]['optionlist'][$optionID] ) )
        {
            $group->Options[$searchResult['id']]['optionlist'][$optionID]['object'] = $objectID;
            $group->Options[$searchResult['id']]['imageoption'] = 1;
        }
    }
    function removeObjectFromOption( $multioptionID, $optionID )
    {
        $searchResult = $this->findMultiOption( $multioptionID );
        if ( !$searchResult )
            return null;
        $group =& $searchResult['group'];

        if ( isset( $group->Options[$searchResult['id']]['optionlist'][$optionID]['object'] ) )
        {
            unset( $group->Options[$searchResult['id']]['optionlist'][$optionID]['object'] );
            $imageoption = 0;
            foreach ( $group->Options[$searchResult['id']]['optionlist'] as $option )
            {
                $imageoption = isset( $option['object'] ) ? 1 : 0;
            }
            $group->Options[$searchResult['id']]['imageoption'] = $imageoption;
        }
    }

    function &findGroup( $groupID, $depth = 0, $groupStack = array() )
    {
        $groupStack[] = array( $this->attribute( 'group_id' ), $depth );
        if ( $depth > 15 )
        {
            var_dump( $groupStack );
            die( "depth=$depth groupID=$groupID" );
        }
        foreach ( $this->Options as $key => $option )
        {
            if ( isset( $option['child_group'] ) )
            {
                if ( $option['child_group']->attribute( "group_id") == $groupID )
                    return $this->Options[$key]['child_group'];
                else
                {
                    if ( $group =& $this->Options[$key]['child_group']->findGroup( $groupID, $depth + 1, $groupStack ) )
                        return $group;
                }
            }
        }

        foreach ( array_keys( $this->ChildGroupList ) as $key  )
        {
            if ( $this->ChildGroupList[$key]->attribute( 'group_id' ) == $groupID )
            {
                return $this->ChildGroupList[$key];
            }

            if ( $group =& $this->ChildGroupList[$key]->findGroup( $groupID, $depth + 1, $groupStack ) )
                return $group;
        }
        $dummyGroup = null;
        return $dummyGroup;
    }

    function findMultiOption( $multioptionID, $depth = 0 )
    {
        foreach ( $this->Options as $key => $optionList )
        {
            if ( $optionList['multioption_id'] == $multioptionID )
            {
                $option = $this->Options[$key];
                return array( "group" => &$this,
                              "id" => $option['id'] );
            }
            if ( isset( $optionList['child_group'] ) )
            {
                $group =& $this->Options[$key]['child_group'];
                $returnArray = $group->findMultiOption( $multioptionID,$depth+1 );
                if ( $returnArray )
                {
                    return $returnArray;
                }
            }
        }

        foreach ( array_keys( $this->ChildGroupList ) as $key  )
        {
            $returnArray =$this->ChildGroupList[$key]->findMultiOption( $multioptionID,$depth+1 );
            if ( $returnArray )
            {
                return $returnArray;
            }
        }
        return null;
    }

    function findOption( $multioption, $optionID )
    {
        if ( $multioption )
        {
            $optionFound = false;
            foreach( $multioption['optionlist'] as $option )
            {
                if ( $option['option_id'] == $optionID )
                {
                    $option['multioption_name'] = $multioption['name'];
                    $optionFound = $option;
                    break;
                }
            }
            return $optionFound;
        }

        foreach ( $this->Options as $key => $optionList )
        {
            if( $option = $this->findOption( $optionList, $optionID ) )
            {
                return $option;
            }
            else if ( array_key_exists( 'child_group',  $optionList ) && $optionList['child_group'] )
            {
                if ( $option = $optionList['child_group']->findOption( false, $optionID ) )
                     return $option;
            }
        }

        foreach ( array_keys( $this->ChildGroupList ) as $key  )
        {
            if ( $option = $this->ChildGroupList[$key]->findOption( false, $optionID ) )
                    return $option;
        }
        return false;
    }

    function runFunctionForAllGroups( $func, $params )
    {
        foreach ( $this->Options as $key => $optionList )
        {
            if ( isset( $optionList['child_group'] ) )
            {
                $optionList['child_group']->runFunctionForAllGroups( $func, $params );
                $optionList['child_group']->$func( $params );
            }
        }
        foreach ( array_keys( $this->ChildGroupList ) as $key  )
        {
            $this->ChildGroupList[$key]->runFunctionForAllGroups( $func, $params );
            $this->ChildGroupList[$key]->$func( $params );
        }
    }


    function resetCounters()
    {
        $this->resetOptionCounter();
    }

    /*!
      Finds the largest \c option_id among the options and sets it as \a $this->OptionCounter
    */
    function resetOptionCounter()
    {
        $maxValue = 0;
        foreach ( $this->Options as $optionList )
        {
            foreach ( $optionList['optionlist'] as $option )
            {
                if ( $maxValue < $option['option_id'] )
                {
                    $maxValue = $option['option_id'];
                }
            }
        }
        $this->OptionCounter = $maxValue;
    }

    /*!
      Change the id of multioption in ascending order.
    */
    function changeMultiOptionId()
    {
        $i = 0 ;
        foreach ( $this->Options as $key => $opt )
        {
            $this->Options[$key]['id'] = $i++;
        }
        $this->MultiOptionCount = $i - 1;
    }

    function removeChildGroup( $groupID, $depth = 0 )
    {
        if ( $depth > 15 )
            die( "depth=$depth" );
        $removed = false;
        foreach ( $this->Options as $key => $option )
        {
            if ( isset($option['child_group']) && $option['child_group']->attribute( "group_id") == $groupID )
            {
                unset( $this->Options[$key]['child_group'] );
                return true;
            }
        }

        foreach ( array_keys( $this->ChildGroupList ) as $key  )
        {
            if ( $this->ChildGroupList[$key]->attribute( 'group_id' ) == $groupID )
            {
                unset( $this->ChildGroupList[$key] );
                return true;
            }
            if ( $this->ChildGroupList[$key]->removeChildGroup( $groupID, $depth + 1 ) )
                return true;
        }
        return false;
    }

    /*!
      Remove MultiOption from the array.
      After calling this function all the options associated with that multioption will be removed.
      This function also calles to changeMultiOption to reset the key value of multioption array.
      \param $array_remove is the array of those multiOptions which is selected to remove.
      \sa removeOptions()
    */
    function removeMultiOptions( $array_remove )
    {
        $options =& $this->Options;
        foreach ( $array_remove as $id )
        {
            unset( $options[ $id ] );
        }
        $options = array_values( $options );
        $this->changeMultiOptionId();
    }


    /*!
      Remove Options from the multioption.
      This function first remove selected options and then reset the key value if all options for that multioption.
      \param $arrayRemove is a list of all array elements which is selected to remove from the multioptions.
      \param $optionid is the key value if multioption from which it is required to remove the options.
      \sa removeMultiOptions()
    */
    function removeOptions( $arrayRemove, $optionId )
    {
        $options =& $this->Options;
        foreach ( $arrayRemove as  $id )
        {
            unset( $options[$optionId]['optionlist'][$id - 1] );
        }
        $options = array_values( $options );
        $i = 1;
        foreach ( $options[$optionId]['optionlist'] as $key => $opt )
        {
            $options[$optionId]['optionlist'][$key]['id'] = $i;
            $i++;
        }
    }

    function getIDsFromMultioptions( $params)
    {
        if ( $params['group'] )
        {
            foreach ( $this->Options as $multioption )
            {
                $params['group']->MultioptionIDList[] = $multioption['multioption_id'];
                foreach ( $multioption['optionlist'] as $option )
                {
                    $params['group']->OptionIDList[] = $option['option_id'];
                }
            }
        }
    }

    function cleanupRules( )
    {
        $this->runFunctionForAllGroups( 'getIDsFromMultioptions', array( 'group' => &$this ) );
        foreach( $this->Rules as $key => $ruleForOption )
        {
            if ( ! in_array( $key, $this->OptionIDList ) )
            {
                unset( $this->Rules[$key] );
                continue;
            }

            foreach ( $ruleForOption as $moption => $rule )
            {
                if ( !in_array( $moption, $this->MultioptionIDList ) )
                {
                    unset( $this->Rules[$key][$moption] );
                    if ( count( $this->Rules[$key] ) == 0 )
                    {
                        unset( $this->Rules[$key] );
                        break;
                    }
                    continue;
                }
                foreach( $rule  as $index =>$optionID )
                {
                    if ( !in_array( $optionID, $this->OptionIDList ) )
                    {
                        unset( $this->Rules[$key][$moption][$index] );
                        if( count( $rule ) == 0 )
                        {
                            unset( $this->Rules[$key][$moption] );
                            break;
                        }
                        continue;
                    }

                }
                if( count( $this->Rules[$key] ) == 0 )
                {
                    unset( $this->Rules[$key] );
                    break;
                }
            }
        }
    }

    function addOptionToRules( $multioptionID, $optionID )
    {
        $rules = $this->Rules;
        foreach( $this->Rules as $key => $ruleForOption )
        {
            foreach ( $ruleForOption as $moption => $rule )
            {
                if ( $multioptionID == $moption )
                {
                    $this->Rules[$key][$moption][] = $optionID;
                }
            }
        }
    }

    /*!
     \return list of supported attributes
    */
    function attributes()
    {
        return array( 'name',
                      'id',
                      'group_id',
                      'rules',
                      'multioption_list',
                      'optiongroup_list' );
    }

    /*!
      Returns true if object have an attribute.
      The valid attributes are \c name and \c multioption_list.
      \param $name contains the name of attribute
    */
    function hasAttribute( $name )
    {
        return in_array( $name, $this->attributes() );
    }

    /*!
    Returns an attribute. The valid attributes are \c name and \c multioption_list
    \a name contains the name of multioption
    \a multioption_list contains the list of all multioptions.
    */
    function attribute( $name )
    {
        switch ( $name )
        {
            case "name" :
            {
                return $this->Name;
            } break;
            case "id" :
            {
                return $this->ID;
            } break;
            case "group_id":
            {
                return $this->GroupID;
            } break;
            case "rules":
            {
                return $this->Rules;
            } break;
            case "multioption_list" :
            {
                return $this->Options;
            } break;
            case "optiongroup_list":
            {
                return $this->ChildGroupList;
            } break;
            default:
            {
                eZDebug::writeError( "Attribute '$name' does not exist", 'eZMultiOption::attribute' );
                $retValue = null;
                return $retValue;
            }break;
        }
    }

    function initCountersRecursive()
    {
        $this->runFunctionForAllGroups( 'initCounters', $this );
    }

    function initCounters( $group )
    {
        if ( $this->GroupIDCounter < $this->attribute( 'group_id' ) )
            $this->GroupIDCounter = $this->attribute( 'group_id' );

        if ( $this->GroupIDCounter < $group->getGroupIDCounter() )
            $this->GroupIDCounter = $group->getGroupIDCounter();

        if ( $this->OptionCounter < $group->getOptionCounter() )
            $this->OptionCounter = $group->getOptionCounter();

        if ( $this->getMultiOptionIDCounter() < $group->getMultiOptionIDCounter() )
            $this->setMultiOptionIDCounter( $group->getMultiOptionIDCounter() );
    }

    /*!
    Will decode an xml string and initialize the eZ Multi option object.
    If $xmlString is on empty then it will call addMultiOption() and addOption() functions
    to create new multioption else it will decode the xml string.
    \param $smlString contain the complete data structure for multioptions.
    \sa xmlString()
    */
    function decodeXML( $xmlString )
    {
        $this->OptionCounter = 0;
        $this->Options = array();
        if ( $xmlString != "" )
        {
            $xml = new eZXML();
            $dom =& $xml->domTree( $xmlString );
//            var_dump( $xmlString );
            $root =& $dom->root();

            if ( $root->name() == 'ezmultioption' )
            {
                $this->initFromXMLCompat( $root );
                return;
            }

            $this->initGroupFromDom( $root );

            $rulesNode = $root->elementByName( "rules" );
            if ( !$rulesNode )
                return;
            $ruleList = $rulesNode->elementsByName( "rule" );
            //Loop for rules
            $rules = array();
            foreach ( $ruleList as $ruleNode )
            {
                $optionID = $ruleNode->attributeValue( "option_id" );
                $ruleDataNodeList = $ruleNode->elementsByName( 'rule_data' );
                $ruleForOption = array();
                foreach ( $ruleDataNodeList as $ruleDataNode )
                {
                    $multioptionID = $ruleDataNode->attributeValue( "multioption_id" );
                    $includeOptionNodeList = $ruleDataNode->elementsByName( 'option_id' );
                    $includeOptions = array();
                    foreach ( $includeOptionNodeList as $includeOptionNode )
                    {
                        $includeOptions[] = $includeOptionNode->textContent();
                    }
                    $ruleForOption[$multioptionID] = $includeOptions;
                }
                $rules[$optionID] = $ruleForOption;
            }
            $this->Rules = $rules;
        }
        else
        {
            //The control come here while creaging new object for MultiOption
            $group = new eZMultioption2( '' );
            $this->addChildGroup( $group );
            $nodeID = $group->addMultiOption( "", 0, false , '' );
            $group->addOption( $nodeID, "", "", "" );
            $this->initCounters( $group );
            unset( $group);
        }
    }

    function initFromXMLCompat( $root, $new = false )
    {
        if ( $root && $root->attributeValue("option_counter") > 0 )
        {
            $this->Name = '';
            $this->OptionCounter = 0;
            $this->MultiOptionIDCounter = 0;
            $this->GroupIDCounter = 1;
            $this->GroupID = 0;
            $this->ID = 0;

            $multiOptionGroup = new eZMultioption2( '', 0,0,0,1 );
            $multiOptionGroup->initCounters( $this );
            $multiOptionGroup->initGroupFromDom( $root );
            $this->initCounters( $multiOptionGroup );
            $this->ChildGroupList[] = $multiOptionGroup;

        }
    }

    function initGroupFromDom( $root, $new = false )
    {

        if ( $root && $root->attributeValue("option_counter") > 0 )
        {
            // set the name of the node
            $this->Name = $root->elementTextContentByName( "name" );
            $this->OptionCounter = $root->attributeValue("option_counter");
            $this->MultiOptionIDCounter = $root->attributeValue("multioption_counter")
                                          ?  $root->attributeValue("multioption_counter")
                                          : $this->MultiOptionIDCounter;

            $this->GroupIDCounter = $root->attributeValue("group_counter") ? $root->attributeValue("group_counter") : $this->GroupIDCounter;
            $this->GroupID = $root->attributeValue("group_id") ? $root->attributeValue("group_id") : $this->GroupID ;
            $this->ID = $root->attributeValue("id") ? $root->attributeValue("id") : $this->ID;

            $multioptionsNode = $root->elementByName( "multioptions" );
            $multioptionsList = $multioptionsNode->elementsByName( "multioption" );
            //Loop for MultiOptions
            foreach ( $multioptionsList as $multioption )
            {

                $newID = $this->addMultiOption( $multioption->attributeValue( "name" ),
                                                $multioption->attributeValue( "priority" ),
                                                $multioption->attributeValue( "default_option_id" ),
                                                $multioption->attributeValue( "multioption_id" ) );

                $optionNode = $multioption->elementsByName( "option" );
                foreach ( $optionNode as $option )
                {
                    $isSelectable = $option->attributeValue( "is_selectable" ) === false ? 1 : $option->attributeValue( "is_selectable" );
                    $this->addOption( $newID,
                                      $option->attributeValue( "option_id" ),
                                      $option->attributeValue( "value" ),
                                      $option->attributeValue( "additional_price" ),
                                      $isSelectable,
                                      $option->attributeValue( "object" ) );
                }
                $groupNode = $multioption->elementByName( "optiongroup" );
                if( $groupNode )
                {
                    $multiOptionGroup = new eZMultioption2( '' );
                    $multiOptionGroup->initCounters( $this );
                    $multiOptionGroup->initGroupFromDom( $groupNode );
                    $this->initCounters( $multiOptionGroup );
                    $this->Options[$newID]['child_group'] = $multiOptionGroup;
                }

            }
            $this->changeMultiOptionId();
            $groupsNode = $root->elementByName( "groups" );
            if ( ! $groupsNode )
                return;
            $groupList = $groupsNode->elementsByName( "optiongroup" );
            foreach ( $groupList as $group )
            {
                $multiOptionGroup = new eZMultioption2( '' );
                $multiOptionGroup->initCounters( $this );
                $multiOptionGroup->initGroupFromDom( $group );
                $this->initCounters( $multiOptionGroup );
                $this->ChildGroupList[] = $multiOptionGroup;
            }

        }
        else
        {
            //The control come here while creating new object for MultiOption
            if ( $new )
            {
                $nodeID = $this->addMultiOption( "", 0, false, '' );
                $this->addOption( $nodeID, "", "", "" );
            }
        }

    }

    /*!
     Will return the XML string for this MultiOption set.
     \sa decodeXML()
    */
    function &xmlString()
    {
        $doc = new eZDOMDocument( "Multioption2" );
        $root = $doc->createElementNode( "ezmultioption2" );
        $doc->setRoot( $root );

        $this->createDomElementForGroup( $doc, $root );

        $rulesNode = $doc->createElementNode( "rules" );

        foreach( $this->Rules as $ruleFor => $rule )
        {
            unset( $ruleNode );
            $ruleNode = $doc->createElementNode( "rule" );
            $ruleNode->appendAttribute( $doc->createAttributeNode( "option_id", $ruleFor ) );
            foreach ( $rule as $multioptionID => $ruleData )
            {
                unset( $ruleDataNode );
                $ruleDataNode = $doc->createElementNode( "rule_data" );
                $ruleDataNode->appendAttribute( $doc->createAttributeNode( "multioption_id", $multioptionID ) );
                foreach ( $ruleData as $optionID )
                {
                    unset( $includeNode );
                    unset( $includeValue );
                    $includeNode = $doc->createElementNode( "option_id" );
                    $includeValue = $doc->createTextNode( $optionID );
                    $includeNode->appendChild( $includeValue );
                    $ruleDataNode->appendChild( $includeNode );
                }
                $ruleNode->appendChild( $ruleDataNode );
            }
            $rulesNode->appendChild( $ruleNode );
        }
        $root->appendChild( $rulesNode );

        $xml = $doc->toString();
        return $xml;
    }

    function createDomElementForGroup( &$doc, &$groupNode, $depth = 0 )
    {

        $root =& $groupNode;
        $name = $doc->createElementNode( "name" );
        $nameValue = $doc->createTextNode( $this->Name );
        $name->appendChild( $nameValue );


        $optionCounter = $doc->createAttributeNode( 'option_counter', $this->OptionCounter );
        $root->appendAttribute( $optionCounter );
        $multiOptionIDCounter = $doc->createAttributeNode( 'multioption_counter', $this->MultiOptionIDCounter);
        $root->appendAttribute( $multiOptionIDCounter );
        $groupIDCounter = $doc->createAttributeNode( 'group_counter', $this->GroupIDCounter );
        $root->appendAttribute( $groupIDCounter );

        $groupID = $doc->createAttributeNode( 'group_id', $this->GroupID );
        $root->appendAttribute( $groupID );
        $ID = $doc->createAttributeNode( 'id', $this->ID );
        $root->appendAttribute( $ID );

        $root->appendChild( $name );
        $multioptions = $doc->createElementNode( "multioptions" );
        $root->appendChild( $multioptions );
        foreach ( $this->Options as $multioption )
        {
            unset( $multioptionNode );
            $multioptionNode = $doc->createElementNode( "multioption" );
            $multioptionNode->appendAttribute( $doc->createAttributeNode( "id", $multioption['id'] ) );
            $multioptionNode->appendAttribute( $doc->createAttributeNode( "name", $multioption['name'] ) );
            $multioptionNode->appendAttribute( $doc->createAttributeNode( "multioption_id", $multioption['multioption_id'] ) );
            $multioptionNode->appendAttribute( $doc->createAttributeNode( "priority", $multioption['priority'] ) );
            $multioptionNode->appendAttribute( $doc->createAttributeNode( 'default_option_id', $multioption['default_option_id'] ) );
            if ( isset( $multioption['imageoption'] ) && $multioption['imageoption'] )
                    $multioptionNode->appendAttribute( $doc->createAttributeNode( "imageoption", $multioption['imageoption'] ) );

            foreach ( $multioption['optionlist'] as $option )
            {
                unset( $optionNode );
                $optionNode = $doc->createElementNode( "option" );
                $optionNode->appendAttribute( $doc->createAttributeNode( "id", $option['id'] ) );
                $optionNode->appendAttribute( $doc->createAttributeNode( "option_id", $option['option_id'] ) );
                $optionNode->appendAttribute( $doc->createAttributeNode( "value", $option['value'] ) );
                if ( isset( $option['object'] ) && $option['object']  )
                    $optionNode->appendAttribute( $doc->createAttributeNode( "object", $option['object'] ) );
                $optionNode->appendAttribute( $doc->createAttributeNode( 'additional_price', $option['additional_price'] ) );
                $optionNode->appendAttribute( $doc->createAttributeNode( 'is_selectable', $option['is_selectable'] ) );
                $multioptionNode->appendChild( $optionNode );
            }
            if ( array_key_exists( 'child_group', $multioption ) && $multioption['child_group'] )
            {
                $childGroup = $multioption['child_group'];
                unset( $childGroupNode );
                $childGroupNode = $doc->createElementNode( "optiongroup" );
                $childGroupNode->appendAttribute( $doc->createAttributeNode( "id", $childGroup->ID ) );
                $childGroup->createDomElementForGroup( $doc, $childGroupNode, $depth + 1 );
                $multioptionNode->appendChild( $childGroupNode );
            }
            $multioptions->appendChild( $multioptionNode );
        }

        $groups = $doc->createElementNode( "groups" );

        foreach ( $this->ChildGroupList as $childGroup )
        {
            unset( $childGroupNode );
            $childGroupNode = $doc->createElementNode( "optiongroup" );
            $childGroupNode->appendAttribute( $doc->createAttributeNode( "id", $childGroup->ID ) );
            $childGroup->createDomElementForGroup( $doc, $childGroupNode, $depth + 1 );
            $groups->appendChild( $childGroupNode );
        }
        $root->appendChild( $groups );
        return $root;

    }
    /// \privatesection
    /// Contains the Option name
    var $Name;
    var $GroupID;
    /// Contains the Options
    var $Options;
    /// Contains the multioption counter value
    var $MultiOptionIDCounter;
    var $GroupIDCounter;
    /// Contains the option counter value
    var $OptionCounter;
    var $ChildGroupList;
    var $MultioptionIDList = array();
    var $OptionIDList = array();
}
?>
