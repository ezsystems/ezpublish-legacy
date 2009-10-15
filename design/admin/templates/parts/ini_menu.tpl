{*
   Reusable menu template using menu.ini settings
   for links and names.

   Two input variables are expected as input:
   * ini_section : The ini section to read settings from
   * i18n_hash : (optional) Hash for i18n values
   
   See parts/setup/menu.tpl for example!
*}

{if is_unset( $ini_section )}
    {def $ini_section = 'Leftmenu_'}
{/if}

{if is_unset( $i18n_hash )}
    {def $i18n_hash = hash()}
{/if}

{if ezini_hasvariable( $ini_section, 'Links', 'menu.ini' )}

{def $url_list  = ezini( $ini_section, 'Links', 'menu.ini' )
     $menu_name = ezini( $ini_section, 'Name', 'menu.ini' )}

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h4>{first_set( $i18n_hash[ $menu_name ], $menu_name )|wash}</h4>
{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{if eq( $ui_context, 'edit' )}
<ul>
{foreach $url_list as $link_name => $link_url}
    <li><div><span class="disabled">{first_set( $i18n_hash[ $link_name ], $link_name )|wash}</span></div></li>
{/foreach}
</ul>
{else}
<ul>
{foreach $url_list as $link_name => $link_url}
    <li><div><a href={$link_url|ezurl}>{first_set( $i18n_hash[ $link_name ], $link_name )|wash}</a></div></li>
{/foreach}
</ul>
{/if}

{* DESIGN: Content END *}</div></div></div></div></div></div>
{undef $url_list $menu_name}

{/if}