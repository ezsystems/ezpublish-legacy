<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Confirm section removal'|i18n( 'design/admin/section/confirmremove' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="message-confirmation">

{section show=$delete_result|count|eq(1)}
    <h2>{'Are you sure you want to remove the section?'|i18n( 'design/admin/section/confirmremove' )}</h2>
{section-else}
    <h2>{'Are you sure you want to remove the sections?'|i18n( 'design/admin/section/confirmremove' )}</h2>
{/section}

<p>{'The following sections will be removed'|i18n( 'design/admin/section/confirmremove' )}:</p>

<ul>
{section name=Result loop=$delete_result}
    <li>{$Result:item.name}</li>
{/section}
</ul>

<p><b>{'Warning'|i18n( 'design/admin/section/confirmremove' )}:</b></p>
<p>{'Removing a section may corrupt permission settings, template output and other things in the system.'|i18n( 'design/admin/section/confirmremove' )}</p>
<p>{'Proceed only if you know what you are doing.'|i18n( 'design/admin/section/confirmremove' )}</p>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">

<form action={concat( $module.functions.list.uri )|ezurl} method="post" name="SectionRemove">
    <input class="button" type="submit" name="ConfirmRemoveSectionButton" value="{'OK'|i18n( 'design/admin/section/confirmremove' )}" />
    <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/section/confirmremove' )}" />
</form>

</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>


