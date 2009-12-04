<div id="currentuser">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>{'Current user'|i18n( 'design/admin/pagelayout' )}</h4>

</div></div></div></div></div></div>

<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{if and( ne( $ui_context, 'edit' ), ne( $ui_context, 'browse' ) )}
	<ul>
	<li><div><a href={'/user/edit/'|ezurl} title="{'Change name, email, password, etc.'|i18n( 'design/admin/pagelayout' )}">{'Change information'|i18n( 'design/admin/pagelayout' )}</a></div></li>
	<li><div><a href={'/user/password/'|ezurl} title="{'Change password for <%username>.'|i18n( 'design/admin/pagelayout',, hash( '%username', $current_user.contentobject.name ) )|wash}">{'Change password'|i18n( 'design/admin/pagelayout' )}</a></div></li>

	<li><div><a href={'/user/logout'|ezurl} title="{'Logout from the system.'|i18n( 'design/admin/pagelayout' )}">{'Logout'|i18n( 'design/admin/pagelayout' )}</a></div></li>
	</ul>
{else}
    <ul>
    <li><div><span class="disabled">{'Change user info'|i18n( 'design/admin/pagelayout' )}</span></div></li>
    <li><div><span class="disabled">{'Change password'|i18n( 'design/admin/pagelayout' )}</span></div></li>
    <li><div><span class="disabled">{'Logout'|i18n( 'design/admin/pagelayout' )}</span></div></li>
    </ul>
{/if}
</div></div></div></div></div></div>

</div>