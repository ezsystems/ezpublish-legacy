<div class="content-view-embeddedmedia">
    <div class="class-image">

    <div class="attribute-image">
    <p>
    {attribute_view_gui attribute=$object.data_map.image image_class=$object_parameters.size href=$link_parameters.href target=$link_parameters.target link_class=$link_parameters.classification link_id=$link_parameters.id}
    </p>
    </div>

    <div class="attribute-caption" style="width: {$object.data_map.image.content[$object_parameters.size].width}px">
        {attribute_view_gui attribute=$object.data_map.caption}
    </div>

    </div>
</div>

