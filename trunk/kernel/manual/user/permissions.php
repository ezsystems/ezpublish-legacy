<h1>Permissions</h1>

<ul>
  <li><a href="#Users">Defining users</a></li>
  <li><a href="#Roles">Defining roles</a></li>
</ul>
<br/>

<h2 id="Users"> Defining users</h2>
<p>
A "user" is defined as a person or group that visits your site.</p>
<p>You can give users or groups different access possibilities to your site. </p>
<p>Adding a person or group in eZ publish is very simple. Click the "User" button in the "User" window.
Then you can choose "User" or "User group" by highlighting that name in the drop down menu and click "New".</p>
<p>When inside the "Edit user - New user" window you write in name, description, passwords etc depending on
where you are. When you are done editing, click "Send for publishing".</p>

<p>Typical user groups on sites can be Anonymous, Administrators, Editors, Paying customers, Partners
or individual users. It is of course all up to you what you name your users.</p>
<br/>

<h2 id="Roles">Defining roles</h2>
<p>
When you have defined your different users or user groups you assign different roles to them.
These roles define what access rights the user or user group have on your site.</p>

<p>The typical scenario is that you name all visitors to your site for "Anonymous". The anonymous user
gets access to a part of your site, or the entire site, depending on what kind of web site you have.
Another group of visitors will log in and these are given access to other parts of the site as well.
For instance, if you build an Intranet site, you could let anyone see the front page, but they would need
to login in order to access anything else.</p>

<p>When you have defined or named a user group you click "Roles" in the "User" box. You will see a list
of all the different roles you have added. Click the "Edit" button to modify a role, and the "Assign"
button to assign it to a user or user group.</p>

<p>If you choose to edit a role, you will see a list of the "Current policies". A policy is a simple
access specification. Roles are made up of one or more policies.</p>

<p><img src="/kernel/manual/user/images/roles.gif" border="1"></p>

<p>This list shows module, function and limitations for each policy.
"Module" and "Function" shows what module and function you are specifying access to.
"Limitation list" is a list of further access limitations. The third policy in the above example specifies
access to create objects of the class Innlegg, provided that the parent object is of the class Forum or
Innlegg.

<!-- <p><img src="/kernel/manual/user/images/roles3.jpg" border="1"></p> -->

<p>To create a new policy click the "Create policy" button. This process is divided into three steps.</p>

<h3>Step 1. Give access to module.</h3>
<p>Here you can choose what module the user can get access to. You can choose all modules or only one of
them. Let us say you want to let the user work with "Content". Next you can decide if you want to give the
user full access to the content module or give him limited access. We will choose the "Allow limited". This
takes us to step two.
</p>
<p><img src="/kernel/manual/user/images/roles4.gif" border="1"></p>

<h3>Step 2. Specify function in module content.</h3>
<p>Now we will decide what this user can do with the content we have allowed him to work with.
We can give him access to read, create, edit, remove or version read content. In this example we choose
to give him access to create content.
Again we can choose to give him access to every function, but we want to restrict him to a limited
access with "Allow limited". This takes us to step three.</p>

<h3>Step 3. Specify limitations in function create in module content.</h3>
<p>Step 3 is divided into three logical lists; Class, Section and Parent class. In the first list you
can decide what classes the user can create objects of. In the second list we decide in which sections he
can create content. In the third list we decide what kind of parent objects he can create objects in.
You can specify "Any" in one or more of these lists if you don't want this limitation.</p>

<p>When you now click "OK" you have created a new policy. You can create more policies to fine-tune the
access rights. When you are done and ready to publish and assign the role to a user or user group, you make
it "live" by clicking the "Apply" button.</p>

<p>Removing policies is also very simple. Mark the policy you want to remove and click "Remove policy".</p>
