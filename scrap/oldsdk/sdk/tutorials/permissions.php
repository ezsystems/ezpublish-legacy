<?php
//
// Created on: <01-Dec-2002 12:43:53 sp>
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

<h1>Permissions and sections</h1>

<p>
With the permission (roles) system in eZ Publish 3 you can very flexibly limit access to different parts of your content. One of the ways to do it is to give limited access to the sections of your site. For example can you have a web shop with articles and products and two groups of persons, where one group of persons can write articles in the "articles" section and the other group updates the products.
</p>

<p>
To make such a configuration you need to do the next five steps:
<ul>
<li>you need to define two sections.</li>
<li>assign these sections to a different parts of you sitemap tree</li>
<li>create two different roles (or you can use already existing roles and just change the access policies)</li>
<li>set up policies for these user roles</li>
<li>assign these roles to the users.</li>
</ul>
</p>

<h2>Define and assign sections</h2>
<p>
To create a new section you need to go to the sections part of the admin interface in the "Set up" menu box and click the "New" button there. Create two sections: "Articles" and "Products".
</p>

<p>
<img src="/doc/images/section_new.png" alt="Creating a section" />
</p>

<p>
To assign the created sections to certain parts of your site you click the "assign" link. You will see the so called "browse" page in which you can select one or more folders (in general any object of any class) to assign the section to. After you select a folder the system will assign the section to that folder and all its children.
</p>

<p>
<img src="/doc/images/browse.png" alt="Browse page" />
</p>

<h2>Roles setup</h2>
<p>
Go to the "roles" page. Create a new role with the "New" button. You will be redirected to the role edit page. There you
can set the name for the role and set up policies. To create a new policy for the role you click "New" at the edit page.
</p>

<p>
<img src="/doc/images/role_edit.png" alt="Role edit page" />
</p>

<p>
Policy creation consist of (at maximum) three steps.
<ul>
<li>selecting a module</li>
<li>selecting a function </li>
<li>creating limitations for function</li>
</ul>
</p>

<h2>Select module</h2>
<p>
Select the "content" module from the dropdown  and click "Allow limited".
</p>

<p>
<img src="/doc/images/policy_step1.png" alt="Selecting module" />
</p>

<h2>Select function</h2>
<p>
Select the "create" function from the dropdown and click "Allow limited"
</p>
<img src="/doc/images/policy_step2.png" alt="Selecting function" />

<h2>Adding limitations to the function</h2>
<p>
You create "policy limitations" when you want to allow the user to execute a module function with some limitations.
For example a user can read content from section "1" and only articles from that section. "Section 1" and "only articles"
are limitations. So in our case we need to create a limitation policy which will allow users to create objects only in
one section. Depending on what role you are editing now (for article editors or for product editors) you need to select
proper section(s) from the list.
</p>

<p>
<img src="/doc/images/policy_step3.png" alt="Selecting function" />
</p>

<p>
In addition to section limitations you can limit access by classes as well. For example for "article editors" you want to
limit the classes of objects they can create to folders and articles. This is shown in the picture above.
</p>

<p>
After selecting limitations you click the "Ok" button which will add the policy to the role.
</p>

<p>
You need to create policies for each function in the "content" module. Create new policies according to the permissions
you want to give to the users that role is assigned to.
</p>

<p>
To save the role you click the "Apply" button on the "role edit" page. Modifications to the role are not visible for the
system until you save the role.
</p>

<p>
Now you need to repeat the steps described above for another role ("Product editors"). You must select different
limitation parameters according to the role you are setting up.
</p>

<h2>Assign these roles to the users</h2>

<p>
After you have created the roles, you need to assign them to users or user groups. If you are planning to have a couple
of product and article editors it is better to create two user groups and assign the roles to these user groups. After
that any users in these groups will have the roles assigned to them. You can assign roles to users from two places:
From the role/view page or from the role/list page. You are redirected to the role/view page after applying modifications
to the role.
</p>

<p>
<img src="/doc/images/role_view.png" alt="Role view" />
</p>

<p>
After clicking the "Assign" button you will be redirected to the "browse" page. You can select user groups or users there.
By clicking "select" on that page you assign the role to the selected groups or users.
</p>

<p>
After assigning roles to the users the configuration is done.
</p>
