<form action="{$module.functions.edit.uri}/{$class.id}" method="post" name="ClassEdit">

<h1>Editing class type - {$class.name}</h1>
<p>Created by {$class.creator_id} on {$class.created|l10n(shortdatetime)}</p>
<p>Modified by {$class.modifier_id} on {$class.modified|l10n(shortdatetime)}</p>
<table>
<tr><td>Object name:</td></tr>
<tr><td>{include uri="design:gui/lineedit.tpl" name=ObjectName id_name=ContentClass_contentobject_name value=$class.contentobject_name}</td></tr>
<tr><td>Identifier:</td></tr>
<tr><td>{include uri="design:gui/lineedit.tpl" name=Identifier id_name=ContentClass_identifier value=$class.identifier}</td></tr>
<tr><td>Name:</td></tr>
<tr><td>{include uri="design:gui/lineedit.tpl" name=Name id_name=ContentClass_name value=$class.name}</td></tr>
<tr><td>In group:</td></tr>
{section name=InGroups loop=$class.ingroup_list sequence=array(bglight,bgdark)}
<tr>
<td class="{$InGroups:sequence}">{$InGroups:item.group_name}</td>
<td class="{$InGroups:sequence}"><input type="checkbox" name="group_id_checked[]" value="{$InGroups:item.group_id}"></td>
</tr>
{/section}

<table width="100%">
<tr>
<td><select name = "ContentClass_group">
{section name=AllGroup loop=$class.group_list}
<option name = "ContentClass_group[]" value="{$AllGroup:item.id}/{$AllGroup:item.name}">{$AllGroup:item.name}</option>
{/section}
</select></td>
<td>{include uri="design:gui/button.tpl" name=AddGroup id_name=AddGroupButton value="Add group"}</td>
<td>{include uri="design:gui/button.tpl" name=DeleteGroup id_name=DeleteGroupButton value="Delete group"}</td>
<td width="99%"></td>
</tr>
</table>

{section show=$validation.processed}

{section name=UnvalidatedAttributes loop=$validation.attributes show=$validation.attributes}
<b>Input did not validate</b>
<i>{$UnvalidatedAttributes:item.identifier}:</i> {$UnvalidatedAttributes:item.name} ({$UnvalidatedAttributes:item.id}) <br/>
{section-else}
<b>Input was stored successfully</b>
{/section}

{/section}

<br />

<h2>Attributes</h2>
<hr/>
<table width="100%">
<tr><th>Identifier/Name/Type</th></tr>
{section name=Attributes loop=$attributes sequence=array("9999ff","ffffff")}
<input type="hidden" name="ContentAttribute_id[]" value="{$Attributes:item.id}" />
<input type="hidden" name="ContentAttribute_position[]" value="{$Attributes:item.placement}" />
    <tr><td><i>{$Attributes:item.data_type.information.name}</i></td></tr>
    <tr><td>{include uri="design:gui/lineedit.tpl" name=FieldID id_name="ContentAttribute_identifier[]" value=$Attributes:item.identifier}</td></tr>
    <tr>
        <td>{include uri="design:gui/lineedit.tpl" name=FieldName id_name="ContentAttribute_name[]" value=$Attributes:item.name}</td>
        <td><a href="{$module.functions.down.uri}/{$class.id}/{$Attributes:item.id}"><img src={"move-down.gif"|ezimage} height="12" width="12" border="0" alt="Down" /></a></td>
        <td><a href="{$module.functions.up.uri}/{$class.id}/{$Attributes:item.id}"><img src={"move-up.gif"|ezimage} height="12" width="12" border="0" alt="Up" /></a></td>
        {*<td><a href="/attribute/edit/{$Attributes:item.id}/{$class.id}"><img name="edit" border="0" src={"edit.png"|ezimage} width="16" height="16" align="top"></a></td>*}
        <td><input type="checkbox" name="ContentAttribute_id_checked[]" value="{$Attributes:item.id}" /></td></tr>
    <tr>
        <td>
        {* {include uri="design:class/datatypes.tpl" name=DataTypes id_name="ContentAttribute_data_type_string[]" datatypes=$datatypes current=$Attributes:item.data_type.information.string} *}
        Searchable<input type="checkbox" name="ContentAttribute_is_searchable_checked[]" value="{$Attributes:item.id}"  {section show=$Attributes:item.is_searchable}checked{/section} />&nbsp;&nbsp;&nbsp;
	Required<input type="checkbox" name="ContentAttribute_is_required_checked[]" value="{$Attributes:item.id}"  {section show=$Attributes:item.is_required}checked{/section} /></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="4">{class_attribute_edit_gui class_attribute=$Attributes:item}</td>
    </tr>
 
{/section}
</table>

<hr/>
<table width="100%">
<tr>
<td width="99%"></td>
<td>{include uri="design:gui/button.tpl" name=New id_name=NewButton value="New Attribute"}</td>
<td>{include uri="design:class/datatypes.tpl" name=DataTypes id_name=DataTypeString datatypes=$datatypes current=$datatype}</td>
<td>{include uri="design:gui/button.tpl" name=Delete id_name=DeleteButton value="Delete"}</td>
</tr>
</table>
<table width="100%">
<tr>
<td width="99%"></td>
<td>{include uri="design:gui/button.tpl" name=Store id_name=StoreButton value=Store}</td>
<td>{include uri="design:gui/button.tpl" name=Apply id_name=ApplyButton value=Apply}</td>
<td>{include uri="design:gui/button.tpl" name=Discard id_name=DiscardButton value=Discard}</td>
</tr>
</table>

</td></tr>
</table>

</form>