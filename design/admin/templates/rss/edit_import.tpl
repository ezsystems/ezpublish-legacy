<form action={"rss/edit_import"|ezurl} method="post" name="RSSImport">

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h2 class="context-title">{'Edit <%rss_import_name> [RSS Import]'|i18n( '/design/admin/rss/edit_import',, hash( '%rss_import_name', $rss_import.name ) )|wash}</h2>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">


<div class="context-attributes">

    {* Title. *}
    <div class="block">
        <label>{"Name"|i18n( 'design/admin/rss/edit_import' )}:</label>
        <input class="halfbox" type="text" name="name" value="{$rss_import.name|wash}" title="{'Name of the rss import. This name is used in the administration interface only, to distinguish the different imports from eachother.'|i18n('design/admin/rss/edit_import')}" />
    </div>

    {* URL. *}
    <div class="block">
    <label>{"Source URL"|i18n( 'design/admin/rss/edit_import' )}:</label>
    <input class="halfbox" type="text" name="url" value="{$rss_import.url|wash}" title="{'Use this field to enter the source URL of the RSS feed to import.'|i18n('design/admin/rss/edit_import')} "/>
    </div>

    {* Destination path. *}
    <div class="block">
    <label>{"Destination path"|i18n( 'design/admin/rss/edit_import' )}:</label>
    <input type="text" readonly="readonly" size="45" value="{$rss_import.destination_path|wash}" maxlength="60" />
    <input class="button" type="submit" name="DestinationBrowse" value="{'Browse'|i18n( 'design/admin/rss/edit_import' )}" title="{'Use this button to select the destination node where objects created by the import are located.'|i18n('design/admin/rss/edit_import')}" />
    </div>

    {* Imported objects owner. *}
    <div class="block">
    <label>{"Imported objects will be owned by"|i18n( 'design/admin/rss/edit_import' )}:</label>
    <p>{$rss_import.object_owner.contentobject.name}</p>
    <input class="button" type="submit" name="UserBrowse" value="{'Change user'|i18n( 'design/admin/rss/edit_import' )}" title="{'Use this button to select the user who should own the objects created by the import.'|i18n('design/admin/rss/edit_import')}" />
    </div>

    {* Class. *}
    <div class="block">
    <label>{"Class"|i18n( 'design/admin/rss/edit_import' )}:</label>
    <select name="Class_ID" title="{'Use this drop down to select the type of object the import should create. Click the "Set" button to load the correct attribute types for the remaining fields.'|i18n('design/admin/rss/edit_import')|wash}">
    {section name=ContentClass loop=$rss_class_array }
    <option
      {section name=Class show=eq($:item.id,$rss_import.class_id)}
        selected="selected"
      {/section} value="{$:item.id}">{$:item.name|wash}
    </option>
    {/section}
    </select>
    <input class="button" type="submit" name="Update_Class" value="{'Set'|i18n( 'design/admin/rss/edit_import' )}" title="{'Use this button to load the correct values into the dropdown fields below. Use the dropdown menu on the left to select the correct class type.'|i18n('design/admin/rss/edit_import')}" />
    </div>

    {* Title. *}
    <div class="block">
    <label>{"Title"|i18n( 'design/admin/rss/edit_import' )}:</label>
    <select name="Class_Attribute_Title" title="{'Use this dropdown menu to select the attribute that should bet set to the title information from the RSS stream.'|i18n('design/admin/rss/edit_import')}">
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
    <select name="Class_Attribute_Link" title="{'Use this dropdown menu to select the attribute that should be set to the URL information from the RSS stream.'|i18n('design/admin/rss/edit_import')}">
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
    <div class="block" title="{'Use this dropdown menu to select the attribute that should be set to the description information from the RSS stream.'|i18n('design/admin/rss/edit_import')}">
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
    <input type="checkbox" name="active" {section show=$rss_import.active|eq(1)}checked="checked"{/section} title="{'Use this checkbox to control if the RSS feed is active or not. An inactive feed will not be automatically updated.'|i18n('design/admin/rss/edit_import')}" />
    </div>

    </div>

{* DESIGN: Content END *}</div></div></div>


    {* Buttons. *}
    <div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
    <div class="block">
    <input type="hidden" name="RSSImport_ID" value={$rss_import_id} />
    <input class="button" type="submit" name="StoreButton" value="{'OK'|i18n( 'design/admin/rss/edit_import' )}" title="{'Apply the changes and return to the RSS overview.'|i18n('design/admin/rss/edit_import')}" />
    <input class="button" type="submit" name="RemoveButton" value="{'Cancel'|i18n( 'design/admin/rss/edit_import' )}" title="{'Cancel the changes and return to the RSS overview.'|i18n('design/admin/rss/edit_import')}" />
    </div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
    </div>

</div>
</form>
