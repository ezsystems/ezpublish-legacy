<form enctype="multipart/form-data" method="post" action={concat("/content/edit/",$object.id,"/",$edit_version,"/")|ezurl}>


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
    <tr><th>{"Name"|i18n('content/object')}</th><th>{"Sort by"|i18n('content/object')}</th><th colspan="2">{"Asc"|i18n('content/object')}</th></tr>
    {let name=Node sort_fields=hash(1,"Path"|i18n('content/object'),2,"Published"|i18n('content/object'),3,"Modified"|i18n('content/object'),4,"Section"|i18n('content/object'),5,"Depth"|i18n('content/object'),6,"Class Identifier"|i18n('content/object'),7,"Class Name"|i18n('content/object'),8,"Priority"|i18n('content/object'))}
    {section loop=$assigned_node_array sequence=array(bglight,bgdark)}
    {let parent_node=$Node:item.parent_node_obj}
    <tr>
        <td class="{$Node:sequence}">
	<span class="normal">
	{switch match=$Node:parent_node.node_id}
	{case match=1}
	Top node
	{/case}
	{case}
        {section name=Path loop=$Node:parent_node.path}
	{$Node:Path:item.name} /
	{/section}
        {$Node:parent_node.name}
	{/case}
	{/switch}
	</span>
        </td>
        <td class="{$Node:sequence}">
        <span class="normal">
          <select name="SortFieldMap[{$Node:item.id}]">
          {section name=Sort loop=$Node:sort_fields}
            <option value="{$Node:Sort:key}" {section show=eq($Node:Sort:key,$Node:item.sort_field)}selected="selected"{/section}>{$Node:Sort:item}</option>
          {/section}
          </select>
        </span>
        </td>
        <td class="{$Node:sequence}"><span class="normal"><input type="checkbox" name="SortOrderMap[{$Node:item.id}]" value="1" {section show=eq($Node:item.sort_order,1)}checked="checked"{/section} /></span></td>
        <td class="{$Node:sequence}" align="right">
        <input type="radio" name="MainNodeID" {section show=eq($main_node_id,$Node:item.parent_node)}checked="checked"{/section} value="{$Node:item.parent_node}" />
        <input type="checkbox" name="DeleteParentIDArray[]" value="{$Node:item.parent_node}" />
       </td>
    </tr>
    {/let}
    {/section}
    {/let}
 </table>
    {switch match=$main_node_id}
	{case match=1}
	{/case}
	{case}
	 <div class="buttonblock">
	 <input class="button" type="submit" name="BrowseNodeButton" value="{'Find node(s)'|i18n('content/object')}" />
	 <input class="button" type="submit" name="DeleteNodeButton" value="{'Delete node(s)'|i18n('content/object')}" />
	 </div>
	{/case}
    {/switch}
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
        <th class="menuheaddark" colspan="2">
        <p class="menuhead">{"Object info"|i18n('content/object')}</p>
        </th>
    </tr>
    <tr>
        <td class="menu">
	    <p class="menufieldlabel">{"Editing version"|i18n('content/object')}:</p>
	    <p class="menufield">{$edit_version}</p>
	    <p class="menufieldlabel">{"Current version"|i18n('content/object')}:</p>
	    <p class="menufield">{$object.current_version}</p>
        </td>
    </tr>
    <tr>
        <th class="menuheaddark">
        <p class="menuhead">{"Related objects"|i18n('content/object')}</p>
        </th>
	<th class="menuheaddark">
        </th>
    </tr>
    {section name=Object loop=$related_contentobjects sequence=array(bglight,bgdark)}
    <tr>
        <td class="{$Object:sequence}">
        {content_view_gui view=text_linked content_object=$Object:item}
        </td>
	<td>
	<input type="checkbox" name="DeleteRelationIDArray[]" value="{$Object:item.id}" />
	</td>
    </tr>
    {/section}
    <tr>
        <td>
        <input class="menubutton" type="submit" name="BrowseObjectButton" value="{'Find object'|i18n('content/object')}" />
        </td>
    </tr>
    <tr>
        <td>
        <input class="menubutton" type="submit" name="DeleteRelationButton" value="{'Delete object'|i18n('content/object')}" />
        </td>
    </tr>
    </table>
    
    <!-- Right part end -->
    </td>
</tr>
</table>

</form>
