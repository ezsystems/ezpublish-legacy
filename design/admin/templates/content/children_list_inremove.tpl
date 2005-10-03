{* DESIGN: Header START *}
<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

{let item_type=ezpreference( 'remove_children_list_limit' )
     number_of_items=min( $item_type, 3)|choose( 10, 10, 25, 50 ) }

<h2 class="context-title">

&nbsp;{'Sub items [%children_count]'|i18n( 'design/admin/node/view/full',, hash( '%children_count', $reverse_list_count_children_array_count ) )}</h2>

{* DESIGN: Subline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>
{* Items per page and view mode selector. *}
<div class="context-toolbar">
<div class="block">
<div class="left">
    <p>
    {switch match=$number_of_items}
    {case match=25}
        <a href={'/user/preferences/set/remove_children_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
        <span class="current">25</span>
        <a href={'/user/preferences/set/remove_children_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>

        {/case}

        {case match=50}
        <a href={'/user/preferences/set/remove_children_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
        <a href={'/user/preferences/set/remove_children_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
        <span class="current">50</span>
        {/case}

        {case}
        <span class="current">10</span>
        <a href={'/user/preferences/set/remove_children_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
        <a href={'/user/preferences/set/remove_children_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>
        {/case}

        {/switch}
    </p>
</div>

<div class="break"></div>

</div>
</div>

{* DESIGN: Content START *}
<div class="box-ml"><div class="box-mr">
<div class="content-navigation-childlist-remove">
    <table class="list" cellspacing="0">
    <tr>
	{* Item column *}
        <th colspan="2">{'Item'|i18n( 'design/admin/node/removeobject' )}</th>
        {* Class type column *}
        <th>{'Type'|i18n( 'design/admin/node/removeobject' )}</th>
        <th>{'Objects referring to this one'|i18n( 'design/admin/node/removeobject' )}</th>
    </tr>

{section var=children_item loop=$children_list sequence=array( bglight, bgdark )}
<tr class="{$children_item.sequence}">
    {* Object icon. *}
    <td class="tight">
         {$children_item.object.class_identifier|class_icon( small, $children_item.object.class_name|wash )}
    </td>

    {* Location. *}
    <td>
    {section show=$reverse_list_count_children_array[$children_item.object.id]|gt( 0 )}	
        <a href={concat( '/content/reverseobjects/', $children_item.object.id, '/' )|ezurl}>
    {/section}
	{section var=path_node loop=$children_item.path|append( $children_item )}
              {$path_node.name|wash}
              {delimiter} / {/delimiter}
        {/section}
    {section show=$reverse_list_count_array[$children_item.object.id]|gt( 0 )}	
          </a>
    {/section} 		
    </td>

    {* Type. *}
    <td>
    {$children_item.object.class_name|wash}
     </td>

    {* Objects referring to this one. *}
    <td>
      {$reverse_list_count_children_array[$children_item.object.id]}
      {section show=$reverse_list_count_children_array[$children_item.object.id]|gt( 0 )}
        ( <a href={concat( '/content/reverseobjects/', $children_item.object.id, '/' )|ezurl}>list</a> )
      {/section}
    </td>

</tr>
{/section}

</table>
</div>
	    {include name=navigator
                     uri='design:navigator/google.tpl'
                     page_uri='/content/removeobject/'
		     item_count=$children_count
                     view_parameters=$view_parameters
                     item_limit=$number_of_items}
		     {*$reverse_list_count_children_array_count*}


{* DESIGN: Content END *}
{/let}</div></div>
