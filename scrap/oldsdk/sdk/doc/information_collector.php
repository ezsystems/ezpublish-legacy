<?php
//
//
// Created on: <21-Nov-2002 16:21:43 bf>
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

<p>
Information collector is a name used to describe objects which can fetch
data from a visitor and display it later. Typical examples of information
collectors are feedback forms and polls.
</p>

<h2>Information collector datatypes</h2>
<p>
To create an information collector object you need to create a class which
included some special data types. If you add one or more information collector data
types to a class it will automatically be able to store and display this data.
The storage is done by a content action.
</p>

<p>
To create e.g. create a form you will need to create a class with the following
attributes:
</p>

<ul>
    <li>Title: text line ( descriptive text )</li>
    <li>Description: xml text ( descriptive text )</li>
    <li>Subject: data collector text line ( active component )</li>
    <li>Message: data collector text field ( active component )</li>
</ul>

<p>
When you then create an object of this form you will be able to add some descriptive
text in the title and description attributes and default text in the active components.
The user will then be able to fill in the subject and message fields and send the response
back to the site. This information will then be stored in the database, it can also be sent
on e-mail.
</p>

<h2>Presentation</h2>
<p>
When this data is stored the user will be redirected to a page where you can show
the data which the user sent and a message. This is template based so you can have
text like "Thanks for your feedback" etc. You also have the option to fetch statistical
information like how many feedbacks have been sent. If you have an option/enum you can
show the average answer in a graph. This can be used to show the result of an on-going
poll.
</p>

<h2>Layout</h2>
<p>
Since the information collector data types is part of a standard eZ publish class you
are able to customize the look using normal templates. E-mail sent is also template based
so you will have full control over the presentation of this data.
</p>
