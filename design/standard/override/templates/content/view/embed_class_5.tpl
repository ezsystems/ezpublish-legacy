{default attribute_parameters=array()}
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
{/default}