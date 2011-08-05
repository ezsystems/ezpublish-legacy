<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$FunctionList = array();
$FunctionList['basket'] = array( 'name' => 'basket',
                                 'operation_types' => array( 'read' ),
                                 'call_method' => array( 'class' => 'eZShopFunctionCollection',
                                                         'method' => 'fetchBasket' ),
                                 'parameter_type' => 'standard',
                                 'parameters' => array( ) );

$FunctionList['best_sell_list'] = array( 'name' => 'best_sell_list',
                                         'operation_types' => array( 'read' ),
                                         'call_method' => array( 'class' => 'eZShopFunctionCollection',
                                                                 'method' => 'fetchBestSellList' ),
                                         'parameter_type' => 'standard',
                                         'parameters' => array( array( 'name' => 'top_parent_node_id',
                                                                       'type' => 'integer',
                                                                       'required' => true ),
                                                                array( 'name' => 'limit',
                                                                       'type' => 'integer',
                                                                       'required' => false ),
                                                                array( 'name' => 'offset',
                                                                       'type' => 'integer',
                                                                       'required' => false,
                                                                       'default' => false ),
                                                                array( 'name' => 'start_time',
                                                                       'type' => 'integer',
                                                                       'required' => false,
                                                                       'default' => false ),
                                                                array( 'name' => 'end_time',
                                                                       'type' => 'integer',
                                                                       'required' => false,
                                                                       'default' => false ),
                                                                array( 'name' => 'duration',
                                                                       'type' => 'integer',
                                                                       'required' => false,
                                                                       'default' => false ),
                                                                array( 'name' => 'ascending',
                                                                       'type' => 'boolean',
                                                                       'required' => false,
                                                                       'default' => false ),
                                                                array( 'name' => 'extended',
                                                                       'type' => 'boolean',
                                                                       'required' => false,
                                                                       'default' => false ) ) );

$FunctionList['related_purchase'] = array( 'name' => 'related_purchase',
                                         'operation_types' => array( 'read' ),
                                         'call_method' => array( 'class' => 'eZShopFunctionCollection',
                                                                 'method' => 'fetchRelatedPurchaseList' ),
                                         'parameter_type' => 'standard',
                                         'parameters' => array( array( 'name' => 'contentobject_id',
                                                                       'type' => 'integer',
                                                                       'required' => true ),
                                                                array( 'name' => 'limit',
                                                                       'type' => 'integer',
                                                                       'required' => true ) ) );

$FunctionList['wish_list'] = array( 'name' => 'wish_list',
                                    'operation_types' => array( 'read' ),
                                    'call_method' => array( 'class' => 'eZShopFunctionCollection',
                                                            'method' => 'fetchWishList' ),
                                    'parameter_type' => 'standard',
                                    'parameters' => array( array( 'name' => 'production_id',
                                                                  'type' => 'integer',
                                                                  'required' => true ),
                                                           array( 'name' => 'offset',
                                                                  'type' => 'integer',
                                                                  'required' => false,
                                                                  'default' => false ),
                                                           array( 'name' => 'limit',
                                                                  'type' => 'integer',
                                                                  'required' => false,
                                                                  'default' => false ) ) );

$FunctionList['wish_list_count'] = array( 'name' => 'wish_list_count',
                                          'operation_types' => array( 'read' ),
                                          'call_method' => array( 'class' => 'eZShopFunctionCollection',
                                                                  'method' => 'fetchWishListCount' ),
                                          'parameter_type' => 'standard',
                                          'parameters' => array( array( 'name' => 'production_id',
                                                                        'type' => 'integer',
                                                                        'required' => true ) ) );

$FunctionList['current_wish_list'] = array( 'name' => 'current_wish_list',
                                            'operation_types' => array( 'read' ),
                                            'call_method' => array( 'class' => 'eZShopFunctionCollection',
                                                                    'method' => 'fetchCurrentWishList' ),
                                            'parameter_type' => 'standard',
                                            'parameters' => array() );

$FunctionList['order'] = array( 'name' => 'order',
                                'operation_types' => array( 'read' ),
                                'call_method' => array( 'class' => 'eZShopFunctionCollection',
                                                        'method' => 'fetchOrder' ),
                                'parameter_type' => 'standard',
                                'parameters' => array( array( 'name' => 'order_id',
                                                              'type' => 'integer',
                                                              'required' => true ) ) );
$FunctionList['order_status_history_count'] = array( 'name' => 'order_status_history_count',
                                                     'operation_types' => array( 'read' ),
                                                     'call_method' => array( 'class' => 'eZShopFunctionCollection',
                                                                             'method' => 'fetchOrderStatusHistoryCount' ),
                                                     'parameter_type' => 'standard',
                                                     'parameters' => array( array( 'name' => 'order_id',
                                                                                   'type' => 'integer',
                                                                                   'required' => true ) ) );
$FunctionList['order_status_history'] = array( 'name' => 'order_status_history',
                                               'operation_types' => array( 'read' ),
                                               'call_method' => array( 'class' => 'eZShopFunctionCollection',
                                                                       'method' => 'fetchOrderStatusHistory' ),
                                               'parameter_type' => 'standard',
                                               'parameters' => array( array( 'name' => 'order_id',
                                                                             'type' => 'integer',
                                                                             'required' => true ) ) );
$FunctionList['currency_list'] = array( 'name' => 'currency_list',
                                        'operation_types' => array( 'read' ),
                                        'call_method' => array( 'class' => 'eZShopFunctionCollection',
                                                                'method' => 'fetchCurrencyList' ),
                                        'parameter_type' => 'standard',
                                        'parameters' => array( array( 'name' => 'status',
                                                                      'type' => 'integer,string',
                                                                      'required' => false ) ) );

$FunctionList['currency'] = array( 'name' => 'currency',
                                   'operation_types' => array( 'read' ),
                                   'call_method' => array( 'class' => 'eZShopFunctionCollection',
                                                           'method' => 'fetchCurrency' ),
                                   'parameter_type' => 'standard',
                                   'parameters' => array( array( 'name' => 'code',
                                                                 'type' => 'string',
                                                                 'required' => true ) ) );

$FunctionList['preferred_currency_code'] = array( 'name' => 'preferred_currency_code',
                                                  'operation_types' => array( 'read' ),
                                                  'call_method' => array( 'class' => 'eZShopFunctionCollection',
                                                                          'method' => 'fetchPreferredCurrencyCode' ),
                                                  'parameter_type' => 'standard',
                                                  'parameters' => array( ) );

$FunctionList['user_country'] = array( 'name' => 'user_country',
                                       'operation_types' => array( 'read' ),
                                       'call_method' => array( 'class' => 'eZShopFunctionCollection',
                                                               'method' => 'fetchUserCountry' ),
                                       'parameter_type' => 'standard',
                                       'parameters' => array( ) );

$FunctionList['product_category_list'] = array( 'name' => 'product_category_list',
                                                'operation_types' => array( 'read' ),
                                                'call_method' => array( 'class' => 'eZShopFunctionCollection',
                                                                        'method' => 'fetchProductCategoryList' ),
                                                'parameter_type' => 'standard',
                                                'parameters' => array( ) );


$FunctionList['product_category'] = array( 'name' => 'product_category',
                                                'operation_types' => array( 'read' ),
                                                'call_method' => array( 'class' => 'eZShopFunctionCollection',
                                                                        'method' => 'fetchProductCategory' ),
                                                'parameter_type' => 'standard',
                                                'parameters' => array( array( 'name' => 'category_id',
                                                                              'type' => 'integer,string',
                                                                              'required' => true ) ) );
?>
