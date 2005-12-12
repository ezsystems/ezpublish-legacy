<?php

$module =& $Params['Module'];

$error = false;
$originalCurrencyCode =& $Params['Currency'];
$currencyCode = false;
$currencySymbol = false;
$customRate = '0.0000';
$factor = '1.0000';

if ( $module->isCurrentAction( 'Cancel' ) )
{
    return $module->redirectTo( '/shop/currencylist/' );
}
else if ( $module->isCurrentAction( 'Create' ) ||
          $module->isCurrentAction( 'StoreChanges' ) )
{
    $originalCurrencyCode = $module->hasActionParameter( 'OriginalCurrencyCode' ) ? $module->actionParameter( 'OriginalCurrencyCode' ) : '';
    $currencyCode = $module->hasActionParameter( 'CurrencyCode' ) ? $module->actionParameter( 'CurrencyCode' ) : '';
    $currencySymbol = $module->hasActionParameter( 'CurrencySymbol' ) ? $module->actionParameter( 'CurrencySymbol' ) : '';
    $customRate = $module->hasActionParameter( 'CustomRate' ) ? $module->actionParameter( 'CustomRate' ) : '0.0000';
    $factor = $module->hasActionParameter( 'RateFactor' ) ? $module->actionParameter( 'RateFactor' ) : '1.0000';

    include_once( 'kernel/shop/classes/ezcurrencydata.php' );
    include_once( 'kernel/shop/classes/ezcurrencyrate.php' );

    $currency = false;
    if ( $module->isCurrentAction( 'Create' ) )
    {
        $currency = eZCurrencyData::create( $currencyCode, $currencySymbol );
    }
    else if ( $module->isCurrentAction( 'StoreChanges' ) )
    {
        $currency = eZCurrencyData::fetch( $originalCurrencyCode );

    }

    if ( is_object( $currency ) )
    {
        $rate = eZCurrencyRate::create( $currency->attribute( 'code' ), '0.0000', $customRate, $factor );
        if ( is_object( $rate ) )
        {
            $db =& eZDB::instance();
            $db->begin();
            $currency->store();
            $rate->store();
            $db->commit();

            return $module->redirectTo( $module->functionURI( 'currencylist' ) );
        }
        else
        {
            $error = eZCurrencyRate::errorMessage( $rate );
        }
    }
    else
    {
        $error = eZCurrencyData::errorMessage( $currency );
    }
}

$pathText = '';
if ( strlen( $originalCurrencyCode ) > 0 )
{
    // we are in 'edit' mode
    $pathText = ezi18n( 'kernel/shop', 'Edit currency' );

    if ( $currencyCode === false )
    {
        // first time in 'edit' mode? => initialize template variables
        // with existing data.
        include_once( 'kernel/shop/classes/ezcurrencydata.php' );
        include_once( 'kernel/shop/classes/ezcurrencyrate.php' );

        $currency = eZCurrencyData::fetch( $originalCurrencyCode );
        eZDebug::writeDebug( $currency, 'lazy: $currency' );
        if ( is_object( $currency ) )
        {
            $currencyCode = $currency->attribute( 'code' );
            $currencySymbol = $currency->attribute( 'symbol' );
            $rate = eZCurrencyRate::fetch( $currencyCode );
            if ( is_object( $rate ) )
            {
                $customRate = $rate->attribute( 'custom_value' );
                $factor = $rate->attribute( 'factor' );
            }
        }
    }
}
else
{
    // we are creating new currency
    $pathText = ezi18n( 'kernel/shop', 'Create new currency' );
}

include_once( 'kernel/common/template.php' );
$tpl =& templateInit();

$tpl->setVariable( 'error', $error );
$tpl->setVariable( 'original_currency_code', $originalCurrencyCode );
$tpl->setVariable( 'currency_code', $currencyCode );
$tpl->setVariable( 'currency_symbol', $currencySymbol );
$tpl->setVariable( 'custom_rate', $customRate );
$tpl->setVariable( 'rate_factor', $factor );


$Result = array();
$Result['content'] =& $tpl->fetch( "design:shop/editcurrency.tpl" );
$Result['path'] = array( array( 'text' => $pathText,
                                'url' => false ) );
?>