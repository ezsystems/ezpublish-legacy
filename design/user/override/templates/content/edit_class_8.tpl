
<form enctype="multipart/form-data" method="post" action="/content/edit/{$object.id}/{$edit_version}/">


<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td valign="top">
    <!-- Left part start -->
    <h1 class="top">Edit {$class.name} - {$object.name}</h1>

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
    {section name=Node loop=$assigned_node_array sequence=array(bglight,bgdark)}
    <tr>
        <td>
        <span class="normal">{"Reply to"|i18n}: {$Node:item.name}</span>
        </td>
        <td align="right">
        {switch name=sw match=$main_node_id}
            {case match=$Node:item.node_id}
            <input type="hidden" name="MainNodeID" checked="checked" value="{$Node:item.node_id}" />
            {/case}
            {case}
            {/case}
       {/switch}
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

{*
    <div class="buttonblock">
    <input class="button" type="submit" name="PreviewButton" value="{'Preview'|i18n('content/object')}" />
    <input class="button" type="submit" name="VersionsButton" value="{'Versions'|i18n('content/object')}" />
    <input class="button" type="submit" name="TranslateButton" value="{'Translate'|i18n('content/object')}" />
    </div>
*}
    <div class="buttonblock">
{*    <input class="button" type="submit" name="StoreButton" value="{'Store Draft'|i18n('content/object')}" /> *}
    <input class="button" type="submit" name="PublishButton" value="{'Send for publishing'|i18n('content/object')}" />
    <input class="button" type="submit" name="CancelButton" value="{'Discard'|i18n('content/object')}" />
    </div>
    <!-- Left part end -->
    </td>
</tr>
</table>

</form>