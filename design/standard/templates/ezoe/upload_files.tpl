{set scope=global persistent_variable=hash('title', 'Upload new File'|i18n('design/standard/ezoe'),
                                           'scripts', array('ezoe/ez_core.js',
                                                            'ezoe/ez_core_animation.js',
                                                            'ezoe/ez_core_accordion.js',
                                                            'ezoe/popup_utils.js'),
                                           'css', array()
                                           )}
<script type="text/javascript">
<!--
var contentType = '{$content_type}', classFilter = ez.$c();

{foreach $class_filter_array as $class_filter}
    classFilter.push('{$class_filter}');
{/foreach}
    
{literal}

tinyMCEPopup.onInit.add( function(){
    var slides = ez.$$('div.panel'), navigation = ez.$$('#tabs li.tab');
    slides.accordion( navigation, {duration: 100, transition: ez.fx.sinoidal, accordionAutoFocusTag: 'input[type=text]'}, {opacity: 0, display: 'none'} );
    // custom link generator, to redirect links to browse view if not in browse view
    eZOEPopupUtils.settings.browseLinkGenerator = function( n, mode, ed )
    {
        if ( n.children_count )
        {
           var tag = document.createElement("a");
           tag.setAttribute('href', 'JavaScript:eZOEPopupUtils.browse(' + n.node_id + ');');
           tag.setAttribute('title', ed.getLang('browse') + ': ' + n.url_alias );
           if ( mode !== 'browse' ) ez.$( tag ).addEvent('click', function(){ slides.accordionGoto( 2 ); });
           return tag;
        }
        var tag = document.createElement("span");
        tag.setAttribute('title', n.url_alias );
        return tag;
    };
});

if ( contentType === 'images' )
{
    eZOEPopupUtils.settings.browseClassGenerator = function( n, hasImage ){
        if ( hasImage && classFilter.indexOf( n.class_identifier ) !== -1 )
            return '';
        if ( n.children_count )
            return 'node_not_image';
        return 'node_not_image node_fadeout';
    };
}

-->
</script>
{/literal}

<div class="upload-view">
    <form action={concat('ezoe/upload/', $object_id, '/', $object_version, '/auto/1' )|ezurl} method="post" target="embed_upload" name="EmbedForm" id="EmbedForm" enctype="multipart/form-data" onsubmit="document.getElementById('upload_in_progress').style.display = '';">

        <div id="tabs" class="tabs">
        <ul>
            <li class="tab" title="{'Upload file from your local machine.'|i18n('design/standard/ezoe/wai')}"><span><a href="JavaScript:void(0);">{'Upload'|i18n('design/admin/content/upload')}</a></span></li>
            <li class="tab" title="{'Search for content already in eZ Publish.'|i18n('design/standard/ezoe/wai')}"><span><a href="JavaScript:void(0);">{'Search'|i18n('design/admin/content/search')}</a></span></li>
            <li class="tab" title="{'Browse the content tree in eZ Publish.'|i18n('design/standard/ezoe/wai')}"><span><a href="JavaScript:void(0);">{'Browse'|i18n('design/standard/ezoe')}</a></span></li>
            <li class="tab" title="{'Select or browse content among your personal eZ Publish bookmarks.'|i18n('design/standard/ezoe/wai')}"><span><a href="JavaScript:void(0);">{'Bookmarks'|i18n( 'design/admin/content/browse' )}</a></span></li>
        </ul>
        </div>

<div class="panel_wrapper" style="min-height: 360px;">
        <div class="panel">
            <table class="properties">
                <tr> 
                    <td class="column1"><label id="titlelabel" for="objectName">{'Name'|i18n('design/standard/ezoe')}</label></td> 
                    <td colspan="2"><input id="objectName" name="objectName" size="40" type="text" value="" title="{'Name for the uploaded object, filename is used if none is specified.'|i18n('design/standard/ezoe/wai')}" /></td> 
                </tr>
                <tr>
                    <td class="column1"><label id="srclabel" for="fileName">{'File'|i18n('design/standard/ezoe')}</label></td>
                    <td colspan="2"><input name="fileName" type="file" id="fileName" size="50" value="" title="{'Choose file to upload from your local machine.'|i18n('design/standard/ezoe/wai')}" /></td>
                </tr>
                <tr id="embedlistsrcrow">
                    <td class="column1"><label for="location">{'Location'|i18n('design/standard/ezoe')}</label></td>
                    <td colspan="2" id="embedlistsrccontainer">
                      <select name="location" id="location" title="{'Lets you specify where in eZ Publish to store the uploaded object.'|i18n('design/standard/ezoe/wai')}">
                        <option value="auto">{'Automatic'|i18n('design/standard/ezoe')}</option>

                        {if $object.published}
                            <option value="{$object.main_node_id}">{$object.name|shorten( 35 )} ({'this'|i18n('design/standard/ezoe')})</option>
                        {/if}

                        {def $root_node_value = ezini( 'LocationSettings', 'RootNode', 'upload.ini' )
                             $root_node = cond( $root_node_value|is_numeric, fetch( 'content', 'node', hash( 'node_id', $root_node_value ) ),
                                             fetch( 'content', 'node', hash( 'node_path', $root_node_value ) ) )
                             $selection_list = fetch( 'content', 'list',
                                                     hash( 'parent_node_id', $root_node.node_id,
                                                           'class_filter_type', include,
                                                           'class_filter_array', ezini( 'LocationSettings', 'ClassList', 'upload.ini' ),
                                                           'load_data_map', false(),
                                                           'sort_by', $root_node.sort_array|append( array('name', true() ) ),
                                                           'limit', ezini( 'LocationSettings', 'MaxItems', 'upload.ini' ) ) )
                             $selection_list_2 = 0
                             $selection_list_3 = 0
                             $selection_depth = ezini( 'LocationSettings', 'MaxDepth', 'upload.ini' )}
                        {foreach $selection_list as $item}
	                        {if $item.can_create}
	                            <option value="{$item.node_id}">{$item.name|wash|shorten( 35 )}</option>
	                        {/if}
	                        {if and( $selection_depth|ge( 2 ), first_set( $item.is_container, $item.object.content_class.is_container, false() ) )}
	                            {set $selection_list_2 = fetch( 'content', 'list',
	                                                     hash( 'parent_node_id', $item.node_id,
	                                                           'class_filter_type', include,
	                                                           'class_filter_array', ezini( 'LocationSettings', 'ClassList', 'upload.ini' ),
	                                                           'load_data_map', false(),
	                                                           'sort_by', $item.sort_array|append( array('name', true() ) ),
	                                                           'limit', ezini( 'LocationSettings', 'MaxItems', 'upload.ini' ) ) )}
	                            {foreach $selection_list_2 as $item_2}
			                        {if $item_2.can_create}
			                            <option value="{$item_2.node_id}">&nbsp;{$item_2.name|wash|shorten( 35 )}</option>
			                        {/if}
			                        {if and( $selection_depth|ge( 3 ), first_set( $item_2.is_container, $item_2.object.content_class.is_container, false() ) )}
			                            {set $selection_list_3 = fetch( 'content', 'list',
			                                                     hash( 'parent_node_id', $item_2.node_id,
			                                                           'class_filter_type', include,
			                                                           'class_filter_array', ezini( 'LocationSettings', 'ClassList', 'upload.ini' ),
			                                                           'load_data_map', false(),
			                                                           'sort_by', $item_2.sort_array|append( array('name', true() ) ),
			                                                           'limit', ezini( 'LocationSettings', 'MaxItems', 'upload.ini' ) ) )}
		                                {foreach $selection_list_3 as $item_3}
		                                    {if $item_3.can_create}
		                                        <option value="{$item_3.node_id}">&nbsp;&nbsp;{$item_3.name|wash|shorten( 35 )}</option>
		                                    {/if}
		                                {/foreach}
			                        {/if}
		                        {/foreach}
	    
	                        {/if}
                        {/foreach}

                      </select>
                    </td>
                </tr>
                <!-- Next attribute is file / media specific  -->
                <tr>
                    <td class="column1"><label id="descriptionlabel" for="objectDescription">{'Description'|i18n('design/standard/ezoe')}</label></td> 
                    <td colspan="2"><input id="objectDescription" name="ContentObjectAttribute_description" size="53" type="text" value="" title="{'Description to the file your uploading, so internet clients can read more about it before they decide to download it.'|i18n('design/standard/ezoe/wai')}" /></td> 
                </tr>
                <tr> 
                    <td colspan="3">
                    <input id="uploadButton" name="uploadButton" type="submit" value="{'Upload local file'|i18n('design/standard/ezoe')}" />
                    <span id="upload_in_progress" style="display: none; color: #666; background: #fff url({"stylesheets/skins/default/img/progress.gif"|ezdesign('single')}) no-repeat top left scroll; padding-left: 32px;">{'Upload is in progress, it may take a few seconds...'|i18n('design/standard/ezoe')}</span>
                    </td> 
                </tr>
            </table>

            <iframe id="embed_upload" name="embed_upload" frameborder="0" scrolling="no" style="border: 0; width: 99%; height: 30px; margin: 0; overflow: auto; overflow-x: hidden;"></iframe>

            {* Related files *}
            {if and( $related_contentobjects|count|gt( 0 ), $grouped_related_contentobjects.files|count|gt( 0 ))}
                <div class="block contenttype_file">
                <h2>{'Related files'|i18n('design/standard/ezoe')}</h2>
                        <table class="list" cellspacing="0">
                        <tr>
                            <th class="name">{'Name'|i18n( 'design/admin/content/edit' )}</th>
                            <th class="class">{'File type'|i18n( 'design/admin/content/edit' )}</th>
                            <th class="filesize">{'Size'|i18n( 'design/admin/content/edit' )}</th>
                        </tr>
                        {foreach $grouped_related_contentobjects.files as $file sequence array( bglight, bgdark ) as $sequence}
                            <tr class="{$sequence}">
                                <td class="name">{$file.object.class_name|class_icon( small, $file.object.class_name )}&nbsp;<a href="JavaScript:eZOEPopupUtils.selectByEmbedId( {$file.object.id} )">{$file.object.name|wash|shorten( 35 )}</a></td>
                                <td class="filetype">{$file.object.data_map.file.content.mime_type|wash}</td>
                                <td class="filesize">{$file.object.data_map.file.content.filesize|si( byte )}</td>
                            </tr>
                        {/foreach}
                        </table>
                </div>
            {else}
            <div class="block">
                <p>{"There are no related files."|i18n("design/standard/ezoe")}</p>
            </div>
            {/if}
        </div>

{include uri="design:ezoe/box_search.tpl" box_embed_mode=false() box_class_filter_array=$class_filter_array}

{include uri="design:ezoe/box_browse.tpl" box_embed_mode=false() box_class_filter_array=$class_filter_array}

{include uri="design:ezoe/box_bookmarks.tpl" box_embed_mode=false()}

</div>
     </form>
</div>