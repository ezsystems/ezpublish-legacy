<form action={"rss/edit_export"|ezurl} method="post" name="RSSExport">

<div class="context-block">
<h2 class="context-title">{'Edit <%rss_export_name> [RSS Export]'|i18n( '/design/admin/rss/edit_export',, hash( '%rss_export_name', $rss_export.name ) )|wash}</h2>
<div class="context-attributes">

    <div class="block">
    <label>{'Name'|i18n( 'design/admin/rss/edit_export' )}:</label>
    {include uri='design:gui/lineedit.tpl' id_name=title value=$rss_export.title|wash}
    </div>

    <div class="block">
    <label>{'Description'|i18n( 'design/admin/rss/edit_export' )}:</label>
    <textarea name="Description" cols="64" rows="3">{$rss_export.description|wash}</textarea>
    </div>

    <div class="block">
    <label>{'Site URL'|i18n( 'design/admin/rss/edit_export' )}:</label>
    {include uri='design:gui/lineedit.tpl' id_name=url value=$rss_export.url}
    </div>

    <input type="hidden" name="RSSImageID" value="{$rss_export.image_id}" />

    <div class="block">
    <label>{'Image'|i18n( 'design/admin/rss/edit_export' )}:</label>
    <input type="text" readonly="readonly" size="45" value="{$rss_export.image_path|wash}" maxlength="50" />
    <input class="button" type="submit" name="BrowseImageButton" value="{'Browse'|i18n( 'design/admin/rss/edit_export' )}" />
    </div>

    <div class="block">
    <label>{'RSS version'|i18n( 'design/admin/rss/edit_export' )}:</label>
    <select name="RSSVersion">
    {section name=Version loop=$rss_version_array}
    <option
    {section name=DefaultSet show=eq( $rss_export.rss_version, 0 )}
      {section name=Default show=eq( $Version:item, $rss_version_default )}
        selected="selected"
      {/section}
    {section-else}
      {section name=Default2 show=eq( $Version:item, $rss_export.rss_version )}
        selected="selected"
      {/section}
    {/section}
      value="{$:item}">{$:item|wash}
    </option>
    {/section}
    </select>
    </div>


    <div class="block">
    <label>{'Active'|i18n( 'design/admin/rss/edit_export' )}:</label>
    <input type="checkbox" name="active" {section show=$rss_export.active|eq( 1 )}checked="checked"{/section} />
    </div>

    <div class="block">
    <label>{'Access URL'|i18n( 'design/admin/rss/edit_export' )}:</label>
    rss/feed/ {include uri='design:gui/lineedit.tpl' id_name='Access_URL' value=$rss_export.access_url}
    </div>


    <input type="hidden" name="RSSExport_ID" value={$rss_export.id} />
    <input type="hidden" name="Item_Count" value={count($rss_export.item_list)} />


    {'Note. Each source only fetch 5 objects from 1 level below.'|i18n( 'design/admin/rss/edit_export' )}

<hr />

    {section name=Source loop=$rss_export.item_list}


       <h2>Source {sum($Source:index, 1)}</h2>

       <input type="hidden" name="Item_ID_{$Source:index}" value="{$Source:item.id}" />
       <div class="block">
       <label>{'Source path'|i18n( 'design/admin/rss/edit_export' )}:</label>
       <input type="text" readonly="readonly" size="45" value="{$Source:item.source_path|wash}" maxlength="60" />
       <input class="button" type="submit" name="{concat( 'SourceBrowse_', $Source:index )}" value="{'Browse'|i18n( 'design/admin/rss/edit_export' )}" />
       </div>

       <div class="block">
       <label>{'Class'|i18n( 'design/admin/rss/edit_export' )}:</label>
       <select name="Item_Class_{$Source:index}">
       {section name=ContentClass loop=$rss_class_array }
       <option
       {section name=Class show=eq( $:item.id, $Source:item.class_id )}
         selected="selected"
       {/section} value="{$:item.id}">{$:item.name|wash}</option>
       {/section}
       </select>
       <input class="button" type="submit" name="Update_Item_Class" value="{'Set'|i18n( 'design/admin/rss/edit_export' )}" />
       </div>

       {section name=Attribute show=count( $rss_export.item_list[$Source:index] )|gt( 0 )}

         <div class="block">
         <label>{'Title'|i18n( 'design/admin/rss/edit_export' )}:</label>
         <select name="Item_Class_Attribute_Title_{$Source:index}">
         {section name=ClassAttribute loop=$rss_export.item_list[$Source:index].class_attributes}
         <option value="{$:item.identifier}"
             {section name=ShowSelected show=eq( $Source:item.title, $:item.identifier )}
                 selected="selected"
             {/section}>{$:item.name|wash}</option>
         {/section}
         </select>
       </div>

       <div class="block">
         <label>{'Description'|i18n( 'design/admin/rss/edit_export' )}:</label>
         <select name="Item_Class_Attribute_Description_{$Source:index}">
         {section name=ClassAttribute loop=$rss_export.item_list[$Source:index].class_attributes}
         <option value="{$:item.identifier|wash}"
             {section name=ShowSelected show=eq( $Source:item.description, $:item.identifier )}
                 selected="selected"
             {/section}>{$:item.name|wash}</option>
         {/section}
         </select>
       </div>
       {/section}

       <input class="button" type="submit" name="{concat( 'RemoveSource_', $Source:index )}" value="{'Remove this source'|i18n( 'design/admin/rss/edit_export' )}" />
<hr />
    {/section}

    <input class="button" type="submit" name="AddSourceButton" value="{'Add Source'|i18n( 'design/admin/rss/edit_export' )}" />

</div>

    <div class="controlbar">
    <div class="block">
        <input class="button" type="submit" name="StoreButton" value="{'OK'|i18n( 'design/admin/rss/edit_export' )}" />
        <input class="button" type="submit" name="RemoveButton" value="{'Cancel'|i18n( 'design/admin/rss/edit_export' )}" />
    </div>
    </div>

</div>
</form>


