{* Article - List item view *}
<div class="content-view-embed">
    <div class="class-article">

     <h2>{$object.data_map.title.content|wash}</h2>

     <div class="embedded-content">
    {section show=$object.data_map.image.content}
        <div class="attribute-image">
            {attribute_view_gui alignment=right image_class=small attribute=$object.data_map.image.content.data_map.image}
        </div>
    {/section}

    <div class="attribute-short">
        {attribute_view_gui attribute=$object.data_map.intro}
    </div>

    {section show=$object.data_map.body.content.is_empty|not}
        <div class="attribute-link">
            <p><a href={$object.main_node.url_alias|ezurl}>Read more...</a></p>
        </div>
    {/section}
    </div>
    </div>
</div>
