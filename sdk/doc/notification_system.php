<?php
//
// Created on: <10-Sep-2002 13:34:43 bf>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

$DocResult = array( 'title' => 'Notification system' );

?>

<p>
Users of a website is often interested in getting feedback when certain
tings are published/changed on the site. E.g. you would want to know when
new articles in the music section are published or when somebody replies
to your message in a forum.
</p>

<p>
These notifications are generalized in eZ publish to enable an extensible
notification system.
</p>

<h2>Message gateway</h2>
<p>
The message geteway can send messages to users via different plugins.
Every user can configure how he wants to be updated. E.g. internal
eZ publish message/task, e-mail, sms etc..
</p>

<p>
Messages can be sent in bulk or when the message event occurs.
</p>

Setting: Send notification in bulk. E.g. once a week. Or immediately.


<h2>Personalized message filter</h2>

<p>
Every registered and logged in user can create their own notification rules.
</p>

<h2>Notification rules</h2>

<h3>Examples</h3>
<table class="example">
<tr>
    <td>
    Check of "I want to receive news from this site". This can then
    be used to send notification whenever articles are published.
    Can also be used to send "manual" newsletters to registered users.
    </td>
</tr>
</table>

<table class="example">
<tr>
    <td>
    Notify me when someone replies to this message. E.g. answer on a
    forum posting. Message comment on an article. Book review.
    </td>
</tr>
</table>

<table class="example">
<tr>
    <td>
    I'm interested in "Pop&Rock and Jazz". Used to send newsletter
    to registered users.

    </td>
</tr>
</table>


<h2>Notification triggers</h2>

<p>
</p>
