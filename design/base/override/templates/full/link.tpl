{* Link - Full view *}

<div class="content-view-full">
    <div class="class-link">

    <h1>{attribute_view_gui attribute=$node.data_map.name}</h1>

    {section show=$node.data_map.description.content.is_empty|not}
        <div class="attribute-long">
            {attribute_view_gui attribute=$node.data_map.description}
        </div>
    {/section}

    {section show=ne($node.data_map.location.content,'')}
        <div class="attribute-link">
            <p><a href="{$node.data_map.location.content}">{$node.data_map.location.data_text}</a></p>
        </div>
    {/section}

    </div>
</div>