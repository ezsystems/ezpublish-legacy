
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
$DocResult["title"] = "Security handling";
?>

<p>
This document will explain to what extent security is considered
in eZ publish. During the design of eZ publish security was one
of the factors constantly considered. The performance and flexibility
were important factors during the design.
</p>
<p>
When you consider security there are two main parts you need to consider,
authentication and authorization. Authentication is a method
of recognizing the user which accesses the system, i.e. login. Authorization
defines what the user has access to when already logged in.
</p>
<h2>Authentication</h2>
<p>
eZ publish identification is based on the combination of username
and password or e-mail and password. To log in a user supplies the combination
of username/e-mail and password to the system. eZ publish  will then look for 
a hashed version of the password in the user table. The password is normally 
stored as an md5 of the password, but this is configurable. This means that 
the original password is not stored, but encrypted with a one way encryption.
If the system matches a username/e-mail in combination with a password hash 
the user is authenticated and logged in to the system.
</p>
<p>
eZ publish sets a session variable for the current session which indicates the 
current authenticated user. This session variable is stored in the eZ publish database
and is not sent from the server in any way. 
</p>
<p>
Every session is identified by a session key, which is a unique md5 hash. This
session key is normally stored in a cookie, but it can also be appended to the URL.
The latter is to be considered less secure as the session key then will be stored
in the apache access log and is therefore accessable by any user on the server which
has read access to apaches access log. It's up to the PHP configuration to decide if
URL based sessions are supported.
</p>
<p>
As long as the session key is kept from other users the authentication is secure. 
Session keys are sent from the users webbrowser to the server on every page load.
It is possible to sniff the TCP traffic to get access to this key. If the connection
between the users webbrowser and the server is using SSL encryption it will be alot
harder for the user to get access to the session key.
</p>
<p>
To get a more secure setup you can set the session timeout to a lower value.
</p>
<p>
If the user is not logged in he is authenticated as the anonymous user 
in the system. The eZ publish administrator can restrict the access of the anonymous
user at the same level as any other user. It serves the function of a default user.
</p>

<p>
<img src="/doc/images/authentication.gif" border="0" alt="Authorization" />
</p>

<h2>Authorization</h2>

<p>
The authorization in eZ publish is done by the role based permission system. 
This is a central component in eZ publish and will automatically
support new or 3rd pary plugin modules.
</p>
<p>
eZ publish 3 is secure by default, i.e. you do not have access to do anything with
the system unless directly specified. 
</p>
<p>
eZ publish will first check if the user is logged in (authenticated), then if
the user has access to the current site access ( admin/user ). You can require that
the user needs to be logged in, i.e. be authenticated as another user than the 
anonymous (default) user.
</p>
<p>
If the user has access to the current site access eZ publish will check if 
the user has access to the current module. Users can have access to either the
whole module or specific functions in that module. If the user has access to the whole
module function level permission checks are skipped and access granted.
</p>
<p>
If the user has access to only some functions in the given module then eZ publish
will check if the current function matches one of the functions the user has access
to. If the function matches access is granted. 
</p>
<p>
It is also possible to limit the specific function with function limitations. This
can e.g. be a limitation to only read articles in a specific section. When the limitation
matches the current function, the given function is responsible for only returning the
data which matches the limitation or return access denied.
</p>
<p>
This means that you have access control on both functional and data level.
</p>
<p>
It is possible to specify that the user has access to everything, then the permission
check on module and function level is bypassed. This setting is normally used
for administrators.
</p>
<p>
The diagram below shows an overview of the permission system.
</p>
<p>
<img src="/doc/images/authorization.gif" border="0" alt="Authorization" />
</p>

