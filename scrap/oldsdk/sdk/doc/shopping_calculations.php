<?php
//
// Created on: <02-Jul-2002 11:11:06 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

?>

<h1>Calculation</h1>
<p>
Besides calculating the total price for all products added to the cart or bought
eZ publish handles numerous calculations. Including VAT, shipping and custom costs.
</p>

<h2>VAT</h2>
<p>
The VAT is the Value Added Cost or TAX which needs to be specified on each product. The
different VAT values are then calculated by eZ publish. Each product can be entered including
or excluding VAT, also it can be part of different VAT groups. Normally B2B setups of
eZ publish have prices excluding TAX and consumer webshops have the products including VAT.
VAT is not charged if there is an international transaction.
</p>

<p>
The table below shows an example of an VAT calculation.
</p>

<table class="listing">
<tr>
	<th>
	Product name
	</th>
	<th>
    Unit price
	</th>
	<th>
    Quantity
	</th>
	<th>
    Total ex. VAT
	</th>
	<th>
    Total inc. VAT
	</th>
</tr>
<tr>
	<td>
    Product inc. VAT
	</td>
	<td>
    100
	</td>
	<td>
    2
	</td>
	<td>
    160
	</td>
	<td>
    200
	</td>
</tr>
<tr>
	<td>
    Product ex. VAT
	</td>
	<td>
    125
	</td>
	<td>
    2
	</td>
	<td>
    200
	</td>
	<td>
    250
	</td>
</tr>
<tr>
	<th>
    Subtotal
	</th>
	<td>
	</td>
	<td>
	</td>
	<td>
	</td>
	<td>
    450
	</td>
</tr>
<tr>
	<th>
    Shipping and handling
	</th>
	<td>
	</td>
	<td>
	</td>
	<td>
	</td>
	<td>
    xx
	</td>
</tr>
<tr>
	<th>
    Total
	</th>
	<td>
	</td>
	<td>
	</td>
	<td>
	</td>
	<td>
    450
	</td>
</tr>
<tr>
	<th>
    &nbsp;
	</th>
	<td>
	</td>
	<td>
	</td>
	<td>
	</td>
	<td>

	</td>
</tr>
<tr>
	<th>
    Tax basis
	</th>
	<th>
	Percentage
	</th>
	<td>
	</td>
	<td>
	</td>
	<th>
	VAT
	</th>
</tr>
<tr>
	<td>
	360
	</td>
	<td>
	25 %
	</td>
	<td>
	</td>
	<td>
	</td>
	<td>
	90
	</td>
</tr>


</table>

<h2>Shipping</h2>
<p>
Every product can belong to a different shipping group. Each shipping group has two values,
startprice and aditional price. The shipping is then calculated as the most expensive
start price plus the aditional prices for each aditional item.
</p>

<p>
The shipping will be based on a plugin system so you will be able to create your own
shipping calculation. E.g. if you need shipping based on the actual weight of the product
then the shipping calculation can be overridden.
</p>

<h2>Discounts</h2>
<p>
Each price calculation can have a discount for a given user. There are two basic discount
types, percentage based and custom price. The percentage will give a usera certain discount
on all or selected products. The custom price will specify a custom price for each customer
group.
</p>

<h2>Custom cost</h2>
<p>
You will be able to add custom costs to the total of an order
E.g. wrapping.
</p>

<h2>Alternative currencies</h2>
<p>
You can specify alternative currencies which prices will be displayed in. These currencies
will only display the prices, you will only be able to do the actual transaction in a given
currency.
</p>
