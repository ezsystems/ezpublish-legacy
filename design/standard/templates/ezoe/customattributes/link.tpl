<select id="{$custom_attribute_id}_source_types" class="mceItemSkip" title="{"List of possible link types. Link types that use the '://' format are technically called protocols."|i18n('design/standard/ezoe')}">
{if ezini_hasvariable( $custom_attribute_settings, 'Selection', 'ezoe_attributes.ini' )}
{foreach ezini( $custom_attribute_settings, 'Selection', 'ezoe_attributes.ini' ) as $custom_value => $custom_name}
    <option value="{$custom_value|wash}"{if $custom_attribute_default|contains( $custom_value )} selected="selected"{/if}>{$custom_name|wash}</option>
{/foreach}
{else}
    <option value="eznode://">{'eznode'|i18n('design/standard/ezoe')}</option>
    <option value="ezobject://">{'ezobject'|i18n('design/standard/ezoe')}</option>
    <option value="ftp://">{'Ftp'|i18n('design/standard/ezoe')}</option>
    <option value="file://">{'File'|i18n('design/standard/ezoe')}</option>
    <option value="http://">{'Http'|i18n('design/standard/ezoe')}</option>
    <option value="https://">{'Https'|i18n('design/standard/ezoe')}</option>
    <option value="mailto:">{'Mail'|i18n('design/standard/ezoe')}</option>
    <option value="#">{'Anchor'|i18n('design/standard/ezoe')}</option>
    <option value="">{'Other'|i18n('design/standard/ezoe')}</option>
{/if}
</select>
<a id="search_for_link" href="JavaScript:void(0);" title="{'Search'|i18n('design/admin/content/search')}"><img width="16" height="16" border="0" alt="{'Search'|i18n('design/admin/content/search')}" src={"tango/system-search.png"|ezimage} /></a>
<a id="browse_for_link" href="JavaScript:void(0);" title="{'Browse'|i18n('design/standard/ezoe')}"><img width="16" height="16" border="0" alt="{'Browse'|i18n('design/standard/ezoe')}" src={"tango/folder.png"|ezimage} /></a>
<a id="bookmarks_for_link" href="JavaScript:void(0);" title="{'Bookmarks'|i18n( 'design/admin/content/browse' )}"><img width="16" height="16" border="0" alt="{'Bookmarks'|i18n( 'design/admin/content/browse' )}" src={"tango/bookmark-new.png"|ezimage} /></a>
<span id="{$custom_attribute_id}_source_info"></span>
<br />

{set $custom_attribute_classes = $custom_attribute_classes|append( 'link_href_input' )}
<input type="text" name="{$custom_attribute}" id="{$custom_attribute_id}_source" value="{$custom_attribute_default|wash}"{if $custom_attribute_disabled} disabled="disabled"{/if} class="{$custom_attribute_classes|implode(' ')}" title="{$custom_attribute_title|wash}" />


<script type="text/javascript">
<!--

{literal}

// register function to be called on end of init
eZOEPopupUtils.settings.onInitDoneArray.push( function( editorElement )
{
    var link = ez.$('link_href_source_types', 'link_href_source')
    link[0].addEvent('change', function( e, el )
    {
        if ( el.value === 'ezobject://' )
        {
            ez.$('link_href_source').el.value = el.value + ezoeLinkAttribute.node['object_id'];
            ezoeLinkAttribute.namePreview( ezoeLinkAttribute.node['name'] );
        }
        else if ( el.value === 'eznode://' )
        {
            ez.$('link_href_source').el.value = el.value + ezoeLinkAttribute.node['node_id'];
            ezoeLinkAttribute.namePreview( ezoeLinkAttribute.node['name'] );
        }
        else
        {
            ez.$('link_href_source').el.value = el.value;
            ezoeLinkAttribute.namePreview();
        }
    });

    // add event to href input to lookup name on object or nodes
    link[1].addEvent('keyup', function( e, el ){
        e = e || window.event;
        var c = e.keyCode || e.which;
        clearTimeout( ezoeLinkAttribute.timeOut );

        // break if user is pressing arrow keys
        if ( c > 36 && c < 41 ) return true;

        ezoeLinkAttribute.typeSet( link[1], link[0] );

        if ( el.value.indexOf( '://' ) === -1 ) return true;

        var url = el.value.split('://'), id = ez.num( url[1], 0, 'int' );

        if ( id === 0 || ( url[0] !== 'eznode' && url[0] !== 'ezobject' ) ) return true;

        ezoeLinkAttribute.timeOut = setTimeout( ez.fn.bind( ezoeLinkAttribute.ajaxCheck, this, url[0] + '_' + id  ), 320 );
        return true;
    });
    //ezoeLinkAttribute.typeSet( link[1], link[0] );

    if ( editorElement && editorElement.href.indexOf( '://' ) !== -1 )
    {
        var url = editorElement.href.split('://'), id = ez.num( url[1], 0, 'int' );
        if ( id !== 0 && ( url[0] === 'eznode' || url[0] === 'ezobject' ) )
            ezoeLinkAttribute.ajaxCheck( url[0] + '_' + id );
    }

    ezoeLinkAttribute.slides = ez.$$('div.panel');//ezoeLinkAttribute.slides is global object used by custom selectByEmbedId function
    var navigation = ez.$('embed_search_go_back_link', 'search_for_link', 'browse_for_link', 'bookmarks_for_link', 'embed_browse_go_back_link', 'embed_bookmarks_go_back_link' );
    ezoeLinkAttribute.slides.accordion( navigation, {duration: 100, transition: ez.fx.sinoidal, accordionAutoFocusTag: 'input[type=text]'}, {opacity: 0, display: 'none'} );
    navigation[4].addEvent('click', ez.fn.bind( ezoeLinkAttribute.slides.accordionGoto, ezoeLinkAttribute.slides, 0 ) ).addClass('accordion_navigation');
    navigation[5].addEvent('click', ez.fn.bind( ezoeLinkAttribute.slides.accordionGoto, ezoeLinkAttribute.slides, 0 ) ).addClass('accordion_navigation');

    ezoeLinkAttribute.typeSet( link[1], link[0] );
});


//custom link generator, to redirect links to browse view if not in browse view
eZOEPopupUtils.settings.browseLinkGenerator = function( n, mode, ed )
{
    if ( n.children_count )
    {
       var tag = document.createElement("a");
       tag.setAttribute('href', 'JavaScript:eZOEPopupUtils.browse(' + n.node_id + ');');
       tag.setAttribute('title', ed.getLang('browse') + ': ' + n.url_alias );
       if ( mode !== 'browse' ) ez.$( tag ).addEvent('click', function(){ ezoeLinkAttribute.slides.accordionGoto( 2 ); });
       return tag;
    }
    var tag = document.createElement("span");
    tag.setAttribute('title', n.url_alias );
    return tag;
};


// override so selected element is redirected to link input
eZOEPopupUtils.selectByEmbedId = function( object_id, node_id, name )
{
    var link = ez.$('link_href_source_types', 'link_href_source');
    if ( link[0].el.value === 'ezobject://' )
        link[1].el.value = 'ezobject://' + object_id;
    else
        link[1].el.value = 'eznode://' + node_id;
    ezoeLinkAttribute.typeSet( link[1], link[0] );
    ezoeLinkAttribute.namePreview( name );
    ezoeLinkAttribute.slides.accordionGoto.call( ezoeLinkAttribute.slides, 0 );
    ezoeLinkAttribute.node = {'object_id': object_id, 'node_id': node_id, 'name': name }
};

// misc link related variables and functions
var ezoeLinkAttribute = {
    timeOut : null,
    slides : 0,
    ajax : ez.ajax( { 'charset': 'UTF-8' } ),
    ajaxResponse : '',
    node : {'object_id': '', 'node_id': '', 'name': '' },
    ajaxCheck : function( url )
    {
        var url = tinyMCEPopup.editor.settings.ez_extension_url + '/load/' + url;
        ezoeLinkAttribute.ajax.load( url, '', ezoeLinkAttribute.postBack );
    },
    postBack : function( r )
    {
        ez.script( 'ezoeLinkAttribute.ajaxResponse=' + r.responseText );
        var info = ez.$('link_href_source_info'), input = ez.$('link_href_source');
        if ( ezoeLinkAttribute.ajaxResponse )
            ezoeLinkAttribute.namePreview( ezoeLinkAttribute.ajaxResponse.name ) 
        else
            ezoeLinkAttribute.namePreview( false );
    },
    namePreview : function( name )
    {
        var info = ez.$('link_href_source_info');
        info.el.innerHTML = name === undefined ? '' : (name ? name : 'Id not valid!');
        info.el.style.border = name === undefined ? '' : (name ? '1px solid green' : '1px solid red');
    },
    typeSet : function( source, types )
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
};

{/literal}
//-->
</script>


{append-block scope=global variable=$attribute_panel_output}

{include uri="design:ezoe/box_search.tpl"}

{include uri="design:ezoe/box_browse.tpl"}

{include uri="design:ezoe/box_bookmarks.tpl"}

{/append-block}