<?php
/**
 * File containing the eZVatRule class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZVatRule ezvatrule.php
  \brief eZVatRule handles VAT charging rules for the default VAT handler.
  \ingroup eZKernel
*/

class eZVatRule extends eZPersistentObject
{
    public $ID;
    public function __construct( $row )
    {
        $this->ProductCategories = null;
        parent::__construct( $row );
    }

    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "country_code"=> array ( 'name' => 'CountryCode',
                                                                  'datatype' => 'string',
                                                                  'default' => null,
                                                                  'required' => true ),
                                         "vat_type" => array( 'name' => "VatType",
                                                              'datatype' => 'integer',
                                                              'default' => null,
                                                              'required' => true,
                                                              'foreign_class' => 'eZVatType',
                                                              'foreign_attribute' => 'id',
                                                              'multiplicity' => '1..*') ),
                      "function_attributes" => array( 'product_categories' => 'productCategories',
                                                      'product_categories_string' => 'productCategoriesString',
                                                      'product_categories_ids' => 'productCategoriesIDs',
                                                      'product_categories_names' => 'productCategoriesNames',
                                                      'vat_type_object' => 'vatTypeObject',
                                                      'vat_type_name' => 'vatTypeName',
                                                      'country' => 'country' ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZVatRule",
                      "name" => "ezvatrule" );
    }

    function setAttribute( $attr, $val )
    {
        switch( $attr )
        {
            case 'product_categories':
            {
                $this->ProductCategories = $val;
            } break;

            default:
            {
                parent::setAttribute( $attr, $val );
            } break;
        }
    }

    static function fetch( $id )
    {
        return eZPersistentObject::fetchObject( eZVatRule::definition(),
                                                null,
                                                array( "id" => $id ),
                                                true );
    }

    static function fetchList()
    {
        return eZPersistentObject::fetchObjectList( eZVatRule::definition(),
                                                    null, null,
                                                    array( 'id' => 'asc' ),
                                                    null, true );
    }

    /**
     * Fetch VAT rules referencing given VAT type.
     *
     * \param $vatID ID of VAT type to count VAT rules for.
     * \public
     * \static
     */
    static function fetchByVatType( $vatID )
    {
        return eZPersistentObject::fetchObjectList( eZVatRule::definition(), null,
                                                     array( 'vat_type' => (int) $vatID ),
                                                     null, null, true, false, null );
    }

    /**
     * Fetch number of VAT rules referencing given product categories
     *
     * @param $categories Category (single or list) to count VAT rules for.
     *
     * @return int
     */
    static function fetchCountByCategory( $categories )
    {
        $db = eZDB::instance();

        $query = "SELECT COUNT(*) AS count FROM ezvatrule vr, ezvatrule_product_category vrpc " .
                 "WHERE vr.id=vrpc.vatrule_id AND ";

        if ( is_array( $categories ) )
        {
            $query .= $db->generateSQLINStatement( $categories, 'vrpc.product_category_id', false, false, 'int' );
        }
        else
        {
            $query .= "vrpc.product_category_id =" . (int) $categories;
        }

        $rows = $db->arrayQuery( $query );

        return $rows[0]['count'];
    }

    /**
     * Return number of VAT rules referencing given VAT type.
     *
     * \param $vatID ID of VAT type to count VAT rules for.
     * \public
     * \static
     */
    static function fetchCountByVatType( $vatID )
    {
        $rows = eZPersistentObject::fetchObjectList( eZVatRule::definition(),
                                                     array(),
                                                     array( 'vat_type' => (int) $vatID ),
                                                     false,
                                                     null,
                                                     false,
                                                     false,
                                                     array( array( 'operation' => 'count( * )',
                                                                   'name' => 'count' ) ) );
        return $rows[0]['count'];
    }

    static function create()
    {
        $row = array(
            "id" => null,
            "country_code" => null,
            "vat_type" => null
            );
        return new eZVatRule( $row );
    }

    /**
     * Remove given VAT charging rule.
     */
    static function removeVatRule( $id )
    {
        $db = eZDB::instance();

        $db->begin();

        // Remove product categories associated with the rule.
        eZVatRule::removeProductCategories( $id );

        // Remove the rule itself.
        eZPersistentObject::removeObject( eZVatRule::definition(), array( "id" => $id ) );

        $db->commit();
    }

    function store( $fieldFilters = null )
    {
        $db = eZDB::instance();
        $db->begin();

        // Store the rule itself.
        parent::store( $fieldFilters );

        // Store product categories associated with the rule,
        $this->removeProductCategories();
        $categories = $this->attribute( 'product_categories' );
        if ( $categories )
        {
            foreach ( $categories as $category )
            {
                $query = sprintf( "INSERT INTO ezvatrule_product_category " .
                                  "(vatrule_id, product_category_id) VALUES (%d,%d)" ,
                                  $this->ID, $category['id'] );
                $db->query( $query );
            }
        }

        $db->commit();
    }

    /**
     * \private
     */
    function productCategories()
    {
        // If product categories for this rule have not been fetched yet
        if ( $this->ProductCategories === null )
        {
            // fetch them
            $db = eZDB::instance();
            $query = "SELECT pc.* FROM ezproductcategory pc, ezvatrule_product_category vrpc WHERE" .
                     " pc.id = vrpc.product_category_id AND" .
                     " vrpc.vatrule_id = {$this->ID}";
            $this->ProductCategories = $db->arrayQuery( $query );
        }

        return $this->ProductCategories;
    }

    /**
     * Return product categories as string, separated with commas.
     *
     * \private
     */
    function productCategoriesString()
    {
        $categories = $this->attribute( 'product_categories' );
        if ( !$categories )
        {
            $result = ezpI18n::tr( 'kernel/shop', 'Any' );
            return $result;
        }

        $categoriesNames = array();
        foreach ( $categories as $cat )
            $categoriesNames[] = $cat['name'];

        return join( ', ', $categoriesNames );
    }

    /**
     * Return IDs of product categories associated with the rule.
     *
     * \private
     */
    function productCategoriesIDs()
    {
        $catIDs = array();
        $categories = $this->attribute( 'product_categories' );

        if ( $categories )
        {
            foreach ( $categories as $cat )
                $catIDs[] = $cat['id'];
        }

        return $catIDs;
    }

    /**
     * Return names of product categories associated with the rule.
     *
     * \private
     */
    function productCategoriesNames()
    {
        $catNames = array();
        $categories = $this->attribute( 'product_categories' );

        if ( $categories )
        {
            foreach ( $categories as $cat )
                $catNames[] = $cat['name'];
        }

        return $catNames;
    }


    /**
     * Return VAT type name.
     */
    function vatTypeName()
    {
        $vatType = eZVatType::fetch( $this->attribute( 'vat_type' ) );
        return $vatType->attribute( 'name' );
    }

    /**
     * Return VAT type object.
     */
    function vatTypeObject()
    {
        return eZVatType::fetch( $this->attribute( 'vat_type' ) );
    }

    /**
     * Returns country name
     */
    function country()
    {
        if ( $this->attribute( 'country_code' ) != '*' )
        {
            $countryINI = eZINI::instance( 'country.ini' );
            $countryName = $countryINI->variable( $this->attribute( 'country_code' ) , 'Name' );
        }
        else
        {
            $countryName = '*';
        }
        return $countryName;
    }

    /**
     * Remove product categories with the rule from DB.
     *
     * \private
     * \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     * the calls within a db transaction; thus within db->begin and db->commit.
     */
    function removeProductCategories( $ruleID = false )
    {
        if ( $ruleID === false )
            $ruleID = $this->ID;
        $ruleID =(int) $ruleID;
        $db = eZDB::instance();
        $db->query( "DELETE FROM ezvatrule_product_category WHERE vatrule_id = $ruleID" );
    }

    /**
     * Remove references to the given product category.
     *
     * \public
     * \static
     * \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     * the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function removeReferencesToProductCategory( $cartegoryID )
    {
        $db = eZDB::instance();
        $query = "DELETE FROM ezvatrule_product_category WHERE product_category_id=" . (int) $cartegoryID;
        $db->query( $query );
    }


    public $ProductCategories;
}

?>
