{default attribute_parameters=hash(alignment,center)
         border_size=0}

{section show=and(first_set($object.data_map.image.content,true()),first_set($object.data_map.image.content.contentobject_attribute_id,false()))}
{switch match=$attribute_parameters[0].alignment}
{case match="left"}
<div class="imageleft">

<img src="{$attribute_parameters[0].src}" border="{$border_size}" /><br />

{attribute_view_gui attribute=$object.data_map.caption}
</div>
{/case}
{case match="right"}
<div class="imageright">

<img src="{$attribute_parameters[0].src}" border="{$border_size}" /><br />

{attribute_view_gui attribute=$object.data_map.caption}
</div>
{/case}
{case}
<div class="imagecenter">

<img src="{$attribute_parameters[0].src}" border="{$border_size}" /><br />

{attribute_view_gui attribute=$object.data_map.caption}
</div>
{/case}
{/switch}

{section-else}
<div class="imagecenter">
{attribute_view_gui attribute=$object.data_map.caption}
</div>
{/section}

{/default}