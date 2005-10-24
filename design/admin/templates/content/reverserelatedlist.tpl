<div class="context-block">
{* DESIGN: Header START *}
<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">
{'"%contentObjectName" [%children_count]: Sub items that are used by other objects '|i18n( 'design/admin/content/reverserelatedlist',,
                hash( '%contentObjectName', $content_object_name ,
                      '%children_count', $reverse_list_children_count ) )}
</h2>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=and( is_set( $children_list ), $children_list )}
	{include uri='design:content/children_reverserelatedlist.tpl'}
{section-else}
     <p>{'There are no items that are used by other objects.'|i18n( 'design/admin/content/reverserelatedlist' )}</p>
{/section}

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}
<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>


