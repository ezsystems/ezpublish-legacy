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
    {def $url_list   = ezini( $ini_section, 'Links', 'menu.ini' )
         $menu_name  = ezini( $ini_section, 'Name', 'menu.ini' )
         $check      = array()
         $has_access = true()}

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
        {* DESIGN: Header START *}<div class="box-header"><div class="box-ml">
        <h4>{first_set( $i18n_hash[ $menu_name ], $menu_name )|wash}</h4>
        {* DESIGN: Header END *}</div></div>

        {* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-content">

        {if eq( $ui_context, 'edit' )}
            <ul>
            {foreach $url_list as $link_name => $link_url}
                <li><div><span class="disabled">{first_set( $i18n_hash[ $link_name ], $link_name )|wash}</span></div></li>
            {/foreach}
            </ul>
        {else}
            <ul class="leftmenu-items">
            {foreach $url_list as $link_name => $link_url}
                {set $has_access = true()}
                {* Check access pr link *}
                {if ezini_hasvariable( $ini_section, concat( 'PolicyList', $link_name ), 'menu.ini' )}
                    {foreach ezini( $ini_section, concat( 'PolicyList', $link_name ), 'menu.ini' ) as $policy}
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
                    <li{if and( $uri_string|begins_with( $link_url ), $uri_string|count_chars|gt( 4 ) )} class="current"{/if}><div><a href={$link_url|ezurl}>{first_set( $i18n_hash[ $link_name ], $link_name )|wash}</a></div></li>
                {else}
                    <li class="disabled-no-access"><div><span class="disabled">{first_set( $i18n_hash[ $link_name ], $link_name )|wash}</span></div></li>
                {/if}
            {/foreach}
            </ul>
        {/if}

        {* DESIGN: Content END *}</div></div></div>
    {/if}
    {undef $url_list $menu_name $check $has_access}
{/if}