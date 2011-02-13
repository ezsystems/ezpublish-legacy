{* File - Line view *}

<div class="content-view-line">
    <div class="class-file">
    <h2>{$node.name|wash}</h2>

    <div class="attribute-short">
        {attribute_view_gui attribute=$node.data_map.description}
    </div>
    <div class="break"></div>
    <div class="attribute-file">
        <p>{attribute_view_gui attribute=$node.data_map.file icon_size='small' icon_title=$node.name}</p>
    </div>

    <div class="attribute-link">
        <p><a href={$node.url_alias|ezurl}>{'Details...'|i18n( 'design/base' )}</a></p>
    </div>

    </div>
</div>
