{* Warnings *}
{section show=$validation.processed}
{section var=UnvalidatedAttributes loop=$validation.attributes show=$validation.attributes}
<div class="message-warning">
<h2>{'Input did not validate'|i18n( 'design/admin/class/edit' )}</h2>
<ul>
    <li>{$UnvalidatedAttributes.item.identifier}: {$UnvalidatedAttributes.item.name|wash} ({$UnvalidatedAttributes.item.id})</li>
</ul>
</div>
{section-else}
<div class="message-feedback">
<h2>{'Input was stored successfully'|i18n( 'design/admin/class/edit' )}</h2>
</div>
{/section}

{/section}


{* Main window *}
<form action={concat( $module.functions.edit.uri, '/', $class.id )|ezurl} method="post" name="ClassEdit">
<input type="hidden" name="ContentClassHasInput" value="1" />

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{$class.identifier|class_icon( 'normal', $class.name )}&nbsp;{'Edit <%class_name> [Class]'|i18n( 'design/admin/class/edit',, hash( '%class_name', $class.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-information">
<p class="date">{'Last modified'|i18n( 'design/admin/class/edit' )}:&nbsp;{$class.modified|l10n( shortdatetime )},&nbsp;{$class.modifier.contentobject.name}</p>
</div>

<div class="context-attributes">
    <div class="block">
    <label>{'Name'|i18n( 'design/admin/class/edit' )}</label>
    <input class="box" type="text" name="ContentClass_name" size="30" value="{$class.name|wash}" />
    </div>

    <div class="block">
    <label>{'Identifier'|i18n( 'design/admin/class/edit' )}</label>
    <input class="box" type="text" name="ContentClass_identifier" size="30" value="{$class.identifier|wash}" />
    </div>

    <div class="block">
    <label>{'Object name pattern'|i18n( 'design/admin/class/edit' )}</label>
    <input class="box" type="text" name="ContentClass_contentobject_name" size="30" value="{$class.contentobject_name|wash}" />
    </div>

    <div class="block">
    <label>
    <input type="hidden" name="ContentClass_is_container_exists" value="1" />
    {section show=$class.is_container|eq( 1 )}
        <input type="checkbox" name="ContentClass_is_container_checked" value="{$class.is_container}" checked />
    {section-else}
        <input type="checkbox" name="ContentClass_is_container_checked" value="{$class.is_container}" />
    {/section}
    {'Container'|i18n( 'design/admin/class/edit' )}</label>
    </div>


{section show=$attributes}
<hr />

<table class="list" cellspacing="0">
{section var=Attributes loop=$attributes}

<tr>
    <th class="tight"><input type="checkbox" name="ContentAttribute_id_checked[]" value="{$Attributes.item.id}" /></th>
    <th class="wide">{$Attributes.number}. {$Attributes.item.name|wash} [{$Attributes.item.data_type.information.name|wash}] (id:{$Attributes.item.id})</th>
    <th class="tight" cellspacing="2"><div class="listbutton"><input type="image" class="button" src={'button-move_down.gif'|ezimage} height="16" width="16" alt="{'Down'|i18n( 'design/admin/class/edit' )}" name="MoveDown_{$Attributes.item.id}" />&nbsp;<input type="image" class="button" src={'button-move_up.gif'|ezimage} height="16" width="16" alt="{'Up'|i18n( 'design/admin/class/edit' )}" name="MoveUp_{$Attributes.item.id}" /></div></th>
</tr>

<tr>

<!-- Attribute input Start -->
<td colspan="4">
<input type="hidden" name="ContentAttribute_id[]" value="{$Attributes.item.id}" />
<input type="hidden" name="ContentAttribute_position[]" value="{$Attributes.item.placement}" />

<div class="block">

<div class="element">
<label>{'Name'|i18n( 'design/admin/class/edit' )}</label>
<input class="halfbox" type="text" name="ContentAttribute_name[]" value="{$Attributes.item.name}" />
</div>

<div class="element">
<label>{'Identifier'|i18n( 'design/admin/class/edit' )}</label>
<input class="halfbox" type="text" name="ContentAttribute_identifier[]" value="{$Attributes.item.identifier}" />
</div>

<div class="break"></div>

</div>
<!-- Attribute input End -->

<!-- Attribute flags Start -->
<div class="block inline">
<label>
<input type="checkbox" name="ContentAttribute_is_required_checked[]" value="{$Attributes.item.id}"  {section show=$Attributes.item.is_required}checked="checked"{/section} />
{'Required'|i18n( 'design/admin/class/edit' )}
</label>

{section show=$Attributes.item.data_type.is_indexable}
<label>
<input type="checkbox" name="ContentAttribute_is_searchable_checked[]" value="{$Attributes.item.id}"  {section show=$Attributes.item.is_searchable}checked="checked"{/section} />
{'Searchable'|i18n( 'design/admin/class/edit' )}
</label>
{/section}

{section show=$Attributes.item.data_type.is_information_collector}
<label>
<input type="checkbox" name="ContentAttribute_is_information_collector_checked[]" value="{$Attributes.item.id}"  {section show=$Attributes.item.is_information_collector}checked="checked"{/section} />
{'Information collector'|i18n( 'design/admin/class/edit' )}
</label>
{/section}

<label>
<input type="checkbox" name="ContentAttribute_can_translate_checked[]" value="{$Attributes.item.id}" {section show=$Attributes.item.can_translate|eq(0)}checked="checked"{/section} />
{'Disable translation'|i18n( 'design/admin/class/edit' )}
</label>

</div>

<div class="block">
  {class_attribute_edit_gui class_attribute=$Attributes.item}
</div>

</td>
<!-- Attribute flags End -->

</tr>

{/section}

</table>

<hr />
{section-else}

<div class="block">
<p>{'There are no attributes within this class.'|i18n( 'design/admin/class/edit' )}</p>
</div>
{/section}


<div class="controlbar">

{* Remove selected attributes button *}
<div class="block">
<input class="button" type="submit" name="RemoveButton" value="{'Remove selected attributes'|i18n( 'design/admin/class/edit' )}" {section show=$attributes|not}disabled="disabled"{/section} />
</div>

<div class="block">
{include uri="design:class/datatypes.tpl" name=DataTypes id_name=DataTypeString datatypes=$datatypes current=$datatype}
<input class="button" type="submit" name="NewButton" value="{'Add attribute'|i18n( 'design/admin/class/edit' )}" />
</div>

</div>

{* DESIGN: Content END *}</div></div></div>


<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
    <div class="block">
    <input class="button" type="submit" name="StoreButton"   value="{'OK'|i18n( 'design/admin/class/edit' )}" />
    <input class="button" type="submit" name="ApplyButton"   value="{'Apply'|i18n( 'design/admin/class/edit' )}" />
    <input class="button" type="submit" name="DiscardButton" value="{'Cancel'|i18n( 'design/admin/class/edit' )}" />
    </div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
