{set scope=global persistent_variable=hash('title', 'Link properties'|i18n('design/standard/ezoe'),
                                           'scripts', array('javascript/ezoe/ez_core.js',
                                                            'javascript/ezoe/ez_core_animation.js',
                                                            'javascript/ezoe/ez_core_accordion.js',
                                                            'javascript/ezoe/popup_utils.js'),
                                           'css', array()
                                           )}

<script type="text/javascript">
<!--

var ezTagName = '{$tag_name|wash}', ezoeLinkTimeOut = null; 
{literal} 

tinyMCEPopup.onInit.add( ez.fn.bind( eZOEPopupUtils.init, window, {
    tagName: ezTagName,
    form: 'EditForm',
    cancelButton: 'CancelButton',
    onInit: function()
    {
        var linkTypes = ez.$('link_href_source_types'), linkSource = ez.$('link_href_source');
        linkTypes.addEvent('change', function( e, el ){
            ez.$('link_href_source').el.value = el.value;
        });
        
        // add event to href input to lookup name on object or nodes
        linkSource.addEvent('keyup', function( e, el ){
            e = e || window.event;
            var c = e.keyCode || e.which;
            clearTimeout( ezoeLinkTimeOut );
        
            // break if user is pressing arrow keys
            if ( c > 36 && c < 41 ) return true;
    
            ezoeLinkTypeSet( linkSource, linkTypes );
    
            if ( el.value.indexOf( '://' ) === -1 ) return true;
    
            var url = el.value.split('://'), id = ez.num( url[1], 0, 'int' );
    
            if ( id === 0 || ( url[0] !== 'eznode' && url[0] !== 'ezobject' ) ) return true;
    
            ezoeLinkTimeOut = setTimeout( ez.fn.bind( ezoeLinkAjaxCheck, this, url.join('_') ), 320 );
            return true;
        });
        ezoeLinkTypeSet( linkSource, linkTypes );
    }
}));


function ezoeLinkAjaxCheck( url )
{
    var url = eZOeMCE['extension_url'] + '/load/' + url;
    eZOEPopupUtils.ajax.load( url, '', ezoeLinkPostBack );
}

function ezoeLinkPostBack( r )
{
    ez.script( 'eZOEPopupUtils.ajaxLoadResponse=' + r.responseText );
    var info = ez.$('link_href_source_info'), input= ez.$('link_href_source');
    if ( eZOEPopupUtils.ajaxLoadResponse )
    {
        info.el.innerHTML = eZOEPopupUtils.ajaxLoadResponse.name;
        info.el.style.border = '1px solid green';
    }
    else
    {
        info.el.innerHTML = 'Id not valid!';
        info.el.style.border = '1px solid red';
    }
}

function ezoeLinkTypeSet( source, types )
{
     if ( source.el.value.indexOf('eznode://') === 0 )
        types.el.value = 'eznode://';
    else if ( source.el.value.indexOf('ezobject://') === 0 )
        types.el.value = 'ezobject://';
    else if ( source.el.value.indexOf('file://') === 0 )
        types.el.value = 'file://';
    else if ( source.el.value.indexOf('ftp://') === 0 )
        types.el.value = 'ftp://';
    else if ( source.el.value.indexOf('http://') === 0 )
        types.el.value = 'http://';
    else if ( source.el.value.indexOf('https://') === 0 )
        types.el.value = 'https://';
    else if ( source.el.value.indexOf('mailto:') === 0 )
        types.el.value = 'mailto:';
    else
        types.el.value = '';
}

{/literal}

// -->
</script>


<div>

    <form action="JavaScript:void(0)" method="post" name="EditForm" id="EditForm" enctype="multipart/form-data" style="width: 360px;">
    

    <div class="slide" style="width: 360px;">
        <div class="attribute-title">
            <h2 style="padding: 0 0 4px 0;">{$tag_name|upfirst|wash}</h2>
        </div>
        {set-block variable=$link_href_types}
            <select id="link_href_source_types" class="mceItemSkip">
                <option value="">Other</option>
                <option value="eznode://">eznode</option>
                <option value="ezobject://">ezobject</option>
                <option value="ftp://">Ftp</option>
                <option value="file://">File</option>
                <option value="http://">Http</option>
                <option value="https://">Https</option>
                <option value="mailto:">Mail</option>
            </select>
            <span id="link_href_source_info"></span>
            <br />
        {/set-block}
        
        {include uri="design:ezoe/generalattributes.tpl"
                 tag_name=$tag_name
                 attributes=hash('href', '',
                                  'view', ezini( 'LinkViewModeSettings', 'AvailableViewModes', 'site.ini' ),
                                  'target', hash('0', 'None', '_blank', 'New Window'),
                                  'class', $class_list,
                                  'title', '',
                                  'id', ''
                                 )
                 attribute_content_prepend=hash('href', $link_href_types)
        }

        {include uri="design:ezoe/customattributes.tpl" tag_name=$tag_name}

        <div class="block"> 
            <div class="left">
                <input id="SaveButton" name="SaveButton" type="submit" value="{'OK'|i18n('design/standard/ezoe')}" />
                <input id="CancelButton" name="CancelButton" type="reset" value="{'Cancel'|i18n('design/standard/ezoe')}" />
            </div> 
        </div>

    </div>
    </form>

</div>