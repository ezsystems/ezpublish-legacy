<form action={"rss/edit_import"|ezurl} method="post" name="RSSImport">

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h2 class="context-title">{'Edit <%rss_import_name> [RSS Import]'|i18n( 'design/admin/rss/edit_import',, hash( '%rss_import_name', $rss_import.name ) )|wash}</h2>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">


<div class="context-attributes">

    {* Title. *}
    <div class="block">
        <label>{"Name"|i18n( 'design/admin/rss/edit_import' )}:</label>
        <input class="halfbox" id="importName" type="text" name="name" value="{$rss_import.name|wash}" title="{'Name of the RSS import. This name is used in the Administration Interface only, to distinguish the different imports from each other.'|i18n('design/admin/rss/edit_import')}" />
    </div>

    {* URL. *}
    <div class="block">
    <label>{"Source URL"|i18n( 'design/admin/rss/edit_import' )}:</label>
    <input class="halfbox" type="text" name="url" value="{$rss_import.url|wash}" title="{'Use this field to enter the source URL of the RSS feed to import.'|i18n('design/admin/rss/edit_import')}" />
    <input class="button" type="submit" name="AnalyzeFeedButton" value="{'Update'|i18n( 'design/admin/rss/edit_import' )}" title="{'Click this button to proceed and analyze the import feed.'|i18n('design/admin/rss/edit_import')}" />
    </div>
    {if and( is_set( $rss_import.import_description_array.rss_version ), $rss_import.import_description_array.rss_version )}
    <label>{"RSS Version"|i18n( 'design/admin/rss/edit_import' )}:</label>
    {$rss_import.import_description_array.rss_version|wash}
    {/if}

    {* Destination path. *}
    <div class="block">
    <label>{"Destination path"|i18n( 'design/admin/rss/edit_import' )}:</label>
    <input type="text" readonly="readonly" size="45" value="{$rss_import.destination_path|wash}" />
    <input class="button" type="submit" name="DestinationBrowse" value="{'Browse'|i18n( 'design/admin/rss/edit_import' )}" title="{'Click this button to select the destination node where objects created by the import are located.'|i18n('design/admin/rss/edit_import')}" />
    </div>

    {if and( is_set( $rss_import.import_description_array.rss_version ), $rss_import.import_description_array.rss_version )}

    {* Imported objects owner. *}
    <div class="block">
    <label>{"Imported objects will be owned by"|i18n( 'design/admin/rss/edit_import' )}:</label>
    <p>{$rss_import.object_owner.contentobject.name|wash}</p>
    <input class="button" type="submit" name="UserBrowse" value="{'Change user'|i18n( 'design/admin/rss/edit_import' )}" title="{'Click this button to select the user who should own the objects created by the import.'|i18n('design/admin/rss/edit_import')}" />
    </div>

    {* Class. *}
    <div class="block">
    <label>{"Class"|i18n( 'design/admin/rss/edit_import' )}:</label>
    <select name="Class_ID" title="{'Use this drop-down to select the type of object the import should create. Click the "Set" button to load the attribute types for the remaining fields.'|i18n('design/admin/rss/edit_import')|wash}">
    {section name=ContentClass loop=$rss_class_array }
    <option
      {section name=Class show=eq($:item.id,$rss_import.class_id)}
        selected="selected"
      {/section} value="{$:item.id}">{$:item.name|wash}
    </option>
    {/section}
    </select>
    <input class="button" type="submit" name="Update_Class" value="{'Set'|i18n( 'design/admin/rss/edit_import' )}" title="{'Click this button to load the correct values into the drop-down fields below. Use the drop-down menu on the left to select the class.'|i18n('design/admin/rss/edit_import')}" />
    </div>

    {if $rss_import.class_id|gt(0)}
    {def $import_description_array = $rss_import.import_description_array}
    {def $field_map = $rss_import.field_map}
    {* Class attributes *}
    <fieldset>
    <legend>{'Class attributes'|i18n( 'design/admin/rss/edit_import' )}</legend>
    {foreach $rss_import.class_attributes as $class_attribute}
        <div class="block">
        <label>{$class_attribute.name|wash}:</label>
            <select name="Class_Attribute_{$class_attribute.id}" title="{'Use this drop-down menu to select the attribute that should bet set as information from the RSS stream.'|i18n('design/admin/rss/edit_import')}">
            <option value="-1">{"Ignore"|i18n( 'design/admin/rss/edit_import' )}</option>
            {foreach $field_map as $key => $value}
                <option value="{$key|wash}" {cond( and( is_set( $import_description_array.class_attributes[$class_attribute.id] ), $import_description_array.class_attributes[$class_attribute.id]|eq($key) ), 'selected="selected"', '' )}>
                {$value|wash}
                </option>
            {/foreach}
            </select>
        </div>
    {/foreach}
    </fieldset>

    {* Object Attributes *}
    <fieldset>
    <legend>{'Object attributes'|i18n( 'design/admin/rss/edit_import' )}</legend>
    {foreach $rss_import.object_attribute_list as $key => $object_attribute}
        <div class="block">
        <label>{$object_attribute|wash}:</label>
            <select name="Object_Attribute_{$key|wash}" title="{'Use this drop-down menu to select the attribute that should bet set as information from the RSS stream.'|i18n('design/admin/rss/edit_import')}">
            <option value="-1">{"Ignore"|i18n( 'design/admin/rss/edit_import' )}</option>
            {foreach $field_map as $key2 => $value}
                <option value="{$key2|wash}" {cond( and( is_set( $import_description_array.object_attributes[$key] ), $import_description_array.object_attributes[$key]|eq($key2) ), 'selected="selected"', '' )}>
                {$value|wash}
                </option>
            {/foreach}
            </select>
        </div>
    {/foreach}
    </fieldset>

    {* Active. *}
    <div class="block">
    <label>{"Active"|i18n( 'design/admin/rss/edit_import' )}:</label>
    <input type="checkbox" name="active" {if $rss_import.active|eq(1)}checked="checked"{/if} title="{'Use this checkbox to control if the RSS feed is active or not. An inactive feed will not be automatically updated.'|i18n('design/admin/rss/edit_import')}" />
    </div>
    {/if}

    {/if} {* Step end RSS *}

    </div>

{* DESIGN: Content END *}</div></div></div>


    {* Buttons. *}
    <div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
    <div class="block">
    <input type="hidden" name="RSSImport_ID" value={$rss_import.id} />
    <input class="button" type="submit" name="StoreButton" value="{'OK'|i18n( 'design/admin/rss/edit_import' )}" title="{'Apply the changes and return to the RSS overview.'|i18n('design/admin/rss/edit_import')}" />
    <input class="button" type="submit" name="RemoveButton" value="{'Cancel'|i18n( 'design/admin/rss/edit_import' )}" title="{'Cancel the changes and return to the RSS overview.'|i18n('design/admin/rss/edit_import')}" />
    </div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
    </div>


</div>
</form>

{literal}
<script type="text/javascript">
<!--
    window.onload=function()
    {
        document.getElementById('importName').select();
        document.getElementById('importName').focus();
    }
-->
</script>
{/literal}
