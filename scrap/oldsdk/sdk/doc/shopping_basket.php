<?php
//
// Created on: <02-Jul-2002 11:10:10 bf>
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

<h1>Shopping basket</h1>
<p>
The default action on products is to assign the product to the current user. The basket can
contain as many products as the user wants to buy. The basket is session based and can not be
shared between different computers. The basket does not require the user to log in before it's
used.
</p>

<p>
Each item you store in the basket has the following attributes:
</p>
<ul>
	<li>Content object/product</li>
	<li>Unit price</li>
	<li>Count</li>
	<li>VAT</li>
	<li>Sub total</li>
</ul>

<p>
The basket will also calculate the total price for the product. VAT will be
calculated on a pr item basis and as a total for the whole basket. Shipping
cost will be calculated, this will however only show the default shipping. The user
can decide to change the shipping type in the checkout.
</p>

<p>
The diagram below shows the different states of the basket.
</p>
<img src="/doc/images/basket_state.png" alt="Basket" />
