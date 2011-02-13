<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'Confirm section removal'|i18n( 'design/admin/section/confirmremove' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="message-confirmation">

{def $allowed_sections_count=$allowed_sections|count()}

{if $unallowed_sections|count()}
{if $allowed_sections_count}
<p>{'The following sections cannot be removed because they are either assigned to objects or used in role and policy limitations'|i18n( 'design/admin/section/confirmremove' )}:</p>
{else}
<p>{'None of the selected sections can be removed because they are either assigned to objects or used in role and policy limitations'|i18n( 'design/admin/section/confirmremove' )}:</p>
{/if}
<ul>
{foreach $unallowed_sections as $section}
    <li><a href={concat( 'section/view/', $section.id )|ezurl}>{$section.name|wash}</a></li>
{/foreach}
</ul>
{/if}

{if $allowed_sections_count}
<hr />
<p><b>{'The following sections will be removed'|i18n( 'design/admin/section/confirmremove' )}:</b></p>
<ul>
{foreach $allowed_sections as $section}
    <li><a href={concat( 'section/view/', $section.id )|ezurl}>{$section.name|wash}</a></li>
{/foreach}
</ul>

<h2>{'Are you sure you want to remove the sections?'|i18n( 'design/admin/section/confirmremove' )}</h2>

<p><b>{'Warning'|i18n( 'design/admin/section/confirmremove' )}:</b></p>
<p>{'Removing a section may corrupt template output and other things in the system.'|i18n( 'design/admin/section/confirmremove' )}</p>
<p>{'Proceed only if you are sure that it is safe.'|i18n( 'design/admin/section/confirmremove' )}</p>
{/if}

</div>

{* DESIGN: Content END *}</div></div></div>
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">

<form action={$module.functions.list.uri|ezurl} method="post" name="SectionRemove">
    {if $allowed_sections_count}
    <input class=""defaultbutton"" type="submit" name="ConfirmRemoveSectionButton" value="{'OK'|i18n( 'design/admin/section/confirmremove' )}" />
    <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'design/admin/section/confirmremove' )}" />
    {else}
    <input class=""defaultbutton"" type="submit" value="{'OK'|i18n( 'design/admin/section/confirmremove' )}" />
    {/if}
</form>

</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

