{default content_object=$node.object
         content_version=$node.contentobject_version_object
         node_name=$node.name}
<form enctype="multipart/form-data" method="post" action={concat("/content/edit/",$object.id,"/",$edit_version,"/",$edit_language|not|choose(array($edit_language,"/"),''))|ezurl}>
<table class="layout" width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td valign="top">
    <!-- Left part start -->
    <div class="maincontentheader">
    <h1>Post new message</h1>
    </div>

    {section show=$validation.processed}
        {section name=UnvalidatedAttributes loop=$validation.attributes show=$validation.attributes}

        <div class="warning">
        <h2>{"Input did not validate"|i18n("design/standard/content/edit")}</h2>
        <ul>
        	<li>{$UnvalidatedAttributes:item.identifier}: {$UnvalidatedAttributes:item.name}</li>
        </ul>
        </div>

        {section-else}

        <div class="feedback">
        <h2>{"Input was stored successfully"|i18n("design/standard/content/edit")}</h2>
        </div>

        {/section}
    {/section}
    <table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">

    <input type="hidden" name="MainNodeID" value="{$main_node_id}" />

    {section name=ContentObjectAttribute loop=$content_attributes sequence=array(bglight,bgdark)}
    <div class="block">
    <label>{$ContentObjectAttribute:item.contentclass_attribute.name}:</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ContentObjectAttribute:item.id}" />
        {attribute_edit_gui attribute=$ContentObjectAttribute:item}
    </div>
    {/section}

    <div class="buttonblock">
    <input class="button" type="submit" name="PublishButton" value="{'Post'|i18n('design/standard/content/edit')}" />
    <input class="button" type="submit" name="DiscardButton" value="{'Discard'|i18n('design/standard/content/edit')}" />
    </div>
    <!-- Left part end -->
    </td>
</tr>
</table>

</form>
