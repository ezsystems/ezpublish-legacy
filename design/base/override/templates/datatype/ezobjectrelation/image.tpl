{section show=$attribute.content}
<div class="imageright"> 
    {*   <p class="box">{content_view_gui view=text_linked content_object=$attribute.content}</p> *}
    {* attribute_view_gui attribute=$attribute.content.data_map.image alignment=$align *}
    <div class="imageobject">
    {attribute_view_gui attribute=$attribute.content.data_map.image}
    </div>

    <div class="imagecaption">
    {attribute_view_gui attribute=$attribute.content.data_map.caption}
    </div>

</div>

{* {section-else}
   <p class="box">{"No relation"|i18n("design/standard/content/datatype")}</p> *}
{/section}