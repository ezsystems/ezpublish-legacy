<form enctype="multipart/form-data" method="post" action={concat("content/edit/",$object.id."/",$edit_version,"/")|ezurl}>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td valign="top">
    <!-- Left part start -->
    <strong>Edit {$class.name} - {$object.name}</strong>

    {section show=$validation.processed}
        {section name=UnvalidatedAttributes loop=$validation.attributes show=$validation.attributes}

        <div class="warning">
        <h3 class="warning">Input did not validate</h3>
        <ul>
        	<li>{$UnvalidatedAttributes:item.identifier}: {$UnvalidatedAttributes:item.name} ({$UnvalidatedAttributes:item.id})</li>
        </ul>
        </div>
        
        {section-else}

        <div class="feedback">
        <h3 class="feedback">Input was stored successfully</h3>
        </div>

        {/section}
    {/section}

    <table class="list" width="100%" border="0" cellspacing="0" cellpadding="1">
    {section name=Node loop=$assigned_node_array}
    <tr>
        <td>
        <span class="normal">{"Reply to"|i18n}: {$Node:item.parent_node_obj.name}</span>
        <input type="hidden" name="MainNodeID" value="{$Node:item.parent_node}" />
        </td>
    </tr>
    {/section}
    </table>

    {section name=ContentObjectAttribute loop=$content_attributes sequence=array(bglight,bgdark)}
    <div class="block">
    <label>{$ContentObjectAttribute:item.contentclass_attribute.name}:</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ContentObjectAttribute:item.id}" />
        {attribute_edit_gui attribute=$ContentObjectAttribute:item}
    </div>
    {/section}

    <div class="buttonblock">
    <input class="button" type="submit" name="PreviewButton" value="{'Preview'|i18n('content/object')}" />
    <input class="button" type="submit" name="CancelButton" value="{'Discard'|i18n('content/object')}" />
    </div>
    <!-- Left part end -->
    </td>
</tr>
</table>

</form>