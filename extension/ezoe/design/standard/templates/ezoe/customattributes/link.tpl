<select id="{$custom_attribute_id}_source_types" class="mceItemSkip atr_link_source_types" title="{"List of possible link types. Link types that use the '://' format are technically called protocols."|i18n('design/standard/ezoe')}">
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
<a id="{$custom_attribute_id}_search_link" class="atr_link_search_link" href="JavaScript:void(0);" title="{'Search'|i18n('design/admin/content/search')}"><img width="16" height="16" border="0" alt="{'Search'|i18n('design/admin/content/search')}" src={"tango/system-search.png"|ezimage} /></a>
<a id="{$custom_attribute_id}_browse_link" class=" atr_link_browse_link" href="JavaScript:void(0);" title="{'Browse'|i18n('design/standard/ezoe')}"><img width="16" height="16" border="0" alt="{'Browse'|i18n('design/standard/ezoe')}" src={"tango/folder.png"|ezimage} /></a>
<a id="{$custom_attribute_id}_bookmark_link" class="atr_link_bookmark_link" href="JavaScript:void(0);" title="{'Bookmarks'|i18n( 'design/admin/content/browse' )}"><img width="16" height="16" border="0" alt="{'Bookmarks'|i18n( 'design/admin/content/browse' )}" src={"tango/bookmark-new.png"|ezimage} /></a>
<span id="{$custom_attribute_id}_source_info" class="atr_link_source_info"></span>
<br />

{set $custom_attribute_classes = $custom_attribute_classes|append( 'link_href_input' )}
<input type="text" name="{$custom_attribute}" id="{$custom_attribute_id}_source" value="{$custom_attribute_default|wash}"{if $custom_attribute_disabled} disabled="disabled"{/if} class="{$custom_attribute_classes|implode(' ')}" title="{$custom_attribute_title|wash}" />

{run-once}
<script type="text/javascript">
{literal}

// register function to be called on end of init
eZOEPopupUtils.settings.onInitDoneArray.push( function( editorElement )
{
    var drop = jQuery( 'select.atr_link_source_types'), inp = jQuery( 'input.link_href_input' );

    // init source type selection
    inp.each(function( i ){
        var self = jQuery(this);
        // set correct selection in type drop down
        ezoeLinkAttribute.typeSet( self, jQuery(drop.get( i )) );
        // lookup node / object data if relation link
        if ( self.val().indexOf( '://' ) !== -1 )
        {
            var url = self.val().split('://'), id = eZOEPopupUtils.Int( url[1] );
            if ( id !== 0 && ( url[0] === 'eznode' || url[0] === 'ezobject' ) )
                ezoeLinkAttribute.ajaxCheck.call( this, url[0] + '_' + id );
        }
    });

    // add event to lookup changes to source type
    drop.change(function( e )
    {
        var lid = ezoeLinkAttribute.lid( this.id ), input = document.getElementById( lid+'_source' );
        if ( this.value === 'ezobject://' )
        {
        	input.value = this.value + ezoeLinkAttribute.node['contentobject_id'];
            ezoeLinkAttribute.namePreview( ezoeLinkAttribute.node['name'], lid );
        }
        else if ( this.value === 'eznode://' )
        {
        	input.value = this.value + ezoeLinkAttribute.node['node_id'];
            ezoeLinkAttribute.namePreview( ezoeLinkAttribute.node['name'], lid );
        }
        else
        {
        	input.value = this.value;
            ezoeLinkAttribute.namePreview( undefined, lid );
        }
    });

    // add event to href input to lookup name on object or nodes
    inp.keyup( function( e )
    {
        e = e || window.event;
        var c = e.keyCode || e.which, lid = ezoeLinkAttribute.lid( this.id ), dropdown = jQuery( '#'+lid + '_source_types' );
        clearTimeout( ezoeLinkAttribute.timeOut );

        // break if user is pressing arrow keys
        if ( c > 36 && c < 41 ) return true;

        ezoeLinkAttribute.typeSet( jQuery( this ), dropdown );

        if ( this.value.indexOf( '://' ) === -1 ) return true;

        var url = this.value.split('://'), id = eZOEPopupUtils.Int( url[1] );

        if ( id === 0 || ( url[0] !== 'eznode' && url[0] !== 'ezobject' ) ) return true;

        ezoeLinkAttribute.timeOut = setTimeout( eZOEPopupUtils.BIND( ezoeLinkAttribute.ajaxCheck, this, url[0] + '_' + id, lid ), 320 );
        return true;
    });

    // setup navigation on bookmark / browse / search links to their 'boxes' (panels)
    jQuery( 'a.atr_link_search_link, a.atr_link_browse_link, a.atr_link_bookmark_link' ).click( function(){
        ezoeLinkAttribute.id = ezoeLinkAttribute.lid( this.id );
        jQuery('div.panel').hide();
        jQuery('#' + ezoeLinkAttribute.box( this.id ) ).show();
        jQuery('#' + ezoeLinkAttribute.box( this.id ) + ' input[type=text]:first').focus();
    });
    jQuery( '#embed_search_go_back_link, #embed_browse_go_back_link, #embed_bookmark_go_back_link' ).click( ezoeLinkAttribute.toggleBack );
});


//custom link generator, to redirect links to browse view if not in browse view
eZOEPopupUtils.settings.browseLinkGenerator = function( n, mode, ed )
{
    if ( n.children_count )
    {
       var tag = document.createElement("a");
       tag.setAttribute('href', 'JavaScript:eZOEPopupUtils.browse(' + n.node_id + ');');
       tag.setAttribute('title', ed.getLang('browse') + ': ' + n.url_alias );
       if ( mode !== 'browse' ) {
           ez.$(tag).addEvent('click', function () {
               jQuery('div.panel').hide();
               jQuery('#browse_box').show();
           });
       }
       return tag;
    }
    var tag = document.createElement("span");
    tag.setAttribute('title', n.url_alias );
    return tag;
};


// override so selected element is redirected to link input
eZOEPopupUtils.selectByEmbedId = function( object_id, node_id, name )
{
    var lid = ezoeLinkAttribute.id, drop = jQuery( '#'+lid+'_source_types'), inp = jQuery( '#'+lid+'_source' ), info = jQuery( '#'+lid+'_source_info' )
    if ( drop.val() === 'ezobject://' )
        inp.val( 'ezobject://' + object_id );
    else
    	inp.val( 'eznode://' + node_id );
    ezoeLinkAttribute.typeSet( inp, drop );
    ezoeLinkAttribute.namePreview( name, lid );
    ezoeLinkAttribute.toggleBack();
    ezoeLinkAttribute.node = {'contentobject_id': object_id, 'node_id': node_id, 'name': name }
};

// misc link related variables and functions
var ezoeLinkAttribute = {
    timeOut : null,
    slides : 0,
    id : null,
    node : {'contentobject_id': '', 'node_id': '', 'name': '' },
    ajaxCheck : function( url, lid )
    {
        var url = tinyMCEPopup.editor.settings.ez_extension_url + '/load/' + url, lid = lid ? lid : ezoeLinkAttribute.lid(this.id);
        jQuery.get( url, {}, function(r){ ezoeLinkAttribute.postBack(r, lid )}, 'json'  );
    },
    postBack : function( r, lid )
    {
        if ( r )
        {
            ezoeLinkAttribute.namePreview( r.name, lid );
            ezoeLinkAttribute.node = r;
        }
        else
            ezoeLinkAttribute.namePreview( false, lid );
    },
    namePreview : function( name, lid )
    {
        var el = document.getElementById( lid + '_source_info' );
{/literal}
        el.innerHTML = name === undefined ? '' : (name ? name : "{'Id not valid!'|i18n('design/standard/ezoe')}" );
{literal}
        el.style.border = name === undefined ? '' : (name ? '1px solid green' : '1px solid red');
    },
    typeSet : function( source, types )
    {
        var selectedValue = '', sourceValue = source.val();
        types.find("option").each( function( i )
        {
            if ( this.value !== '' && sourceValue.indexOf( this.value ) === 0 )
            {
                selectedValue = this.value;
            }
        });
        types.val( selectedValue );
    },
    // get link attributre id from element id
    lid : function( id )
    {
        var arr = id.split('_');
        return arr[0] + '_' + arr[1];
    },
    // get box id by link id
    box : function( id )
    {
        var arr = id.split('_');
        return arr[2] + '_box';
    },
    // Toggle panel back to initial panel
    toggleBack : function()
    {
        ezoeLinkAttribute.id = null;
        jQuery('div.panel').hide();
        jQuery('div.panel:first').show();
        jQuery('div.panel:first input[type=text]:first').focus();
    }
};

{/literal}
</script>

{append-block scope=global variable=$attribute_panel_output}

{include uri="design:ezoe/box_search.tpl"}

{include uri="design:ezoe/box_browse.tpl"}

{include uri="design:ezoe/box_bookmarks.tpl"}

{/append-block}

{/run-once}
