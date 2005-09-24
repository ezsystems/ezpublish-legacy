<div class="context-block">

{* DESIGN: Header START *}
<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">
{'Objects referring to %contentObjectName'|i18n( 'design/admin/content/view/viewreverseobjects',,
                hash( '%contentObjectName', $content_object_name ) )}
</h2>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$reverse_related_object_count|eq( 0 )}
<div class="block">
    <p>{'There are no objects referring to this one.'|i18n( 'design/admin/content/view/viewreverseobjects' )}</p>
</div>
{/section}

<table class="list" cellspacing="0">
<tr>
    <th colspan="2">{'Item'|i18n( 'design/admin/node/removeobject' )}</th>
    <th>{'Type'|i18n( 'design/admin/node/removeobject' )}</th>
</tr>

{section var=reverse_item loop=$reverse_related_object_list sequence=array( bglight, bgdark )}

  <tr class="{$reverse_item.sequence}">
    {* Object icon. *}

    <td class="tight">{$reverse_item.main_node.class_identifier|class_icon( small, $reverse_item.main_node.class_name|wash )}
    </td>

    {* Location. *}
    <td>
	    <a href={$reverse_item.main_node.url_alias|ezurl}>{$reverse_item.name|wash}</a>
    </td>

    {* Type. *}
    <td>
	{$reverse_item.main_node.object.class_name|wash}
    </td>
  </tr>
{/section}

</table>


{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}
<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<form name="back" method="post" action={'content/viewreverseobjects'|ezurl}>
<div class="block">
	<input class="button" type="submit" name="BackButton" value="{'Back'|i18n( 'design/admin/content/view/viewreverseobjects' )}" />
</div>
</form>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>


