{* Warnings *}

{section show=$validation.processed}
{* handle attribute validation errors *}
{section show=$validation.attributes}
<div class="message-warning">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The class definition could not be stored.'|i18n( 'design/admin/class/edit' )}</h2>
<p>{'The following information is either missing or invalid'|i18n( 'design/admin/class/edit' )}:</p>
<ul>
    {section var=UnvalidatedAttributes loop=$validation.attributes}
    {section show=is_set( $UnvalidatedAttributes.item.reason )}
        <li>attribute '{$UnvalidatedAttributes.item.identifier}': ({$UnvalidatedAttributes.item.id})
            {$UnvalidatedAttributes.item.reason.text|wash}
        <ul>
        {section var=subitem loop=$UnvalidatedAttributes.item.reason.list}
            <li>{if is_set( $subitem.identifier )}{$subitem.identifier|wash}: {/if}{$subitem.text|wash}</li>
        {/section}
        </ul>
        </li>
    {section-else}
        <li>attribute '{$UnvalidatedAttributes.item.identifier}': {$UnvalidatedAttributes.item.name|wash} ({$UnvalidatedAttributes.item.id})</li>
    {/section}
    {/section}
</ul>
</div>
{section-else}
{* no attribute validation errors *}
<div class="message-feedback">
    <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The class definition was successfully stored.'|i18n( 'design/admin/class/edit' )}</h2>
</div>
{/section}

{section-else} {* !$validation|processed *}
{* we're about to store the class, so let's handle basic class properties errors (name, identifier, presence of attributes) *}
    {section show=or( $validation.class_errors )}
    <div class="message-warning">
    <h2>{"The class definition contains the following errors"|i18n("design/admin/class/edit")}:</h2>
    <ul>
    {section var=ClassErrors loop=$validation.class_errors}
        <li>{$ClassErrors.item.text}</li>
    {/section}
    </ul>
    </div>
    {/section}
{/section}

{* Main window *}
<form action={concat( $module.functions.edit.uri, '/', $class.id, '/(language)/', $language_code )|ezurl} method="post" id="ClassEdit" name="ClassEdit">
<input type="hidden" name="ContentClassHasInput" value="1" />

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title" title="{'Class name and number of objects'|i18n( 'design/admin/class/view' )}">{$class.identifier|class_icon( 'normal', $class.name|wash )}&nbsp;{'Edit <%class_name> [%object_count]'|i18n( 'design/admin/class/edit',, hash( '%class_name', $class.nameList[$language_code], '%object_count', $class.object_count ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-information">
<p class="modified">{'Last modified'|i18n( 'design/admin/class/edit' )}:&nbsp;{$class.modified|l10n( shortdatetime )},&nbsp;{$class.modifier.contentobject.name|wash}</p>
{def $locale = fetch( 'content', 'locale', hash( 'locale_code', $language_code ) )}
<p class="translation">{$locale.intl_language_name}&nbsp;<img src="{$language_code|flag_icon}" alt="{$language_code}" style="vertical-align: middle;" /></p>
{undef $locale}
</div>

<div class="context-attributes">

    {* Name. *}
    <div class="block">
    <label for="className">{'Name'|i18n( 'design/admin/class/edit' )}:</label>
    <input class="box" type="text" id="className" name="ContentClass_name" size="30" value="{$class.nameList[$language_code]|wash}" title="{'Use this field to set the informal name of the class. The name field can contain whitespaces and special characters.'|i18n( 'design/admin/class/edit' )|wash}" />
    </div>

    {* Identifier. *}
    <div class="block">
    <label for="ContentClass_identifier">{'Identifier'|i18n( 'design/admin/class/edit' )}:</label>
    <input class="box" type="text" id="ContentClass_identifier" name="ContentClass_identifier" size="30" value="{$class.identifier|wash}" title="{'Use this field to set the internal name of the class. The identifier will be used in templates and in PHP code. Allowed characters are letters, numbers and underscores.'|i18n( 'design/admin/class/edit' )|wash}" />
    </div>

    {* Object name pattern. *}
    <div class="block">
    <label for="ContentClass_contentobject_name">{'Object name pattern'|i18n( 'design/admin/class/edit' )}:</label>
    <input class="box" type="text" id="ContentClass_contentobject_name" name="ContentClass_contentobject_name" size="30" value="{$class.contentobject_name|wash}" title="{'Use this field to configure how the name of the objects are generated. Type in the identifiers of the attributes that should be used. The identifiers must be enclosed in angle brackets. Text outside angle brackets will be included as it is shown here.'|i18n( 'design/admin/class/edit' )|wash}" />
    </div>

    {* URL alias name pattern. *}
    <div class="block">
    <label for="ContentClass_url_alias_name">{'URL alias name pattern'|i18n( 'design/admin/class/edit' )}:</label>
    <input class="box" type="text" id="ContentClass_url_alias_name" name="ContentClass_url_alias_name" size="30" value="{$class.url_alias_name|wash}" title="{'Use this field to configure how the url alias of the objects are generated (applies to nice URLs). Type in the identifiers of the attributes that should be used. The identifiers must be enclosed in angle brackets. Text outside angle brackets will be included as is.'|i18n( 'design/admin/class/edit' )|wash}" />
    </div>

    {* Container. *}
    <div class="block">
    <label for="ContentClass_is_container_checked">{'Container'|i18n( 'design/admin/class/edit' )}:</label>
    <input type="hidden" name="ContentClass_is_container_exists" value="1" />
    <input type="checkbox" id="ContentClass_is_container_checked" name="ContentClass_is_container_checked" value="{$class.is_container}" {if $class.is_container|eq( 1 )}checked="checked"{/if} title="{'Use this checkbox to allow instances of the class to have sub items. If checked, it will be possible to create new sub items. If not checked, the sub items will not be displayed.'|i18n( 'design/admin/class/edit' )|wash}" />
    </div>

    {* Class Default Sorting *}
    <div class="block">
    <label for="ContentClass_default_sorting_field">{'Default sorting of children'|i18n( 'design/admin/class/edit' )}:</label>
    {def $sort_fields=fetch( content, available_sort_fields )
         $title='Use these controls to set the default sorting method for the sub items of instances of the content class.'|i18n( 'design/admin/class/edit' )|wash }
    <input type="hidden" name="ContentClass_default_sorting_exists" value="1" />
    <select id="ContentClass_default_sorting_field" name="ContentClass_default_sorting_field" title="{$title}">
    {foreach $sort_fields as $sf_key => $sf_item}
        <option value="{$sf_key}" {if eq( $sf_key, $class.sort_field )}selected="selected"{/if}>{$sf_item}</option>
    {/foreach}
    </select>
    <select id="ContentClass_default_sorting_order" name="ContentClass_default_sorting_order" title="{$title}">
        <option value="0"{if eq($class.sort_order, 0)} selected="selected"{/if}>{'Descending'|i18n( 'design/admin/class/edit' )}</option>
        <option value="1"{if eq($class.sort_order, 1)} selected="selected"{/if}>{'Ascending'|i18n( 'design/admin/class/edit' )}</option>
    </select>
    {undef}
    </div>

    {* Object availablility. *}
    <div class="block">
    <label for="ContentClass_always_available">{'Default object availability'|i18n( 'design/standard/class/edit' )}:</label>
    <input type="hidden" name="ContentClass_always_available_exists" value="1" />
    <input type="checkbox" id="ContentClass_always_available" name="ContentClass_always_available"{if $class.always_available|eq(1)} checked="checked"{/if} title="{'Use this checkbox to set the default availability for the objects of this class. The availability controls whether an object should be shown even if it does not exist in one of the languages specified by the "SiteLanguageList" setting. If this is the case, the system will use the main language of the object.'|i18n( 'design/admin/class/edit' )|wash}" />
    </div>

<div class="block">
<label>{'Class attributes'|i18n( 'design/admin/class/edit' )}:</label>
</div>
{section show=$attributes}
{def $priority_value = 0}

<table id="ezcca-edit-list" class="special" cellspacing="0" summary="{'List of class attributes'|i18n( 'design/admin/class/edit' )}">
<tbody>
{section var=Attributes loop=$attributes sequence=array( bglight, bgdark )}

{set $priority_value = $priority_value|sum( 10 )}

<tr class="ezcca-edit-list-item {$Attributes.sequence}"{if $last_changed_id|eq( $Attributes.item.id )} id="LastChangedID"{/if}>
<td>
<table cellspacing="0" summary="{'Class attribute item'|i18n( 'design/admin/class/edit' )}">
<tr>
    <th class="tight"><input type="checkbox" name="ContentAttribute_id_checked[]" value="{$Attributes.item.id}" title="{'Select attribute for removal. Click the "Remove selected attributes" button to remove the selected attributes.'|i18n( 'design/admin/class/edit' )|wash}" /></th>
    <th class="wide">{$Attributes.number}. {$Attributes.item.name|wash} [{$Attributes.item.data_type.information.name|wash}] (id:{$Attributes.item.id})</th>
    <th class="tight">
      <div class="listbutton">
          <input type="image" src={'button-move_down.gif'|ezimage} alt="{'Down'|i18n( 'design/admin/class/edit' )}" name="MoveDown_{$Attributes.item.id}" title="{'Use the order buttons to set the order of the class attributes. The up arrow moves the attribute one place up. The down arrow moves the attribute one place down.'|i18n( 'design/admin/class/edit' )|wash}" />&nbsp;
          <input type="image" src={'button-move_up.gif'|ezimage} alt="{'Up'|i18n( 'design/admin/class/edit' )}" name="MoveUp_{$Attributes.item.id}" title="{'Use the order buttons to set the order of the class attributes. The up arrow moves the attribute one place up. The down arrow moves the attribute one place down.'|i18n( 'design/admin/class/edit' )|wash}" />
          <input size="2" maxlength="4" type="text" name="ContentAttribute_priority[]" value="{$priority_value}" />
      </div>
    </th>
</tr>
<tr>
<td>&nbsp;</td>
<!-- Attribute input Start -->
<td colspan="2">
<input type="hidden" name="ContentAttribute_id[]" value="{$Attributes.item.id}" />
<input type="hidden" name="ContentAttribute_position[]" value="{$Attributes.item.placement}" />


{* Attribute name. *}
<div class="block">
<label for="ContentAttribute_name_{$Attributes.item.id}">{'Name'|i18n( 'design/admin/class/edit' )}:</label>
<input class="box" type="text" id="ContentAttribute_name_{$Attributes.item.id}" name="ContentAttribute_name[]" value="{$Attributes.item.nameList[$language_code]|wash}" title="{'Use this field to set the informal name of the attribute. This field can contain whitespaces and special characters.'|i18n( 'design/admin/class/edit' )|wash}" />
</div>

{* Attribute identifier. *}
<div class="block">
<label for="ContentAttribute_identifier_{$Attributes.item.id}">{'Identifier'|i18n( 'design/admin/class/edit' )}:</label>
<input class="box" type="text" id="ContentAttribute_identifier_{$Attributes.item.id}" name="ContentAttribute_identifier[]" value="{$Attributes.item.identifier}" title="{'Use this field to set the internal name of the attribute. The identifier will be used in templates and in PHP code. Allowed characters are letters, numbers and underscores.'|i18n( 'design/admin/class/edit' )|wash}" />
</div>

<!-- Attribute input End -->

<!-- Attribute flags Start -->
<div class="block">

{* Required. *}
<div class="element">
<label for="ContentAttribute_is_required_{$Attributes.item.id}">
<input type="checkbox" id="ContentAttribute_is_required_{$Attributes.item.id}" name="ContentAttribute_is_required_checked[]" value="{$Attributes.item.id}"  {if $Attributes.item.is_required}checked="checked"{/if} title="{'Use this checkbox to specify whether the user should be forced to enter information into the attribute.'|i18n( 'design/admin/class/edit' )|wash}" />
{'Required'|i18n( 'design/admin/class/edit' )}
</label>
</div>

{* Searchable. *}
<div class="element">
<label for="ContentAttribute_is_searchable_{$Attributes.item.id}">
{if $Attributes.item.data_type.is_indexable}
<input type="checkbox" id="ContentAttribute_is_searchable_{$Attributes.item.id}" name="ContentAttribute_is_searchable_checked[]" value="{$Attributes.item.id}"  {if $Attributes.item.is_searchable}checked="checked"{/if} title="{'Use this checkbox to specify whether the contents of the attribute should be indexed by the search engine.'|i18n( 'design/admin/class/edit' )|wash}" />
{else}
<input type="checkbox" id="ContentAttribute_is_searchable_{$Attributes.item.id}" name="ContentAttribute_is_searchable_checked[]" value="" disabled="disabled" title="{'The <%datatype_name> datatype does not support search indexing.'|i18n( 'design/admin/class/edit',, hash( '%datatype_name', $Attributes.item.data_type.information.name ) )|wash}" />
{/if}
{'Searchable'|i18n( 'design/admin/class/edit' )}
</label>
</div>

{* Information collector. *}
<div class="element">
<label for="ContentAttribute_is_information_collector_{$Attributes.item.id}">
{if $Attributes.item.data_type.is_information_collector}
<input type="checkbox" id="ContentAttribute_is_information_collector_{$Attributes.item.id}" name="ContentAttribute_is_information_collector_checked[]" value="{$Attributes.item.id}"  {if $Attributes.item.is_information_collector}checked="checked"{/if} title="{'Use this checkbox to specify whether the attribute should collect input from users.'|i18n( 'design/admin/class/edit' )|wash}" />
{else}
<input type="checkbox" id="ContentAttribute_is_information_collector_{$Attributes.item.id}" name="ContentAttribute_is_information_collector_checked[]" value="" disabled="disabled" title="{'The <%datatype_name> datatype cannot be used as an information collector.'|i18n( 'design/admin/class/edit',, hash( '%datatype_name', $Attributes.item.data_type.information.name ) )|wash}" />
{/if}
{'Information collector'|i18n( 'design/admin/class/edit' )}
</label>
<div class="break"></div>
</div>


{* Disable translation. *}
<div class="element">
<label for="ContentAttribute_can_translate_{$Attributes.item.id}">
<input type="checkbox" id="ContentAttribute_can_translate_{$Attributes.item.id}" name="ContentAttribute_can_translate_checked[]" value="{$Attributes.item.id}" {if or( $Attributes.item.can_translate|eq(0), $Attributes.item.data_type.properties.translation_allowed|not )}checked="checked"{/if} {if $Attributes.item.data_type.properties.translation_allowed|not}disabled="disabled"{/if} title="{'Use this checkbox for attributes that contain non-translatable content.'|i18n( 'design/admin/class/edit' )|wash}" />
{'Disable translation'|i18n( 'design/admin/class/edit' )}
</label>
</div>

</div>

{class_attribute_edit_gui class_attribute=$Attributes.item}

</td>
</tr>
<!-- Attribute flags End -->
</table>
</td>
</tr>

{/section}
</tbody>
</table>
{undef $priority_value}
{section-else}

<div class="block">
<p>{'This class does not have any attributes.'|i18n( 'design/admin/class/edit' )}</p>
</div>
{/section}




<div class="controlbar">

{* Remove selected attributes button *}
<div class="block">
{if $attributes}
<input class="button" type="submit" name="RemoveButton" value="{'Remove selected attributes'|i18n( 'design/admin/class/edit' )}" title="{'Remove the selected attributes.'|i18n( 'design/admin/class/edit' )|wash}" />
{else}
<input class="button-disabled" type="submit" name="RemoveButton" value="{'Remove selected attributes'|i18n( 'design/admin/class/edit' )}" title="{'Remove the selected attributes.'|i18n( 'design/admin/class/edit' )|wash}" disabled="disabled" />
{/if}
</div>

<div class="block">
{include uri="design:class/datatypes.tpl" name=DataTypes id_name=DataTypeString datatypes=$datatypes current=$datatype}
<input class="button" type="submit" name="NewButton" value="{'Add attribute'|i18n( 'design/admin/class/edit' )}" title="{'Add a new attribute to the class. Use the menu on the left to select the attribute type.'|i18n( 'design/admin/class/edit' )|wash}" />
</div>

</div>

</div>

{* DESIGN: Content END *}</div></div></div>


<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
    <div class="block">
    <input class="defaultbutton" type="submit" name="StoreButton"   value="{'OK'|i18n( 'design/admin/class/edit' )}" title="{'Store changes and exit from edit mode.'|i18n( 'design/admin/class/edit' )|wash}" />

    {if eq( ezini( 'ClassSettings', 'ApplyButton', 'content.ini' ), 'enabled' )}
    <input class="button" type="submit" name="ApplyButton"   value="{'Apply'|i18n( 'design/admin/class/edit' )}" title="{'Store changes and continue editing.'|i18n( 'design/admin/class/edit' )|wash}" />
    {/if}

    <input class="button" type="submit" name="DiscardButton" value="{'Cancel'|i18n( 'design/admin/class/edit' )}" title="{'Discard all changes and exit from edit mode.'|i18n( 'design/admin/class/edit' )|wash}" />
    </div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

</form>


{literal}
<script language="JavaScript" type="text/javascript">
<!--
jQuery(function( $ )//called on document.ready
{
    var el = $('#LastChangedID input[name^=ContentAttribute_name]');
    if ( !el.size() )
    	el = $('#className');
    window.scrollTo(0, Math.max( el.offset().top - 180, 0 ));
    el.focus();

    var moveButtons = $('#ezcca-edit-list div.listbutton input[name^=Move]');
    moveButtons.click(function( e )
    {
    	e.preventDefault();
    	var tr = $(this).closest('tr.ezcca-edit-list-item'), param = this.name.split('_'), up = param[0] === 'MoveUp';

    	// swap items in dom
    	if ( up )
    	{

            var swap = tr.prev();
        	if ( !swap.size() )
                return false;
        	swap.before( tr );
        }
    	else
    	{
            var swap = tr.next();
        	if ( !swap.size() )
                return false;
        	swap.after( tr );
        }

        // swap priority number
        var inp = tr.find('input[name^=ContentAttribute_priority]'), inp2 = swap.find('input[name^=ContentAttribute_priority]'), inpv = inp.val();
        inp.val( inp2.val() );
        inp2.val( inpv );

        // store with ajax request
        var postVar = { 'ContentClassHasInput': 0 };
        postVar[ param[0] ] = param[1];
        $.post( $('#ClassEdit').attr('action'), postVar );
        return false;
    });
    
});
-->
</script>
{/literal}
