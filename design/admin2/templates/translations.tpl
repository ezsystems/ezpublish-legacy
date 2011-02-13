{if gt($available_languages,1)}
{def $object_can_edit = $node.object.can_edit}

<form name="translationsform" method="post" action={'content/translation'|ezurl}>
<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
<input type="hidden" name="ViewMode" value="{$viewmode|wash}" />
<input type="hidden" name="ContentObjectLanguageCode" value="{$language_code|wash}" />

<fieldset>
<legend>{'Existing translations'|i18n( 'design/admin/node/view/full' )}</legend>

<table id="tab-translations-list" class="list" cellspacing="0" summary="{'Language list of translations for current object.'|i18n( 'design/admin/node/view/full' )}">
<tr>
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} width="16" height="16" alt="{'Invert selection.'|i18n( 'design/admin/node/view/full' )}" title="{'Invert selection.'|i18n( 'design/admin/node/view/full' )}" onclick="ezjs_toggleCheckboxes( document.translationsform, 'LanguageID[]' ); return false;"/></th>
    <th>{'Language'|i18n( 'design/admin/node/view/full' )}</th>
    <th>{'Locale'|i18n( 'design/admin/node/view/full' )}</th>
    <th class="tight">{'Main'|i18n( 'design/admin/node/view/full' )}</th>
    <th class="tight">&nbsp;</th>
</tr>

{section var=Translations loop=$translations sequence=array( bglight, bgdark )}
{def $can_edit=fetch( 'content', 'access', hash( 'access', 'edit',
                                                 'contentobject', $node.object,
                                                 'language', $Translations.item.locale ) )}

<tr class="{$Translations.sequence}">

{* Remove. *}
<td>
    <input type="checkbox" name="LanguageID[]" value="{$Translations.item.id}"{if or($can_edit|not,$Translations.item.id|eq($node.object.initial_language_id))} disabled="disabled"{/if} />
</td>

{* Language name. *}
<td>
<img src="{$Translations.item.locale|flag_icon}" width="18" height="12" alt="{$Translations.item.locale}" />
&nbsp;
{if eq( $Translations.item.locale, $node.object.current_language )}
<b><a href={concat( $node.url, '/(language)/', $Translations.item.locale )|ezurl} title="{'View translation.'|i18n( 'design/admin/node/view/full' )}">{$Translations.item.name}</a></b>
{else}
<a href={concat( $node.url, '/(language)/', $Translations.item.locale )|ezurl} title="{'View translation.'|i18n( 'design/admin/node/view/full' )}">{$Translations.item.name}</a>
{/if}
</td>

{* Locale code. *}
<td>{$Translations.item.locale}</td>

{* Main. *}
<td>

{if $object_can_edit}

<input type="radio" {if $Translations.item.id|eq($node.object.initial_language_id)} checked="checked" class="main-translation-radio main-translation-radio-initial"{else} class="main-translation-radio"{/if} name="InitialLanguageID" value="{$Translations.item.id}" title="{'Use these radio buttons to select the desired main language.'|i18n( 'design/admin/node/view/full' )}" />

{/if}

</td>

{* Edit. *}
<td>

{if $can_edit}

<a href={concat( 'content/edit/', $node.object.id, '/f/', $Translations.item.locale )|ezurl}><img src={'edit.gif'|ezimage} width="16" height="16" alt="{'Edit in <%language_name>.'|i18n( 'design/admin/node/view/full',, hash( '%language_name', $Translations.item.locale_object.intl_language_name ) )|wash}" title="{'Edit in <%language_name>.'|i18n( 'design/admin/node/view/full',, hash( '%language_name', $Translations.item.locale_object.intl_language_name ) )|wash}" /></a>

{/if}

</td>

</tr>

{undef $can_edit}
{/section}
</table>

<div class="block">
<div class="button-left">
{if $object_can_edit}
    {if $translations_count|gt( 1 )}
    <input class="button" type="submit" name="RemoveTranslationButton" value="{'Remove selected'|i18n( 'design/admin/node/view/full' )}" title="{'Remove selected languages from the list above.'|i18n( 'design/admin/node/view/full' )}" />
    {else}
    <input class="button-disabled" type="submit" name="RemoveTranslationButton" value="{'Remove selected'|i18n( 'design/admin/node/view/full' )}" title="{'There is no removable language.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
    {/if}
{else}
    <input class="button-disabled" type="submit" name="RemoveTranslationButton" value="{'Remove selected'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" title="{'You cannot remove any language because you do not have permission to edit the current item.'|i18n( 'design/admin/node/view/full' )}" />
{/if}
</div>

<div class="button-right">
{if $object_can_edit}
    {if $translations_count|gt( 1 )}
    <input id="tab-translations-list-set-main" class="button" type="submit" name="UpdateInitialLanguageButton" value="{'Set main'|i18n( 'design/admin/node/view/full' )}" title="{'Select the desired main language using the radio buttons above then click this button to store the setting.'|i18n( 'design/admin/node/view/full' )}" />
    <script type="text/javascript">
    {literal}
    (function( $ )
    {
        $('#tab-translations-list input.main-translation-radio').change(function()
        {
            if ( this.className === 'main-translation-radio' )
                $('#tab-translations-list-set-main').removeClass('button').addClass('defaultbutton');
            else
                $('#tab-translations-list-set-main').removeClass('defaultbutton').addClass('button');
        });
    })( jQuery );
    {/literal}
    </script>
    {else}
    <input class="button-disabled" type="submit" name="UpdateInitialLanguageButton" value="{'Set main'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" title="{'You cannot change the main language because the object is not translated to any other languages.'|i18n( 'design/admin/node/view/full' )}" />
    {/if}
{else}
    <input class="button-disabled" type="submit" name="UpdateInitialLanguageButton" value="{'Set main'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" title="{'You cannot change the main language because you do not have permission to edit the current item.'|i18n( 'design/admin/node/view/full' )}" />
{/if}
</div>

<div class="break"></div>
</div>

</fieldset>


<div class="block">

<div class="block">
<input id="tab-translations-alwaysavailable-checkbox" type="checkbox"{if $object_can_edit|not} disabled="disabled"{/if} name="AlwaysAvailable" value="1"{if $node.object.always_available} checked="checked"{/if} /> {'Use the main language if there is no prioritized translation.'|i18n( 'design/admin/node/view/full' )}
</div>

<div class="block">
{if $object_can_edit}
    <input id="tab-translations-alwaysavailable-btn" class="button" type="submit" name="UpdateAlwaysAvailableButton" value="{'Update'|i18n( 'design/admin/node/view/full' )}" title="{'Use this button to store the value of the checkbox above.'|i18n( 'design/admin/node/view/full' )}" />
    <script type="text/javascript">
    {literal}
    (function( $ )
    {
        $('#tab-translations-alwaysavailable-checkbox').change(function()
        {
            $('#tab-translations-alwaysavailable-btn').removeClass('button').addClass('defaultbutton');
        });
    })( jQuery );
    {/literal}
    </script>
{else}
    <input class="button-disabled" disabled="disabled" type="submit" name="UpdateAlwaysAvailableButton" value="{'Update'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permission to change this setting.'|i18n( 'design/admin/node/view/full' )}" />
{/if}
</div>
</div>


</form>

{/if}