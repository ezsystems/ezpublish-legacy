<select id="{$custom_attribute_id}_source_types" class="mceItemSkip" title="{"List of possible link types. Link types that use the '://' format are technically called protocols."|i18n('design/standard/ezoe')}">
{if ezini_hasvariable( $custom_attribute_settings, 'LinkType', 'ezoe_attributes.ini' )}
{foreach ezini( $custom_attribute_settings, 'LinkType', 'ezoe_attributes.ini' ) as $custom_value => $custom_name}
    <option value="{if $custom_value|ne('-0-')}{$custom_value|wash}{/if}"{if $custom_attribute_default|contains( $custom_value )} selected="selected"{/if}>{$custom_name|wash}</option>
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
<a id="{$custom_attribute_id}_search_link" href="JavaScript:void(0);" title="{'Search'|i18n('design/admin/content/search')}"><img width="16" height="16" border="0" alt="{'Search'|i18n('design/admin/content/search')}" src={"tango/system-search.png"|ezimage} /></a>
<a id="{$custom_attribute_id}_browse_link" href="JavaScript:void(0);" title="{'Browse'|i18n('design/standard/ezoe')}"><img width="16" height="16" border="0" alt="{'Browse'|i18n('design/standard/ezoe')}" src={"tango/folder.png"|ezimage} /></a>
<a id="{$custom_attribute_id}_bookmarks_link" href="JavaScript:void(0);" title="{'Bookmarks'|i18n( 'design/admin/content/browse' )}"><img width="16" height="16" border="0" alt="{'Bookmarks'|i18n( 'design/admin/content/browse' )}" src={"tango/bookmark-new.png"|ezimage} /></a>
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
    var base_id = '#' + ezoeLinkAttribute.id, drop = jQuery( base_id+'_source_types'), inp = jQuery( base_id+'_source' );
    drop.change(function( e )
    {
        var base_id = ezoeLinkAttribute.id, input = document.getElementById( base_id+'_source' );
        if ( this.value === 'ezobject://' )
        {
        	input.value = this.value + ezoeLinkAttribute.node['contentobject_id'];
            ezoeLinkAttribute.namePreview( ezoeLinkAttribute.node['name'] );
        }
        else if ( this.value === 'eznode://' )
        {
        	input.value = this.value + ezoeLinkAttribute.node['node_id'];
            ezoeLinkAttribute.namePreview( ezoeLinkAttribute.node['name'] );
        }
        else
        {
        	input.value = this.value;
            ezoeLinkAttribute.namePreview( undefined );
        }
    });

    // add event to href input to lookup name on object or nodes
    inp.keyup( function( e )
    {
        e = e || window.event;
        var c = e.keyCode || e.which, base_id = '#' + ezoeLinkAttribute.id, dropdown = jQuery( base_id + '_source_types' );
        clearTimeout( ezoeLinkAttribute.timeOut );

        // break if user is pressing arrow keys
        if ( c > 36 && c < 41 ) return true;

        ezoeLinkAttribute.typeSet( this, dropdown[0] );

        if ( this.value.indexOf( '://' ) === -1 ) return true;

        var url = this.value.split('://'), id = ez.num( url[1], 0, 'int' );

        if ( id === 0 || ( url[0] !== 'eznode' && url[0] !== 'ezobject' ) ) return true;

        ezoeLinkAttribute.timeOut = setTimeout( eZOEPopupUtils.BIND( ezoeLinkAttribute.ajaxCheck, this, url[0] + '_' + id ), 320 );
        return true;
    });

    if ( inp.val().indexOf( '://' ) !== -1 )
    {
        var url = inp.val().split('://'), id = ez.num( url[1], 0, 'int' );
        if ( id !== 0 && ( url[0] === 'eznode' || url[0] === 'ezobject' ) )
            ezoeLinkAttribute.ajaxCheck( url[0] + '_' + id );
    }

    ezoeLinkAttribute.slides = ez.$$('div.panel');//ezoeLinkAttribute.slides is global object used by custom selectByEmbedId function
    var navigation = ez.$('embed_search_go_back_link', base_id+'_search_link', base_id+'_browse_link', base_id+'_bookmarks_link', 'embed_browse_go_back_link', 'embed_bookmarks_go_back_link' );
    ezoeLinkAttribute.slides.accordion( navigation, {duration: 100, transition: ez.fx.sinoidal, accordionAutoFocusTag: 'input[type=text]'}, {opacity: 0, display: 'none'} );
    navigation[4].addEvent('click', eZOEPopupUtils.BIND( ezoeLinkAttribute.slides.accordionGoto, ezoeLinkAttribute.slides, 0 ) ).addClass('accordion_navigation');
    navigation[5].addEvent('click', eZOEPopupUtils.BIND( ezoeLinkAttribute.slides.accordionGoto, ezoeLinkAttribute.slides, 0 ) ).addClass('accordion_navigation');

    ezoeLinkAttribute.typeSet( inp[0], drop[0] );
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
    var base_id = '#' + ezoeLinkAttribute.id, drop = jQuery( base_id+'_source_types'), inp = jQuery( base_id+'_source' ), info = jQuery( base_id+'_source_info' )
    if ( drop.val() === 'ezobject://' )
        inp.val( 'ezobject://' + object_id );
    else
    	inp.val( 'eznode://' + node_id );
    ezoeLinkAttribute.typeSet( inp[0], drop[0] );
    ezoeLinkAttribute.namePreview( name, info[0] );
    ezoeLinkAttribute.slides.accordionGoto.call( ezoeLinkAttribute.slides, 0 );
    ezoeLinkAttribute.node = {'contentobject_id': object_id, 'node_id': node_id, 'name': name }
};

// misc link related variables and functions
var ezoeLinkAttribute = {
    id : {/literal}'{$custom_attribute_id}'{literal},
    timeOut : null,
    slides : 0,
    ajax : ez.ajax( { 'charset': 'UTF-8' } ),
    ajaxResponse : '',
    node : {'contentobject_id': '', 'node_id': '', 'name': '' },
    ajaxCheck : function( url )
    {
        var url = tinyMCEPopup.editor.settings.ez_extension_url + '/load/' + url;
        ezoeLinkAttribute.ajax.load( url, '', ezoeLinkAttribute.postBack );
    },
    postBack : function( r )
    {
        ez.script( 'ezoeLinkAttribute.ajaxResponse=' + r.responseText );
        if ( ezoeLinkAttribute.ajaxResponse )
        {
            ezoeLinkAttribute.namePreview( ezoeLinkAttribute.ajaxResponse.name );
            ezoeLinkAttribute.node = ezoeLinkAttribute.ajaxResponse;
        }
        else
            ezoeLinkAttribute.namePreview( false );
    },
    namePreview : function( name )
    {
        var el = document.getElementById( ezoeLinkAttribute.id+'_source_info' );
{/literal}
        el.innerHTML = name === undefined ? '' : (name ? name : "{'Id not valid!'|i18n('design/standard/ezoe')}" );
{literal}
        el.style.border = name === undefined ? '' : (name ? '1px solid green' : '1px solid red');
    },
    typeSet : function( source, types )
    {
        var selectedValue = '', sourceValue = source.el.value, options = types.el.options;
        for ( var i = 0, l = options.length; i < l; i++ )
        {
            if ( options[i].value !== '' && sourceValue.indexOf( options[i].value ) === 0 )
                selectedValue = options[i].value;
        }
        types.el.value = selectedValue;
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