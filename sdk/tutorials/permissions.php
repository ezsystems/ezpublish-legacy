<?php
//
// Created on: <01-Dec-2002 12:43:53 sp>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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

?>

<h1>Permissions and sections </h1>

<p>
With the permission ( roles ) system in eZ Publish 3 you can very flexible limit access to different parts of your content. One of the ways to do it is to give limited acces to the sections of your site. For example you have a webshop with articles and products  and two groups of persons.Where one group of persons can write articles in the "articles" section and another group updates the products.
</p>

<p>
To  make that configuration you need to do next steps

<ul>
<li>you need to define two sections.</li>
<li>assign that sections to a different parts of you sitemap tree</li>
<li>create two different roles (or you can use allready existend roles and just change the access policies )</li>
<li>set up policies for that user roles </li>
<li>assign that roles to the user  </li>
</ul>
</p>

<h4>Define and assigning sections </h4>
<p>
To create a neew section you need to go to the sections part of admin interface in the "Set up" box  and  push "New" button there. Create two sections: "Articles" and "Products". <br>

<img src="/doc/images/section_new.png" alt="Creating a section" />
<br>
</p>
<p>
To assign created sections to the part of your site you need to click "assign" link. You will see so called "browse" page in which you can select a folder(s) ( in general any object of any class ) to assign section to. After you select a falder system will assign section to that folder and all its children.
</p>
<img src="/doc/images/browse.png" alt="Browse page" />

<h4>Roles setup</h4>
<p>
Go to the "roles" page. Create new role with the "New" button. You will be redirected to the role edit page. There you can set the name for role and  set up policies. To create new policy for role you click "New" at edit page.
</p>
<img src="/doc/images/role_edit.png" alt="Role edit page" />
<p>
Policy creation consist of max three steps.
<ul>
<li>selecting a module</li>
<li>selecting a function </li>
<li>creating limitation for fumction</li>
</ul>
</p>

<h5> Select module </h5>
<p>
Select "content" module from dropdown  and click "Allow limited".
</p>
<img src="/doc/images/policy_step1.png" alt="Selecting module" />


<h5> Select function </h5>
<p>
Select "create" function from dropdown and click "Allow limited"
</p>
<img src="/doc/images/policy_step2.png" alt="Selecting function" />

<h5> Adding limitation to the function </h5>
<p>
You create "policy limitations" when you want to allow user execute a module function with some limitations. For example user can read content from section "1" and only articles from that section. "Section 1" and "only articles" are limitations. So in our case we need to create policy limitation which will allow users to create objects only in one section. Depending of what role you are editing now ( for article editors or for product editors ) you need to select proper section(s) from list.
</p>
<img src="/doc/images/policy_step3.png" alt="Selecting function" />
<p>
In addition to section limitation you can limit access with classes as well. It will be more restricted policy. For example for "article editors" you want to limit classes of objects they can create to folders and articles. Selection for that case is shown on picture above.
</p>
<p>
After selecting limitation you click "Ok" button which will add policy to role.
</p>
<p>
You need to create policies for each function in "content" module. Create new policies  according to the permissions you want to give to the users which that role assigned to.
</p>
<p>
To save the role you need to click "Apply" button in "role edit" page. Modifications to role are not visible for the system until you save the role.
</p>
<p>
So you need to repeat the  steps described above for another role ( "Product editors" ). But you need to select different limitation parameters according to the role you are seting up.
</p>

<h5> Assign that roles to the user  </h5>

<p>
After you create roles. You need to assign that roles to users or user groups. If you are planing to have couple of product and article editors it is beter to create two user groups and assign roles to that user groups. After that role assigned to that user group  automatically  assigned  to  every user in that group. You can assign roles to users from two places: from role/view page or from role/list page. You are redirected to the role/view page after applying modifications to the role.
</p>
<img src="/doc/images/role_view.png" alt="Role view" />
<p>
After clicking "Assign" button you will be redirected to the "browse" page. You can select usergroups or users there. By clicking "select" on that page you assign role to the selected groups or users.
</p>
<p>
after assigning roles to the users configuration is done
</p>
