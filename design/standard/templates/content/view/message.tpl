<p class="comment">I don't know anything about the use of this template, and haven't done anything to it yet. BF has said that we should get back to it later. th[eZ]</p>

<h1>{$object.name}</h1>

<table>
<tr>
    <td width="80%" valign="top">

    <table width="100%">
    {section name=ContentObjectAttribute loop=$object.contentobject_attributes}

    <tr>
	<td>
	<i>{$ContentObjectAttribute:item.contentclass_attribute.name}</i>
	</td>
    </tr>
    <tr><td>

    <table cellspacing="0" cellpadding="0"><tr><td>&nbsp;&nbsp;</td><td>
    {attribute_view_gui attribute=$ContentObjectAttribute:item}</td></tr></table>
    
    </td></tr>
    {/section}

    </table>

    </td>
    <td width="20%" valign="top">
    {let related=$object.related_contentobject_array}
      {section show=$related}
        <h3>Related objects</h3>
        <table width="100%" cellspacing="0">
        {section name=Object loop=$related sequence=array(bglight,bgdark)}
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
      {/section}
    {/let}
    </td>
</tr>
</table>
