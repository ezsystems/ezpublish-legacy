<form enctype="multipart/form-data" action={'content/upload'|ezurl} method="post">

<div id="leftmenu">
<div id="leftmenu-design">

<div class="objectinfo">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>{'Object information'|i18n( 'design/admin/content/upload' )}</h4>

</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-br"><div class="box-bl"><div class="box-content">

{let content_object=fetch( content, object, hash( object_id, $upload.content.object_id  ) )
     content_version=fetch( content, version, hash( object_id, $upload.content.object_id, version_id, $upload.content.object_version ) )}
<p>
<label>{'ID'|i18n( 'design/admin/content/upload' )}:</label>
{$content_object.id}
</p>

<p>
<label>{'Created'|i18n( 'design/admin/content/upload' )}:</label>
{section show=$content_object.published}
{$content_object.published|l10n( shortdatetime )}<br />
{$content_object.current.creator.name}
{section-else}
{'Not yet published'|i18n( 'design/admin/content/upload' )}
{/section}
</p>

<p>
<label>{'Modified'|i18n( 'design/admin/content/upload' )}:</label>
{section show=$content_object.modified}
{$content_object.modified|l10n( shortdatetime )}<br />
{fetch( content, object, hash( object_id, $content_object.content_class.modifier_id ) ).name}
{section-else}
{'Not yet published'|i18n( 'design/admin/content/upload' )}
{/section}
</p>

<p>
<label>{'Published version'|i18n( 'design/admin/content/upload' )}:</label>
{section show=$content_object.published}
{$content_object.current.version}
{section-else}
{'Not yet published'|i18n( 'design/admin/content/upload' )}
{/section}
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
{$content_version.creator.name}
</p>

{* Modified. *}
<p>
<label>{'Modified'|i18n( 'design/admin/content/upload' )}:</label>
{$content_version.modified|l10n( shortdatetime )}<br />
{$content_version.creator.name}
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

<div id="maincontent"><div id="fix">
<div id="maincontent-design">
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




{section show=$upload.description_template}
    {include name=Description uri=$upload.description_template upload=$upload}
{section-else}
<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'File upload'|i18n( 'design/admin/content/upload' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="block">
<p>{'Choose a file from your local machine and click the "Upload" button. An object will be created according to file type and placed in your chosen location.'|i18n( 'design/admin/content/upload' )}</p>
</div>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>
{/section}




<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Upload file'|i18n( 'design/admin/content/upload' )}</h2>

{* DESIGN: Subline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<input type="hidden" name="UploadActionName" value="{$upload.action_name}" />

<div class="block">

    <label>{'Location'|i18n( 'design/admin/content/upload' )}:</label>
    <select	name="UploadLocationChoice" class="combobox locationchoice" title="{'The location where the uploaded file should be placed.'|i18n( 'design/admin/content/upload' )}">
        <option value="auto">{'Automatic'|i18n( 'design/admin/content/upload' )}</option>
    {let root_node_value=ezini( 'LocationSettings', 'RootNode', 'upload.ini' )
         root_node=cond( $root_node_value|is_numeric, fetch( content, node, hash( node_id, $root_node_value ) ),
                         fetch( content, node, hash( node_path, $root_node_value ) ) )}
    {section var=node loop=fetch( content, tree,
                                  hash( parent_node_id, $root_node.node_id,
                                        class_filter_type, include,
                                        class_filter_array, ezini( 'LocationSettings', 'ClassList', 'upload.ini' ),
                                        depth, ezini( 'LocationSettings', 'MaxDepth', 'upload.ini' ),
                                        limit, ezini( 'LocationSettings', 'MaxItems', 'upload.ini' ) ) )}
        <option value="{$node.node_id}">{'&nbsp;'|repeat( sub( $node.depth, $root_node.depth, 1 ) )}{$node.name|wash}</option>
    {/section}
    {/let}
  	</select>

</div>

<div class="block">
    <label>{'File'|i18n( 'design/admin/content/upload' )}:</label>
    <input name="UploadFile" type="file" title="{'Select the file that you wish to upload.'|i18n( 'design/admin/content/upload' )}" />
    <input type="hidden" name="MAX_FILE_SIZE" value="50000000" />
</div>

 </div>

{* DESIGN: Content END *}</div></div></div>
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">

<input class="button" type="submit" name="UploadFileButton" value="{'Upload'|i18n( 'design/admin/content/upload' )}" title="{'Proceed with uploading the selected file.'|i18n( 'design/admin/content/upload' )}" />
<input class="button" type="submit" name="CancelUploadButton" value="{'Cancel'|i18n( 'design/admin/content/upload' )}" title="{'Abort the upload operation and go back to where you came from.'|i18n( 'design/admin/content/upload' )}" />

</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

<!-- Maincontent END -->
</div>
<div class="break"></div>
</div></div>

</form>