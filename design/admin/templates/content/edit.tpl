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


<div class="content-edit">

<form name="editform" id="editform" enctype="multipart/form-data" method="post" action={concat("/content/edit/",$object.id,"/",$edit_version,"/",$edit_language|not|choose(concat($edit_language,"/"),''))|ezurl}>

    <div class="context-block">

<div class="box-header">
<div class="box-tc"><div class="box-ml"><div class="box-mr">
<div class="box-tl"><div class="box-tr">

    <h2 class="context-title">{$object.class_identifier|class_icon( normal, $object.class_name )}&nbsp;{'Edit <%object_name> [%class_name]'|i18n( 'design/admin/content/edit',, hash( '%object_name', $object.name, '%class_name', $class.name ) )|wash}&nbsp;[{$edit_language|locale().intl_language_name}]</h2>

<div class="header-mainline"></div>

</div></div>
</div></div></div>
</div>

<div class="box-ml"><div class="box-mr">
<div class="box-content">

    {include uri="design:content/edit_validation.tpl"}

    {* The location edit field is only used when the INI setting is set to enabled *}
    {section show=eq( ezini( 'EditSettings', 'EmbedLocationHandling', 'content.ini' ), 'enabled' )}
    {include uri="design:content/edit_placement.tpl"}
    {section-else}
    {* This disables all node assignment checking in content/edit *}
    <input type="hidden" name="UseNodeAssigments" value="0" />
    {/section}

    <div class="context-attributes"">
    {include uri="design:content/edit_attribute.tpl"}
    </div>

</div>
</div></div>

    <div class="controlbar">

<div class="box-bc"><div class="box-ml"><div class="box-mr">
<div class="box-tc">
<div class="box-bl"><div class="box-br">

    <div class="block">
    <input class="button" type="submit" name="PublishButton" value="{'Send for publishing'|i18n('design/standard/content/edit')}" />
    <input class="button" type="submit" name="StoreButton" value="{'Store draft'|i18n('design/standard/content/edit')}" />
    <input class="button" type="submit" name="DiscardButton" value="{'Cancel'|i18n('design/standard/content/edit')}" onclick="return confirmDiscard( '{'Are you sure that you want to discard the changes?'|i18n( '/design/admin/layout' )}' );" />
    <input type="hidden" name="DiscardConfirm" value="1" />
    </div>

</div></div>
</div>
</div></div></div>

    </div>

    </div>

    {include uri="design:content/edit_related.tpl"}

</form>

</div>