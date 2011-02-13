<form method="post" action={'/visual/menuconfig/'|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Menu management'|i18n( 'design/standard/visual/menuconfig' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<label>{'Siteaccess'|i18n( 'design/standard/visual/menuconfig' )}:</label>

        <select name="CurrentSiteAccess">
            {section var=siteaccess loop=$siteaccess_list}
                {if eq( $current_siteaccess, $siteaccess )}
                    <option value="{$siteaccess}" selected="selected">{$siteaccess}</option>
                {else}
                <option value="{$siteaccess}">{$siteaccess}</option>
            {/if}
        {/section}
        </select>
        &nbsp;

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
        <input class="button" type="submit" value="{'Set'|i18n( 'design/standard/visual/menuconfig' )}" name="SelectCurrentSiteAccessButton" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>


<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{"Menu positioning"|i18n( 'design/standard/visual/menuconfig' )}</h2>

{* DESIGN: Subline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<table class="list" cellspacing="0">
{section var=menu loop=$available_menu_array sequence=array( bglight, bgdark )}
<tr class="{$menu.sequence}">
    <td>{$menu.settings.TitleText}</td>
    <td>
    <label for="Menu_{$menu.type}">
        <img src={$menu.settings.MenuThumbnail|ezimage} alt="{$menu.settings.TitleText}" />
    </label>
    </td>
    <td>
    <input type="radio" id="Menu_{$menu.type}" name="MenuType" {$current_menu|eq( $menu.type )|choose( '', 'checked="checked"' )}  value="{$menu.type}" />
    </td>
</tr>
    {delimiter modulo=1}
    {/delimiter}
{/section}
</table>


{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
    <input class="button" type="submit" name="StoreButton" value="{'Apply changes'|i18n( 'design/standard/visual/menuconfig' )}" title="{'Click here to store the changes if you have modified the menu settings above.'|i18n( 'design/standard/visual/menuconfig' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>

