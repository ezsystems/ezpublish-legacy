<table width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td>
{*	{$object.name|texttoimage("hatten",45,0,,,-1,-3)}*}
 	<h3>{$object.name}</h3>
	</td>
</tr>
</table>

<table width="100%">
<tr>
    <td width="80%" valign="top">
    <table width="100%">
    {section name=ContentObjectAttribute loop=$object.contentobject_attributes}
    <tr>
	<td>
	<b>
	{$ContentObjectAttribute:item.contentclass_attribute.name}
	</b><br />
	{attribute_view_gui attribute=$ContentObjectAttribute:item}
	</td>
    </tr>
    {/section}
    </table>
    </td>
    <td width="20%" valign="top">
    <h3>Related objects</h3>
    <table width="100%" cellspacing="0">
    {section name=Object loop=$related_contentobject_array show=$related_contentobject_array sequence=array(bglight,bgdark)}
    <tr>
	<td class="{$Object:sequence}">
	{content_view_gui view=line content_object=$Object:item}
	</td>
    </tr>
    {section-else}
    <tr>
	<td class="{$Object:sequence}">
	None
	</td>
    </tr>
    {/section}
   </table>
   </td>
</tr>
</table>
