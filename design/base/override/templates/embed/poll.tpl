<div class="view-embed">
    <div class="class-poll">
        <h3>{$object.name}</h3>

        <form method="post" action={"content/action"|ezurl}>
        <input type="hidden" name="ContentNodeID" value="{$object.main_node_id}" />
        <input type="hidden" name="ContentObjectID" value="{$object.id}" />
        <input type="hidden" name="ViewMode" value="full" />

        {attribute_view_gui attribute=$object.data_map.question}

        <input class="button" type="submit" name="ActionCollectInformation" value="Vote" />

        </form>

    </div>
</div>
