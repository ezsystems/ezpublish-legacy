<form enctype="multipart/form-data" method="post" action={concat("/content/edit/",$object.id,"/",$edit_version,"/",$edit_language|not|choose(array($edit_language,"/"),''))|ezurl}>
<input type="hidden" name="RedirectURI" value="/setup/menu/" />
<table class="layout" width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td valign="top">
    <!-- Left part start -->
    <div class="maincontentheader">
    <h1>Edit Look and Feel</h1>
    </div>

    {include uri="design:content/edit_validation.tpl"}

    <input type="hidden" name="MainNodeID" value="{$main_node_id}" /> 
    <br/>

    <div class="block">
    <label>Site title:</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$content_attributes[0].id}" />
        {attribute_edit_gui attribute=$object.data_map.title}
    </div>

    <div class="block">
    <label>Site URL:</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$content_attributes[6].id}" />
        {attribute_edit_gui attribute=$object.data_map.siteurl}
    </div>

    <div class="block">
    <label>Logo:</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$content_attributes[2].id}" />
        {attribute_edit_gui attribute=$object.data_map.image}
    </div>

    <div class="block">
    <label>Appearance:</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$content_attributes[3].id}" />
        {attribute_edit_gui attribute=$object.data_map.sitestyle}
    </div>

    <div class="block">
    <label>Meta data:</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$content_attributes[1].id}" />
        {attribute_edit_gui attribute=$object.data_map.meta_data}
    </div>

    <div class="block">
    <label>Admin Email:</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$content_attributes[5].id}" />
        {attribute_edit_gui attribute=$object.data_map.email}
    </div>

    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$content_attributes[4].id}" />
    <input type="hidden" name="ContentObjectAttribute_ezstring_data_text_{$content_attributes[4].id}" value="forum_package"  />
    
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