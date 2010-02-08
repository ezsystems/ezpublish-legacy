{include uri='design:class/window_controls.tpl'}

{section show=$validation.processed}
{section show=$validation.groups}
<div class="message-warning">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Input did not validate'|i18n( 'design/admin/class/view' )}</h2>
<ul>
{section var=item loop=$validation.groups}
    <li>{$item.text}</li>
{/section}
</ul>
</div>
{/section}
{/section}


<div class="context-block">
{* DESIGN: Header START *}<div class="box-header">
<h1 class="context-title" title="{'Class name and number of objects'|i18n( 'design/admin/class/view' )}">{$class.identifier|class_icon( 'normal', $class.nameList[$language_code]|wash )}&nbsp;{$class.nameList[$language_code]|wash} [{$class.object_count}]</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div>

{* DESIGN: Content START *}<div class="box-content">

<div class="context-information">
<p class="left modified">{'Last modified: %time, %username'|i18n( 'design/admin/class/view',, hash( '%username',$class.modifier.contentobject.name, '%time', $class.modified|l10n( shortdatetime ) ) )|wash}</p>
{def $locale = fetch( 'content', 'locale', hash( 'locale_code', $language_code ) )}
<p class="right translation">{$locale.intl_language_name}&nbsp;<img src="{$language_code|flag_icon}" alt="{$language_code}" style="vertical-align: middle;" /></p>
{undef $locale}
<div class="break"></div>
</div>

<div class="context-attributes">

<div class="block">
<div class="left">
    <div class="block">
        <h6>{'Name'|i18n( 'design/admin/class/view' )}:</h6>
        {$class.nameList[$language_code]|wash}
    </div>

    <div class="block">
        <h6>{'Identifier'|i18n( 'design/admin/class/view' )}:</h6>
        {$class.identifier|wash}
    </div>

    <div class="block">
        <h6>{'Description'|i18n( 'design/admin/class/view' )}:</h6>
        {$class.descriptionList[$language_code]|wash}
    </div>

    <div class="block">
        <h6>{'Object name pattern'|i18n( 'design/admin/class/view' )}:</h6>
        {$class.contentobject_name|wash}
    </div>

    <div class="block">
        <h6>{'URL alias name pattern'|i18n( 'design/admin/class/view' )}:</h6>
        {$class.url_alias_name|wash}
    </div>
</div>

<div class="right">
    <div class="block">
        <h6>{'Container'|i18n( 'design/admin/class/view' )}:</h6>
        {if $class.is_container|eq(1)}
            {'Yes'|i18n( 'design/admin/class/view' )}
        {else}
            {'No'|i18n( 'design/admin/class/view' )}
        {/if}
    </div>

    <div class="block">
        <h6>{'Default object availability'|i18n( 'design/admin/class/view' )}:</h6>
        {if $class.always_available|eq(0)}
            {'Not available'|i18n( 'design/admin/class/view' )}
        {else}
            {'Available'|i18n( 'design/admin/class/view' )}
        {/if}
    </div>

    {*** Class Default Sorting ***}
    <div class="block">
    <h6>{'Default sorting of children'|i18n( 'design/admin/class/view' )}:</h6>
    {def $sort_fields=fetch( content, available_sort_fields )}
    {if is_set( $sort_fields[$class.sort_field] )} {$sort_fields[$class.sort_field]} {else}{$class.sort_field}{/if} / {if eq($class.sort_order, 0)}{'Descending'|i18n( 'design/admin/class/edit' )}{else}{'Ascending'|i18n( 'design/admin/class/edit' )}{/if}
    {undef $sort_fields}</div>

    <div class="block">
        <h6>{'Object count'|i18n( 'design/admin/class/view' )}:</h6>
        {$class.object_count}
    </div>
</div>
<div class="float-break"></div>
</div>

<h2>{'Attributes'|i18n( 'design/admin/class/view' )}</h2>
<table class="special" cellspacing="0">

{def $attribute_categorys        = ezini( 'ClassAttributeSettings', 'CategoryList', 'content.ini' )
     $attribute_default_category = ezini( 'ClassAttributeSettings', 'DefaultCategory', 'content.ini' )}

{section var=Attributes loop=$attributes sequence=array( bglight, bgdark )}

<tr>
    <th colspan="5">{$Attributes.number}.&nbsp;{$Attributes.item.nameList[$language_code]|wash}&nbsp;[{$Attributes.item.data_type.information.name|wash}]&nbsp;(id:{$Attributes.item.id})</th>
</tr>

<tr class="{$Attributes.sequence}">
    <td>
        <input type="hidden" name="ContentAttribute_id[]" value="{$Attributes.item.id}" />
        <input type="hidden" name="ContentAttribute_position[]" value="{$Attributes.item.placement}" />

        <div class="block">
            <h6>{'Name'|i18n( 'design/admin/class/view' )}:</h6>
            <p>{$Attributes.item.nameList[$language_code]|wash}</p>
        </div>
    </td>

    <td class="{$Attributes.sequence}">
        <div class="block">
            <h6>{'Identifier'|i18n( 'design/admin/class/view' )}:</h6>
            <p>{$Attributes.item.identifier|wash}</p>
        </div>
    </td>

    <td class="{$Attributes.sequence}">
        <div class="block">
            <h6>{'Category'|i18n( 'design/admin/class/view' )}:</h6>
	        {if $Attributes.item.category|not}
	            <p>{'Default'|i18n( 'design/admin/class/edit' )} ({$attribute_categorys[ $attribute_default_category ]|wash})</p>
	        {elseif is_set( $attribute_categorys[ $Attributes.item.category ] )}
                <p>{$attribute_categorys[ $Attributes.item.category ]|wash}</p>
            {else}
                <p>{$attribute_categorys[ $attribute_default_category ]|wash}</p>
            {/if}
        </div>
    </td>

    <td class="{$Attributes.sequence}">
        <div class="block">
            <h6>{'Description'|i18n( 'design/admin/class/view' )}:</h6>
            <p>{$Attributes.item.descriptionList[$language_code]|wash}</p>
        </div>
    </td>

    <td rowspan="2">

		<div class="block">
		  <h6>{'Flags'|i18n( 'design/admin/class/view' )}:</h6>
		</div>

        <div class="block">
            <p>{if $Attributes.item.is_required}{'Is required'|i18n( 'design/admin/class/view' )}{else}{'Is not required'|i18n( 'design/admin/class/view' )}{/if}</p>
        </div>

        {if $Attributes.item.data_type.is_indexable}
        <div class="block">
            <p>{if $Attributes.item.is_searchable}{'Is searchable'|i18n( 'design/admin/class/view' )}{else}{'Is not searchable'|i18n( 'design/admin/class/view' )}{/if}</p>
        </div>
        {else}
        <div class="block">
            <p>{'Is not searchable'|i18n( 'design/admin/class/view' )}</p>
        </div>
        {/if}

        {if $Attributes.item.data_type.is_information_collector}
        <div class="block">
            <p>{if $Attributes.item.is_information_collector}{'Collects information'|i18n( 'design/admin/class/view' )}{else}{'Does not collect information'|i18n( 'design/admin/class/view' )}{/if}</p>
        </div>
        {else}
        <div class="block">
            <p>{'Does not collect information'|i18n( 'design/admin/class/view' )}</p>
        </div>
        {/if}

        <div class="block">
            <p>{if or( $Attributes.item.can_translate|eq(0), $Attributes.item.data_type.properties.translation_allowed|not )}{'Translation is disabled'|i18n( 'design/admin/class/view' )}{else}{'Translation is enabled'|i18n( 'design/admin/class/view' )}{/if}</p>
        </div>
    </td>
</tr>

<tr class="{$Attributes.sequence}">
    <td colspan="4">
        {class_attribute_view_gui class_attribute=$Attributes.item}
    </td>
</tr>
{/section}

{undef $attribute_categorys $attribute_default_category}

</table>

</div>

{* DESIGN: Content END *}</div>
<div class="block">
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc">
        <form action={concat( '/class/edit/', $class.id )|ezurl} method="post">

            {def $languages=$class.prioritized_languages
                 $availableLanguages = fetch( 'content', 'prioritized_languages' )}
            {if and( eq( $availableLanguages|count, 1 ), eq( $languages|count, 1 ), is_set( $languages[$availableLanguages[0].locale] ) )}
                <input type="hidden" name="EditLanguage" value="{$availableLanguages[0].locale|wash()}" />
            {else}
                <select name="EditLanguage" title="{'Use this menu to select the language you want to use for editing then click the "Edit" button.'|i18n( 'design/admin/class/view' )|wash()}">
                    {foreach $languages as $language}
                        <option value="{$language.locale|wash()}">{$language.name|wash()}</option>
                    {/foreach}
                    {if gt( $class.can_create_languages|count, 0 )}
                        <option value="">{'Another language'|i18n( 'design/admin/class/view')}</option>
                    {/if}
                </select>
            {/if}
            {undef $languages $availableLanguages}
            <input class="button" type="submit" name="_DefaultButton" value="{'Edit'|i18n( 'design/admin/class/view' )}" title="{'Edit this class.'|i18n( 'design/admin/class/view' )}" />
            {* <input class="button" type="submit" name="_DefaultButton" value="{'Remove'|i18n( 'design/admin/class/view' )}" /> *}
        </form>
{* DESIGN: Control bar END *}</div>
</div>
</div>
{include uri="design:class/windows.tpl"}

</div>

