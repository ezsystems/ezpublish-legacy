<?php
//
// Created on: <19-Jun-2002 19:31:20 bf>
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

<h1>Other concepts</h1>

<h2>Information collector</h2>
<p>
In the class edit you can mark attributes as information collectors. This means that
they can be used to get information from the user. Not all data types can be used as
information collectors. Information collector will primarily be used for handling forms
and polls, but may be extended to handle other information collections as well.
See the <a href="/sdk/tutorials/view/forms">Creating forms</a> tutorial for an example.
</p>

<h2>Object version</h2>
<p>
Every content object consists of one or more versions. These versions contain the content
data for the content object. Only one version of an object can be considered published,
this is called the current version. Each version may belong to a different user.
</p>

<h2>Object translation</h2>
<p>
Every content object consists of minimum one version with one translation.
You can have as many translations of the content object as you like.
</p>
