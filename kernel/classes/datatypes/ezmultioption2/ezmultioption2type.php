<?php
//
// Definition of eZMultiOption2Type class
//
// Created on: <07-Jul-2007 15:52:24 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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
  \class eZMultiOption2Type ezmultioption2type.php
  \ingroup eZDatatype
  \brief A datatype which works with multiple options.

  This allows the user to add several option choices almost as if he
  was adding attributes with option datatypes.

  This class implements the interface for a datatype but passes
  most of the work over to the eZMultiOption2 class which handles
  parsing, storing and manipulation of multioption2s and options.

  This datatype supports:
  - fetch and validation of HTTP data
  - search indexing
  - product option information
  - class title
  - class serialization

*/

//include_once( "kernel/classes/ezdatatype.php" );
//include_once( "kernel/classes/datatypes/ezmultioption2/ezmultioption2.php" );
//include_once( 'lib/ezutils/classes/ezstringutils.php' );

class eZMultiOption2Type extends eZDataType
{
    const DEFAULT_NAME_VARIABLE = "_ezmultioption2_default_name_";
    const MAX_CHILD_LEVEL = 50;
    const DATA_TYPE_STRING = "ezmultioption2";

    /*!
     Constructor to initialize the datatype.
    */
    function eZMultiOption2Type()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezi18n( 'kernel/classes/datatypes', "Multi-option2", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     Validates the input for this datatype.
     \return True if input is valid.
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     This function calles xmlString function to create xml string and then store the content.
    */
    function storeObjectAttribute( $contentObjectAttribute )
    {
        $optiongroup = $contentObjectAttribute->content();
        $contentObjectAttribute->setAttribute( "data_text", $optiongroup->xmlString() );
    }

    /*!
     \return An eZMultiOption2 object which contains all the option data
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        $optiongroup = new eZMultiOption2( "" );
        $optiongroup->decodeXML( $contentObjectAttribute->attribute( "data_text" ) );
        return $optiongroup;
    }

    /*!
     \reimp
    */
    function isIndexable()
    {
        return true;
    }

    /*!
     \return The internal XML text.
    */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_text" );
    }

    /*!
     Fetches the http post var integer input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $editMode = $http->postVariable( $base . "_data_edit_mode_" . $contentObjectAttribute->attribute( "id" ) );
        if( $editMode == 'rules' )
        {
            $optiongroup = $contentObjectAttribute->content();

            $optionRulesArray = $http->hasPostVariable( $base . "_data_multioption_rule_for" )
                                 ? $http->postVariable( $base . "_data_multioption_rule_for" )
                                 : array();
            $rules = array();
            foreach( $optionRulesArray  as $ruleFor )
            {
                $parentMultioptionIDList = $http->postVariable( $base . "_data_rule_parent_multioption_id_" . $ruleFor );
                $rule = array();
                foreach ( $parentMultioptionIDList as $parentMultioptionID )
                {
                    $parentMultioptionData = $optiongroup->findMultiOption( $parentMultioptionID );
                    $parentMultioptionGroup = $parentMultioptionData['group'];

                    $parentMultioptionOptionList = $parentMultioptionGroup->Options[$parentMultioptionData['id']]['optionlist'];

                    $ruleData = $http->hasPostVariable( $base . "_data_multioption_rule_" . $ruleFor . '_' . $parentMultioptionID )
                                 ? $http->postVariable( $base . "_data_multioption_rule_" . $ruleFor . '_' . $parentMultioptionID )
                                : array();
                    if ( count( $parentMultioptionOptionList ) == count( $ruleData ) )
                        continue;
                    $rule[$parentMultioptionID] = $ruleData;
                }
                if ( count( $rule ) )
                    $rules[$ruleFor] = $rule;
            }
            $optiongroup->Rules = $rules;
            $contentObjectAttribute->setContent( $optiongroup );
            return true;
        }

        $optiongroup = new eZMultiOption2( '' );

        $oldoptiongroup = $contentObjectAttribute->content();
        if ( $oldoptiongroup->Rules )
           $optiongroup->Rules = $oldoptiongroup->Rules;
        $this->fetchHTTPInputForGroup( $optiongroup, $http, $base, $contentObjectAttribute );
        $optiongroup->initCountersRecursive();
        $contentObjectAttribute->setContent( $optiongroup );
        return true;
    }

    function fetchHTTPInputForGroup( $parentOptionGroup, $http, $base, $contentObjectAttribute, $depth = 0 )
    {

        $optionGroupIDArray = $http->hasPostVariable( $base . "_data_optiongroup_id_" .
                                                      $contentObjectAttribute->attribute( "id" ) . '_' .
                                                      $parentOptionGroup->attribute( 'group_id' ) )
                              ? $http->postVariable( $base . "_data_optiongroup_id_" .
                                                      $contentObjectAttribute->attribute( "id" ) . '_' .
                                                      $parentOptionGroup->attribute( 'group_id' ) )
                              : array();
        $optionGroupNameList = count( $optionGroupIDArray ) > 0
             ? $http->postVariable( $base . "_data_optiongroup_name_" .
                                    $contentObjectAttribute->attribute( "id" )  . '_' .
                                    $parentOptionGroup->attribute( 'group_id' ) )
             : array();
        foreach ( $optionGroupIDArray as $key => $optionGroupID )
        {
            unset( $optionGroup );
            $optionGroup = new eZMultiOption2( $optionGroupNameList[$key], 0,0,0, $optionGroupID );

            if ( $http->hasPostVariable( $base . "_data_optiongroup_id_parent_multioption_" .
                                                      $contentObjectAttribute->attribute( "id" ) . '_' .
                                                      $optionGroupID ) )
            {
               $parentMultioptionID =   $http->postVariable( $base . "_data_optiongroup_id_parent_multioption_" .
                                                             $contentObjectAttribute->attribute( "id" ) . '_' .
                                                             $optionGroupID );
               $parentOptionGroup->addChildGroup( $optionGroup, $parentMultioptionID );
            }
            else
            {
                $parentOptionGroup->addChildGroup( $optionGroup );
            }
            if ( $optionGroupID == 0 )
                continue ;

            $multioptionIDArray = $http->hasPostVariable( $base . "_data_multioption_id_" .
                                                          $contentObjectAttribute->attribute( "id" ) . '_' .
                                                          $optionGroupID )
                 ? $http->postVariable( $base . "_data_multioption_id_" .
                                        $contentObjectAttribute->attribute( "id" ). '_' .
                                        $optionGroupID )
                              : array();
            foreach( $multioptionIDArray as $multioptionID )
            {

                $multioptionName = $http->postVariable( $base . "_data_multioption_name_" .
                                                        $contentObjectAttribute->attribute( "id" ). '_' .
                                                        $optionGroupID . '_' . $multioptionID );

                $defaultValue = $http->hasPostVariable( $base . "_data_default_option_" .
                                                        $contentObjectAttribute->attribute( "id" ). '_' .
                                                        $optionGroupID . '_' . $multioptionID )
                     ? $http->postVariable( $base . "_data_default_option_" .
                                            $contentObjectAttribute->attribute( "id" ). '_' .
                                            $optionGroupID . '_' . $multioptionID )
                     : '';

                $multiOptionPriority = 0;

                $newID = $optionGroup->addMultiOption( $multioptionName, $multiOptionPriority, $defaultValue, $multioptionID  );


                $optionCountArray = $http->hasPostVariable( $base . "_data_option_option_id_" .
                                                            $contentObjectAttribute->attribute( "id" ) . '_' .
                                                            $optionGroupID . '_' .  $multioptionID )
                                ? $http->postVariable( $base . "_data_option_option_id_" .
                                                       $contentObjectAttribute->attribute( "id" ) . '_' .
                                                       $optionGroupID . '_' .  $multioptionID )
                                : array();

                $optionValueArray = $http->hasPostVariable( $base . "_data_option_value_" .
                                                            $contentObjectAttribute->attribute( "id" ) . '_' .
                                                            $optionGroupID . '_' .  $multioptionID )
                     ? $http->postVariable( $base . "_data_option_value_" .
                                            $contentObjectAttribute->attribute( "id" ) . '_' .
                                            $optionGroupID . '_' .  $multioptionID )
                     : array();
                $http->postVariable( $base . "_data_option_value_" .
                                            $contentObjectAttribute->attribute( "id" ) . '_' .
                                     $optionGroupID . '_' .  $multioptionID );
                $http->postVariable( $base . "_data_option_additional_price_" .
                                            $contentObjectAttribute->attribute( "id" ) . '_' .
                                     $optionGroupID . '_' .  $multioptionID );
                $optionAdditionalPriceArray = $http->hasPostVariable( $base . "_data_option_additional_price_" .
                                                            $contentObjectAttribute->attribute( "id" ) . '_' .
                                                            $optionGroupID . '_' .  $multioptionID )
                     ? $http->postVariable( $base . "_data_option_additional_price_" .
                                            $contentObjectAttribute->attribute( "id" ) . '_' .
                                            $optionGroupID . '_' .  $multioptionID )
                     : array();
                for ( $i = 0; $i < count( $optionCountArray ); $i++ )
                {
                    $isSelectable = $http->hasPostVariable( $base . "_data_option_is_selectable_" .
                                                            $contentObjectAttribute->attribute( "id" ) . '_' .
                                                            $optionGroupID . '_' .  $multioptionID . '_' . $optionCountArray[$i] )
                         ? 0 : 1;
                    $objectID = $http->hasPostVariable( $base . "_data_option_object_" .
                                                            $contentObjectAttribute->attribute( "id" ) . '_' .
                                                            $optionGroupID . '_' .  $multioptionID . '_' . $optionCountArray[$i] )
                                ? $http->postVariable( $base . "_data_option_object_" .
                                                            $contentObjectAttribute->attribute( "id" ) . '_' .
                                                            $optionGroupID . '_' .  $multioptionID . '_' . $optionCountArray[$i] ) : 0;
                    $optionGroup->addOption( $newID, $optionCountArray[$i], $optionValueArray[$i], $optionAdditionalPriceArray[$i], $isSelectable, $objectID );
                }
            }
            if ( $depth > self::MAX_CHILD_LEVEL)
                die('max recursion level has been reached');
            $this->fetchHTTPInputForGroup( $optionGroup, $http, $base, $contentObjectAttribute, $depth+1 );

            $parentOptionGroup->initCounters( $optionGroup );

        }

    }


    /*!
     This function performs specific actions.

     It has some special actions with parameters which is done by exploding
     $action into several parts with delimeter '_'.
     The first element is the name of specific action to perform.
     The second element will contain the key value or id.

     The various operation's that is performed by this function are as follow.
     - new-option - A new option is added to a multioption.
     - remove-selected-option - Removes a selected option.
     - new_multioption - Adds a new multioption.
     - remove_selected_multioption - Removes all multioptions given by a selection list
    */
    function customObjectAttributeHTTPAction( $http, $action, $contentObjectAttribute, $parameters )
    {
        $actionlist = explode( "_", $action );
        if ( $actionlist[0] == "new-option" )
        {
            $rootGroup = $contentObjectAttribute->content();
            $groupID = $actionlist[1];
            $multioptionID = $actionlist[2];
            $optionID = $rootGroup->addOptionForMultioptionID( $multioptionID,  "", "", "" );

            $group = $rootGroup->findGroup( $groupID );

            $rootGroup->addOptionToRules( $multioptionID, $optionID );

            $rootGroup->initCounters( $group );
            $rootGroup->initCountersRecursive();
            $contentObjectAttribute->setContent( $rootGroup );
            $contentObjectAttribute->store();
        }
        else if ( $actionlist[0] == "new-sublevel" )
        {
            $rootGroup = $contentObjectAttribute->content();
            $groupID = $actionlist[1];
            $multioptionID = $actionlist[2];

            $group = $rootGroup->findGroup( $groupID );
            if ( !$group )
                return;
            $childGroup = new eZMultiOption2( '', 0, $rootGroup->getMultiOptionIDCounter(),$rootGroup->getOptionCounter() );

            $group->addChildGroup( $childGroup, $multioptionID );

            $newID = $childGroup->addMultiOption( "", 0, false , '' );
            $childGroup->addOption( $newID, "", "", "" );
            $rootGroup->initCounters( $childGroup );

            $rootGroup->initCountersRecursive();

            $contentObjectAttribute->setContent( $rootGroup );
            $contentObjectAttribute->store();
        }
        else if ( $actionlist[0] == "new-group" )
        {
            $rootGroup = $contentObjectAttribute->content();
            $childGroup = new eZMultiOption2( '', 0, $rootGroup->getMultiOptionIDCounter(),$rootGroup->getOptionCounter() );
            $rootGroup->addChildGroup( $childGroup );
            $newID = $childGroup->addMultiOption( "", 0, false , '' );
            $childGroup->addOption( $newID, "", "", "" );
            $rootGroup->initCounters( $childGroup );
            $rootGroup->initCountersRecursive();

            $contentObjectAttribute->setContent( $rootGroup );
            $contentObjectAttribute->store();
        }
        else if ( $actionlist[0] == "remove-selected-option" )
        {
            $rootGroup = $contentObjectAttribute->content();
            $groupID = $actionlist[1];
            $multioptionID = $actionlist[2];
            $postvarname = "ContentObjectAttribute" . "_data_option_remove_" . $contentObjectAttribute->attribute( "id" ) . "_" .
                            $groupID . "_" . $multioptionID;
            $array_remove = $http->hasPostVariable( $postvarname ) ? $http->postVariable( $postvarname ) : array();

            $group = $rootGroup->findGroup( $groupID );

            if ( $group )
            {
                $group->removeOptions( $array_remove, $multioptionID );
                $rootGroup->cleanupRules();
                $contentObjectAttribute->setContent( $rootGroup );
                $contentObjectAttribute->store();
            }
        }
        else if ( $actionlist[0] == "new-multioption" )
        {
            $rootGroup = $contentObjectAttribute->content();
            $groupID = $actionlist[1];
            $group = $rootGroup->findGroup( $groupID );
            if ( !$group )
                return;

            $newID = $group->addMultiOption( "" ,0,false, '' );
            $group->addOption( $newID, "", "", "" );
            $group->addOption( $newID, "" ,"", "" );
            $rootGroup->initCounters( $group );
            $rootGroup->initCountersRecursive();
            $contentObjectAttribute->setContent( $rootGroup );
            $contentObjectAttribute->store();

        }
        else if ( $actionlist[0] == "remove-selected-multioption" )
        {
            $rootGroup = $contentObjectAttribute->content();
            $groupID = $actionlist[1];
            $postvarname = "ContentObjectAttribute" . "_data_multioption_remove_" . $contentObjectAttribute->attribute( "id" ) . "_" . $groupID;
            $array_remove = $http->hasPostVariable( $postvarname ) ? $http->postVariable( $postvarname ) : array();

            $group = $rootGroup->findGroup( $groupID );

            if ( $group )
            {
                $group->removeMultiOptions( $array_remove );
                $rootGroup->cleanupRules();
                if ( count( $group->attribute( 'multioption_list') ) == 0 )
                {
                    $rootGroup->removeChildGroup( $group->attribute( 'group_id' ) );
                }
                $contentObjectAttribute->setContent( $rootGroup );
                $contentObjectAttribute->store();
            }

        }
        else if ( $actionlist[0] == "switch-mode" )
        {
            $editMode = $actionlist[1];
            $sessionVarName = 'eZEnhancedMultioption_edit_mode_' . $contentObjectAttribute->attribute( 'id' );
            $http->setSessionVariable( $sessionVarName, $editMode );
            $rootGroup = $contentObjectAttribute->content();
            $contentObjectAttribute->setContent( $rootGroup );
        }
        else if ( $actionlist[0] == "reset-rules" )
        {
            $rootGroup = $contentObjectAttribute->content();
            $rootGroup->Rules = array();
            $contentObjectAttribute->setContent( $rootGroup );
            $contentObjectAttribute->store();

        }
        else if ( $actionlist[0] == "set-object" )
        {

            if ( $http->hasPostVariable( 'BrowseActionName' ) and
                 $http->postVariable( 'BrowseActionName' ) == ( 'AddRelatedObject_' . $contentObjectAttribute->attribute( 'id' ) ) and
                 $http->hasPostVariable( "SelectedObjectIDArray" ) )
            {
                if ( !$http->hasPostVariable( 'BrowseCancelButton' ) )
                {
                    $selectedObjectArray = $http->hasPostVariable( "SelectedObjectIDArray" );
                    $selectedObjectIDArray = $http->postVariable( "SelectedObjectIDArray" );
                    $rootGroup = $contentObjectAttribute->content();
                    $groupID = $actionlist[1];
                    $multioptionID = $actionlist[2];
                    $optionID = $actionlist[3];

                    $objectID = $selectedObjectIDArray[0];

                    $rootGroup->setObjectForOption(  $multioptionID,  $optionID, $objectID );
                    $contentObjectAttribute->setContent( $rootGroup );
                    $contentObjectAttribute->store();

                }
           }
        }
        else if ( $actionlist[0] == "browse-object" )
        {
            $module = $parameters['module'];

            $redirectionURI = $parameters['current-redirection-uri'];
            $ini = eZINI::instance( 'content.ini' );

            //include_once( 'kernel/classes/ezcontentbrowse.php' );

            $browseType = 'AddRelatedObjectToDataType';
            $browseTypeINIVariable = $ini->variable( 'ObjectRelationDataTypeSettings', 'ClassAttributeStartNode' );
            foreach( $browseTypeINIVariable as $value )
            {
                list( $classAttributeID, $type ) = explode( ';',$value );
                if ( $classAttributeID == $contentObjectAttribute->attribute( 'contentclassattribute_id' ) && strlen( $type ) > 0 )
                {
                    $browseType = $type;
                    break;
                }
            }
            eZContentBrowse::browse( array( 'action_name' => 'AddRelatedObject_' . $contentObjectAttribute->attribute( 'id' ),
                                            'type' =>  $browseType,
                                            'browse_custom_action' => array( 'name' => 'CustomActionButton[' . $contentObjectAttribute->attribute( 'id' ) .
                                                                             '_set-object_'. $actionlist[1] . '_' . $actionlist[2] . '_' .$actionlist[3] . ']',
                                                                             'value' => $contentObjectAttribute->attribute( 'id' ) ),
                                                'persistent_data' => array( 'HasObjectInput' => 0 ),
                                                'from_page' => $redirectionURI ),
                                         $module );

        }
        else if ( $actionlist[0] == "remove-object" )
        {


            $rootGroup = $contentObjectAttribute->content();
            $groupID = $actionlist[1];
            $multioptionID = $actionlist[2];
            $optionID = $actionlist[3];

            $rootGroup->removeObjectFromOption( $multioptionID, $optionID );

            $contentObjectAttribute->setContent( $rootGroup );
            $contentObjectAttribute->store();


        }
        else
        {
            eZDebug::writeError( "Unknown custom HTTP action: " . $action, "eZMultiOptionType" );
        }
    }

    /*!
     \reimp
     Finds the option which has the correct ID , if found it returns an option structure.

     \param $optionString must contain the multioption ID an underscore (_) and a the option ID.
    */
    function productOptionInformation( $objectAttribute, $optionID, $productItem )
    {
        $optiongroup = $objectAttribute->attribute( 'content' );

        $option = $optiongroup->findOption( false, $optionID );
        if ( $option )
        {
            return array( 'id' => $option['option_id'],
                          'name' => $option['multioption_name'],
                          'value' => $option['value'],
                          'additional_price' => $option['additional_price'] );
        }
        return null;
    }


    /*!
      \reimp
      \return \c true if there are more than one multioption in the list.
    */
    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        $groups = $contentObjectAttribute->content();
        $grouplist = $groups->attribute( 'optiongroup_list' );
        return count( $grouplist ) > 0;
    }

    /*!
     Sets default multioption values.
    */
    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion == false )
        {
            $optiongroup = $contentObjectAttribute->content();
            if ( $optiongroup )
            {
                $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();
                $optiongroup->setName( $contentClassAttribute->attribute( 'data_text1' ) );
                $contentObjectAttribute->setAttribute( "data_text", $optiongroup->xmlString() );
                $contentObjectAttribute->setContent( $optiongroup );
            }
        }
        else
        {
            $dataText = $originalContentObjectAttribute->attribute( "data_text" );
            $contentObjectAttribute->setAttribute( "data_text", $dataText );
        }
    }

    /*!
     \reimp
    */
    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        return false;
    }


    /*!
     \reimp
     Validates the input for an object attribute during add to basket process
     and returns a validation state as defined in eZInputValidator.
    */
    function validateAddToBasket( $objectAttribute, $data, &$errors )
    {

        $optiongroup = $objectAttribute->attribute( 'content' );
        $rules = $optiongroup->Rules;

        $validationErrors = array();


        foreach( $data as $moptionID => $selectedItem )
        {
            $failedOptionData = $optiongroup->findMultiOption( $moptionID );
            $failedMultiOption = $failedOptionData['group']->Options[$failedOptionData['id']];
            $failedOption = $optiongroup->findOption( $failedMultiOption, $selectedItem );
            if ( $failedOption['is_selectable'] != 1 )
            {
                $objectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                              "You cannot choose option value \"%1\" from \"%2\" because it is unselectable " ),
                                                      $failedOption['value'],
                                                      $failedMultiOption['name'] );
                $validationErrors[] = $objectAttribute->attribute( 'validation_error' );
            }
            if ( ! isset( $rules[$selectedItem] ) )
                continue;

            $rulesForOption = $rules[$selectedItem];
            foreach( array_keys( $rulesForOption ) as $ruleKey )
            {

                if ( isset( $data[$ruleKey] ) )
                {
                    $selectedMultioptionValue = $data[$ruleKey];
                    $canSelectIfIdArray = $rulesForOption[$ruleKey];
                    if ( !in_array( $data[$ruleKey], $canSelectIfIdArray  ) )
                    {
                        $wrongParentData =  $optiongroup->findMultiOption( $ruleKey );
                        $wrongParentMultiOption = $wrongParentData['group']->Options[$wrongParentData['id']];

                        $wrongParentOptionID = $data[$ruleKey];
                        $wrongParentOption =  $optiongroup->findOption( $wrongParentMultiOption, $wrongParentOptionID );

                        $objectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                      "You cannot choose option value \"%1\" from \"%2\"  \n if you selected option \"%3\" from \"%4\" " ),
                                                              $failedOption['value'],
                                                              $failedMultiOption['name'],
                                                              $wrongParentOption['value'],
                                                              $wrongParentMultiOption['name'] );
                        $validationErrors[] = $objectAttribute->attribute( 'validation_error' );

                    }
                }
            }
        }

        if ( count( $validationErrors ) > 0 )
        {
            $errors = $validationErrors;
            return eZInputValidator::STATE_INVALID;
        }
        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     \reimp
     \return true if the datatype requires validation during add to basket procedure
    */
    function isAddToBasketValidationRequired()
    {
        return true;
    }

    /*!
     \reimp
    */
    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $defaultValue = $classAttribute->attribute( 'data_text1' );
        $defaultValueNode = $attributeParametersNode->ownerDocument->createElement( 'default-value', $defaultValue );
        $attributeParametersNode->appendChild( $defaultValueNode );
    }

    /*!
     \reimp
    */
    function unserializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $defaultValue = $attributeParametersNode->getElementsByTagName( 'default-value' )->item( 0 )->textContent;
        $classAttribute->setAttribute( 'data_text1', $defaultValue );
    }

    /*!
     \reimp
    */
    function serializeContentObjectAttribute( $package, $objectAttribute )
    {
        $node = $this->createContentObjectAttributeDOMNode( $objectAttribute );

        $dom = new DOMDocument( '1.0', 'utf-8' );
        $success = $dom->loadXML( $objectAttribute->attribute( 'data_text' ) );

        $importedNode = $node->ownerDocument->importNode( $dom->documentElement, true );
        $node->appendChild( $importedNode );

        return $node;
    }

    /*!
     \reimp
    */
    function unserializeContentObjectAttribute( $package, $objectAttribute, $attributeNode )
    {
        $rootNode = $attributeNode->getElementsByTagName( 'ezmultioption2' )->item( 0 );
        $xmlString = $attributeNode->ownerDocument->saveXML( $rootNode );
        $objectAttribute->setAttribute( 'data_text', $xmlString );
    }

    /*!
     \return the template name to use for editing the attribute.
     \note Default is to return the datatype string which is OK
           for most datatypes, if you want dynamic templates
           reimplement this function and return a template name.
     \note The returned template name does not include the .tpl extension.
     \sa viewTemplate, informationTemplate
    */
    function editTemplate( $contentObjectAttribute )
    {
        $http = eZHTTPTool::instance();
        $sessionVarName = 'eZEnhancedMultioption_edit_mode_' . $contentObjectAttribute->attribute( 'id' );
        if ( $http->hasSessionVariable( $sessionVarName )  && $http->sessionVariable( $sessionVarName )  == 'rules' )
        {
            return $this->DataTypeString . '_rules';
        }
        return $this->DataTypeString;
    }

}

eZDataType::register( eZMultiOption2Type::DATA_TYPE_STRING, "eZMultiOption2Type" );

?>
