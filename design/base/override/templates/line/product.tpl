{* Product - Line view *}

<div class="view-line">
    <div class="class-product">

        <h2>{$node.name|wash()}</h2>

        <div class="content-short">
           {attribute_view_gui attribute=$node.object.data_map.intro}
        </div>

        <div class="content-price">
           {attribute_view_gui attribute=$node.object.data_map.price}
        </div>

        <div class="content-link">
            <a href={$node.url_alias|ezurl}>Details</a>
        </div>
   </div>
</div>