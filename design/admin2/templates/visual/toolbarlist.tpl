<form method="post" action={'visual/toolbarlist'|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Toolbar management'|i18n( 'design/admin/visual/toolbar' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<label>{'Siteaccess'|i18n( 'design/admin/visual/toolbar' )}:</label>
    {if $current_siteaccess}
{*        <p>{'Current siteaccess'|i18n( 'design/admin/visual/toolbar' )}: <strong>{$current_siteaccess}</strong></p> *}
    {/if}
{*        <label>{'Select siteaccess'|i18n( 'design/admin/visual/toolbar' )}:</label> *}

        <select name="CurrentSiteAccess">
            {section var=siteaccess loop=$siteaccess_list}
                {if eq( $current_siteaccess, $siteaccess )}
                    <option value="{$siteaccess}" selected="selected">{$siteaccess}</option>
                {else}
                <option value="{$siteaccess}">{$siteaccess}</option>
            {/if}
        {/section}
        </select>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
        <input class="button" type="submit" name="SelectCurrentSiteAccessButton" value="{'Set'|i18n( 'design/admin/visual/toolbar' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>


<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Available toolbars for the <%siteaccess> siteaccess'|i18n( 'design/admin/visual/toolbar',, hash( '%siteaccess', $current_siteaccess ) )|wash}</h2>

{* DESIGN: Subline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<table class="list" cellspacing="0">
{section var=toolbar loop=$toolbar_list sequence=array( bglight, bgdark )}
<tr class="{$toolbar.sequence}">
    <td>
    <a href={concat( 'visual/toolbar/', $current_siteaccess, '/', $toolbar )|ezurl}>{$toolbar}</a>
    </td>
</tr>
{/section}
</table>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

</form>
