{* Feedback form - Line view *}

<div class="content-view-line">
    <div class="class-feedback-form">

        <h2>{$node.name|wash()}</h2>

        <div class="attribute-short">
                {attribute_view_gui attribute=$node.object.data_map.description}
        </div>

        <div class="attribute-link">
            <p><a href={$node.url_alias|ezurl}>More...</a></p>
        </div>

    </div>
</div>