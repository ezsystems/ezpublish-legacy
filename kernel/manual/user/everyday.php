<h1>Everyday functions</h1>

<ul>
	<li><a href="#Admin">The admin interface</a></li>
	<li><a href="#Sections">Sections</a></li>
	<li><a href="#Create">How to create a folder</a></li>
	<li><a href="#Content">How to add content in a folder</a></li>
	<li><a href="#Pictures">How to add pictures in an article</a></li>
	<li><a href="#Finding">Finding content</a></li>
	<li><a href="#Drafts">Drafts</a></li>
	<li><a href="#Locations">Publish in several locations</a></li>
	<li><a href="#Edit">Edit objects</a></li>
	<li><a href="#Related">Related objects</a></li>
	<li><a href="#Version">Version control</a></li>
	<li><a href="#Preview">Preview your content</a></li>
	<li><a href="#Translate">Translate your content</a></li>
	<li><a href="#Sorting">Sorting content</a></li>
	<li><a href="#Tags">Using tags</a></li>
	<li><a href="#Classes">Classes</a></li>
</ul>
<br></br>

<p>
</p>
<br></br>

<h2 id="Admin">The admin interface</h2>
<p>
The admin user face in eZ publish 3 is where you do all the work on your content.
You can say that this is where you build your site. In the admin interface you 
create and edit articles, add pictures and manage workflows - this is where you manage your site.
</p>

<h2 id="Sections">Sections</h2>
<p>
You will use sections to name the different parts of your site. These are the main folders where all 
the content is stored and published. In our demo the sections are Standard, Whitebox, News, Crossroads,
 Bookcorner, My company, Intranet and MySite. for a newspaper typical sections can be News, Sports, Economy,
 Weather etc.</p>
<p>The section information are basically used for two things, permissions and layout. You make different
people access different parts of your site based on the section information. You normally also use
the section information to create different layout on the different parts of your site.</p>

<p>Visit <a href="http://sdk.ez.no">sdk.ez.no</a> for a more indepth tutorial.

</p>

<h2 id="Create">How to create a folder</h2>
<p>
Making a folder in eZ publish 3 is very simple. A folder is where you store other content. "Sports" 
can for instance be a folder. In this folder you put all the content (articles, pictures, forums etc) 
that you want to publish in "Sports". for a newspaper the folder can be: Editorial, News, Sports, Weather 
and Economy. There are no limits to how many folders you can create. 
When you want to create folders you have to be sure that you are in the Content view mode (list). You will
 get there by using the "Content box" in the to left corner of the admin interface.
</p><p><img src="/kernel/manual/user/images/content1.jpg" border="1"></p>
<p>In the "Content" window you will get a view over all the folders you have created. 
When in this window you will also see a button and a dropdown many that says "New" and "Folder".
</p><p><img src="/kernel/manual/user/images/new.jpg" border="1"></p>
 <p>Click "New" and you will go to the "Edit folder - New folder" page. Write in the name of the folder, 
 for example "Sports" and a description of the folder if you want that. 
You will also get some different choices on what you want to do with your folder. You can preview it, 
store it as a draft that you can work with later or publish it.</p>
<p><img src="/kernel/manual/user/images/muligheter1.jpg" border="1"></p>
<p>We choose to publish it. Now this folder will appear on your site as "Sports". 
Now you have created a folder. When you have clicked the "Publish" button you will see the Folder in a 
new window. The next step is to put content like; articles and pictures in the folder. 

</p>
<br>
<h2 id="Content">How to add content in a folder</h2>
<p>
Now, that you have made a folder its time to create content and add that to the right folder. 
First you have to find the folder that you want to add content to. You can find content in eZ publish 
in several ways; through the "Content" box, the "Sitemap" and through the "Search engine". 
if you just made the folder you are where you are supposed to be, in the folder window.

You will now get the same choice you got when creating the folder. But instead of creating a new folder 
you choose "Article" in the drop down menu.</p>
<p><img src="/kernel/manual/user/images/sport.jpg" border="1"> </p>
<p>Click "New" and you will get into the "Edit article - New article" window. Write in the title, the intro 
and the body of the article. You can also add pictures to your article.
You will then get the same choices for what you want to do with your article that you got for you folder.
 Again we choose to "Publish" the article. 
You are now done. The article is published under the "Sports" folder and is readable on your site.

</p>
<br>
<h2 id="Pictures">How to add pictures in an article</h2>
<p>
Often you want to add pictures to a site. Sometimes you want pictures in an article or to a product or 
perhaps your site is an image gallery. This is done very easily in eZ publish. Use exactly the same 
procedure as you use when making folders or articles. Mark "Image" and click "New". </p>

<p>In the "Edit image - New image" window you name the image and write a caption/ picture text to the image. 
Get the image and click on the desired function for the picture. Voila, the picture is published.</p>
<p>Usually you want to add a picture to an article or a product. To do this you go into the article that you 
want to add the picture. The picture that you add at the bottom of the article edit page will be a 
standard picture that is connected with your article.</p> 
<p>if you want to add more pictures than this one in your article you can use the "Related objects" function 
in eZ publish. With this function you can include other files in your article. include other articles, 
images, MPEG's etc. Click the "Find object" button and find the object that you want to add to your article.
 Select the file with a check and click the "Select" button.</p> 
<p>This will take you back to the "Edit article" page and show the selected file in the "Related objects" box. 
if you want to add this file in the article copy the <tag> and paste it wherever you want in the text.</p>
<p><img src="/kernel/manual/user/images/object2.jpg" border="1">.</p>
<p>do the same procedure over again if you want to add more objects to your article.

</p>
<br>
<h2 id="Finding">Finding content</h2>
<p>
Often a site can be very large and the content can be difficult to find or perhaps you have forgotten where
 you saved it. There are several ways to find the content again in eZ publish 3.</p> 
 
<p>You can find the content through the "Content" box where you find everything that is published on your site divided in the different 
 folders you have created. You can also choose to use the "Sitemap" where you can view your content in a more 
 familiar tree structure. </p>
<p>The third option you get is to search for the content: eZ publish 3 has a powerful search engine included.</p>

<p>We have placed this search engine on the top of the Admin user interface so that it will always be easily 
accessible. In the search field you can search between everything that is published on your site. We have 
also included an "Advanced search".</p><p><img src="/kernel/manual/user/images/sok.jpg" border="1"></p> 
<p>With the search engine you can search through your site in many ways. Search for words, exact phrases or folders.
 You can also search within sections. There are also possibilities to do advanced search as: Search for all 
 images of a "Ball" in the "Sports" section that is published the last year. </p> 
<p>With this functionality you will find whatever you want on your site without problems. You no longer need to
 "loose" content.

</p>
<br>
<h2 id="Drafts">Drafts</h2>
<p>
Have you ever experienced writers block? Or perhaps you have lost an article that you started because you 
had to leave the office for the night? </p>
<p>With the draft function in eZ publish 3 you can start writing on an article and save it as a draft. Then you 
can bring up this draft some other time and continue writing on it. Let's say that you have ideas for several 
articles and want to start writing on them before you forget your ideas. Store them as drafts and publish them 
when you are ready with the article.</p> 
<p>In the draft function you can save several drafts at the same time that you want to continue working on at 
a later time.</p>
<p>To store an article as a draft you get that choice at the bottom of the "Article edit" page. When you click 
the "Store draft" button you will get this message: "Input was stored successfully". That means that your 
draft is saved. When you want to find the draft and continue working on it you will find it in the drafts section in the
 "Content" box. </p>
<p><img src="/kernel/manual/user/images/content1.jpg" border="1"></p>
</p>

<br>

<h2 id="Locations">Publish in several locations</h2>
<p>
An object like an article or a product can often be placed in several folders. A bookstore can have several 
books on programming and computers. A book about php programming is originally put in php folder. But it can 
also be put in other folders like Programming books and Computers. This is easily handled in eZ publish 3.</p>

<p>First you find the article or product that you want to publish in several folders. Click the "Edit" button 
and you will get to the "Edit article - article name" page. On that site you will find the button "Add location".
 With this button you can add as many places you want your article to be published at.</p>
<p><img src="/kernel/manual/user/images/location1.jpg" border="1"></p>

<p>Click the "Add location" button. You will now get an overview of all the folders on your site. if you want 
your article to be published on the top level of one of these folders you choose select that folder by ticking 
the "Select" box. if you want to publish your article deeper down in the tree you click on the links until you
 find the location you are looking for.</p>

<p>When you have selected where to publish your article you will get back to the "Edit" window again. There you
 can see in what places your article is published. </p>
<p>To remove a location for your article "tick" the tiny box next to the location that you want to remove and 
click the "Remove location" button.  That's it. Your article or product is removed from that specific location.

</p>
<br>
<h2 id="Edit">Edit objects</h2>
<p>
Objects like folders, articles, forums and products can be edited anytime you wish. 
This is no big deal in eZ publish 3 and it really explains itself. All you need to do is find the object you 
want to edit, click the "Edit" button and start editing.</p>
<p><img src="/kernel/manual/user/images/edit.jpg" border="1"></p>
<p>You can everything in the object. Change the title, location, intro and body text, add pictures or relate 
other objects. Everything is done in this window. </p>
<p>Every time you edit an object like an article you can choose to save the different versions of this object. 
</p>

<br>

<h2 id="Related">Related objects</h2>
<p>
Related objects are files you can connect to other objects. That can be an article, a product or a picture etc.</p>

<p>Let us say that you have an article in which you want to add another article and a picture. Again, you have to 
find the specific article that you want to work with. Open the article with the "Edit" button and you will find 
the "Related objects" function.</p> 

<p>With this function you can include other files in your article. include other articles, images, MPEG's etc. 
Click the "Find object" button and find the object that you want to add to your article. Select the file by 
ticking the tiny box and click the "Select" button. This will take you back to the "Edit article" page and 
show the selected file in the "Object info" box. if you want to add this file in the article use the "Include 
in article" button.</p> 

<p>The selected object will appear in the article where the indicator is. To move the related object within 
the article use the cut and paste function or mark it and move it to wherever you want.
Go through the same process for all objects that you want to relate or relate all the objects at the same time.</p>

<p>All the related objects will appear in the "Related objects" window as thumbnails if there are pictures and
  "filename" in something else. </p>

<p>NB! if you use Internet Explorer there is a WYSIWYG editor available from www.ez.no that will make the 
entire "Related objects" process even easier. 
</p>

<br>

<h2 id="Version">Version control</h2>
<p>
By "version control" we define the different versions you have on an object as an article or product. </p>

<p>After you have written or edited an object you can save this and publish it immediately. But perhaps after you
 published it you want to change it by adding some more text and a picture. You can work on this new version of
the object without worrying about the current published version.</p> 

<p>When you have opened the object (article) and changed the content you will find a box that says "Version info" 
on the right side. In this box you wil find a "Manage" button. This button will open a new window where you 
get all the different versions of the present article you are working on. The list will tell you version 
numbers, who wrote the article, when it was written and when it was edited or modified. </p>

<p>There will also be a (*) that shows the present version of the article that is published. if you want 
to change a version to be the "Current" tick the box next to the version that you want to set as "Current".
if you want to work on the published and current version you need to make a copy of it. You do that by 
clicking the "Copy" button. Now you can edit the copy of the "Current" version and publish this. You also 
save it as another draft. When you have published the "Copy" that is set to be the current version and the 
former current version is set to "Archived". Back in the "Edit view" you will find information about which 
version you are now working on and how many versions exists of this object.</p>
<p><img src="/kernel/manual/user/images/versions1.jpg" border="1"></p>

<p>In the picture we are working on/editing version "2" while version 1 is the current version or the version 
that is published.
 
</p>

<br>

<h2 id="Preview">Preview your content</h2>
<p>
The preview function shows how your object (article) will look after you have published it.</p>

<p>When you click the "Preview" button</p>
<p><img src="/kernel/manual/user/images/preview.jpg" border="1"></p>

<p>you will get into the "Preview" window. Here you can also change what design you want this article to be 
previewed in by changing the "Site design" dropdown menu. </p>
</p><img src="/kernel/manual/user/images/sitedesign1.jpg" border="1"></p>

<p>By doing this you can preview the article in the different designs you have on your site.   
This function gives you the possibility to edit your article if you're not satisfied with it. When in the 
preview window you can choose to publish the article or go back to the editing page. 
</p>

<br>

<h2 id="Translate">Translate your content</h2>
<p>
Many sites have the opportunity for users to show content in different languages. International sites with 
users in more than one country will find this helpful. eZ publish 3 has multi-lingual support. In short that 
means that you can publish an article in different languages depending on where your users come from. </p>

<p>To translate you go to the object (article) you want to translate and click the "Manage" button in the
 "Translations" box. </p>
<p><img src="/kernel/manual/user/images/translate.jpg" border="1"></p>

<p>This will take you to the "Translating - object" window. Click the "Translate" button. Now you will see a 
different window with the text you want to translate on the right side and the "Edit" windows for your new 
language on the left side. Now you simply add the new language text in those windows and click "Store". </p>
<p>To go back to the article you just added a new language to click "Edit object". This will take you back to 
the "Edit Article - article name" window where you now can see your new translation in the "Translations" window.
      
</p>

<br>

<h2 id="Sorting">Sorting content</h2>
<p>
eZ publish 3 gives you the possibility to sort your content in different ways. This can be sorting of 
folders, articles etc. </p>

<p>An article you can sort by path, when it was publish, when it was modified etc.
if you want to sort the different contents in a folder, click on the  "Edit" button on the folder itself. 
Then you will get into the "Edit folder - Folder name" page. Use the "Sort by" drop down menu and decide 
in what manner you want to sort your content.</p> 
<p><img src="/kernel/manual/user/images/sorting.jpg" border="1"></p>

<p>Let us say that you want to sort it by Priority. Highlight "Priority" and click "Send for publishing". 
Back in the Folder page you will now find a new box on the right hand side with a "0" inside. 
Now you decide the different objects' priority by typing 1,2,3,4 etc in the boxes. When you have prioritised 
the objects you click the "Update Sorting Priority" button and you are done. You can later change the 
prioritising at any time. </p>
<p>The sorting decides in what way your content will be displayed when published.</p>
  
<p>Path: sort by path</p>
<p>Published: sort by when it was published</p>
<p>Modified: sort by when it was modified</p>
<p>Section: sort by sections</p>
<p>Depth:</p>
<p>class identifier:</p>
<p>class name:</p>
<p>Priority: sort by priorities</p>

<p>You can also decide if you want to sort this in a ascending or descending way by the arrows to the right 
of the sorting dropdown menu.

</p>

<br>

<h2 id="Tags">Using tags</h2>
<p>
When you are writing articles etc in eZ publish sometimes you need to edit the text or related objects.
Examples on that can be changing size and placement of a picture, bold text, adding links or making headers.
for that we use simple tags *.</p>

<p>These are the standard tags you can use to do that:
</p>
<ul>
<li>&lt;em&gt;emphasize text&lt;/em&gt;  </li>
<li>&lt;b&gt;bold text&lt;/b&gt; </li>
<li>&lt;ul&gt;unordered list&lt;/ul&gt;</li>
<li>&lt;ol&gt;ordered list&lt;/ol&gt; </li>
<li>&lt;li&gt;list element inside &lt;/li&gt; </li>


<li>&lt;h&gt;heading text&lt;/h&gt; or &lt;h&gt; level="1-6"&gt;heading text with defined size&lt;/h&gt; </li>

<li>&lt;link href="link url"&gt;link text&lt;/link&gt; or &lt;link id="id"&gt;link text&lt;/link&gt; where id is an existing eZ url id.</li> 
<li>&lt;object id="id" view="view type"/&gt; where 'id' should be an existing eZ object id and 'view type' could be 'embed', 'text_linked' or not specified.</li> 
<li>&lt;table border='0-10' width="1-100%"&gt;table content&lt;/table&gt; where attribute 'border' and 'width' could be not presented. Table content should be written according to normal table syntax with &lt;tr&gt; and &lt;td&gt; tag.</li>
<li>&lt;image align="" src="..."&gt; if you want to change size and placement of a picture.</li>
</ul>
<p>* Editors will be available at <a href="www.ez.no"> ez.no</a> to make this process much easier.</p>

<h2 id="Classes">Classes</h2>
<p>
In eZ publish you can define your own content classes. A content class
is an object definition if you like. Some examples of content classes
are article, forum, product and user account.
</p>

<p>
To be able to create for instance an article you have to decide what
this article should look like. This you can read more about in
www.ez.no/SDK and we will not take this further up here. When you have
created the different classes you seldom need to do anything with this
again (eZ publish 3 also comes with demo set-ups of articles, folders,
products etc)
</p>

<p>
The "design" of the different classes you can find in the Class list or
Defined class groups. The Class list is available from the Set up box in
the Admin interface. When you click "Classes" you will se the Class
list. This is a list naming the different class groups you have on your
site. For instance a class group can be "Content". In the "Content"
folder you store classes like article, form or product. It is from this
location you will find the classes in the dropdown menu when you want to
create a new article, product etc. If you create a new class in this
list it will be available in the dropdown menu.
</p>

<p>
In this menu it is also possible to Edit and Remove the Classes. When
ticking the Remove box for the class you want to delete and click Remove
it will be deleted from the system.
You can also create a new class group. To do that you click the "New"
button and type in the name of the new group and save it. These groups
can for instance be Partners, Customers, Products, Content and Users.
</p>

<p>
For more info about classes please read the <a href="http://sdk.ez.no">eZ publish SDK.</a>
</p>


