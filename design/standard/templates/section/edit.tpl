<form method="post" action={concat("/section/edit/",$section.id,"/")|ezurl}>
<h1>Section edit</h1>

<input type="hidden" name="SectionID" value="{$section.id}" />

Name:<br />
<input type="text" name="Name" value="{$section.name}" />
<br/>
Locale:<br />
<input type="text" name="Locale" value="{$section.locale}" />
<br/>


<input type="submit" name="StoreButton" value="Store" />

</form>