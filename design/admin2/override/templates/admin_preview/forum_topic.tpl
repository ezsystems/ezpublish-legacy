{* Forum topic - Admin preview *}

<div class="content-view-full">
    <div class="class-forum_topic">

    <h1>{$node.name|wash}</h1>

    <div class="attribute-long">
    {attribute_view_gui attribute=$node.data_map.message}
    </div>

    <div class="content-control">
        <label>Sticky:</label>
        {attribute_view_gui attribute=$node.data_map.sticky}
        </div>
    </div>

    </div>
</div>
