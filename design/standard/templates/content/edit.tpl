<form enctype="multipart/form-data" method="post" action="/content/edit/{$object.id}/{$edit_version}/">


<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr>
    <td width="99%" valign="top">
    <!-- Left part start -->
    <h1>Edit {$class.name} - {$object.name}</h1>

    {section show=$validation.processed}
        {section name=UnvalidatedAttributes loop=$validation.attributes show=$validation.attributes}
        <b>Input did not validate</b>
        <i>{$UnvalidatedAttributes:item.identifier}:</i> {$UnvalidatedAttributes:item.name} ({$UnvalidatedAttributes:item.id}) <br/>
        {section-else}
        <b>Input was stored successfully</b>
        {/section}
    {/section}

    <table width="50%">
    {section name=Node loop=$assigned_node_array sequence=array(bglight,bgdark)}
    <tr>
        <td class="{$Node:sequence}">
        {$Node:item.name}
        </td>
        <td class="{$Node:sequence}">
        {switch name=sw match=$main_node_id}
            {case match=$Node:item.node_id}
            <input type="radio" name="MainNodeID" checked="checked" value="{$Node:item.node_id}" />
            {/case}
            {case}
            <input type="radio" name="MainNodeID" value="{$Node:item.node_id}" />
            {/case}
       {/switch}
       <input type="checkbox" name="DeleteParentIDArray[]" value="{$Node:item.node_id}" />
       </td>
    </tr>
    {/section}
    </table>

    <input type="submit" name="BrowseNodeButton" value="{'Find node(s)'|i18n('content/object')}" />
    <input type="submit" name="DeleteNodeButton" value="{'Delete node(s)'|i18n('content/object')}" />

    <table width="100%" cellspacing="0">
    {section name=ContentObjectAttribute loop=$content_attributes sequence=array(bglight,bgdark)}
    <tr>
        <th class="{$ContentObjectAttribute:sequence}">
        {$ContentObjectAttribute:item.contentclass_attribute.name}
        </th>
    </tr>
    <tr>   
        <td class="{$ContentObjectAttribute:sequence}">
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ContentObjectAttribute:item.id}" />
        {attribute_edit_gui attribute=$ContentObjectAttribute:item}
	</td>
    </tr>
    {/section}
    </table>

    <input type="submit" name="PreviewButton" value="{'Preview'|i18n('content/object')}" />
    <input type="submit" name="VersionsButton" value="{'Versions'|i18n('content/object')}" />
    <input type="submit" name="TranslateButton" value="{'Translate'|i18n('content/object')}" />
    <br /><br />
    <input type="submit" name="StoreButton" value="{'Store Draft'|i18n('content/object')}" />
    <input type="submit" name="PublishButton" value="{'Send for publishing'|i18n('content/object')}" />
    <input type="submit" name="CancelButton" value="{'Discard'|i18n('content/object')}" />
    <!-- Left part end -->
    </td>
    <td width="120" align="right" valign="top">
    <!-- Right part start-->
    <table class="menuboxright" width="120" cellpadding="1" cellspacing="0" border="0">
    <tr>
        <th class="menuheaddark" colspan="2">
        <p class="menuhead">{"Object info"|i18n('content/object')}</p>
        </th>
    </tr>
    <tr>
        <td class="bullet">
	{"Editing version"|i18n}: {$edit_version}
        </td>
    </tr>
    <tr>
        <td>
	{"Current version"|i18n}: {$object.current_version}
        </td>
    </tr>
    </table>

    <table class="menuboxright" width="120" cellpadding="1" cellspacing="0" border="0">
    <tr>
        <th class="menuheaddark" colspan="2">
        <p class="menuhead">{"Related objects"|i18n('content/object')}</p>
        </th>
    </tr>
    {section name=Object loop=$related_contentobjects sequence=array(bglight,bgdark)}
    <tr>
        <td class="bullet">
        {content_view_gui view=line content_object=$Object:item}
        </td>
    </tr>
    {/section}
    <tr>
        <td>
        <input type="submit" name="BrowseObjectButton" value="{'Find object'|i18n('content/object')}" />
        </td>
    </tr>
    </table>
    <!-- Right part end -->
    </td>
</tr>
</table>

</form>