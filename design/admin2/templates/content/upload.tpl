<form enctype="multipart/form-data" action={'content/upload'|ezurl} method="post">

{if $ui_context|eq('edit')}
{let content_object=fetch( content, object, hash( object_id, $upload.content.object_id  ) )
     content_version=fetch( content, version, hash( object_id, $upload.content.object_id, version_id, $upload.content.object_version ) )}
<div id="leftmenu">
<div id="leftmenu-design">

<div class="objectinfo">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>{'Object information'|i18n( 'design/admin/content/upload' )}</h4>

</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-br"><div class="box-bl"><div class="box-content">

<p>
<label>{'ID'|i18n( 'design/admin/content/upload' )}:</label>
{$content_object.id}
</p>

<p>
<label>{'Created'|i18n( 'design/admin/content/upload' )}:</label>
{if $content_object.published}
{$content_object.published|l10n( shortdatetime )}<br />
{$content_object.current.creator.name|wash}
{else}
{'Not yet published'|i18n( 'design/admin/content/upload' )}
{/if}
</p>

<p>
<label>{'Modified'|i18n( 'design/admin/content/upload' )}:</label>
{if $content_object.modified}
{$content_object.modified|l10n( shortdatetime )}<br />
{fetch( content, object, hash( object_id, $content_object.content_class.modifier_id ) ).name|wash}
{else}
{'Not yet published'|i18n( 'design/admin/content/upload' )}
{/if}
</p>

<p>
<label>{'Published version'|i18n( 'design/admin/content/upload' )}:</label>
{if $content_object.published}
{$content_object.current.version}
{else}
{'Not yet published'|i18n( 'design/admin/content/upload' )}
{/if}
</p>


{* Manage versions. *}
<div class="block">
<input class="button-disabled" type="submit" name="" value="{'Manage versions'|i18n( 'design/admin/content/upload' )}" disabled="disabled" />
</div>

</div></div></div></div></div></div>

</div>

<br />

<div class="drafts">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>{'Current draft'|i18n( 'design/admin/content/upload' )}</h4>

</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{* Created. *}
<p>
<label>{'Created'|i18n( 'design/admin/content/upload' )}:</label>
{$content_version.created|l10n( shortdatetime )}<br />
{$content_version.creator.name|wash}
</p>

{* Modified. *}
<p>
<label>{'Modified'|i18n( 'design/admin/content/upload' )}:</label>
{$content_version.modified|l10n( shortdatetime )}<br />
{$content_version.creator.name|wash}
</p>

{* Version. *}
<p>
<label>{'Version'|i18n( 'design/admin/content/upload' )}:</label>
{$content_version.version}
</p>

</div></div></div></div></div></div>

</div>

</div>
</div>
{/let}
{/if}
<div id="maincontent">
<div id="maincontent-design" class="float-break"><div id="fix">
<!-- Maincontent START -->


{* Errors. *}
{section show=$errors|count|gt( 0 )}
    <div class="message-error">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The file could not be uploaded'|i18n( 'design/admin/content/upload' )}</h2>
        <p>{'The following errors occurred'|i18n( 'design/admin/content/upload' )}:</p>
        <ul>
            {section var=error loop=$errors}
                <li>{$error.description}</li>
            {/section}
        </ul>
    </div>
{/section}




{if $upload.description_template}
    {include name=Description uri=$upload.description_template upload=$upload}
{else}
<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'File upload'|i18n( 'design/admin/content/upload' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="block">
<p>{'Choose a file from your local machine then click the "Upload" button. An object will be created according to file type and placed in the specified location.'|i18n( 'design/admin/content/upload' )}</p>
</div>

{* DESIGN: Content END *}</div></div></div>

</div>
{/if}




<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Upload file'|i18n( 'design/admin/content/upload' )}</h2>

{* DESIGN: Subline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<input type="hidden" name="UploadActionName" value="{$upload.action_name}" />

{if $upload.parent_nodes|count|eq( 0 )}
<div class="block">
    <label>{'Location'|i18n( 'design/admin/content/upload' )}:</label>
    <select    name="UploadLocationChoice" class="combobox locationchoice" title="{'The location where the uploaded file should be placed.'|i18n( 'design/admin/content/upload' )}">
        <option value="auto">{'Automatic'|i18n( 'design/admin/content/upload' )}</option>

{def $root_node_value=ezini( 'LocationSettings', 'RootNode', 'upload.ini' )
     $root_node=cond( $root_node_value|is_numeric, fetch( content, node, hash( node_id, $root_node_value ) ),
                      fetch( content, node, hash( node_path, $root_node_value ) ) )
     $content_object=fetch( content, object, hash( object_id, $upload.content.object_id ) )
     $selection_list=fetch( 'content', 'tree',
                            hash( 'parent_node_id', $root_node.node_id,
                            'class_filter_type', include,
                            'class_filter_array', ezini( 'LocationSettings', 'ClassList', 'upload.ini' ),
                            'depth', ezini( 'LocationSettings', 'MaxDepth', 'upload.ini' ),
                            'depth_operator', 'lt',
                            'limit', ezini( 'LocationSettings', 'MaxItems', 'upload.ini' ) ) )}

    {if $content_object.published}
     <option value="{$content_object.main_node_id}">{$content_object.name}</option>
    {/if}

{foreach $selection_list as $selection}
        {if $selection.can_create}
          <option value="{$selection.node_id}">{'&nbsp;'|repeat( sub( $selection.depth, $root_node.depth, 1 ) )}{$selection.name|wash}</option>
        {/if}
{/foreach}
{undef $root_node_value $root_node $selection_list}
      </select>

</div>
{/if}

<div class="block">
    <label>{"Name"|i18n( 'design/admin/content/upload' )}:</label>
    <input class="halfbox" name="ObjectName" type="text" />
</div>

<div class="block">
    <label>{'File'|i18n( 'design/admin/content/upload' )}:</label>
    <input class="halfbox" name="UploadFile" type="file" title="{'Select the file that you want to upload.'|i18n( 'design/admin/content/upload' )}" />
    <input type="hidden" name="MAX_FILE_SIZE" value="50000000" />
</div>

</div>

{* DESIGN: Content END *}</div></div></div>
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">

<input class="button" type="submit" name="UploadFileButton" value="{'Upload'|i18n( 'design/admin/content/upload' )}" title="{'Proceed with uploading the selected file.'|i18n( 'design/admin/content/upload' )}" />
<input class="button" type="submit" name="CancelUploadButton" value="{'Cancel'|i18n( 'design/admin/content/upload' )}" title="{'Abort the upload operation.'|i18n( 'design/admin/content/upload' )}" />

</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

<!-- Maincontent END -->
</div>
<div class="break"></div>
</div></div>

</form>