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
$DocResult["title"] = "Security Standard";

?>

<p>
This document explains guidelines for PHP programmers on how to create secure code.
If you are a template designers or an end user you might want to try these documents instead.
</p>
<ul>
 <li><a href="/sdk/doc/view/template_coding_standard#security">Template designers</a></li>
 <li><a href="">End users</a></li>
</ul>

<h2>Passwords</h2>
<p>
Storing passwords should be done by creating a non-reversible hashed version of it. A very
good method for creating hashed versions is to use the <a href="http://www.faqs.org/rfcs/rfc1321.html">MD5 Message-Digest Algorithm</a>.
The password is fed to the <a href="http://www.php.net/manual/en/function.md5.php">md5 function</a> with the username and a unique
id for the site. This ensures that two users with the same password cannot be spotted in the DB tables,
not even across sites*.
</p>
<p>
<b>Note:</b> Make sure that the supplied username and password are sent using <a href="http://www.openssl.org/">SSL</a> when submitting a form. Otherwise
it's possible to sniff the traffic of a site and fetch the username/password.
</p>

<p class="footnote">* If users are to be shared across multiple sites, the site id must be supplied on all sites.</p>
<h3>MD5 example</h3>
<pre class="example"><? print( eZTextTool::highlightHTML(
'function createHash( $user, $password, $siteid )
{
    return md5( "$user\n$password\n$siteid" );
}

function authenticateHash( $user, $password, $siteid, $stored_hash )
{
    return createHash( $user, $password, $siteid ) == $stored_hash;
}
'
) );?>
</pre>

<h3>Implementation</h3>
<p>This method has been implemented in the <i>eZUser</i> datatype. See <i>kernel/classes/datatypes/ezuser/ezuser.php</i> for more details.</p>

<h2>Input validation</h2>
<p>
All input from the user should be validated before storing it. For instance when expecting
integer or date data always check to see if it is actually of the wanted type, if not
issue a warning to the user. The input may then be converted to a more reasonable state, for instance
dates should be converted to integers, but do not perform any escaping of text or similar text washing.
When the input is valid and in an acceptable form, store it as it is.
</p>

<h3>Reasons for not escaping input data</h3>
<ul>
<li>The data may not only be used for HTML output but may be sent to other clients which require them in their original form.</li>
<li>There's no way to know if the data is escaped or not after it has been saved, this means that inputting data from other sources than HTML needs escaping as well.</li>
</ul>

<pre class="example"><? print( eZTextTool::highlightHTML(
'$date = eZHTTPTool::postVariable( "Date" );
$date_obj = new eZDate( $date );
$date_num = $date_obj->value(); // Returns integer value
'
) );?>
</pre>


<?php
/* This is commented for now, do not remove!

<h2>Form tickets</h2>
<p>
To avoid Client side Trojans all forms must use a system called <i>tickets</i>. A
ticket is a unique key which is created dynamically for a given action. The ticket is created
on the server when a form is created and stored in a database as well as placed in the
form as a hidden variable. When the user submits the form data the code fetches the ticket
and authenticates it against the stored ticket in the DB table. If the ticket is valid
the action is performed and the ticket removed, if not a warning message is printed to
user telling him that he's probably been tricked by a Trojan.
</p>
<p>
Tickets can also be used to help with double-click problems from forms. The code
starts a check for the ticket, if it exists it is locked in some way and code
continues. If the user then double-clicks and the code is restarted it will see that
the ticket exists but is locked and thus shows a page explaining that the a double-click
was detected.
</p>

<h3>Ticket creation</h3>
<pre class="example"><? print( eZTextTool::highlightHTML(
'$ticket = eZTicket::createFor( "myaction" );

$tpl->setVariable( "ticket", "myaction-$ticket" );

$tpl->display( "myaction.tpl" );

// myaction.tpl
&lt;input type="hidden" name="ticket" value="{$ticket}"/&gt;'
) );?>
</pre>

<h3>Ticket validation</h3>
<pre class="example"><? print( eZTextTool::highlightHTML(
'$ticket = eZHTTPTool::postVariable( "ticket" );
if ( eZTicket::validate( "myaction", $ticket ) )
{
    eZTicket::destroy( "myaction", $ticket );
    ... // action!
}
else
{
    $tpl->display( "warning.tpl" );
}
'
) );?>
</pre>


*/
?>

<h3>References</h3>
<ul>
  <li><a href="http://shh.thathost.com/text/cleartext-passwords.txt">Why Clear Text Passwords are Bad, and How to Avoid Them[Sverre H. Huseby]</a></li>
  <li><a href="http://shh.thathost.com/text/client-side-trojans.txt">Client Side Trojans[Sverre H. Huseby]</a></li>
  <li><a href="http://shh.thathost.com/text/passing-data-03.txt">Why Escaping Quotes Will not Always Help[Sverre H. Huseby]</a></li>
  <li><a href="http://shh.thathost.com/text/passing-data-02.txt">On HTML Sanitizing on the Input Side[Sverre H. Huseby]</a></li>
  <li><a href="http://shh.thathost.com/text/passing-data-01.txt">Splitting Input Validation and Meta Character Escaping[Sverre H. Huseby]</a></li>
  <li><a href="http://shh.thathost.com/text/cross-site-scripting-01.txt">Cross-site Scripting and Timing When Stealing Sessions[Sverre H. Huseby]</a></li>
</ul>
