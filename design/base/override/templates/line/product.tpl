{* Product - Line view *}

<div class="view-line">
    <div class="class-product">

        <h2>{$node.name|wash()}</h2>

        <div class="content-short">
           {attribute_view_gui attribute=$node.object.data_map.intro}
        </div>

        <div class="content-price">
         <p>
           {attribute_view_gui attribute=$node.object.data_map.price}
         </p>
        </div>

        <div class="content-link">
           <p>
            <a href={$node.url_alias|ezurl}>More information</a>
           </p>
        </div>
   </div>
</div>