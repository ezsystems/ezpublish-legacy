{let item_type=ezpreference( 'reverse_children_list_limit' )
     number_of_items=min( $item_type, 3)|choose( 10, 10, 25, 50 ) }

{* DESIGN: Subline *}
<div class="context-toolbar">
<div class="block">
<div class="left">
    <p>
    {switch match=$number_of_items}
    {case match=25}
        <a href={'/user/preferences/set/reverse_children_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
        <span class="current">25</span>
        <a href={'/user/preferences/set/reverse_children_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>

        {/case}

        {case match=50}
        <a href={'/user/preferences/set/reverse_children_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
        <a href={'/user/preferences/set/reverse_children_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
        <span class="current">50</span>
        {/case}

        {case}
        <span class="current">10</span>
        <a href={'/user/preferences/set/reverse_children_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
        <a href={'/user/preferences/set/reverse_children_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>
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
        <th colspan="2">{'Item'|i18n( 'design/admin/content/children_reverserelatedlist' )}</th>
        {* Class type column *}
        <th>{'Type'|i18n( 'design/admin/node/removeobject' )}</th>
        <th>{'Objects referring to this item'|i18n( 'design/admin/content/children_reverserelatedlist' )}</th>
    </tr>

{section var=children_item loop=$children_list sequence=array( bglight, bgdark )}
<tr class="{$children_item.sequence}">
    {* Object icon. *}
    <td class="tight">
         {$children_item.object.class_identifier|class_icon( small, $children_item.object.class_name|wash )}
    </td>

    {* Location. *}
    <td>
    {if $reverse_list_count_children_array[$children_item.object.id]|gt( 0 )}
        <a href={concat( $children_item.object.main_node.url_alias, '/(show_relations)/1#relations' )|ezurl}>
    {/if}
    {section var=path_node loop=$children_item.path|append( $children_item )}
              {$path_node.name|wash}
              {delimiter} / {/delimiter}
        {/section}
    {if $reverse_list_count_children_array[$children_item.object.id]|gt( 0 )}
          </a>
    {/if}
    </td>

    {* Type. *}
    <td>
    {$children_item.object.class_name|wash}
     </td>

    {* Objects referring to this item. *}
    <td>
      {$reverse_list_count_children_array[$children_item.object.id]}
      {if $reverse_list_count_children_array[$children_item.object.id]|gt( 0 )}
        ( <a href={concat( $children_item.object.main_node.url_alias, '/(show_relations)/1#relations' )|ezurl}>list</a> )
      {/if}
    </td>

</tr>
{/section}

</table>
</div>

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat( '/content/reverserelatedlist/', $node_id )
         item_count=$children_count
         view_parameters=$view_parameters
         item_limit=$number_of_items}
</div>

{* DESIGN: Content END *}
{/let}</div></div>
