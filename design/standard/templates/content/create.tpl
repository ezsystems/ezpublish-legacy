<form method="post" action="/content/create/">
<h1>Create new {$class.name}</h1>


<hr />

{section name=attributes loop=$attributes sequence=array(aaaaff,eeeeff)}
{$attributes:item.id}
{$attributes:item.name}
<br />
<textarea name="Content_{$attributes:item.id}" columns="50" rows="5"></textarea>
<br />


{/section}

<hr />

<input type="submit" name="StoreButton" value="{Store|i18n}" />
<input type="submit" name="CancelButton" value="{Cancel|i18n}" />
</form>