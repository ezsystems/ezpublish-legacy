<h1>Information collectors</h1>

<p>
In eZ publish 3 we have added what we call Information Collector. This feature can be used to create forms.
</p>

<p>
To make a form that is usable from your site is also quite easy in eZ publish.
What you need to do is to make a new class. Click the "Classes" button in the "Setup" box
and choose where you want to add the form. When you have decided where to put the form you
click "New" which will take you to the "Editing class type - New Class" page.
Add the different attributes you want to have in your form.
</p>

<p>
An example can be the attributes Name, e-mail and What's my problem and we name the form "Contact me".
When we add these attributes we also get a choice on the right side of the attributes; the "Type".
The "Type" has three lines; "Required", "Searchable" and "Information collector".
</p>

<p><img src="/kernel/manual/user/images/infocollector.jpg" border="1"></p>

<p></p>
<p class="important">
Required: if an attribute is required the use must fill in values to this attribute when an object
is created.
</p>

<p></p>
<p class="important">
Searchable: Searchable means that the content input by the user in the specific attribute is indexed
in the search engine.
</p>

<p></p>
<p class="important">
Information collector: This is where we make the form. if you check the box for this you will include
this field in the form. In our example we want all the three attributes included and therefore check
the "Information collector" box for all attributes.
</p>

<p>
When you now click "Store" you have created the form and added it to the place you want it to be seen.
To use the form you now go to "Lists" in the "Content" box. Choose "Form" in the drop down menu and
click "New".
</p>

<p>
You now have your form and as soon as you click "Publish" it is available on your site.
</p>

<p>
The e-mail will be sent to the e-mail address that is mentioned in the site.ini file.
</p>
