{* Warnings *}
{section show=$validation.processed}
{section var=UnvalidatedAttributes loop=$validation.attributes show=$validation.attributes}
<div class="message-warning">
<h2>{'The class definition could not be stored.'|i18n( 'design/admin/class/edit' )}</h2>
<p>{'The following information is either missing or invalid'|i18n( 'design/admin/class/edit' )}:</p>
<ul>
    {section show=is_set( $UnvalidatedAttributes.item.reason )}
        <li>{$UnvalidatedAttributes.item.identifier}: ({$UnvalidatedAttributes.item.id})
            {$UnvalidatedAttributes.item.reason.text|wash}
        <ul>
        {section var=subitem loop=$UnvalidatedAttributes.item.reason.list}
            <li>{section show=is_set( $subitem.identifier )}{$subitem.identifier|wash}: {/section}{$subitem.text|wash}</li>
        {/section}
        </ul>
        </li>
    {section-else}
        <li>{$UnvalidatedAttributes.item.identifier}: {$UnvalidatedAttributes.item.name|wash} ({$UnvalidatedAttributes.item.id})</li>
    {/section}
</ul>
</div>
{section-else}
<div class="message-feedback">
<h2>{'The class definition was successfully stored.'|i18n( 'design/admin/class/edit' )}</h2>
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

    {* Name. *}
    <div class="block">
    <label>{'Name'|i18n( 'design/admin/class/edit' )}</label>
    <input class="halfbox" type="text" name="ContentClass_name" size="30" value="{$class.name|wash}" title="{'Use the name field to set the informal name of the class. The name field may contain whitespace and special characters.'|i18n( 'design/admin/class/edit' )}"/>
    </div>

    {* Identifier. *}
    <div class="block">
    <label>{'Identifier'|i18n( 'design/admin/class/edit' )}</label>
    <input class="halfbox" type="text" name="ContentClass_identifier" size="30" value="{$class.identifier|wash}" title="{'Use the identifier field to set the name of the class internally in eZ publish. This name is used within the templates and PHP code. The identifier field may not contain whitespace or special characters.'|i18n( 'design/admin/class/edit' )}"/>
    </div>

    {* Object name pattern. *}
    <div class="block">
    <label>{'Object name pattern'|i18n( 'design/admin/class/edit' )}</label>
    <input class="halfbox" type="text" name="ContentClass_contentobject_name" size="30" value="{$class.contentobject_name|wash}" title="{'Use the object name pattern field to control how the name of the object (used for nice urls and listings in the administration part) is created. To use the value inserted in any of an attributes insert the identifier of that attribute enclosed in angle brackets. Any other text inserted in this field will be included literally.'|i18n( 'design/admin/class/edit' )}"/>
    </div>

    {* Container. *}
    <div class="block">
    <label>{'Container'|i18n( 'design/admin/class/edit' )}</label>
    <input type="hidden" name="ContentClass_is_container_exists" value="1" />
      <input type="checkbox" name="ContentClass_is_container_checked" value="{$class.is_container}" {section show=$class.is_container|eq( 1 )}checked{/section} title="{'Use the container checkbox to control if instances of this class can have sub-items or not. If checked, it will not be possible to create new sub-items in instances of this class. Any existing sub-items will not be displayed.'|i18n( 'design/admin/class/edit' )}"/>
    </div>

{section show=$attributes}
<hr />

<table class="list" cellspacing="0">
{section var=Attributes loop=$attributes}

<tr>
    <th class="tight"><input type="checkbox" name="ContentAttribute_id_checked[]" value="{$Attributes.item.id}" title="{'Use these checkboxes to mark attributes for removal. Click the "Remove selected" button to actually remove the selected attributes.'|i18n( 'design/admin/class/edit' )|wash}"/></th>
    <th class="wide">{$Attributes.number}. {$Attributes.item.name|wash} [{$Attributes.item.data_type.information.name|wash}] (id:{$Attributes.item.id})</th>
    <th class="tight" cellspacing="2">
      <div class="listbutton">
          <input type="image" class="button" src={'button-move_down.gif'|ezimage} height="16" width="16" alt="{'Down'|i18n( 'design/admin/class/edit' )}" name="MoveDown_{$Attributes.item.id}" title="{'Use the order buttons to set the order of the attributes in the class. The up arrow moves the attribute one place up in the list of attributes. The down arrow moves the attribute one place down in the list of attributes.'|i18n( 'design/admin/class/edit' )}" />&nbsp;
          <input type="image" class="button" src={'button-move_up.gif'|ezimage} height="16" width="16" alt="{'Up'|i18n( 'design/admin/class/edit' )}" name="MoveUp_{$Attributes.item.id}" title="{'Use the order buttons to set the order of the attributes in the class. The up arrow moves the attribute one place up in the list of attributes. The down arrow moves the attribute one place down in the list of attributes.'|i18n( 'design/admin/class/edit' )}" />
      </div>
    </th>
</tr>

<tr>
<td></td>
<!-- Attribute input Start -->
<td colspan="3">
<input type="hidden" name="ContentAttribute_id[]" value="{$Attributes.item.id}" />
<input type="hidden" name="ContentAttribute_position[]" value="{$Attributes.item.placement}" />


{* Attribute name. *}
<div class="block">
<label>{'Name'|i18n( 'design/admin/class/edit' )}</label>
<input class="box" type="text" name="ContentAttribute_name[]" value="{$Attributes.item.name}" title="{'Use the name field to set the informal name of this field. Use this field to describe the content that is supposed to be entered into this attribute. The name field may contain whitespace and special characters.'|i18n( 'design/admin/class/edit' )}" />
</div>

{* Attribute identifier. *}
<div class="block">
<label>{'Identifier'|i18n( 'design/admin/class/edit' )}</label>
<input class="box" type="text" name="ContentAttribute_identifier[]" value="{$Attributes.item.identifier}" title="{'Use the identifier field to set the name of the attribute internally in eZ publish. This name is used within the templates you create for this class. The attribute identifier may not contain whitespace or special characters.'|i18n( 'design/admin/class/edit' )}" />
</div>

<!-- Attribute input End -->

<!-- Attribute flags Start -->
<div class="block inline">

{* Required. *}
<label>
<input type="checkbox" name="ContentAttribute_is_required_checked[]" value="{$Attributes.item.id}"  {section show=$Attributes.item.is_required}checked="checked"{/section} title="{'Use the required checkbox to control if the user should be forced to enter information into this attribute.'|i18n( 'design/admin/class/edit' )}" />
{'Required'|i18n( 'design/admin/class/edit' )}
</label>

{* Searchable. *}

<label>
{section show=$Attributes.item.data_type.is_indexable}
<input type="checkbox" name="ContentAttribute_is_searchable_checked[]" value="{$Attributes.item.id}"  {section show=$Attributes.item.is_searchable}checked="checked"{/section} title="{'Use the searchable checkbox to control if the information found in this attribute should be indexed by the search engine.'|i18n( 'design/admin/class/edit' )}" />
{section-else}
<input type="checkbox" name="" value="" disabled="disabled" title="{'This attribute type can not be indexed by the search engine.'|i18n( 'design/admin/class/edit' )}" />
{/section}
{'Searchable'|i18n( 'design/admin/class/edit' )}
</label>

{* Information collector. *}
<label>
{section show=$Attributes.item.data_type.is_information_collector}
<input type="checkbox" name="ContentAttribute_is_information_collector_checked[]" value="{$Attributes.item.id}"  {section show=$Attributes.item.is_information_collector}checked="checked"{/section} title="{'Use the information collector checkbox to control whether this attribute is used to display information to the user or to fetch information from the user.'|i18n( 'design/admin/class/edit' )}" />
{section-else}
<input type="checkbox" name="" value="" disabled="disabled" title="{'This attribute type can not be used as an information collector.'|i18n( 'design/admin/class/edit' )}" />
{/section}
{'Information collector'|i18n( 'design/admin/class/edit' )}
</label>


{* Disable translation. *}
<label>
<input type="checkbox" name="ContentAttribute_can_translate_checked[]" value="{$Attributes.item.id}" {section show=$Attributes.item.can_translate|eq(0)}checked="checked"{/section} title="{'Use disable translation checkbox to control if this attribute should be translated or not when an instance of this class is translated into another language. Use this setting for attributes that contain non-translatable content.'|i18n( 'design/admin/class/edit' )}" />
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
<p>{'This class does not have any attributes.'|i18n( 'design/admin/class/edit' )}</p>
</div>
{/section}


<div class="controlbar">

{* Remove selected attributes button *}
<div class="block">
{section show=$attributes}
<input class="button" type="submit" name="RemoveButton" value="{'Remove selected attributes'|i18n( 'design/admin/class/edit' )}" title="{'Remove the selected attributes from the list above.'|i18n( 'design/admin/class/edit' )}" />
{section-else}
<input class="button-disabled" type="submit" name="RemoveButton" value="{'Remove selected attributes'|i18n( 'design/admin/class/edit' )}" title="{'Remove the selected attributes from the list above.'|i18n( 'design/admin/class/edit' )}" disabled="disabled" />
{/section}
</div>

<div class="block">
{include uri="design:class/datatypes.tpl" name=DataTypes id_name=DataTypeString datatypes=$datatypes current=$datatype}
<input class="button" type="submit" name="NewButton" value="{'Add attribute'|i18n( 'design/admin/class/edit' )}" title="{'Create a new attribute within this class. Use the menu on the left to select the type of the attribute.'|i18n( 'design/admin/class/edit' )}"/>
</div>

</div>

{* DESIGN: Content END *}</div></div></div>


<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
    <div class="block">
    <input class="button" type="submit" name="StoreButton"   value="{'OK'|i18n( 'design/admin/class/edit' )}" title="{'Store the changes to the class and exit to the class overview.'|i18n( 'design/admin/class/edit' )}" />
    <input class="button" type="submit" name="ApplyButton"   value="{'Apply'|i18n( 'design/admin/class/edit' )}" title="{'Store the changes to the class and continue editing.'|i18n( 'design/admin/class/edit' )}" />
    <input class="button" type="submit" name="DiscardButton" value="{'Cancel'|i18n( 'design/admin/class/edit' )}" title="{'Discard all changes to the class and exit to the class overview.'|i18n( 'design/admin/class/edit' )}" />
    </div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
