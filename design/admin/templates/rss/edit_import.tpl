<form action={"rss/edit_import"|ezurl} method="post" name="RSSImport">

<div class="context-block">
<h2 class="context-title">{'Edit'|i18n( '/design/admin/rss/edit_import' )}&nbsp;<i>{$rss_import.name}</i>&nbsp;[{'RSS Import'|i18n( 'design/admin/rss/edit_import' )}]</h2>


<div class="context-attributes">

    {* Title. *}
    <div class="block">
        <label>{"Name"|i18n( 'design/admin/rss/edit_import' )}:</label>
        {include uri="design:gui/lineedit.tpl" id_name=name value=$rss_import.name|wash}
    </div>

    {* URL. *}
    <div class="block">
    <label>{"Source URL"|i18n( 'design/admin/rss/edit_import' )}:</label>
    {include uri="design:gui/lineedit.tpl" id_name=url value=$rss_import.url|wash}
    </div>

    {* Destination path. *}
    <div class="block">
    <label>{"Destination path"|i18n( 'design/admin/rss/edit_import' )}:</label>
    <input type="text" readonly="readonly" size="45" value="{$rss_import.destination_path|wash}" maxlength="60" />
    {include uri="design:gui/button.tpl" id_name="DestinationBrowse" value="Browse"|i18n( 'design/admin/rss/edit_import' )}
    </div>

    {* Imported objects owner. *}
    <div class="block">
    <label>{"Imported objects will be owned by"|i18n( 'design/admin/rss/edit_import' )}:</label>
    <p>{$rss_import.object_owner.contentobject.name}</p>
    {include uri="design:gui/button.tpl" id_name="UserBrowse" value="Change user"|i18n( 'design/admin/rss/edit_import' )}
    </div>

    {* Class. *}
    <div class="block">
    <label>{"Class"|i18n( 'design/admin/rss/edit_import' )}:</label>
    <select name="Class_ID">
    {section name=ContentClass loop=$rss_class_array }
    <option
      {section name=Class show=eq($:item.id,$rss_import.class_id)}
        selected="selected"
      {/section} value="{$:item.id}">{$:item.name|wash}
    </option>
    {/section}
    </select>
    {include uri="design:gui/button.tpl" id_name="Update_Class" value="Set"|i18n( 'design/admin/rss/edit_import' )}
    </div>

    {* Title. *}
    <div class="block">
    <label>{"Title"|i18n( 'design/admin/rss/edit_import' )}:</label>
    <select name="Class_Attribute_Title">
    {section name=ClassAttribute loop=$rss_import.class_attributes}
    <option value="{$:item.identifier|wash}" 
      {section name=ShowSelected show=eq($rss_import.class_title,$:item.identifier)}
        selected="selected"
      {/section}>{$:item.name|wash}
    </option>
    {/section}
    <option value="-1"
      {section name=ShowSelected show=eq($rss_import.class_title,-1)}
        selected="selected"
      {/section}>{"Ignore"|i18n( 'design/admin/rss/edit_import' )}</option>
    </select>
    </div>

    {* URL. *}
    <div class="block">
    <label>{"URL"|i18n( 'design/admin/rss/edit_import' )}:</label>
    <select name="Class_Attribute_Link">
    {section name=ClassAttribute loop=$rss_import.class_attributes}
    <option value="{$:item.identifier|wash}"
      {section name=ShowSelected show=eq($rss_import.class_url,$:item.identifier)}
        selected="selected"
      {/section}>{$:item.name|wash}
    </option>
    {/section}
    <option value="-1"
      {section name=ShowSelected show=eq($rss_import.class_url,-1)}
        selected="selected"
      {/section}>{"Ignore"|i18n( 'design/admin/rss/edit_import' )}</option>
    </select>
    </div>

    {* Description. *}
    <div class="block">
    <label>{"Description"|i18n( 'design/admin/rss/edit_import' )}:</label>
    <select name="Class_Attribute_Description">
    {section name=ClassAttribute loop=$rss_import.class_attributes}
    <option value="{$:item.identifier|wash}"
      {section name=ShowSelected show=eq($rss_import.class_description,$:item.identifier)}
        selected="selected"
      {/section}>{$:item.name|wash}
    </option>
    {/section}
    <option value="-1"
      {section name=ShowSelected show=eq($rss_import.class_description,-1)}
        selected="selected"
      {/section}>{"Ignore"|i18n( 'design/admin/rss/edit_import' )}</option>
    </select>
    </div>

    {* Active. *}
    <div class="block">
    <label>{"Active"|i18n( 'design/admin/rss/edit_import' )}:</label>
    <input type="checkbox" name="active" {section show=$rss_import.active|eq(1)}checked="checked"{/section} />
    </div>

    </div>

    {* Buttons. *}
    <div class="controlbar">
    <div class="block">
    <input type="hidden" name="RSSImport_ID" value={$rss_import_id} />
    <input class="button" type="submit" name="StoreButton" value="{'OK'|i18n( 'design/admin/rss/edit_import' )}" />
    <input class="button" type="submit" name="RemoveButton" value="{'Cancel'|i18n( 'design/admin/rss/edit_import' )}" />
    </div>
    </div>

</div>
</form>
