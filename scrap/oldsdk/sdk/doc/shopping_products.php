<?php
//
// Created on: <02-Jul-2002 11:09:42 bf>
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

<h1>Products</h1>
<p>
For products we use normal content objects in eZ publish. This means that you can
easily create fully custom products with the attributes you need. The difference from
normal content objects is the data types. When you create a product you use a combination
of normal content data types and e-commerce data types.
</p>

<p>
Below is a list of normal e-commerce data types. You can also create your own custom
data types if you need special functionality.
</p>

<ul>
	<li>Price</li>
	<li>Option</li>
	<li>Priced option</li>
	<li>Shipping group</li>
	<li>VAT group</li>
	<li>Add to wishlist - action button</li>
	<li>Add to cart - action button</li>
</ul>

<p>
A minimalistic e-commerce object would have a price data type and an add to cart action
button. A price, which is used as the product base price is absolutely needed to create
product.
</p>

<h2>Availability</h2>
<p>
The products can be available for a given time period. E.g. you can have a x-mas sale
with some related products which are only available from december to january. When the
product is no longer available for shopping it is set to a discontinued state. This means
that the product will be available if you e.g. have bookmarked it in your browser, but it
will not show up in searches nor will it be available for shopping.
</p>

<p>
eZ publish handles to number of products which are in stock. Whenever a product is bought
this number is decreaced. If there are no more products you can choose to make eZ publish
set the product to discontinued or you can just give a message to the user, e.g. "this
product will be delivered in 1-2 weeks".
</p>
