<div class="content-view-embeddedmedia">
    <div class="class-image">

    <div class="attribute-image">
    {let href="" target="_self"}
        {section show=is_set($object_parameters.href)}
            {set href=$object_parameters.href}
        {/section}
        {section show=is_set($object_parameters.target)}
            {set target=$object_parameters.target}
        {/section}
        <p>{attribute_view_gui attribute=$object.data_map.image image_class=$object_parameters.size href=$href target=$target}</p>
    {/let}
    </div>

    <div class="attribute-caption" style="width: {$object.data_map.image.content[$object_parameters.size].width}px">
        {attribute_view_gui attribute=$object.data_map.caption}
    </div>

    </div>
</div>

