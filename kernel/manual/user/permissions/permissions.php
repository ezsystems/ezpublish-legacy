<h1>Permissions</h1>

<ul>
	<li><a href="#Users">Defining users</a></li>
	<li><a href="#Roles">Defining roles</a></li>
</ul>
<br></br>

<h2 id="Users"> Defining users</h2>
<p>
	A "user" is defined as a person or group that visits your site. You can give this user or group different access possibilities to your site. 
Adding a person or groups in eZ publish is very simple. Click the "User" button in the "User" window. Then you can choose "User" or "User group" by highlighting that name in the dropdown menu and click "New". When inside the "Edit user - New user" window you write in name, description, passwords etc depending on where you are. When you are done writing in the specifics click "Send for publishing".
Typical user groups on sites can be Anonymous, Administrators, Editors, Paying customers, Partners or individual users. It is of course all up to you what you name your users.

</p>
<br></br>

<h2 id="Roles">Defining roles</h2>
<p>
	When you have defined your different users or user groups you assign them different roles. These roles define what possibilities the user or user group have access to on your site. Typical is that you name all visitors to your site for "Anonymous". The anonymous get access to a part of your site or the entire site depending on what kind of website you have. Another group of visitors Log in and these are given access to other parts of the site as well. for instance an Intranet site. Everybody can get to the front page but you need to Log in to get to see what's inside the Intranet.
By defining roles and assigning these to different access possibilities you can easily deny visitors access to parts of the site you want only visitors with privileges to see.
This is how you give the different groups different access:
When you have defined or named a user group you click "Roles" in the "User" box. When you have done that you will see a list of all the different roles you have added. To modify these you click the "Edit" button on the user you want to assign right to. You click "Edit" button on the role if you want to edit policies in it and the "Assign" button to assign it to a user or user group. This will bring you to the "Role edit Anonymous" page if you want to edit the rights for the "Anonymous" group.
You will see a list of the "Current policies". This shows what rights you have assigned the "Anonymous". 

<img src="/kernel/manual/user/permissions/images/roles.jpg">

This list shows Module, function and Limitations list. 
The "Module" shows what function you are allowed to work with. This example shows 3x content and 1 notification. The "Function" shows what you are allowed to do with the module. The "limitations list" indicates where you are allowed to use the functions and modules.

 <img src="/kernel/manual/user/permissions/images/roles3.jpg">

In this example you have access to create content in a forum in the Crossroad forum. 
if you want to allow the user better access and possibilities simply click the "Create policy" button. This will take you to the "create policy" window. The next processes are divided into three steps. 

Step 1. Give access to module.
Here you can choose what modules the user can get access to. What will the user get access to work with? You can choose all modules or select one or more of them. 
Let us say you want to let the user work with "Content". next you can decide if you want to let the user get full access or give him a limited access. We will choose the "Allow limited". This will take you to step two.
<img src="/kernel/manual/user/permissions/images/roles4.jpg">
Step 2. Specify function in module content.
Now we will decide what this user can do with the content we have allowed him to work with. We can give him access to read, create, edit, remove or version read content. In this example we choose to give him access to create content. 
Again we can choose to let him create access to every module but we want him restrict him to a limited access through "Allow limited". This will take you to step three.

Step 3. Specify limitations in function create in module content.
Step 3 is divided into three logical windows; class, Section and Parent class. In the first window you can decide that he can create articles. In the second window we decide that he can create articles in the News section before we decide what he can do within the Parent class. When you now click "OK" you have created a role for that user.
	
Now we have created a role for the user. This way we can create or edit the user rights for different users. You can create as many roles as you have users.
When you are done and ready to publish and assign the roles to the different user groups you make them "live" by clicking the "Apply" button.

Removing policies is also very simple. Mark the policy you want to remove and click "Remove policy". 


</p>

