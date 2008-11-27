{set scope=global persistent_variable=hash('title', 'Related content'|i18n('design/standard/ezoe'),
                                           'scripts', array('javascript/ezoe/ez_core.js',
                                                            'javascript/ezoe/ez_core_animation.js',
                                                            'javascript/ezoe/ez_core_accordion.js',
                                                            'javascript/ezoe/popup_utils.js'),
                                           'css', array()
                                           )}

<script type="text/javascript">
<!--

eZOEPopupUtils.embedObject = {$embed_data};
eZOEPopupUtils.settings.customAttributeStyleMap = {$custom_attribute_style_map};
var defaultEmbedSize = '{$default_size}', selectedSize = defaultEmbedSize, contentType = '{$content_type}', attachmentIcon = {"tango/mail-attachment32.png"|ezimage};
var viewListData = {$view_list}, classListData = {$class_list}, attributeDefaults = {$attribute_defaults}, selectedTagName = '', compatibilityMode = '{$compatibility_mode}';

{literal}

tinyMCEPopup.onInit.add( ez.fn.bind( eZOEPopupUtils.init, window, {
    tagName: 'embed',
    form: 'EditForm',
    cancelButton: 'CancelButton',
    cssClass: compatibilityMode !== 'enabled' ? 'mceNonEditable mceItemContentTypeObjects' : '',
    onInitDone: function( el, tag, ed )
    {        
        var selectors = ez.$('embed_alt_source', 'embed_align_source', 'embed_class_source', 'embed_view_source', 'embed_inline_source');
        var tag = selectors[4].el.checked ? 'embed-inline' : 'embed', def = attributeDefaults[ tag ]
        inlineSelectorChange.call( selectors[4], false, selectors[4].el  );
        selectors[4].addEvent('click', inlineSelectorChange );
        var align = el ? el.getAttribute('align') || '' : def['align']  || '';
        if ( align === 'center' ) align = 'middle';

        ez.$('embed_preview').addClass('object_preview float-break').setStyles( ez.ie56 ? {'margin': '0 5px 5px 5px'} : {});
        selectors[1].el.value = align;
        selectors.callEach('addEvent', 'change', loadEmbedPreview );

        if ( el && el.nodeName !== 'IMG' )//&& el.id.split('_')[1] == eZOEPopupUtils.embedObject.id )
            ez.$('embed_preview').el.innerHTML = el.innerHTML;
        else
            loadEmbedPreview();

        var slides = ez.$$('div.panel'), navigation = ez.$$('#tabs li.tab');
        slides.accordion( navigation, {duration: 100, transition: ez.fx.sinoidal, accordionAutoFocusTag: 'input[type=text]'}, {opacity: 0, display: 'none'} );
    },
    tagGenerator: function( tag, customTag )
    {
        if ( contentType === 'images' || compatibilityMode === 'enabled' )
            return '<img id="__mce_tmp" src="javascript:void(0);" />';
        if ( ez.$('embed_inline_source').el.checked )
           return '<span id="__mce_tmp"></span>';
        return '<div id="__mce_tmp"></div>';
    },
    onTagGenerated:  function( el, ed, args )
    {
        // append a paragraph if user just inserted a embed tag in editor and it's the last tag
        var edBody = el.parentNode, doc = ed.getDoc();
        if ( edBody.nodeName !== 'BODY' )
        {
            el = edBody;
            edBody = edBody.parentNode
        }
        if ( edBody.nodeName === 'BODY'
        && edBody.childNodes.length <= (ez.array.indexOf( edBody.childNodes, el ) +1) )
        {
            var p = doc.createElement('p');
            p.innerHTML = ed.isIE ? '&nbsp;' : '<br />';
            edBody.appendChild( p );
        }
    },
    tagAttributeEditor: function( ed, el, args )
    {
        //args['id'] = 'eZObject_' + eZOEPopupUtils.embedObject['contentobject_id'];
        args['inline'] = ez.$('embed_inline_source').el.checked ? 'true' : 'false';
        el = eZOEPopupUtils.switchTagTypeIfNeeded( el, (contentType === 'images' || compatibilityMode === 'enabled' ? 'img' : (args['inline'] === 'true' ? 'span' : 'div') ) );
        if ( compatibilityMode === 'enabled' )
        {
            args['src'] = attachmentIcon;
            args['width'] = args['height'] = 32;
        }
        else
        {
            ed.dom.setHTML( el, ez.$('embed_preview').el.innerHTML );
            //ed.dom.setStyle(el, 'float', args['align'] === 'middle' ? '' : args['align']);
        }
        args['title']   = eZOEPopupUtils.safeHtml( eZOEPopupUtils.embedObject['name'] );
        ed.dom.setAttribs( el, args );
    }
}));


function inlineSelectorChange( e, el )
{
    // toogles data when the user clicks inline, since
    // embed and embed-inline have different settings
    var viewList = ez.$('embed_view_source'), classList = ez.$('embed_class_source'), inline = el.checked;
    var tag = inline ? 'embed-inline' : 'embed', editorEl = eZOEPopupUtils.settings.editorElement, def = attributeDefaults[ tag ];
    if ( tag === selectedTagName ) return;
    selectedTagName = tag;
    eZOEPopupUtils.settings.selectedTag = tag;
    eZOEPopupUtils.removeChildren( viewList.el );
    eZOEPopupUtils.removeChildren( classList.el );
    eZOEPopupUtils.addSelectOptions( viewList.el, viewListData[ tag ] );
    eZOEPopupUtils.addSelectOptions( classList.el, classListData[ tag ] );
    ez.$( inline ? 'embed_customattributes' : 'embed-inline_customattributes' ).hide();
    ez.$( !inline ? 'embed_customattributes' : 'embed-inline_customattributes' ).show();

    if ( editorEl )
    {
        var viewValue = editorEl.getAttribute('view');
        var classValue = ez.string.trim( editorEl.className.replace(/(webkit-[\w\-]+|Apple-[\w\-]+|mceItem\w+|mceVisualAid|mceNonEditable)/g, '') );
    }

    if ( viewValue && viewListData[ tag ].join !== undefined && (' ' + viewListData[ tag ].join(' ') + ' ').indexOf( ' ' + viewValue + ' ' ) !== -1 )
        viewList.el.value = viewValue;
    else if ( def['view'] !== undefined )
        viewList.el.value = def['view'];

    if ( classValue && classListData[ tag ][ classValue ] !== undefined )
        classList.el.value = classValue;
    else if ( def['class'] !== undefined )
        classList.el.value = def['class'];
    
    if ( tinymce.isIE && contentType !== 'images' && e !== false )
        loadEmbedPreview();
}


function setEmbedAlign( e, el )
{
    ez.$('embed_preview_image').el.align = el.value;
}

function loadImageSize( e, el )
{
    // Dynamically loads image sizes as they are requested
    // global objects: ez
    var imageAttributes = eZOEPopupUtils.embedObject['image_attributes'], previewImageNode = ez.$('embed_preview_image'), eds = tinyMCEPopup.editor.settings;
    if ( !imageAttributes || !eZOEPopupUtils.embedObject['data_map'][ imageAttributes[0] ] )
    {
        previewImageNode.el.src = attachmentIcon;
        return;
    }
    var attribObj = eZOEPopupUtils.embedObject['data_map'][ imageAttributes[0] ]['content'] || false, size = el.value;
    if ( !attribObj || !previewImageNode || !attribObj['original']['url'] )
    {
        // Image attribute or node missing
    }
    else if ( attribObj[size] )
    {
        previewImageNode.el.src = eds.ez_root_url + attribObj[size]['url'];
        tinyMCEPopup.resizeToInnerSize();
    }
    else
    {
        var url = eds.ez_extension_url + '/load/' + eZOEPopupUtils.embedObject['contentobject_id'];
        eZOEPopupUtils.ajax.load( url, 'imagePreGenerateSizes=' + size, function(r){
            ez.script( 'eZOEPopupUtils.ajaxLoadResponse=' + r.responseText );
            if ( eZOEPopupUtils.ajaxLoadResponse )
            {
                var size = ez.$('embed_alt_source').el.value, imageAttributes = eZOEPopupUtils.embedObject['image_attributes'];
                eZOEPopupUtils.embedObject['data_map'][ imageAttributes[0] ]['content'][ size ] = eZOEPopupUtils.ajaxLoadResponse['data_map'][ imageAttributes[0] ]['content'][ size ];
                previewImageNode.el.src = eds.ez_root_url + eZOEPopupUtils.embedObject['data_map'][ imageAttributes[0] ]['content'][ size ]['url'];
            }
        });
    }
}

function loadEmbedPreview( )
{
    // Dynamically loads embed preview when attributes change
    // global objects: ez         
    var url = tinyMCEPopup.editor.settings.ez_extension_url + '/embed_view/' + eZOEPopupUtils.embedObject['contentobject_id'];
    var postData = ez.$$('#embed_attributes input,#embed_attributes select').callEach('postData').join('&');
    eZOEPopupUtils.ajax.load( url, postData, function(r)
    {
        ez.$('embed_preview').el.innerHTML = r.responseText;
    });
}

// -->
</script>
{/literal}

<div class="tag-view tag-type-{$tag_name} embed-view embed-class-{$embed_object.class_identifier}  embed-content-type-{$content_type}">

    <form action="JavaScript:void(0)" method="post" name="EditForm" id="EditForm" enctype="multipart/form-data">

        <div id="tabs" class="tabs">
        <ul>
            <li class="tab"><span><a href="JavaScript:void(0);">{'Properties'|i18n('design/standard/ezoe')}</a></span></li>
            {*if $content_type|eq( 'images' )}
            <li class="tab"><span><a href="JavaScript:void(0);">{'Crop'|i18n('design/standard/ezoe')}</a></span></li>
            {/if*}
        </ul>
        </div>

<div class="panel_wrapper" style="height: auto;">
    <div class="panel">
        <div class="attribute-title">
            <h2 style="padding: 0 0 4px 0;">{$embed_object.name|wash}</h2>
        </div>
        <table class="properties" id="embed_attributes">
            <tr id="embed_id">
                <td class="column1"><label for="embed_alt_source">{'Relation'|i18n('design/standard/ezoe')}</label></td>
                <td>
                    <select name="id" id="embed_id_source">
                    <option value="eZObject_{$embed_object.id}"{if $embed_type|eq( 'eZObject' )} selected="selected"{/if}>
                        {'Object'|i18n('design/standard/ezoe')}: {$embed_object.name|wash}
                    </option>
                    {foreach $embed_object.assigned_nodes as $assigned_node}
                    <option value="eZNode_{$assigned_node.node_id}"{if and($embed_type|eq( 'eZNode' ), $assigned_node.node_id|eq( $embed_id ))} selected="selected"{/if}>
                        {'Node'|i18n('design/standard/ezoe')}: {$assigned_node.path_identification_string}
                    </option>
                    {/foreach}
                    </select>
                </td>
            </tr>
            <tr id="embed_view">
                <td class="column1"><label for="embed_view_source">{'View'|i18n('design/standard/ezoe')}</label></td>
                <td>
                    <input type="hidden" name="alt" id="embed_alt_source" value="" />
                    <select name="view" id="embed_view_source">
                    </select>
                </td>
            </tr>
            <tr id="embed_class">
                <td class="column1"><label for="embed_class_source">{'Class'|i18n('design/standard/ezoe')}</label></td>
                <td>
                    <select name="class" id="embed_class_source">
                    </select>
                </td>
            </tr>
            <tr id="embed_align">
                <td class="column1"><label for="embed_align_source">{'Align'|i18n('design/standard/ezoe')}</label></td>
                <td>
                    <select name="align" id="embed_align_source">
                        <option value="">{'None'|i18n('design/standard/ezoe')}</option>
                        <option value="left">{'Left'|i18n('design/standard/ezoe')}</option>
                        <option value="middle">{'Center'|i18n('design/standard/ezoe')}</option>
                        <option value="right">{'Right'|i18n('design/standard/ezoe')}</option>
                    </select>
                </td>
            </tr>
            <tr id="embed_inline">
                <td class="column1"><label for="embed_inline_source">{'Inline'|i18n('design/standard/ezoe')}</label></td>
                <td>
                    <input type="checkbox" class="input_noborder" id="embed_inline_source" name="inline" value="true"{*if $tag_name|eq('embed-inline')} checked="checked"{/if*} />
                </td>
            </tr>
        </table>

        {include uri="design:ezoe/customattributes.tpl" tag_name="embed" hide=$tag_name|ne('embed') custom_attributes=$custom_attributes.embed}
        {include uri="design:ezoe/customattributes.tpl" tag_name="embed-inline" hide=$tag_name|ne('embed-inline') custom_attributes=$custom_attributes.embed-inline}

        <div class="block"> 
            <div class="left">
                <input id="SaveButton" name="SaveButton" type="submit" value="{'OK'|i18n('design/standard/ezoe')}" />
                <input id="CancelButton" name="CancelButton" type="reset" value="{'Cancel'|i18n('design/standard/ezoe')}" />
            </div>
            <div class="right" style="text-align: right;">
                <a id="embed_switch_link" href={concat( 'ezoe/upload/', $object_id,'/', $object_version,'/', $content_type )|ezurl}>
                    {'Switch embed object'|i18n('design/standard/ezoe')}
                </a>
            </div>
        </div>

        <div class="block">
            <h4 id="embed_preview_heading">{'Preview'|i18n('design/standard/node/view')}:</h4>
            <div id="embed_preview">
            </div>
        </div>
    </div>

    <!-- div class="panel" id="crop_container" style="display: none;">
    </div -->
</div>
    </form>
</div>