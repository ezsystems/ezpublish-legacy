<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Remove group?'|i18n( 'design/admin/class/removegroup' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="message-confirmation">

{section show=$DeleteResult|count|gt(1)}
    <h2>{'Are you sure you want to remove these class groups?'|i18n( 'design/admin/class/removegroup' )}</h2>
{section-else}
    <h2>{'Are you sure you want to remove this class group?'|i18n( 'design/admin/class/removegroup' )}</h2>
{/section}

<ul>
{section var=ClassGroups loop=$DeleteResult}
	<li>{"Removing class group '%1' will result in the removal of classes %2!"|i18n( 'design/admin/class/removegroup',,array( $ClassGroups.item.groupName, $ClassGroups.item.deletedClassName ) )}</li>
{/section}
</ul>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">

<form action={concat( $module.functions.removegroup.uri )|ezurl} method="post" name="GroupRemove">
    <input class="button" type="submit" name="ConfirmButton" value="{'OK'|i18n( 'design/admin/class/removegroup' )}">
    <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/class/removegroup' )}">
</form>

</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>