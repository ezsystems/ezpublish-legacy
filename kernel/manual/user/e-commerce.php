<h1>e-commerce functions and settings</h1>

<ul>
  <li><a href="#Discount">Discount rules</a></li>
  <li><a href="#VAT">VAT types</a></li>
  <li><a href="#Adding">Adding a class to your VAT types</a></li>
  <li><a href="#Order">Order list</a></li>
<!--  <li><a href="#Shopping">Shopping basket</a></li> -->
  <li><a href="#Shipping">Shipping</a></li>
  <li><a href="#Currency">Currency</a></li>
  <li><a href="#Wishlist">Wishlist</a></li>
<!--  <li><a href="#Vouchers">Vouchers</a></li> -->
</ul>
<br></br>

<h2 id="Discount">Discount rules</h2>
<p>
Very often we want to have discounts on a special product and give discounts to a group of customers.
Again, this is easy to do with eZ publish 3.</p>

<p>We do this by making different user groups that we assign the discount to. Then we add users in that
discount group.</p>

<p>To do this you click on the "Discount" link in the "Shop" box. This will take you to the
"Defined discount groups" page. Here you will find a list of the different discount groups you have
set on your site. Now we want to add a new User group in this list.
</p>

<p>Click the "New" button. Now you will see the "Editing discount group - New group" page where you
type in the name of the discount group you want to add, for instance "Partner". Then you must click "Apply".
This is the first step of adding discount groups.</p>

<p>In step two we have to add the discounts the partners will get.</p>
<p>Click on the "Partner" link that will take you into another page: "Group view". This is where we
assign the actual discount to the Partner group.</p>

<p><img src="/kernel/manual/user/images/discount1.jpg" border="1"></p>

<p>Click the "Add rule" button to assign the discount. In the "Editing rule" window you write in
"Computer store" in the "Name" field and type in how much they will get in discount. Next you decide if
they are to get the same discount for all products in the "Computer store" or a selected range of products,
e.g. CD's. You can also go down another step to decide what kind of CD's they get the discount for.</p>

<p>When you click "Store" you are done. You can add or remove discount rules in this window at any time.</p>

<p>You can now include the users you want in the "Partner" group through the "Add customer" button. Check
the groups, users or persons you want to include. You can go deeper into the customer tree by clicking the
"User" links until you find the user you want to add.
</p>
<br />

<h2 id="VAT">VAT types</h2>
<p>
Very often you need to have different VAT types on different products or product groups in your shop.
To save time and make the whole thing as easy as possible this is all done within "VAT types" in the
"Shop" box.</p>
<p><img src="/kernel/manual/user/images/vattypes.gif" border="1"></p>

<p>When you want to add a VAT type to a product or edit the VAT you go into this window. Let's say that you
run a grocery store and you have several products with different VAT types. You have vegetables (20% VAT),
bread (15% VAT) and fish (30% VAT). Click the "VAT types". This will take you to the VAT types window where
you now see an "Add VAT type" button. When clicking this button you will see a "Name" window and a
"Percentage" window. Simply add the name of the product and what percentage of VAT this product should have.
In our example we enter the following:</p>

<p><img src="/kernel/manual/user/images/vattypes2.gif" border="1"></p>

<p>Then click the "Store" button. If you want to add more VAT types simply click the "Add VAT types" button
and go through the process again.</p>

<br>
<h2 id="Adding">Adding a class to your VAT types</h2>

<p>
To get to use the VAT types in the way that it is meant to be used it is important that you make the VAT
type before you add any products. We have mentioned how you add classes earlier but we will show it shortly
again.</p>

<p>Click on the "Classes" button in the "Setup" window. Then find out where you want to add the new Class.
Click the "new class" button and write in the name of the of the new Class, for example "Vegetables".
This name will also be written in the "object name" field as soon as you go on. Then add the attributes you
want for your class. We will use "Text line", "Text field" and "Price" in this example. You can add as many
attributes as you like.</p>

<p>Note that when you add the "Price" attribute you now get to choose Vat types. Since we now are making
the Vegetables class we will choose "Vegetables, 20%". To end this process click "Apply".</p>
<p>You have now made the class "Vegetables" with a VAT types at 20%. For every product you now make for the
product group "Vegetables" will have this VAT type.</p>

<p>Adding products is done by adding "new Vegetables" in the "Content" window.</p>
<!-- You can always test this out on the "Shopping basket" page. -->

<h2 id="Order">Order list</h2>
<p>
In the order list you will se the orders your customers have sent in to your shop. Here you will be
able to see who bought your goods, what they bought and when they bought. You can alter this list
to suit your needs better.
</p>

<!--
<h2 id="Shopping">Shopping basket</h2>
<p>
This shopping basket we have put here to test out . In this window you can test out how your shop works before you release products. You will be able to
</p>
-->

<h2 id="Shipping">Shipping</h2>
<p>
eZ publish comes with a default workflow for adding shipping to the order. This is however
a plug-in system so you can add any shipping calculation to this checkout process. Visit
<a href={"/sdk"|ezurl}>eZ publish SDK</a> to get more information about how you can set up a custom
shipping calculation.
</p>

<h2 id="Currency">Currency</h2>
<p>
Currencies are handled by the locale system in eZ publish. All you need to do is to tell eZ publish
to use the correct locale. This is set in RegionalSettings in the configuration file site.ini.
</p>

<pre class="example">
[RegionalSettings]
Locale=eng-GB
</pre>

<h2 id="Wishlist">Wishlist</h2>
<p>
Wish lists in eZ publish is a function which the user of the site can use to save a
product for later shopping.
</p>
