{* Article - Line view *}
<div class="content-view-line">
    <div class="class-article">

    {section show=$node.object.data_map.image.content}
        <div class="content-image">
            {attribute_view_gui alignment=right image_class=small attribute=$node.object.data_map.image.content.data_map.image}
        </div>
    {/section}

    <h2><a href={$node.url_alias|ezurl}>{$node.object.data_map.title.content|wash}</a></h2>

    {section show=$node.object.data_map.intro.content.is_empty|not}
    <div class="attribute-short">
        {attribute_view_gui attribute=$node.object.data_map.intro}
    </div>
    {/section}

    {section show=$node.object.data_map.body.content.is_empty|not}
        <div class="attribute-link">
            <p><a href={$node.url_alias|ezurl}>Read more...</a></p>
        </div>
    {/section}

    </div>
</div>