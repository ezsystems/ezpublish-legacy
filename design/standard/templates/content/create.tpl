<form method="post" action={"/content/create/"|ezurl}>
<div class="maincontentheader">
<h1>Create new {$class.name}</h1>
</div>

{section name=attributes loop=$attributes sequence=array(aaaaff,eeeeff)}
{$attributes:item.id}
{$attributes:item.name}
<textarea class="box"name="Content_{$attributes:item.id}" columns="50" rows="5"></textarea>

{/section}

<div class="buttonblock">
<input type="submit" name="StoreButton" value="{Store|i18n('content/create')}" />
<input type="submit" name="CancelButton" value="{Cancel|i18n('content/create')}" />
</div>
</form>
