<p class="box">
{switch name=sw match=$attribute.content}
   {case match=0}
   {"No relation"|i18n("design/standard/content/datatype")}
   {/case}
   {case}
   <a href={concat("content/view/",$attribute.content.main_node_id,"/")|ezurl}>{$attribute.content.name|wash(xhtml)}</a>
   {/case}
{/switch}
</p>
