<form action={concat("content/urltranslator/")|ezurl} method="post" >
<h1>URL translator</h1>

<table>
{section name=Alias loop=$alias_list show=$alias_list}
<tr>
    <td>
    <input type="text" name="URLAliasSourceValue[{$Alias:item.id}]" value="{$Alias:item.source_url}" />
    <input type="text" name="URLAliasDestinationValue[{$Alias:item.id}]" value="{$Alias:item.destination_url}" />
    </td>
</tr>
{/section}
</table>

{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('/content/urltranslator/')
         item_count=$alias_count
         view_parameters=$view_parameters
         item_limit=$alias_limit}


<input type="submit" name="StoreURLAliasButton" value="Store" />

<table class="list">
<tr>
    <td class="bglight">
    <input type="text" name="NewURLAliasSouce" value="" />
    <input type="text" name="NewURLAliasDestination" value="" />
    <input type="submit" name="NewURLAliasButton" value="Add" />
    </td>
</tr>
</table>

</form>