{default page_uri_suffix=false()}
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=$page_uri
         page_uri_suffix=$page_uri_suffix
         item_count=$item_count
         view_parameters=$view_parameters
         item_limit=$item_limit}
{if and( ezini_hasvariable( 'AlphabeticalFilterSettings', 'ContentFilterList', 'content.ini' ),
         gt( count( ezini( 'AlphabeticalFilterSettings', 'ContentFilterList', 'content.ini' ) ), 0 )
	 )}

   {default children_count_by_letter=false()
            objectname_filter=false()
            alphabet=alphabet()
	    node_id=false()
	    page_uri_suffix=false()
	    page_count=int( ceil( div( $item_count, $item_limit ) ) )}

   {* Create array of used letters. All unused letters will be disabled in alphabetical navigator. *}
   {if and( ne( $node_id, false() ), eq( ezini( 'AlphabeticalFilterSettings', 'EnableUnusedLetters', 'content.ini' ), 'true' ) )}
      {def $alphabet_tmp=merge( $alphabet, array( 'others' ) )
           $hash_by_letter=false()}
      {foreach $alphabet_tmp as $letter}
           {set $hash_by_letter=hash( $letter, fetch( content, list_count, hash( parent_node_id, $node_id, objectname_filter, $letter ) ) )}
           {if eq( count( $children_count_by_letter ), 0 )}
              {set $children_count_by_letter=$hash_by_letter}
           {else}
              {set $children_count_by_letter=merge( $hash_by_letter, $children_count_by_letter )}
           {/if}
      {/foreach}
      {undef $alphabet_tmp $hash_by_letter}
   {/if}

   {* Initialize $objectname_filter *}
   {if is_set( $view_parameters.namefilter )}
      {set objectname_filter=$view_parameters.namefilter}
   {/if}

   {if and( $:objectname_filter|eq( false() ), $:page_count|eq( 0 ) )}
   {* DO NOTHING *}
   {else}
       <div class="pagenavigator">
       <p>
       {let $c=0}
       <span class="pages">
            {if $:objectname_filter|eq( false() )}
                 <span class="current">*</span>
	    {else}
                 <span class="other"><a href={concat( $page_uri, $page_uri_suffix )|ezurl}>*</a></span>
	    {/if}

	    {if $:objectname_filter|eq( 'others' )}
                 <span class="current">others</span>
	    {else}
	         <span class="other">
		 {if or( and( ne($children_count_by_letter, false() ), gt( $children_count_by_letter['others'], 0 ) ), eq( $children_count_by_letter, false() ) )}
		     <a href={concat( $page_uri, '/(namefilter)/', 'others', $page_uri_suffix )|ezurl}>
                 {else}
		     <span class="disabled">
		 {/if}
		 others
		 {if or( and( ne($children_count_by_letter, false() ), gt( $children_count_by_letter['others'], 0 ) ), eq( $children_count_by_letter, false() ) )}
		     </a>
                 {else}
                     </span>
                 {/if}
		
                 </span>		
	    {/if}
            {* Create alphabetical navigator *}
            {foreach $alphabet as $letter}
                 {if $letter|eq( $:objectname_filter )}
                      <span class="current">{$letter}</span>
                 {else}
                      <span class="other">
                      {if or( and( ne( $children_count_by_letter, false() ), and( is_set( $children_count_by_letter[$letter] ), gt( $children_count_by_letter[$letter], 0 ) ) ), eq( $children_count_by_letter, false() ) )}
                         <a href={concat( $page_uri, '/(namefilter)/', $letter, $page_uri_suffix )|ezurl}>
		      {else}
		         <span class="disabled">
		      {/if}
		      {$letter}
                      {if or( and( ne( $children_count_by_letter, false() ), and( is_set( $children_count_by_letter[$letter] ), gt( $children_count_by_letter[$letter], 0 ) ) ), eq( $children_count_by_letter, false() ) )}
		         </a>
		      {else}
		         </span>
		      {/if}
		      </span>
                 {/if}
		
	         {set $c=inc($:c)}
	         {if $:c|gt(25)}
	            {set $c=0}
	            <br>
                 {/if}		
            {/foreach}
       </span>
       {/let}
       </p>
       <div class="break"></div>
       </div>
   {/if}
   {/default}
{/if}
{/default}
