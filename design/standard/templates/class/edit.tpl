<form action={concat($module.functions.edit.uri,"/",$class.id)|ezurl} method="post" name="ClassEdit">

<div class="maincontentheader">
<h1>Editing class type - {$class.name}</h1>
</div>

<div class="byline">
<p class="created">Created by {$class.creator_id} on {$class.created|l10n(shortdatetime)}</p>
<p class="date">Modified by {$class.modifier_id} on {$class.modified|l10n(shortdatetime)}</p>
</div>

<div class="block">
<label>Object name:</label><div class="labelbreak"></div>
{include uri="design:gui/lineedit.tpl" name=ObjectName id_name=ContentClass_contentobject_name value=$class.contentobject_name}
</div>

<div class="block">
<label>Identifier:</label><div class="labelbreak"></div>
{include uri="design:gui/lineedit.tpl" name=Identifier id_name=ContentClass_identifier value=$class.identifier}
</div>

<div class="block">
<label>Name:</label><div class="labelbreak"></div>
{include uri="design:gui/lineedit.tpl" name=Name id_name=ContentClass_name value=$class.name}
</div>

<table class="list" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <th colspan="2">In group:</th>
</tr>
{section name=InGroups loop=$class.ingroup_list sequence=array(bglight,bgdark)}
<tr>
    <td class="{$InGroups:sequence}" width="99%">{$InGroups:item.group_name}</td>
    <td class="{$InGroups:sequence}" width="1%"><input type="checkbox" name="group_id_checked[]" value="{$InGroups:item.group_id}" /></td>
</tr>
{/section}
</table>


<div class="buttonblock">
<select name="ContentClass_group">
{section name=AllGroup loop=$class.group_list}
<option name="ContentClass_group[]" value="{$AllGroup:item.id}/{$AllGroup:item.name}">{$AllGroup:item.name}</option>
{/section}
</select>
{include uri="design:gui/button.tpl" name=AddGroup id_name=AddGroupButton value="Add group"}
{include uri="design:gui/button.tpl" name=DeleteGroup id_name=DeleteGroupButton value="Delete group"}
</div>


{section show=$validation.processed}

{section name=UnvalidatedAttributes loop=$validation.attributes show=$validation.attributes}
<div class="warning">
<h2>Input did not validate</h2>
<ul>
    <li>{$UnvalidatedAttributes:item.identifier}: {$UnvalidatedAttributes:item.name} ({$UnvalidatedAttributes:item.id})</li>
</ul>
</div>
{section-else}
<div class="feedback">
<h2>Input was stored successfully</h2>
</div>
{/section}

{/section}

<h2>Attributes</h2>
<table class="layoutlist" width="100%" cellpadding="0" cellspacing="0" border="0">
{section name=Attributes loop=$attributes sequence=array("bglight","bgdark")}
<tr>
<td class="{$Attributes:sequence}">
<input type="hidden" name="ContentAttribute_id[]" value="{$Attributes:item.id}" />
<input type="hidden" name="ContentAttribute_position[]" value="{$Attributes:item.placement}" />

<div class="block">
<label>Identifier:</label><div class="labelbreak"></div>
{include uri="design:gui/lineedit.tpl" name=FieldID id_name="ContentAttribute_identifier[]" value=$Attributes:item.identifier}
</div>

<div class="block">
<label>Name:</label><div class="labelbreak"></div>
{include uri="design:gui/lineedit.tpl" name=FieldName id_name="ContentAttribute_name[]" value=$Attributes:item.name}
</div>

{class_attribute_edit_gui class_attribute=$Attributes:item}

<td class="{$Attributes:sequence}">
<div class="block">
<label>Type:</label><div class="labelbreak"></div>
<p class="box">{$Attributes:item.data_type.information.name}</p>
</div>

<div class="block">
<input type="checkbox" name="ContentAttribute_is_searchable_checked[]" value="{$Attributes:item.id}"  {section show=$Attributes:item.is_searchable}checked{/section} /><label>Searchable</label>
</div>

<div class="block">
<input type="checkbox" name="ContentAttribute_is_required_checked[]" value="{$Attributes:item.id}"  {section show=$Attributes:item.is_required}checked{/section} /><label>Required</label>
</div>

{* {include uri="design:class/datatypes.tpl" name=DataTypes id_name="ContentAttribute_data_type_string[]" datatypes=$datatypes current=$Attributes:item.data_type.information.string} *}
</td>
        <td class="{$Attributes:sequence}" width="1%"><div class="listbutton"><a href={concat($module.functions.down.uri,"/",$class.id,"/",$Attributes:item.id)|ezurl}><img class="button" src={"button-move_down.gif"|ezimage} height="16" width="16" alt="Down" /></a></div></td>
        <td class="{$Attributes:sequence}" width="1%"><div class="listbutton"><a href={concat($module.functions.up.uri,"/",$class.id,"/",$Attributes:item.id)|ezurl}> <img class="button" src={"button-move_up.gif"|ezimage} height="16" width="16" alt="Up" /></a></div></td>
        {*<td class="{$Attributes:sequence}" width="1%"><div class="listbutton"><a href={concat("/attribute/edit/",$Attributes:item.id,"/",$class.id)|ezurl}><img class="button" src={"edit.png"|ezimage} width="16" height="16" alt="Edit"></a></div></td>*}
        <td class="{$Attributes:sequence}" width="1%"><input type="checkbox" name="ContentAttribute_id_checked[]" value="{$Attributes:item.id}" /></td>
</tr>
{/section}
</table>

<div class="buttonblock">
{include uri="design:class/datatypes.tpl" name=DataTypes id_name=DataTypeString datatypes=$datatypes current=$datatype}
{include uri="design:gui/button.tpl" name=New id_name=NewButton value="New Attribute"}
{include uri="design:gui/button.tpl" name=Delete id_name=DeleteButton value="Delete"}
</div>
<div class="buttonblock">
{include uri="design:gui/button.tpl" name=Store id_name=StoreButton value=Store}
{include uri="design:gui/button.tpl" name=Apply id_name=ApplyButton value=Apply}
{include uri="design:gui/button.tpl" name=Discard id_name=DiscardButton value=Discard}
</div>

</form>