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
        <td class="{$Node:sequence}">
        <span class="normal">{$Node:item.name}</span>
        </td>
        <td class="{$Node:sequence}" align="right">
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

    <div class="buttonblock">
    <input class="button" type="submit" name="BrowseNodeButton" value="{'Find node(s)'|i18n('content/object')}" />
    <input class="button" type="submit" name="DeleteNodeButton" value="{'Delete node(s)'|i18n('content/object')}" />
    </div>

    {section name=ContentObjectAttribute loop=$content_attributes sequence=array(bglight,bgdark)}
    <div class="block">
    <label>{$ContentObjectAttribute:item.contentclass_attribute.name}:</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ContentObjectAttribute:item.id}" />
        {attribute_edit_gui attribute=$ContentObjectAttribute:item}
    </div>
    {/section}

    <div class="buttonblock">
    <input class="button" type="submit" name="PreviewButton" value="{'Preview'|i18n('content/object')}" />
    <input class="button" type="submit" name="VersionsButton" value="{'Versions'|i18n('content/object')}" />
    <input class="button" type="submit" name="TranslateButton" value="{'Translate'|i18n('content/object')}" />
    </div>
    <div class="buttonblock">
    <input class="button" type="submit" name="StoreButton" value="{'Store Draft'|i18n('content/object')}" />
    <input class="button" type="submit" name="PublishButton" value="{'Send for publishing'|i18n('content/object')}" />
    <input class="button" type="submit" name="CancelButton" value="{'Discard'|i18n('content/object')}" />
    </div>
    <!-- Left part end -->
    </td>
    <td width="120" align="right" valign="top" style="padding-left: 16px;">
    <!-- Right part start-->
    <table class="menuboxright" width="120" cellpadding="1" cellspacing="0" border="0">
    <tr>
        <th class="menuheaddark">
        <p class="menuhead">{"Object info"|i18n('content/object')}</p>
        </th>
    </tr>
    <tr>
        <td class="menu">
	    <p class="menufieldlabel">{"Editing version"|i18n}:</p>
	    <p class="menufield">{$edit_version}</p>
	    <p class="menufieldlabel">{"Current version"|i18n}:</p>
	    <p class="menufield">{$object.current_version}</p>
        </td>
    </tr>
    </table>

    <table class="menuboxright" width="120" cellpadding="1" cellspacing="0" border="0">
    <tr>
        <th class="menuheaddark">
        <p class="menuhead">{"Related objects"|i18n('content/object')}</p>
        </th>
    </tr>
    {section name=Object loop=$related_contentobjects sequence=array(bglight,bgdark)}
    <tr>
        <td class="menu">
        {content_view_gui view=line content_object=$Object:item}
        </td>
    </tr>
    {/section}
    <tr>
        <td>
        <input class="menubutton" type="submit" name="BrowseObjectButton" value="{'Find object'|i18n('content/object')}" />
        </td>
    </tr>
    </table>
    <!-- Right part end -->
    </td>
</tr>
</table>

</form>