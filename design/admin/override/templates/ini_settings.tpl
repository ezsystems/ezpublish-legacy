<form enctype="multipart/form-data" method="post" action={concat("/content/edit/",$object.id,"/",$edit_version,"/",$edit_language|not|choose(array($edit_language,"/"),''))|ezurl}>
<input type="hidden" name="RedirectURI" value="/setup/menu/" />
<table class="layout" width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td valign="top">
    <!-- Left part start -->
    <div class="maincontentheader">
    <h1>Edit ini settings</h1>
    </div>

    {include uri="design:content/edit_validation.tpl"}

    <input type="hidden" name="MainNodeID" value="{$main_node_id}" />
    <br/>

    <div class="block">
    <label>Object Name:</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$content_attributes[0].id}" />
        {attribute_edit_gui attribute=$object.data_map.name}
    </div>

    <div class="block">
    <label>Index page:</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$content_attributes[1].id}" />
        {attribute_edit_gui attribute=$object.data_map.indexpage}
    </div>

    <div class="block">
    <label>Default page:</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$content_attributes[2].id}" />
        {attribute_edit_gui attribute=$object.data_map.defaultpage}
    </div>

    <div class="block">
    <label>Debug output:</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$content_attributes[3].id}" />
        {attribute_edit_gui attribute=$object.data_map.debugoutput}
    </div>

    <div class="block">
    <label>Debug by IP:</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$content_attributes[4].id}" />
        {attribute_edit_gui attribute=$object.data_map.debugbyip}
    </div>

    <div class="block">
    <label>Debug IP list:</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$content_attributes[5].id}" />
        {attribute_edit_gui attribute=$object.data_map.debugiplist}
    </div>

    <div class="block">
    <label>Debug redirection:</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$content_attributes[6].id}" />
        {attribute_edit_gui attribute=$object.data_map.debugredirection}
    </div>

    <div class="block">
    <label>View caching:</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$content_attributes[7].id}" />
        {attribute_edit_gui attribute=$object.data_map.viewcaching}
    </div>

    <div class="block">
    <label>Template Cache:</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$content_attributes[8].id}" />
        {attribute_edit_gui attribute=$object.data_map.templatecache}
    </div>

    <div class="block">
    <label>Template Compile:</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$content_attributes[9].id}" />
        {attribute_edit_gui attribute=$object.data_map.templatecompile}
    </div>

    <div class="block">
    <label>Small image size:</label><div class="labelbreak"></div>
    ( To change image size, check 'Make empty array' checkbox and write the folowing line into textarea field:
        =geometry/scaledownonly=width;height )
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$content_attributes[10].id}" />
        {attribute_edit_gui attribute=$object.data_map.imagesmall}
    </div>

    <div class="block">
    <label>Medium image size:</label><div class="labelbreak"></div>
    ( To change image size, check 'Make empty array' checkbox and write the folowing line into textarea field:
        =geometry/scaledownonly=width;height )
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$content_attributes[11].id}" />
        {attribute_edit_gui attribute=$object.data_map.imagemedium}
    </div>

    <div class="block">
    <label>Large image size:</label><div class="labelbreak"></div>
    ( To change image size, check 'Make empty array' checkbox and write the folowing line into textarea field:
        =geometry/scaledownonly=width;height )
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$content_attributes[12].id}" />
        {attribute_edit_gui attribute=$object.data_map.imagelarge}
    </div>

    <br/>

    <div class="buttonblock">
    <input class="button" type="submit" name="PublishButton" value="{'Store'|i18n('design/standard/content/edit')}" />
    <input class="button" type="submit" name="DiscardButton" value="{'Discard'|i18n('design/standard/content/edit')}" />
    </div>
    <!-- Left part end -->
    </td>
</tr>
</table>
</form>