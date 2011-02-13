<?php
/**
 * File containing the eZMultiPriceTypeRegression class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZMultiPriceTypeRegression extends ezpDatabaseTestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZMultiPriceType Regression Tests" );
    }

    /**
     * Test scenario for issue #13712: Multiprice datatype shows wrong price after multiple calls in template
     *
     * Test Outline
     * ------------
     * 1. Create a euro currency
     * 2. Create a VAT type of 10 %
     * 3. Create a content class with an attribute of the datatype ezmultiprice
     * 4. Create a content object of this content class and set a custom price ex. VAT with the VAT type of 10% that we created
     * 5. Subsequently retrieve the attribute 'inc_vat_price_list'
     *
     * @result: the returned eZMultiPriceData instances differ on each call, their values are increased each time with VAT
     * @expected: the returned eZMultiPriceData instances are equal
     * @link http://issues.ez.no/13712
     * @group issue_13712
     */
    public function testMultipleCallsToCalculatedPrice()
    {
        $currencyCode = 'EUR';

        // create currency
        $currencyParams = array(
            'code' => $currencyCode,
            'symbol' => false,
            'locale' => 'eng-GB',
            'custom_rate_value' => 0,
            'rate_factor' => 1 );

        $currency = eZCurrencyData::create( $currencyCode, '€', 'eng-GB', 0, 0, 1 );
        $currency->store();

        $currencyID = $currency->attribute( 'id' );
        $this->assertType( 'integer', $currencyID );

        // create VAT type
        $row = array( 'name' => 'Test', 'percentage' => 10.0 );
        $vatType = new eZVatType( $row );
        $vatType->store();
        $vatTypeID = $vatType->attribute( 'id' );
        $this->assertType( 'integer', $vatTypeID );

        $class = eZContentClass::create( false, array( 'name' => 'eZMultiPrice::testMultipleCallsToCalculatedPrice',
                                                       'identifier' => 'ezmultiprice_test' ) );
        $class->store();
        $classID = $class->attribute( 'id' );
        $this->assertType( 'integer', $classID );

        $attributes = $class->fetchAttributes();

        // add class attributes
        $newAttribute = eZContentClassAttribute::create( $classID, 'ezmultiprice', array( 'name' => 'Test',
                                                                                          'identifier' => 'test' ) );
        $dataType = $newAttribute->dataType();
        $dataType->initializeClassAttribute( $newAttribute );
        $newAttribute->setAttribute( eZMultiPriceType::DEFAULT_CURRENCY_CODE_FIELD, $currencyCode );
        $newAttribute->setAttribute( eZMultiPriceType::VAT_ID_FIELD, $vatTypeID );
        $newAttribute->store();
        $attributes[] = $newAttribute;

        $class->storeDefined( $attributes );

        $contentObject = $class->instantiate();

        $version = $contentObject->currentVersion();

        $dataMap = $version->dataMap();
        $multiPrice = $dataMap['test']->content();

        $multiPrice->setAttribute( 'selected_vat_type', $vatTypeID );
        $multiPrice->setAttribute( 'is_vat_included', eZMultiPriceType::EXCLUDED_VAT );
        $multiPrice->setCustomPrice( $currencyCode, 100 );
        $multiPrice->updateAutoPriceList();

        $dataMap['test']->setContent( $multiPrice );
        $dataMap['test']->setAttribute( 'data_text', $vatTypeID . ',' . eZMultiPriceType::EXCLUDED_VAT );
        $dataMap['test']->store();

        // test values
        $firstIncVatPriceList = $multiPrice->attribute( 'inc_vat_price_list' );
        $this->assertArrayHasKey( 'EUR', $firstIncVatPriceList );
        $firstCallValue = $firstIncVatPriceList['EUR']->attribute( 'value' );

        $secondIncVatPriceList = $multiPrice->attribute( 'inc_vat_price_list' );
        $this->assertArrayHasKey( 'EUR', $secondIncVatPriceList );
        $secondCallValue = $secondIncVatPriceList['EUR']->attribute( 'value' );

        $this->assertEquals( $firstCallValue, $secondCallValue );

        $thirdIncVatPriceList = $multiPrice->attribute( 'inc_vat_price_list' );
        $this->assertArrayHasKey( 'EUR', $thirdIncVatPriceList );
        $thirdCallValue = $thirdIncVatPriceList['EUR']->attribute( 'value' );

        $this->assertEquals( $firstCallValue, $thirdCallValue );
    }
}

?>
