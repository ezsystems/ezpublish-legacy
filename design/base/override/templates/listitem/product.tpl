{* Product - List item view *}
<div class="view-listitem">
    <div class="class-product">

        <a href={$node.url_alias|ezurl}><h3>{$node.name|wash()}</h3></a>

        <div class="content-short">
           {attribute_view_gui attribute=$node.object.data_map.intro}
        </div>

        <div class="content-price">
           <p>
           {attribute_view_gui attribute=$node.object.data_map.price}
           </p>
        </div>
   </div>
</div>
