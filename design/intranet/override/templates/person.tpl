{default content_object=$node.object
         content_version=$node.contentobject_version_object}

<div class="maincontentheader">
    <h1>{$node.name}</h1>
</div>

<div class="imageright">
    {attribute_view_gui attribute=$content_version.data_map.picture image_class=medium}
</div>

<div class="text">
<b>Last Name:</b> {attribute_view_gui attribute=$content_version.data_map.last_name}
</div>
<div class="text">
<b>First Name:</b> {attribute_view_gui attribute=$content_version.data_map.first_name}
</div>

<div class="text">
<b>Position:</b> {attribute_view_gui attribute=$content_version.data_map.position}
</div>

<div class="contact">
{attribute_view_gui attribute=$content_version.data_map.person_numbers}
</div>

{/default}
