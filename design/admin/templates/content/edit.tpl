<form name="editform" id="editform" enctype="multipart/form-data" method="post" action={concat( '/content/edit/', $object.id, '/', $edit_version, '/', $edit_language|not|choose( concat( $edit_language, '/' ), '' ) )|ezurl}>

<div id="leftmenu">
<div id="leftmenu-design">

{include uri='design:edit_menu.tpl'}

</div>
</div>

<div id="maincontent"><div id="fix">
<div id="maincontent-design">
<!-- Maincontent START -->

{literal}
<script language="JavaScript" type="text/javascript">
<!--
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

{include uri="design:content/edit_validation.tpl"}

<div class="content-edit">

    <div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

    <h1 class="context-title">{$object.class_identifier|class_icon( normal, $object.class_name )}&nbsp;{'Edit <%object_name> [%class_name]'|i18n( 'design/admin/content/edit',, hash( '%object_name', $object.name, '%class_name', $class.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-information">
<p class="translation">
 {let language_index=0
     default_translation=$content_version.translation
     other_translation_list=$content_version.translation_list
     translation_list=$other_translation_list|array_prepend($default_translation)}

{section loop=$translation_list}
  {section show=eq( $edit_language, $item.language_code)}
    {set language_index=$:index}
  {/section}
{/section}

{$translation_list[$language_index].locale.intl_language_name}&nbsp;<img src="{$translation_list[$language_index].language_code|flag_icon}" style="vertical-align: middle;" alt="{$translation_list[$language_index].language_code}" />

{/let}
</p>
<div class="break"></div>
</div>

    {* The location edit field is only used when the INI setting is set to enabled *}
    {section show=eq( ezini( 'EditSettings', 'EmbedLocationHandling', 'content.ini' ), 'enabled' )}
    {include uri="design:content/edit_placement.tpl"}
    {section-else}
    {* This disables all node assignment checking in content/edit *}
    <input type="hidden" name="UseNodeAssigments" value="0" />
    {/section}

    <div class="context-attributes">
    {include uri="design:content/edit_attribute.tpl"}
    </div>

{* DESIGN: Content END *}</div></div></div>

    <div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

    <div class="block">
    <select>
        <option>This location is visible (current)</option>
        <option>This location is hidden</option>
        <option>All locations are visible</option>
        <option>All locations are hidden</option>
    </select>
    {*<label><input type="checkbox" />{'Publish as hidden'|i18n( 'design/admin/content/edit' )}</label>*}
    </div>
    <div class="block">
<input class="button" type="submit" name="PublishButton" value="{'Send for publishing'|i18n('design/standard/content/edit')}" />
<input class="button" type="submit" name="DiscardButton" value="{'Discard draft'|i18n('design/standard/content/edit')}" onclick="return confirmDiscard( '{'Are you sure that you want to discard the changes?'|i18n( '/design/admin/layout' )}' );" />




    <input type="hidden" name="DiscardConfirm" value="1" />
    </div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

    </div>

    </div>

    {include uri="design:content/edit_related.tpl"}

</div>

<!-- Maincontent END -->
</div>
<div class="break"></div>
</div></div>

</form>

