{let image_node=$node.parent}
<div id="comment">

    {section show=$is_preview}
    <div id="image">
        <div class="maincontentheader">
            <h1>Comment on image {$image_node.name|wash}</h1>
        </div>

        {attribute_view_gui attribute=$image_node.data_map.image image_class=small}
        <p>
            {attribute_view_gui attribute=$image_node.object.data_map.caption}
        </p>
    </div>
    {/section}


    {section}
    <div id="commentlist">
    {/section}

    <h2>{$node.name|wash}</h2>
    {$node.object.data_map.name.content|wash()}
    <p>
        {$node.object.data_map.comment.content|wash()|nl2br()|wordtoimage()|autolink()}
    </p>

    {section}
    </div>
    {/section}

</div>
{/let}
