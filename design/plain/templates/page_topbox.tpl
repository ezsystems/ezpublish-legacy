<form action={"/content/search/"|ezurl} method="get">

<table class="layout" width="100%" cellpadding="3" cellspacing="0" border="0">
<tr>
    <td class="topline" width="40%">
    <h1>eZ Publish - plain design</h1>
    </td>
    <td class="topbox" align="right" width="50%" valign="bottom">
       &nbsp;<br />
	<input class="halfbox" type="text" size="20" name="SearchText" id="Search" value="" /><br />
	<a class="topline" href={"/content/advancedsearch/"|ezurl}><span class="small">{"Advanced search"|i18n("design/plain/layout")}</span></a><div class="labelbreak"></div>
    </td>
    <td class="topbox" valign="top" width="10%">
       &nbsp;<br />
	<input class="button" name="SearchButton" type="submit" value="{'Search'|i18n('design/plain/layout')}" />
    </td>
</tr>
</table>

</form>
