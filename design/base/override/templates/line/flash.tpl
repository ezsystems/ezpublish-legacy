{* Flash - Line view *}

<div class="content-view-line">
    <div class="class-flash">

    <h2>{$node.name|wash}</h2>

    <div class="attribute-short">
        {attribute_view_gui attribute=$node.data_map.description}
    </div>

    <div class="attribute-link">
        <p><a href={$node.url_alias|ezurl}>{"View flash"|i18n("design/base")}</a></p>
    </div>

    </div>
</div>