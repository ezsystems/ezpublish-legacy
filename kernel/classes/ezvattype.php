<?php
/**
 * File containing the eZVatType class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZVatType ezvattype.php
  \brief eZVatType handles different VAT types
  \ingroup eZKernel

*/

class eZVatType extends eZPersistentObject
{
    public $ID;
    public $Percentage;
    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "name" => array( 'name' => "Name",
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => true ),
                                         "percentage" => array( 'name' => "Percentage",
                                                                'datatype' => 'float',
                                                                'default' => 0,
                                                                'required' => true ) ),
                      "function_attributes" => array( 'is_dynamic' => 'isDynamic' ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZVatType",
                      "name" => "ezvattype" );
    }

    function getPercentage( $object, $country )
    {
        if ( $this->ID == -1 )
        {
            $percentage = eZVATManager::getVAT( $object, $country );
            if ( $percentage === null )
                $percentage = -1; // indicate that VAT percentage is unknown
        }
        else
            $percentage = $this->Percentage;

        return $percentage;
    }

    static function dynamicVatType( $asObject = true )
    {
        $row = array( 'id' => -1,
                      'name' => eZVatType::dynamicVatTypeName(),
                      'percentage' => 0.0 );

        if ( !$asObject )
            return $row;

        return new eZVatType( $row );
    }

    /**
     * Return name of the "fake" dynamic VAT type.
     *
     * \private
     * \static
     */
    static function dynamicVatTypeName()
    {
        if ( !isset( $GLOBALS['eZVatType_dynamicVatTypeName'] ) )
        {
            $shopINI = eZINI::instance( 'shop.ini' );
            $desc = $shopINI->variable( 'VATSettings', 'DynamicVatTypeName' );
            $GLOBALS['eZVatType_dynamicVatTypeName'] = $desc;
        }

        return $GLOBALS['eZVatType_dynamicVatTypeName'];
    }

    static function fetch( $id, $asObject = true )
    {
        if ( $id == -1 && eZVATManager::isDynamicVatChargingEnabled() )
            return eZVatType::dynamicVatType( $asObject );

        return eZPersistentObject::fetchObject( eZVatType::definition(),
                                                null,
                                                array( "id" => $id ),
                                                $asObject );
    }

    function isDynamic()
    {
        return ( $this->ID == -1 );
    }

    /**
     * \param $skipDynamic if false, include dynamic VAT type to the list being returned.
     */
    static function fetchList( $asObject = true, $skipDynamic = false )
    {
        // Fetch "real" VAT types, stored in DB.
        $VATTypes = eZPersistentObject::fetchObjectList( eZVatType::definition(),
                                                         null, null, array( 'id' => false ), null,
                                                         $asObject );
        if ( !$VATTypes )
            $VATTypes = array();

        // Add "fake" VAT type: dynamic.
        if ( !$skipDynamic )
        {
            if ( eZVATManager::isDynamicVatChargingEnabled() )
                $VATTypes[] = eZVatType::dynamicVatType( $asObject );
        }

        return $VATTypes;
    }

    /**
     * Fetches number of products using given VAT type.
     *
     * \public
     * \static
     * \param $vatID id of VAT type to count dependent products for.
     * \return Number of dependent products.
     */
    static function fetchDependentProductsCount( $vatID )
    {
        $vatID = (int) $vatID; // prevent SQL injection

        // We need DISTINCT here since there might be several object translations.
        $query = "SELECT COUNT(DISTINCT coa.contentobject_id) AS count " .
                 "FROM ezcontentobject_attribute coa, ezcontentobject co " .
                 "WHERE " .
                 "coa.contentobject_id=co.id AND " .
                 "coa.version=co.current_version AND " .
                 "data_type_string IN ('ezprice', 'ezmultiprice') " .
                 "AND data_text LIKE '$vatID,%'";

        $db = eZDB::instance();
        $rslt = $db->arrayQuery( $query );
        $nProducts = $rslt[0]['count'];
        return $nProducts;
    }

    /**
     * Fetches number of product classes having given VAT type set as default.
     *
     * \public
     * \static
     * \return Number of dependent product classes.
     */
    static function fetchDependentClassesCount( $vatID )
    {
        $vatID = (int) $vatID; // prevent SQL injection

        $query = "SELECT COUNT(DISTINCT cc.id) AS count " .
                 "FROM ezcontentclass cc, ezcontentclass_attribute cca " .
                 "WHERE cc.id=cca.contentclass_id AND " .
                 "cca.data_type_string IN ('ezprice', 'ezmultiprice') AND data_float1=$vatID";

        $db = eZDB::instance();
        $rslt = $db->arrayQuery( $query );
        $nClasses = $rslt[0]['count'];
        return $nClasses;
    }

    static function create()
    {
        $row = array(
            "id" => null,
            "name" => ezpI18n::tr( 'kernel/shop', 'VAT type' ),
            "percentage" => 0.0 );
        return new eZVatType( $row );
    }

    /**
     * Change VAT type in all products from $oldVAT to the default VAT of a product class.
     *
     * \private
     * \static
     * \param $oldVAT old VAT type id.
     * \param $newVAT new VAT type id.
     */
    static function resetToDefaultInProducts( $oldVAT )
    {
        $db = eZDB::instance();
        $db->begin();

        $selectProductsQuery =
            "SELECT coa.id, data_text, cca.data_float1 AS default_vat " .
            "FROM ezcontentclass cc, ezcontentclass_attribute cca, ezcontentobject_attribute coa, ezcontentobject co " .
            "WHERE " .
            "cc.id=cca.contentclass_id AND " .
            "cca.id=coa.contentclassattribute_id AND " .
            "coa.contentobject_id=co.id AND  " .
            "coa.version=co.current_version AND " .
            "cca.data_type_string IN ('ezprice', 'ezmultiprice') " .
            "AND data_text LIKE '$oldVAT,%'";

        // Fetch the attributes by small portions to avoid memory overflow.
        for ( $offset = 0; true; $offset += 50 )
        {
            $rows = $db->arrayQuery( $selectProductsQuery, array( 'offset' => $offset, 'limit' => 50 ) );
            if ( !$rows )
                break;

            foreach ( $rows as $row )
            {
                list( $oldVatType, $vatExInc ) = explode( ',', $row['data_text'], 2 );
                $updateQuery = "UPDATE ezcontentobject_attribute " .
                               "SET data_text = '" . $row['default_vat'] . ",$vatExInc' " .
                               "WHERE id=" . $row['id'];
                $db->query( $updateQuery );
            }

            if ( count( $rows ) < 50 )
                break;
        }

        $db->commit();
    }

    /**
     * Remove given VAT type and all references to it.
     *
     * Drops VAT charging rules referencing the VAT type.
     * Resets VAT type in associated products to its default value for a product class.
     *
     * \param $vatID id of VAT type to remove.
     * \public
     * \static
     */
    function removeThis()
    {
        $vatID = $this->ID;
        $db = eZDB::instance();
        $db->begin();

        // remove dependent VAT rules
        $dependentRules = eZVatRule::fetchByVatType( $vatID );
        foreach ( $dependentRules as $rule )
        {
            eZVatRule::removeVatRule( $rule->attribute( 'id' ) );
        }

        // replace VAT type in dependent products.
        eZVatType::resetToDefaultInProducts( $vatID );

        // Remove the VAT type itself.
        eZPersistentObject::removeObject( eZVatType::definition(),
                                          array( "id" => $vatID ) );

        $db->commit();
    }

    function VATTypeList()
    {
        if ( !isset( $this->VatTypeList ) )
        {
            $this->VatTypeList = eZVatType::fetchList();
            if ( !isset( $this->VatTypeList ) )
                $this->VatTypeList = array();
        }

        return $this->VatTypeList;
    }

    public $VatTypeList;
}

?>
