<form name="editform" id="editform" enctype="multipart/form-data" method="post" action={concat( '/content/edit/', $object.id, '/', $edit_version, '/', $edit_language|not|choose( concat( $edit_language, '/' ), '/' ), $is_translating_content|not|choose( concat( $from_language, '/' ), '' ) )|ezurl}>

<div id="leftmenu">
<div id="leftmenu-design">

{include uri='design:content/edit_menu.tpl'}

</div>
</div>

<div id="maincontent">
<div id="maincontent-design" class="float-break"><div id="fix">
<!-- Maincontent START -->

{include uri='design:content/edit_validation.tpl'}

<div class="content-edit">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{$object.class_identifier|class_icon( normal, $object.class_name )}&nbsp;{'Edit <%object_name> [%class_name]'|i18n( 'design/admin/content/edit',, hash( '%object_name', $object.name, '%class_name', $class.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-information">
<p class="right translation">
{let language_index=0
     from_language_index=0
     default_translation=$content_version.translation
     other_translation_list=$content_version.translation_list
     translation_list=$other_translation_list|array_prepend($default_translation)}

{section loop=$translation_list}
  {if eq( $edit_language, $item.language_code)}
    {set language_index=$:index}
  {/if}
{/section}

{if $is_translating_content}

    {let from_language_object=$object.languages[$from_language]}

    {'Translating content from %from_lang to %to_lang'|i18n( 'design/admin/content/edit',, hash(
        '%from_lang', concat( $from_language_object.name, '&nbsp;<img src="', $from_language_object.locale|flag_icon, '" width="18" height="12" style="vertical-align: middle;" alt="', $from_language_object.locale, '" />' ),
        '%to_lang', concat( $translation_list[$language_index].locale.intl_language_name, '&nbsp;<img src="', $translation_list[$language_index].language_code|flag_icon, '" width="18" height="12" style="vertical-align: middle;" alt="', $translation_list[$language_index].language_code, '" />' ) ) )}

    {/let}

{else}

    {$translation_list[$language_index].locale.intl_language_name}&nbsp;<img src="{$translation_list[$language_index].language_code|flag_icon}" width="18" height="12" style="vertical-align: middle;" alt="{$translation_list[$language_index].language_code}" />

{/if}

{/let}
</p>
<div class="break"></div>
</div>

{if $is_translating_content}
<div class="content-translation">
{/if}

<div class="context-attributes">
    {include uri='design:content/edit_attribute.tpl'}
</div>

{if $is_translating_content}
</div>
{/if}

{* DESIGN: Content END *}</div></div></div>
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
    <input class="button" type="submit" name="PublishButton" value="{'Send for publishing'|i18n( 'design/admin/content/edit' )}" title="{'Publish the contents of the draft that is being edited. The draft will become the published version of the object.'|i18n( 'design/admin/content/edit' )}" />
    <input class="button" type="submit" name="StoreButton" value="{'Store draft'|i18n( 'design/admin/content/edit' )}" title="{'Store the contents of the draft that is being edited and continue editing. Use this button to periodically save your work while editing.'|i18n( 'design/admin/content/edit' )}" />
    <input class="button" type="submit" name="DiscardButton" value="{'Discard draft'|i18n( 'design/admin/content/edit' )}" onclick="return confirmDiscard( '{'Are you sure you want to discard the draft?'|i18n( 'design/admin/content/edit' )|wash(javascript)}' );" title="{'Discard the draft that is being edited. This will also remove the translations that belong to the draft (if any).'|i18n( 'design/admin/content/edit' ) }" />
    <input type="hidden" name="DiscardConfirm" value="1" />
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

{include uri='design:content/edit_relations.tpl'}


{* Locations window. *}
{* section show=eq( ezini( 'EditSettings', 'EmbedNodeAssignmentHandling', 'content.ini' ), 'enabled' ) *}
{if ezpreference( 'admin_edit_show_locations' )}
    {include uri='design:content/edit_locations.tpl'}
{else}
    {* This disables all node assignment checking in content/edit *}
    <input type="hidden" name="UseNodeAssigments" value="0" />
{/if}

</div>

<!-- Maincontent END -->
</div>
<div class="break"></div>
</div></div>

{* Override default redirection urls. *}
{section var=Menu loop=topmenu('default')}
    {if eq($Menu.name, 'Design')}
        <input type="hidden" name="RedirectURIAfterPublish" value="/{$Menu.url}" />
        <input type="hidden" name="RedirectIfDiscarded" value="/{$Menu.url}" />
    {/if}
{/section}

</form>




{literal}
<script type="text/javascript">
<!--
jQuery(function( $ )//called on document.ready
{
    with( document.editform )
    {
        for( var i = 0, l = elements.length; i < l; i++ )
        {
            if( elements[i].type == 'text' )
            {
                elements[i].select();
                elements[i].focus();
                return;
            }
        }
    }
});

function confirmDiscard( question )
{
    // Disable/bypass the reload-based (plain HTML) confirmation interface.
    document.editform.DiscardConfirm.value = "0";

    // Ask user if she really wants do it, return this to the handler.
    return confirm( question );
}
-->
</script>
{/literal}

