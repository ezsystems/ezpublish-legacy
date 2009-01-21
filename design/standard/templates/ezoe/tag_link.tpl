{set scope=global persistent_variable=hash('title', 'New %tag_name tag'|i18n('design/standard/ezoe', '', hash( '%tag_name', concat('&lt;', $tag_name_alias, '&gt;') )),
                                           'scripts', array('javascript/ezoe/ez_core.js',
                                                            'javascript/ezoe/ez_core_animation.js',
                                                            'javascript/ezoe/ez_core_accordion.js',
                                                            'javascript/ezoe/popup_utils.js'),
                                           'css', array()
                                           )}

<script type="text/javascript">
<!--

var ezTagName = '{$tag_name|wash}', ezoeLinkTimeOut = null, slides = 0, eZSelectedLinkNode = {ldelim}'object_id': '', 'node_id': '', 'name': '' {rdelim};
eZOEPopupUtils.settings.customAttributeStyleMap = {$custom_attribute_style_map};
eZOEPopupUtils.settings.tagEditTitleText = "{'Edit %tag_name tag'|i18n('design/standard/ezoe', '', hash( '%tag_name', concat('&lt;', $tag_name_alias, '&gt;') ))}";
{literal}

eZOEPopupUtils.ajaxLink = ez.ajax( { 'charset': 'UTF-8' } );
eZOEPopupUtils.ajaxLinkLoadResponse = '';

tinyMCEPopup.onInit.add( ez.fn.bind( eZOEPopupUtils.init, window, {
    tagName: ezTagName,
    form: 'EditForm',
    cancelButton: 'CancelButton',
    onInit: function( editorElement )
    {
        var link = ez.$('link_href_source_types', 'link_href_source')
        link[0].addEvent('change', function( e, el )
        {
            if ( el.value === 'ezobject://' )
            {
                ez.$('link_href_source').el.value = el.value + eZSelectedLinkNode['object_id'];
                ezoeLinkNamePreview( eZSelectedLinkNode['name'] );
            }
            else if ( el.value === 'eznode://' )
            {
                ez.$('link_href_source').el.value = el.value + eZSelectedLinkNode['node_id'];
                ezoeLinkNamePreview( eZSelectedLinkNode['name'] );
            }
            else
            {
                ez.$('link_href_source').el.value = el.value;
                ezoeLinkNamePreview();
            }
        });
        
        // add event to href input to lookup name on object or nodes
        link[1].addEvent('keyup', function( e, el ){
            e = e || window.event;
            var c = e.keyCode || e.which;
            clearTimeout( ezoeLinkTimeOut );
        
            // break if user is pressing arrow keys
            if ( c > 36 && c < 41 ) return true;
    
            ezoeLinkTypeSet( link[1], link[0] );
    
            if ( el.value.indexOf( '://' ) === -1 ) return true;
    
            var url = el.value.split('://'), id = ez.num( url[1], 0, 'int' );
    
            if ( id === 0 || ( url[0] !== 'eznode' && url[0] !== 'ezobject' ) ) return true;
    
            ezoeLinkTimeOut = setTimeout( ez.fn.bind( ezoeLinkAjaxCheck, this, url[0] + '_' + id  ), 320 );
            return true;
        });
        //ezoeLinkTypeSet( link[1], link[0] );
        
        if ( editorElement && editorElement.href.indexOf( '://' ) !== -1 )
        {
            var url = editorElement.href.split('://'), id = ez.num( url[1], 0, 'int' );
            if ( id !== 0 && ( url[0] === 'eznode' || url[0] === 'ezobject' ) )
                ezoeLinkAjaxCheck( url[0] + '_' + id );
        }
 
        slides = ez.$$('div.panel');//slides is global object used by custom selectByEmbedId function
        var navigation = ez.$('embed_search_go_back_link', 'search_for_link', 'browse_for_link', 'bookmarks_for_link', 'embed_browse_go_back_link', 'embed_bookmarks_go_back_link' );
        slides.accordion( navigation, {duration: 100, transition: ez.fx.sinoidal, accordionAutoFocusTag: 'input[type=text]'}, {opacity: 0, display: 'none'} );
        navigation[4].addEvent('click', ez.fn.bind( slides.accordionGoto, slides, 0 ) ).addClass('accordion_navigation');
        navigation[5].addEvent('click', ez.fn.bind( slides.accordionGoto, slides, 0 ) ).addClass('accordion_navigation');
    },
    onInitDone : function( editorElement, tagName, ed )
    {
        var link = ez.$('link_href_source_types', 'link_href_source')
        ezoeLinkTypeSet( link[1], link[0] );
    }
}));

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


//override 
eZOEPopupUtils.selectByEmbedId = function( object_id, node_id, name )
{
    var link = ez.$('link_href_source_types', 'link_href_source');
    if ( link[0].el.value === 'ezobject://' )
        link[1].el.value = 'ezobject://' + object_id;
    else
        link[1].el.value = 'eznode://' + node_id;
	ezoeLinkTypeSet( link[1], link[0] );
	ezoeLinkNamePreview( name );
    slides.accordionGoto.call( slides, 0 );
    eZSelectedLinkNode = {'object_id': object_id, 'node_id': node_id, 'name': name }
};

function ezoeLinkAjaxCheck( url )
{
    var url = tinyMCEPopup.editor.settings.ez_extension_url + '/load/' + url;
    eZOEPopupUtils.ajaxLink.load( url, '', ezoeLinkPostBack );
}

function ezoeLinkPostBack( r )
{
    ez.script( 'eZOEPopupUtils.ajaxLinkLoadResponse=' + r.responseText );
    var info = ez.$('link_href_source_info'), input = ez.$('link_href_source');
    if ( eZOEPopupUtils.ajaxLinkLoadResponse )
        ezoeLinkNamePreview( eZOEPopupUtils.ajaxLinkLoadResponse.name ) 
    else
        ezoeLinkNamePreview( false);
}

function ezoeLinkNamePreview( name )
{
    var info = ez.$('link_href_source_info');
    info.el.innerHTML = name === undefined ? '' : (name ? name : 'Id not valid!');
    info.el.style.border = name === undefined ? '' : (name ? '1px solid green' : '1px solid red');
}


function ezoeLinkTypeSet( source, types )
{
     if ( source.el.value.indexOf('eznode:') === 0 )
        types.el.value = 'eznode://';
    else if ( source.el.value.indexOf('ezobject:') === 0 )
        types.el.value = 'ezobject://';
    else if ( source.el.value.indexOf('file:') === 0 )
        types.el.value = 'file://';
    else if ( source.el.value.indexOf('ftp:') === 0 )
        types.el.value = 'ftp://';
    else if ( source.el.value.indexOf('http:') === 0 )
        types.el.value = 'http://';
    else if ( source.el.value.indexOf('https:') === 0 )
        types.el.value = 'https://';
    else if ( source.el.value.indexOf('mailto:') === 0 )
        types.el.value = 'mailto:';
    else if ( source.el.value.indexOf('#') === 0 )
        types.el.value = '#';
    else if ( source.el.value !== '' )
        types.el.value = '';
}



// -->
</script>
{/literal}

<div class="tag-view tag-type-{$tag_name}">

    <form action="JavaScript:void(0)" method="post" name="EditForm" id="EditForm" enctype="multipart/form-data">
        <div id="tabs" class="tabs">
        <ul>
            <li class="tab current"><span><a href="JavaScript:void(0);">{'Properties'|i18n('design/standard/ezoe')}</a></span></li>
        </ul>
        </div>


<div class="panel_wrapper" style="min-height: 300px;">
    <div class="panel">
        <div class="attribute-title">
            <h2 style="padding: 0 0 4px 0;" id="tag-edit-title">{'New %tag_name tag'|i18n('design/standard/ezoe', '', hash( '%tag_name', concat('&lt;', $tag_name_alias, '&gt;') ))}</h2>
        </div>
        {set-block variable=$link_href_types}
            <select id="link_href_source_types" class="mceItemSkip" title="{"List of possible link types. Link types that use the '://' format are technically called protocols."|i18n('design/standard/ezoe')}">
                <option value="eznode://">eznode</option>
                <option value="ezobject://">ezobject</option>
                <option value="ftp://">Ftp</option>
                <option value="file://">File</option>
                <option value="http://">Http</option>
                <option value="https://">Https</option>
                <option value="mailto:">Mail</option>
                <option value="#">Anchor</option>
                <option value="">Other</option>
            </select>
            <a id="search_for_link" href="JavaScript:void(0);" title="{'Search'|i18n('design/admin/content/search')}"><img width="16" height="16" border="0" alt="{'Search'|i18n('design/admin/content/search')}" src={"tango/system-search.png"|ezimage} /></a>
            <a id="browse_for_link" href="JavaScript:void(0);" title="{'Browse'|i18n('design/standard/ezoe')}"><img width="16" height="16" border="0" alt="{'Browse'|i18n('design/standard/ezoe')}" src={"tango/folder.png"|ezimage} /></a>
            <a id="bookmarks_for_link" href="JavaScript:void(0);" title="{'Bookmarks'|i18n( 'design/admin/content/browse' )}"><img width="16" height="16" border="0" alt="{'Bookmarks'|i18n( 'design/admin/content/browse' )}" src={"tango/bookmark-new.png"|ezimage} /></a>
            <span id="link_href_source_info"></span>
            <br />
        {/set-block}
        
        {def $viewModes = hash('0', '[default]')}
        {foreach ezini( 'link', 'AvailableViewModes', 'content.ini')  as $viewMode}
            {set $viewModes = $viewModes|merge(  hash( $viewMode, $viewMode|upfirst ) )}
        {/foreach} 
        
        {*
         Removed targets used for frames as most people though they worked like windows.open (javascript)
         This is the removed values, specify selections in ezoe_customattributes.ini to re add them:
         , '_self', 'Same frame (_self)', '_parent', 'Parent frame (_parent)', '_top', 'Full window (_top)'
         *}
        {include uri="design:ezoe/generalattributes.tpl"
                 tag_name=$tag_name
                 attributes=hash('href', '',
                                  'view', $viewModes,
                                  'target', hash('0', 'None', '_blank', 'New window (_blank)'),
                                  'class', $class_list,
                                  'title', '',
                                  'id', ''
                                 )
                 attribute_content_prepend=hash('href', $link_href_types)
                 classes=hash('href', 'link_href_input')
        }

        {include uri="design:ezoe/customattributes.tpl" tag_name=$tag_name}

        <div class="block"> 
            <div class="left">
                <input id="SaveButton" name="SaveButton" type="submit" value="{'OK'|i18n('design/standard/ezoe')}" />
                <input id="CancelButton" name="CancelButton" type="reset" value="{'Cancel'|i18n('design/standard/ezoe')}" />
            </div> 
        </div>

    </div>
    
{include uri="design:ezoe/box_search.tpl"}

{include uri="design:ezoe/box_browse.tpl"}

{include uri="design:ezoe/box_bookmarks.tpl"}

</div>
    </form>

</div>