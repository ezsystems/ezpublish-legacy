{default content_object=$node.object
         content_version=$node.contentobject_version_object}

<table width="95%" border="0" align="left" cellpadding="0" cellspacing="0" >
<tr> 
    <td valign="top"> 
    <table width="100%" border="0" cellspacing="0" cellpadding="4">
    <tr>
        <td class="header">
	&nbsp;
        {attribute_view_gui attribute=$content_version.data_map.title}
        </td>
    </tr>
    </table>
   </td>
</tr> 
<tr> 
    <td width="100%" valign="top">
    <table width="100%" height="124" border="0" cellpadding="10" cellspacing="0">
    <tr>
        <td valign="top">
        <div class="imageright">
	<a class="small" href={concat("/content/view/full/",$node.node_id,"/")|ezurl}>
        {attribute_view_gui attribute=$content_version.data_map.thumbnail image_class=medium}
	</a>
        </div>
        <span class="small"><i>{$content_object.published|l10n(datetime)}</i></span>
        <br /><br />
        {attribute_view_gui attribute=$content_version.data_map.intro}
        <strong>
        <a class="small" href={concat("/content/view/full/",$node.node_id,"/")|ezurl}>Read more...</a>
        </strong>
        </td>
     </tr>
     </table>
     </td>
</tr>
</table>

<br clear="all" /><br />
{/default}