<h1>e-commerce functions and settings</h1>

<ul>
	<li><a href="#Discount">Discount rules</a></li>
	<li><a href="#VAT">VAT types</a></li>
	<li><a href="#Adding">Adding a class to your VAT types</a></li>
	<li><a href="#Order">Order list</a></li>
	<li><a href="#Shopping">Shopping basket</a></li>
	<li><a href="#Shipping">Shipping</a></li>
	<li><a href="#Currency">Currency</a></li>
	<li><a href="#Wishlist">Wishlist</a></li>
	<li><a href="#Vouchers">Vouchers</a></li>
</ul>
<br></br>

<h2 id="Discount"> Discount rules</h2>
<p>
Skriv dette fra http://admin.bf.dvh1.ez.no/content/view/full/200
</p>
<br></br>

<h2 id="VAT">VAT types</h2>
<p>
Very often you are in need of having different VAT types on different products or product groups in your shop. To save time and make the whole thing as easy as possible this is all done within "VAT types" in the "Shop box.  
</p><p><img src="/kernel/manual/user/images/vattypes.gif"></p>
<p>When you want to add a VAT type to a product or edit the VAT you go into this window. 
Let's say that you run a grocery store and you have several products with different VAT types. You have vegetables (20% VAT), bread (15% VAT) and fish (30% VAT). Click the "VAT types". This will take you to the VAT types window where you now see an "Add VAT type" button. When clicking this button you will see a "Name" window and a "Percentage" window. Simply add the name of the product and what percentage of VAT this product should have. In our example we write in.:
</p><p><img src="/kernel/manual/user/images/vattypes2.gif"></p> 

<p>Then click the "Store" button. if you want to add more VAT types simply click the "Add VAT types" button and go through the process again.
	
</p>
<br>
<h2 id="Adding">Adding a class to your VAT types</h2>

<p>
To get to use the VAT types in the way that it is meant to be used it is important that you make the VAT type before you add any products. 
We have mentioned how you add classes earlier but we will show it shortly again.

Click on the "Classes" button in the "Setup" window. Then find out where you want to add the new Class. Click the "new class" button and write in the name of the of the new Class, for example "Vegetables". This name will also be written in the "object name" field as soon as you go on. Then add the attributes you want for your class. We will use "Text line", "Text field" and "Price" in this example. You can add as many attributes as you like. Note that when you add the "Price" attribute you now get to choose Vat types. Since we now are making the Vegetables class we will choose "Vegetables, 20%". To end this process click "Apply". You have now made the class "Vegetables" with a VAT types at 20%. For every product you now make for the product group "Vegetables" will have this VAT type. Adding products is done by adding "new Vegetables" in the "Content" window. 
You can always test this out in the "Shopping basket" in the "MY" window.
	
</p>

<h2 id="Order">Order list</h2>

<p>
In the order list you will se the orders your customers have sent in to your shop. Here you will be able to see who bought, what they bought and when they bought. You can alter this list the way you want to suit your needs better. 
Test ut på http://admin.bf.dvh1.ez.no/content/view/full/200

</p>

<h2 id ="Shopping">Shopping basket</h2>
<p>
This shopping basket we have put here to test out . In this window you can test out how your shop works before you release products. You will be able to 
Test ut på http://admin.bf.dvh1.ez.no/content/view/full/200

</p>

<h2 id ="Shipping">Shipping</h2>
<p>
eZ publish comes with a default workflow for adding shipping to the order. This is however
a plugin system so you can add any shipping calculation to this checkout process. Visit <a href="http://sdk.ez.no">sdk.ez.no</a>
To get more information about how you can set up a custom shipping calculation.
</p>

<h3 id ="Currency">Currency</h3>
<p>
Currencies are handles by the locale system in eZ publish. All you need to do is to tell eZ publish
to use the correct locale. This is set in RegionalSettings in the configuration files site.ini.
</p>
<pre class="example">
[RegionalSettings]
Locale=eng-GB
</pre>

<h3 id ="Wishlist">Wishlist</h3>
<p>
Wish lists in eZ publish is a function which the user of the site can use to save a
product for later shopping.
</p>


