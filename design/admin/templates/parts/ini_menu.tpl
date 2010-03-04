{*
   Reusable menu template using menu.ini settings
   for links and names.

   Two input variables are expected as input:
   * ini_section : The ini section to read settings from
   * i18n_hash : (optional) Hash for i18n values
   
   See parts/setup/menu.tpl for example!
*}

{if is_unset( $ini_section )}
    {def $ini_section  = 'Leftmenu_'
         $i18n_section = 'design/admin/parts/menu'}
{elseif $ini_section|contains('_')}
    {def $i18n_section = concat('design/admin/parts/', $ini_section|explode('_')[1], '/menu')}
{else}
    {def $i18n_section = 'design/admin/parts/menu'}
{/if}

{if is_unset( $i18n_hash )}
    {def $i18n_hash = hash()}
{/if}

{if $uri_string}
    {def $current_uri_string = $uri_string}
{else}
    {def $current_uri_string = ezini( 'SiteSettings', 'IndexPage')}
{/if}


{if ezini_hasvariable( $ini_section, 'Links', 'menu.ini' )}
    {def $url_list   = ezini( $ini_section, 'Links', 'menu.ini' )
         $name_list  = ezini( $ini_section, 'LinkNames', 'menu.ini' )
         $menu_name  = ezini( $ini_section, 'Name', 'menu.ini' )
         $check      = array()
         $has_access = true()
         $item_name = ''}

    {* Check access globally *}
    {if ezini_hasvariable( $ini_section, 'PolicyList', 'menu.ini' )}
        {foreach ezini( $ini_section, 'PolicyList', 'menu.ini' ) as $policy}
            {if $policy|contains('/')}
                {set $check = $policy|explode('/')}
                {if fetch( 'user', 'has_access_to', hash( 'module', $check[0], 'function', $check[1] ) )|not}
                    {set $has_access = false()}
                    {break}
                {/if}
            {else}
                {set $check = fetch('content', 'node', hash( 'node_id', $policy ))}
                {if and( $check, $check.can_read )|not}
                    {set $has_access = false()}
                    {break}
                {/if}
            {/if}
        {/foreach}
    {/if}

    {if $has_access}
        {* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
        <h4>{if is_set( $i18n_hash[ $menu_name ] )}{$i18n_hash[ $menu_name ]|wash}{else}{$menu_name|d18n($i18n_section)}{/if}</h4>
        {* DESIGN: Header END *}</div></div></div></div></div></div>

        {* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

        {if eq( $ui_context, 'edit' )}
            <ul class="leftmenu-items">
            {foreach $url_list as $link_key => $link_url}
                {if is_set( $name_list[ $link_key ] )}
                    {set $item_name = $name_list[$link_key]|d18n($i18n_section)}
                {else}
                    {set $item_name = first_set( $i18n_hash[ $link_key ], $link_key )|wash}
                {/if}
                <li><div><span class="disabled">{$item_name}</span></div></li>
            {/foreach}
            </ul>
        {else}
            <ul class="leftmenu-items">
            {foreach $url_list as $link_key => $link_url}
                {if is_set( $name_list[ $link_key ] )}
                    {set $item_name = $name_list[$link_key]|d18n($i18n_section)}
                {else}
                    {set $item_name = first_set( $i18n_hash[ $link_key ], $link_key )|wash}
                {/if}
                {set $has_access = true()}
                {* Check access pr link *}
                {if ezini_hasvariable( $ini_section, concat( 'PolicyList_', $link_key ), 'menu.ini' )}
                    {foreach ezini( $ini_section, concat( 'PolicyList_', $link_key ), 'menu.ini' ) as $policy}
                        {if $policy|contains('/')}
                            {set $check = $policy|explode('/')}
                            {if fetch( 'user', 'has_access_to', hash( 'module', $check[0], 'function', $check[1] ) )|not}
                                {set $has_access = false()}
                                {break}
                            {/if}
                        {else}
                            {set $check = fetch('content', 'node', hash( 'node_id', $policy ))}
                            {if and( $check, $check.can_read )|not}
                                {set $has_access = false()}
                                {break}
                            {/if}
                        {/if}
                    {/foreach}
                {/if}
                {if $has_access}
                    <li{if $current_uri_string|begins_with( $link_url )} class="current"{/if}><div><a href={$link_url|ezurl}>{$item_name}</a></div></li>
                {else}
                    <li class="disabled-no-access"><div><span class="disabled">{$item_name}</span></div></li>
                {/if}
            {/foreach}
            </ul>
        {/if}

        {* DESIGN: Content END *}</div></div></div></div></div></div>
    {/if}
    {undef $url_list $menu_name $check $has_access}
{/if}