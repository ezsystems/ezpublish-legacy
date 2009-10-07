<div id="currentuser">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr">{if $first}<div class="box-tl"><div class="box-tr">{/if}

<h4>{'Current user'|i18n( 'design/admin/pagelayout' )}</h4>

</div></div></div></div>{if $first}</div></div>{/if}

{if $last}
<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">
{else}
<div class="box-ml"><div class="box-mr"><div class="box-content">
{/if}

{let basket=fetch( shop, basket )}

{if and( ne( $ui_context, 'edit' ), ne( $ui_context, 'browse' ) )}

{let current_user_link=$current_user.contentobject.main_node.url_alias|ezurl}
<p><a href={$current_user_link}><img src={'current-user.gif'|ezimage} height="22" width="22" alt="" style="text-align: left; vertical-align: middle;" /></a>&nbsp;<a style="font-weight: normal; text-decoration: none; color: #000000" href={$current_user_link}>{$current_user.contentobject.name|wash}</a></p>
{/let}

<ul>
<li><div>
{if $current_user.contentobject.can_edit}
    <a href={concat( '/content/edit/',  $current_user.contentobject_id, '/' )|ezurl} title="{'Change name, email, password, etc.'|i18n( 'design/admin/pagelayout' )}">{'Change information'|i18n( 'design/admin/pagelayout' )}</a>
{else}
    <span class="disabled">{'Change user info'|i18n( 'design/admin/pagelayout' )}</span>
{/if}</div></li>
<li><div><a href={concat( '/user/password/', $current_user.contentobject_id )|ezurl} title="{'Change password for <%username>.'|i18n( 'design/admin/pagelayout',, hash( '%username', $current_user.contentobject.name ) )|wash}">{'Change password'|i18n( 'design/admin/pagelayout' )}</a></div></li>
{if $basket.is_empty|not}
{if $basket.items|count|eq(1)}
<li><div><a href={'shop/basket'|ezurl} title="{'There is %basket_count item in the shopping basket.'|i18n( 'design/admin/pagelayout',, hash( '%basket_count', $basket.items|count ) )}">{'Shopping basket (%basket_count)'|i18n( 'design/admin/pagelayout',, hash( '%basket_count', $basket.items|count ) )}</a></div></li>
{else}
<li><div><a href={'shop/basket'|ezurl} title="{'There are %basket_count items in the shopping basket.'|i18n( 'design/admin/pagelayout',, hash( '%basket_count', $basket.items|count ) )}">{'Shopping basket (%basket_count)'|i18n( 'design/admin/pagelayout',, hash( '%basket_count', $basket.items|count ) )}</a></div></li>
{/if}
{/if}
<li><div><a href={'/user/logout'|ezurl} title="{'Logout from the system.'|i18n( 'design/admin/pagelayout' )}">{'Logout'|i18n( 'design/admin/pagelayout' )}</a></div></li>
</ul>
{else}
    <p><img src={'current-user-disabled.gif'|ezimage} height="22" width="22" alt="" style="text-align: left; vertical-align: middle;" /> {$current_user.contentobject.name|wash}</p>
    <ul>
    <li><div><span class="disabled">{'Change user info'|i18n( 'design/admin/pagelayout' )}</span></div></li>
    <li><div><span class="disabled">{'Change password'|i18n( 'design/admin/pagelayout' )}</span></div></li>
    {if $basket.is_empty|not}
    <li><div><span class="disabled">{'Shopping basket (%basket_count)'|i18n( 'design/admin/pagelayout',, hash( '%basket_count', $basket.items|count ) )}</span></div></li>
    {/if}
    <li><div><span class="disabled">{'Logout'|i18n( 'design/admin/pagelayout' )}</span></div></li>
    </ul>
{/if}
{/let}
</div></div></div>{if $last}</div></div></div>{/if}

</div>