<?php
//
// Created on: <03-Jun-2002 13:43:16 bf>
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

$DocResult = array();
$DocResult["title"] = "XHTML Coding Standard";

?>
<p>
All HTML should be written in XHTML 1.0 (or higher). It should also be written with no
browser specific tags, this is to ensure that the application works in as many browsers
as possible. Java/ECMA script should be avoided. Frames are not recommended,
eZ publish is designed to work without frames, however it is fully possible to do so.
</p>
<p>
Reasons for not using frames are:
<ul>
<li>Hard to bookmark</li>
<li>Hard to print</li>
<li>Does not work correctly in all browsers</li>
</ul>

<h2>Syntax</h2>

HTML code should be written in lower case.
