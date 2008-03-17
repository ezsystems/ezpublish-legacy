{set scope=global persistent_variable=hash('title', 'Upload new'|i18n('design/standard/ezoe'),
                                           'scripts', array('javascript/ezoe/ez_core.js',
                                                            'javascript/ezoe/ez_core_animation.js',
                                                            'javascript/ezoe/ez_core_accordion.js',
                                                            'javascript/ezoe/popup.js'),
                                           'css', array()
                                           )}
<script type="text/javascript">
<!--
var contentType = '{$content_type}', eZOeMCE = new Object();
eZOeMCE['root']          = {'/'|ezroot};
eZOeMCE['extension_url'] = {'ezoe/'|ezurl};
eZOeMCE['relation_url']  = {concat('ezoe/relations/', $object_id, '/', $object_version, '/auto' )|ezurl};
eZOeMCE['i18n']          = {ldelim}
    previous: "{'Previous'|i18n('design/admin/navigator')}",
    next: "{'Next'|i18n('design/admin/navigator')}",
    select: "{'Select'|i18n('design/admin/content/browse')}",
    type: "{'Type'|i18n('design/standard/ezoe')}"
{rdelim};
    
{literal} 

tinyMCEPopup.onInit.add( function(){
    var slides = ez.$$('div.slide'), navigation = ez.$$('#tabs div.tab');
    slides.accordion( navigation, {duration: 150, transition: ez.fx.sinoidal, accordionAutoFocusTag: 'input[type=text]'}, {marginLeft: 480, display: 'none'} );
});


function ajaxBrowse( nodeId, offset )
{
    ezajaxObject.load( eZOeMCE['extension_url'] + '/expand/' + nodeId + '/' + (offset || 0), '', ajaxBrowseCallBack  );
}

function ajaxBrowseCallBack( r )
{
    ez.script( 'ezajaxLoadResponse=' + r.responseText );
    var tbody = ez.$$('#browse_box_prev tbody')[0], thead = ez.$$('#browse_box_prev thead')[0], tfoot = ez.$$('#browse_box_prev tfoot')[0];
    tbody.el.innerHTML = '';
    thead.el.innerHTML = '';
    tfoot.el.innerHTML = '';
    if ( ezajaxLoadResponse )
    {
        var node = ezajaxLoadResponse['node'];
        if ( node['path'].length )
            thead.el.innerHTML = '<tr><td><\/td><td colspan="2">' + ez.$c( node['path'] ).map( function( n ){
               return '<a href="JavaScript:ajaxBrowse(' + n.node_id + ');" title="' + eZOeMCE['i18n']['type'] + ': ' + n.class_name + '">' + n.name + '<\/a>';
            } ).join(' / ') + ' / ' + node.name + '<\/td><\/tr>';
        else
            thead.el.innerHTML = '<tr><td><\/td><td colspan="2">' + node.name + '<\/td><\/tr>';
        
        
        if ( ezajaxLoadResponse['list'] )
           tbody.el.innerHTML = '<tr>' + ez.$c( ezajaxLoadResponse['list'] ).map( function( n ){
               var html = '<td><a href="JavaScript:selectByEmbedId(' + n.contentobject_id + ')">' + eZOeMCE['i18n']['select'] + '<\/a><\/td>';
               if ( n.children_count )
                   return html +'<td><a href="JavaScript:ajaxBrowse(' + n.node_id + ');">' + n.name + '<\/a><\/td><td>' + n.class_name + '<\/td>';
               else
                   return html +'<td>' + n.name + '<\/td><td>' + n.class_name + '<\/td>';
            } ).join('<\/tr><tr>') + '<\/tr>';
        
        var html = '<tr><td colspan="2">';
        if ( ezajaxLoadResponse['offset'] !== 0 )
        {
            html += '<a href="JavaScript:ajaxBrowse('+ node['node_id'] +','+ (ezajaxLoadResponse['offset'] - ezajaxLoadResponse['limit'])  +')">&lt;&lt; ' + eZOeMCE['i18n']['previous'] + '<\/a>';
        }
        html += '<\/td><td>';
        if ( (ezajaxLoadResponse['offset'] + ezajaxLoadResponse['limit']) < ezajaxLoadResponse['total_count'] )
        {
            html += '<a href="JavaScript:ajaxBrowse('+ node['node_id'] +','+ (ezajaxLoadResponse['offset'] + ezajaxLoadResponse['limit'])  +')">' + eZOeMCE['i18n']['next'] + ' &gt;&gt;<\/a>';
        }
        html += '<\/td><\/tr>';
        tfoot.el.innerHTML = html;

        
    }
    return false;
}


-->
</script>
<style>

table#browse_box_prev { border-collapse: collapse; }

table#browse_box_prev thead td { padding-bottom: 5px; }

table#browse_box_prev tfoot td { padding-top: 5px; }

</style>
{/literal}

<div style="width: 470px;">
    <form action={concat('ezoe/upload/', $object_id, '/', $object_version, '/auto/1' )|ezurl} method="post" target="embed_upload" name="EmbedForm" id="EmbedForm" enctype="multipart/form-data"
    style="float:left; width: 940px" onsubmit="ez.$('upload_in_progress').show();">

        <div id="tabs">
            <div class="tab"><div>{'Upload'|i18n('design/admin/content/upload')}</div></div>
            <div class="tab"><div>{'Search'|i18n('design/admin/content/search')}</div></div>
            <div class="tab"><div>{'Browse'|i18n('design/standard/ezoe')}</div></div>
        </div>

        <div class="slide">
            <table class="properties">
                <tr>
                    <td class="column1"><label id="srclabel" for="src">{'File'|i18n('design/standard/ezoe')}</label></td>
                    <td colspan="2"><input name="fileName" type="file" id="fileName" value="" /></td>
                </tr>
                <tr id="embedlistsrcrow">
                    <td class="column1"><label for="location">{'Location'|i18n('design/standard/ezoe')}</label></td>
                    <td colspan="2" id="embedlistsrccontainer">
                      <select name="location" id="location">
                        <option value="auto">{'Automatic'|i18n('design/standard/ezoe')}</option>

                        {if $object.published}
                         <option value="{$object.main_node_id}">{$object.name} (this)</option>
                        {/if}

                        {def $root_node_value = ezini( 'LocationSettings', 'RootNode', 'upload.ini' )
                             $root_node = cond( $root_node_value|is_numeric, fetch( content, node, hash( node_id, $root_node_value ) ),
                                             fetch( content, node, hash( node_path, $root_node_value ) ) )
                             $selection_list = fetch( 'content', 'tree',
                                                     hash( 'parent_node_id', $root_node.node_id,
                                                           'class_filter_type', include,
                                                           'class_filter_array', ezini( 'LocationSettings', 'ClassList', 'upload.ini' ),
                                                           'depth', ezini( 'LocationSettings', 'MaxDepth', 'upload.ini' ),
                                                           'depth_operator', 'lt',
                                                           'load_data_map', false(),
                                                           'limit', ezini( 'LocationSettings', 'MaxItems', 'upload.ini' ) ) )}
                        {foreach $selection_list as $item}
                        {if $item.can_create}
                         <option value="{$item.node_id}">{'&nbsp;'|repeat( sub( $item.depth, $root_node.depth, 1 ) )}{$item.name|wash}</option>
                        {/if}
                        {/foreach}

                      </select>
                    </td>
                </tr>
                <tr> 
                    <td class="column1"><label id="titlelabel" for="title">{'Name'|i18n('design/standard/ezoe')}</label></td> 
                    <td colspan="2"><input id="objectName" name="objectName" type="text" value="" /></td> 
                </tr>
                <tr>
                {if $content_type|eq('image')}
                    <td class="column1"><label id="titlelabel" for="title">{'Caption'|i18n('design/standard/ezoe')}</label></td> 
                    <td colspan="2"><input id="objectText" name="ContentObjectAttribute_caption" type="text" value="" size="32" /></td>
                {else}
                    <td class="column1"><label id="titlelabel" for="title">{'Description'|i18n('design/standard/ezoe')}</label></td> 
                    <td colspan="2"><input id="objectText" name="ContentObjectAttribute_description" type="text" value="" size="32" /></td>
                {/if} 
                </tr>
                <tr> 
                    <td colspan="3">
                    <input id="uploadButton" name="uploadButton" type="submit" value="{'Upload local file'|i18n('design/standard/ezoe')}" />
                    <span id="upload_in_progress" style="display: none; color: #666">{'Upload is in progress, it may take a few seconds ...'|i18n('design/standard/ezoe')}</span>
                    </td> 
                </tr>
            </table>

            <iframe id="embed_upload" name="embed_upload" style="border: 0; width: 99%; height: 30px; margin: 0; overflow-x: hidden;"></iframe>

            {if $related_contentobjects|count|gt( 0 )}
                {* Related images *}
                {if and( $content_type|eq('image'), $grouped_related_contentobjects.images|count|gt( 0 ))}
                <div class="block">
                <h2>{'Related images'|i18n('design/standard/ezoe')}</h2>
                    {foreach $grouped_related_contentobjects.images as $img}

                    <div class="image-thumbnail-item">
                        <a title="{$img.object.name|wash}" href="JavaScript:selectByEmbedId( {$img.object.id} )" class="contenttype_image">
                        {attribute_view_gui attribute=$img.object.data_map.image image_class=small}
                        </a>
                    </div>
                    {/foreach}
                </div>
                {/if}
            
                {* Related files *}
                {*if and( $content_type|eq('file'), $grouped_related_contentobjects.files|count|gt( 0 ))*}
                {if and( $content_type|eq('object'), $grouped_related_contentobjects.files|count|gt( 0 ))}
                <div class="block">
                <h2>{'Related files'|i18n('design/standard/ezoe')}</h2>
                        <table class="list" cellspacing="0">
                        <tr>
                            <th class="tight">&nbsp;</th>
                            <th class="name">{'Name'|i18n( 'design/admin/content/edit' )}</th>
                            <th class="class">{'File type'|i18n( 'design/admin/content/edit' )}</th>
                            <th class="filesize">{'Size'|i18n( 'design/admin/content/edit' )}</th>
                        </tr>
                        {foreach $grouped_related_contentobjects.files as $file sequence array( bglight, bgdark ) as $sequence}
                            <tr class="{$sequence}">
                                <td><input type="radio" {section show=$file.selected}class="selected"{/section} name="ContentObjectIDString" value="{$file.id}" {section show=$file.selected}checked{/section} /></td>
                                <td class="name">{$file.object.class_name|class_icon( small, $file.object.class_name )}&nbsp;{$file.object.name|wash}</td>
                                <td class="filetype">{$file.object.data_map.file.content.mime_type|wash}</td>
                                <td class="filesize">{$file.object.data_map.file.content.filesize|si( byte )}</td>
                            </tr>
                        {/foreach}
                        </table>
                </div>
                {/if}
            
                {* Related objects *}
                {if and( $content_type|eq('object'), $grouped_related_contentobjects.objects|count|gt( 0 ))}
                <div class="block">
                <h2>{'Related content'|i18n('design/standard/ezoe')}</h2>
                        <table class="list" cellspacing="0">
                        <tr>
                            <th class="name">{'Name'|i18n( 'design/admin/content/edit' )}</th>
                            <th class="class">{'Type'|i18n( 'design/admin/content/edit' )}</th>
                        </tr>
                        {foreach $grouped_related_contentobjects.objects as $relation sequence array( bglight, bgdark ) as $sequence}
                            <tr class="{$sequence}">
                                <td class="name">{$relation.object.class_name|class_icon( small, $relation.object.class_name )}&nbsp;<a href="JavaScript:selectByEmbedId( {$relation.object.id} )">{$relation.object.name|wash}</a></td>
                                <td class="class">{$relation.object.class_name|wash}</td>
                            </tr>
                        {/foreach}
                        </table>
                </div>
                {/if}
            {else}
            <div class="block">
                <p>{"There are no related objects."|i18n("design/standard/ezoe")}</p>
            </div>
            {/if}
        </div>

        <div class="slide" id="search_box" style="display: none;">
            {if $class_filter_array}
              <input type="hidden" name="SearchContentClassIdentifier" value="{$class_filter_array}" />
            {/if}
            <table class="properties">
            <tr>
                <td><input id="SearchText" name="SearchStr" type="text" value="" onkeypress="return ezajaxSearchEnter(event)" /></td>
                <td><input type="submit" name="SearchButton" id="SearchButton" value="{'Search'|i18n('design/admin/content/search')}"  onclick="return ezajaxSearchEnter(event, true)" /></td>
            </tr>
            <tr>
                <td colspan="2"><div id="search_box_prev"></div></td>
            </tr>
            </table>
            <!-- todo: paging support -->
        </div>
        
        <div class="slide" style="display: none;">
            {def $root_nodes = fetch('content', 'list', hash('parent_node_id', 1))}
            <div style="background-color: #eee; text-align: center">
            {foreach $root_nodes as $n}
                <a href="JavaScript:ajaxBrowse( {$n.node_id} )" style="font-weight: bold">{$n.name}</a> &nbsp;
            {/foreach}
            </div>
            <table id="browse_box_prev">
                <thead>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                </tfoot>
            </table>
        </div>

     </form>
</div>