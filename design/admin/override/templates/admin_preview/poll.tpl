{* Poll - Admin preview *}

<div class="content-view-full">
    <div class="class-poll">

        <h1>{$node.name|wash()}</h1>

        <div class="attribute-short">
        {attribute_view_gui attribute=$node.data_map.description}
        </div>

        <div class="content-question">
        {attribute_view_gui attribute=$node.data_map.question}
        </div>

        <div class="attribute-link">
        <p><a href={concat( '/content/collectedinfo/', $node.node_id, '/' )|ezurl}>{'Result'|i18n( 'design/admin/preview/poll' )}</a></p>
        </div>

    </div>
</div>
