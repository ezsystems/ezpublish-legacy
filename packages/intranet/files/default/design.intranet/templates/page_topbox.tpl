<form action={"/content/search/"|ezurl} method="get">

<table class="layout" width="100%" cellpadding="3" cellspacing="0" border="0">
<tr>
    <td class="topbox" align="left" width="15%" valign="bottom" background={"06_intranet_background_repeat.png"|ezimage}>
       &nbsp;<br />
	<input class="halfbox" type="text" size="20" name="SearchText" id="Search" value="" />  <br />
	<a class="topline" href={"/content/advancedsearch/"|ezurl}><span class="small">{"Advanced search"|i18n("design/standard/layout")}</span></a><div class="labelbreak"></div>
    </td>
    <td class="topbox" valign="top" width="10%" background={"06_intranet_background_repeat.png"|ezimage}>
       &nbsp;<br />
	<input class="button" name="SearchButton" type="submit" value="{'Search'|i18n('design/standard/layout')}" />
    </td>
    <td class="topline" align="right" width="40%" background={"06_intranet_background_repeat.png"|ezimage} >
<img src={"06_intranet_logo.png"|ezimage} />
    </td>
</tr>
</table>

</form>
