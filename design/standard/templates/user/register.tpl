<form enctype="multipart/form-data"  action={concat($module.functions.register.uri)|ezurl} method="post" name="Register">

<div class="maincontentheader">
<h1>Register user</h1>
</div>

{section show=$validation.processed}

{section name=UnvalidatedAttributes loop=$validation.attributes show=$validation.attributes}
<div class="warning">
<h2>Input did not validate</h2>
<ul>
    <li><i>{$UnvalidatedAttributes:item.identifier}:</i> {$UnvalidatedAttributes:item.name} ({$UnvalidatedAttributes:item.id})</li>
</ul>
</div>
{section-else}
<div class="feedback">
<h2>Input was stored successfully</h2>
</div>
{/section}

{/section}


{section name=ContentObjectAttribute loop=$content_attributes sequence=array(bglight,bgdark)}
<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ContentObjectAttribute:item.id}" />
<div class="block">
<label>{$ContentObjectAttribute:item.contentclass_attribute.name}</label><div class="labelbreak"></div>
{attribute_edit_gui attribute=$ContentObjectAttribute:item}
</div>
{/section}

<div class="buttonblock">
<input type="submit" name="PublishButton" value="{'Register'|i18n('user/register')}" />
<input type="submit" name="CancelButton" value="{'Discard'|i18n('user/register')}" />
</div>

</form>
