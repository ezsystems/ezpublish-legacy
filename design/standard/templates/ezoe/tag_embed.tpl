{set scope=global persistent_variable=hash('title', 'Related content'|i18n('design/standard/ezoe'),
                                           'scripts', array('javascript/ezoe/ez_core.js',
                                                            'javascript/ezoe/ez_core_animation.js',
                                                            'javascript/ezoe/ez_core_accordion.js',
                                                            'javascript/ezoe/popup.js'),
                                           'css', array()
                                           )}

<script type="text/javascript">
<!--
var embedObject = {$embed_data}, defaultEmbedSize = '{$default_size}', selectedSize = defaultEmbedSize;
var contentType = '{$content_type}';
var viewListData = {$view_list}, viewListDataInline = {$view_list_inline};
var classListData = {$class_list}, classListDataInline = {$class_list_inline};

var attributeDefaults = {$attribute_defaults}, attributeDefaultsInline = {$attribute_defaults_inline};

{literal} 
var tinyMCEelement = false, embedImageNode;

function specificTagGenerator()
{
    if ( contentType === 'image' )
        return '<img id="__mce_tmp" src="javascript:;" />';
    return '<div id="__mce_tmp">' + ez.$$('#embed_preview div')[0].el.innerHTML + '</div>';

}

tinyMCEPopup.onInit.add( function(){
    // Initialize page with default values and tabs
    var ed = tinyMCEPopup.editor, el = ed.selection.getNode();
    var sizeSelector = ez.$('embed_size_source'), inline = ez.$('embed_inline_source'), defaults = inline.el.checked ? attributeDefaultsInline : attributeDefaults;
    if ( el && (el.nodeName === 'IMG' || (el.nodeName === 'DIV' && el.className.indexOf('mceNonEditable') !== -1 )) )
    {
        tinyMCEelement = el;
        initCustomAttributeValue( (inline.el.checked ? 'embed-inline_customattributes' : 'embed_customattributes' ), el.getAttribute('customattributes'));
    }
    if ( contentType === 'image' )
    {
        selectedSize = tinyMCEelement.alt || defaultEmbedSize;
        embedImageNode = document.createElement('img');
        loadImageSize( selectedSize )
        embedImageNode.alt = embedObject['name'];
        ez.$('embed_preview').el.appendChild( embedImageNode );
        sizeSelector.el.value = selectedSize;
        sizeSelector.addEvent('change', function(){
            selectedSize = this.el.value;
            loadImageSize( selectedSize );
        });
        var align = tinyMCEelement.align || defaults['align'] || 'right';
        embedImageNode.align = align;
        ez.$('embed_align_source').addEvent('change', function(e, el){ embedImageNode.align = el.value; });
    }
    else
    {
        var align = tinyMCEelement ? ed.dom.getStyle(tinyMCEelement, 'float') || 'center' : defaults['align']  || 'right';
        ez.$('embed_preview').addClass('object_preview').setStyles( ez.ie56 ? {'margin': '0 5px 5px 5px'} : {});
    }
    
    loadInlineDependatSelections( inline.el.checked );

    if ( tinyMCEelement.className )
        ez.$('embed_class_source').el.value = ez.string.trim( tinyMCEelement.className.replace('mceNonEditable', '') );
    else
        ez.$('embed_class_source').el.value = defaults['class'] || '';
    ez.$('embed_view_source').el.value = tinyMCEelement.view || defaults['view'] ||'embed';
    ez.$('embed_align_source').el.value = align === 'center' ? 'middle' : align;
    inline.addEvent('change', function( e, el ){
        loadInlineDependatSelections( el.checked );
    });
    
    if ( contentType !== 'image' )
    {
        inline.addEvent('change', loadEmbedPreview );
        ez.$('embed_align_source').addEvent('change', loadEmbedPreview );
        ez.$('embed_class_source').addEvent('change', loadEmbedPreview );
        ez.$('embed_view_source').addEvent('change', loadEmbedPreview );
        if ( tinyMCEelement )
            ez.$('embed_preview').el.innerHTML = '<div title="' + tinyMCEelement.title + '" style="float:' + align + ';clear:both;text-align: left;">' + tinyMCEelement.innerHTML + '<\/div>';
        else
            loadEmbedPreview();        
    }
    var slides = ez.$$('div.slide'), navigation = ez.$$('#tabs div.tab');
    slides.accordion( navigation, {duration: 150, transition: ez.fx.sinoidal}, {marginLeft: 480, display: 'none'} );

});


function specificTagEditor( el )
{
    if ( contentType !== 'image' )
        el.innerHTML = ez.$$('#embed_preview div')[0].el.innerHTML;
}

{/literal}
// -->
</script>


<div style="width: 470px;">
    <form onsubmit="return insertEmbedTag(  );" action="JavaScript:void(0)" method="post" name="EditForm" id="EditForm" enctype="multipart/form-data"
    style="float:left; width: 940px;">
    
    <div id="tabs">
        <div class="tab"><div>{'Properties'|i18n('design/standard/ezoe')}</div></div>
    {*if $content_type|eq( 'image' )}
        <div class="tab"><div>Crop</div></div>
    {/if*}
    </div>

    <div class="slide">
        <div class="attribute-title">
            <h2 style="padding: 0 0 4px 0;">{$embed_object.name|wash}</h2>
        </div>
        <table class="properties" id="embed_attributes">
        {if $content_type|eq( 'image' )}
            <tr id="embed_size">
                <td class="column1"><label for="embed_size_source">{'Size'|i18n('design/standard/ezoe')}</label></td>
                <td>
                    <select name="embed_size_source" id="embed_size_source">
                    {foreach $size_list as $value => $name}
                        <option value="{$value|wash}">{$name|wash}</option>
                    {/foreach}
                    </select>
                    <input type="hidden" name="embed_view_source" id="embed_view_source" value="" />
                </td>
            </tr>
        {else}
            <tr id="embed_view">
                <td class="column1"><label for="embed_view_source">{'View'|i18n('design/standard/ezoe')}</label></td>
                <td>
                    <input type="hidden" name="embed_size_source" id="embed_size_source" value="" />
                    <select name="embed_view_source" id="embed_view_source">
                    </select>
                </td>
            </tr>
        {/if}
            <tr id="embed_class">
                <td class="column1"><label for="embed_class_source">{'Class'|i18n('design/standard/ezoe')}</label></td>
                <td>
                    <select name="embed_class_source" id="embed_class_source">
                    </select>
                </td>
            </tr>
            <tr id="embed_align">
                <td class="column1"><label for="embed_align_source">{'Align'|i18n('design/standard/ezoe')}</label></td>
                <td>
                    <select name="embed_align_source" id="embed_align_source">
                        <option value="left">{'Left'|i18n('design/standard/ezoe')}</option>
                        <option value="middle">{'Center'|i18n('design/standard/ezoe')}</option>
                        <option value="right">{'Right'|i18n('design/standard/ezoe')}</option>
                    </select>
                </td>
            </tr>
            <tr id="embed_inline">
                <td class="column1"><label for="embed_inline_source">{'Inline'|i18n('design/standard/ezoe')}</label></td>
                <td>
                    <input type="checkbox" id="embed_inline_source" name="embed_inline_source" value="true"{if $embed_inline} checked="checked"{/if} />
                </td>
            </tr>
        </table>

        {include uri="design:ezoe/customattributes.tpl" tag_name="embed" hide=$embed_inline}
        {include uri="design:ezoe/customattributes.tpl" tag_name="embed-inline" hide=$embed_inline|not}

        <div class="block"> 
            <div class="left">
                <input id="SaveButton" name="SaveButton" type="submit" value="{'OK'|i18n('design/standard/ezoe')}" />
                <input id="CancelButton" name="CancelButton" type="reset" value="{'Cancel'|i18n('design/standard/ezoe')}" onclick="cancelAction();" />
                <!-- todo: upload new button / link / tab -->
            </div> 
        </div>

        <h4 id="embed_preview_heading">{'Preview'|i18n('design/standard/node/view')}:</h4>
        <div id="embed_preview">
        </div>
    </div>

    <!-- div class="slide" id="crop_container" style="display: none;">
    </div -->

    </form>
</div>