<div class="context-block">

<form enctype="multipart/form-data"  action={"/user/register/"|ezurl} method="post" name="Register">

<h2 class="context-title">{"Register user"|i18n("design/standard/user")}</h2>

{section show=$validation.processed}

{section name=UnvalidatedAttributes loop=$validation.attributes show=$validation.attributes}
<div class="message-warning">
    <h2>{"Input did not validate"|i18n("design/standard/user")}</h2>
    <ul>
        <li>{$UnvalidatedAttributes:item.name}: {$UnvalidatedAttributes:item.description}</li>
    </ul>
</div>
{section-else}
<div class="message-feedback">
    <h2>{"Input was stored successfully"|i18n("design/standard/user")}</h2>
</div>
{/section}

{/section}

<div class="context-attributes">

{section name=ContentObjectAttribute loop=$content_attributes sequence=array(bglight,bgdark)}
<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ContentObjectAttribute:item.id}" />
<div class="block">
    <label>{$ContentObjectAttribute:item.contentclass_attribute.name}:</label>
    {attribute_edit_gui attribute=$ContentObjectAttribute:item}
</div>
{/section}

</div>

<div class="controlbar">
    <div class="block">
        <input class="button" type="submit" name="PublishButton" value="{'Register'|i18n('design/standard/user')}" />
        <input class="button" type="submit" name="CancelButton" value="{'Discard'|i18n('design/standard/user')}" />
    </div>
</div>

</form>

</div>