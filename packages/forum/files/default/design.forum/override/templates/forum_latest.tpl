{* Latest news *}

<table class="frontpagelist" width="100%">
<tr>
  <th><p>{"Latest posts"|i18n("design/forum/layout")}</p></th>
</tr>
<tr>
  <td>
    <ul>
      {* Get latest posts in forum, and loop through them *}
      {section name=Child 
               loop=fetch( content ,tree ,hash( parent_node_id, 2,
	                                        limit, $count,
		  			        sort_by, array( array( 'modified', false() ) ),
					        class_filter_type, 'include',
					        class_filter_array, array( 'forum_reply' ) ) ) }
        <li>
	  <a href={$Child:item.parent.url_alias|ezurl}>{$Child:item.parent.name|wash} - {$Child:item.name|wash}</a>
	  {$Child:item.creator.name|wash} - {$Child:item.object.modified|l10n(datetime)}
	</li>
      {/section}
    </ul>
  </td>
</tr>
</table>
{/default}
