{section show=$attribute.content}
   <p class="box">{content_view_gui view=text_linked content_object=$attribute.content}</p>
{section-else}
   <p class="box">{"No relation"|i18n("design/standard/content/datatype")}</p>
{/section}