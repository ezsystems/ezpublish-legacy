<?php
//
// Created on: <04-Jun-2002 09:09:16 bf>
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

include_once( "lib/ezutils/classes/eztexttool.php" );

$DocResult = array();
$DocResult["title"] = "Subversion standards";

?>

<h1>Subversion</h1>
<p>
Subversion is the system we use for version control for eZ publish. It's similar to CVS but
has some differences that make it a better version control system. A brief explanation of
the usage will be given, for more detailed information visit its home page.
</p>

<h2>Checking out</h2>
<p>
Checking out a copy of eZ publish is done by invoking the command:
</p>

<pre class="example">
svn co <i>uri</i>

for instance

svn co <i>http://zev.ez.no/svn/nextgen/trunk</i> -d nextgen
</pre>

<p>
Using the <i>-d</i> option will allow you to change the created directory to something different
than the one you're checking out.
</p>

<p>
The checkout command will ask you for a user name and password, you should however be aware
that the earliest versions of subversion <b>stores your password in clear text</b> on the checked out
version. This will however change in newer versions of subversion.
</p>

<h2>Checking for local changes</h2>
<p>
Whenever you've done some changes locally you can get an overview of
it by running the <i>status</i> command.
</p>

<pre class="example">
svn status

or

svn st
</pre>

<p>
It will then list all files which are modified, added, removed or not present in the repository.
This is done without asking the repository server since subversion keeps a copy of the original
file locally.

If you want to figure out changes done on the repository you run the same command with the
<i>-u</i> option.
</p>

<pre class="example">
svn st -u
</pre>

<h2></h2>

<h3>References</h3>
<ul>
  <li><a href="http://subversion.tigris.org">Subversion homepage</a></li>
</ul>
