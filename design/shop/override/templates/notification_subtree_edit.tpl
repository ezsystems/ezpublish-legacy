{let subscribed_nodes=$handler.rules}

<div class="contentheader">
<h2>{"Node notification"|i18n("design/standard/section")}</h2>
</div>
<table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<th width="29%">
	{"Name"|i18n("design/standard/content/view")}
	</th>
	<th width="49%">
	{"Path"}
	</th>
	<th width="20%">
	{"Class"|i18n("design/standard/content/view")}
	</th>
{*	<th width="30%">
	{"Section"|i18n("design/standard/content/view")}
	</th>*}
	<th width="1%">
	{"Select"|i18n("design/standard/content/view")}
	</th>
</tr>

{section name=Rules loop=$subscribed_nodes sequence=array(bgdark,bglight)}
<tr>

        <td class="{$Rules:sequence}">
	<a href={concat($Rules:item.node.url_alias)|ezurl}>
	{$Rules:item.node.name|wash}
        </a>
	</td>

        <td class="{$Rules:sequence}">
	<a href={concat($Rules:item.node.parent.url_alias)|ezurl}>
	{$Rules:item.node.parent.name|wash}
        </a>
	</td>

	<td class="{$Rules:sequence}">
	{$Rules:item.node.object.content_class.name|wash}
	</td>
{*	<td class="{$Rules:sequence}">
	{$Rules:item.node.object.section_id}
	</td>*}

	<td class="{$Rules:sequence}">
	      <input type="checkbox" name="SelectedRuleIDArray_{$handler.id_string}[]" value="{$Rules:item.id}" />
	</td>


</tr>
{/section}
</table>
<div class="buttonblock">
{*<input class="button" type="submit" name="NewRule_{$handler.id_string}" value="{'New'|i18n('design/standard/notification')}" />*}
<input class="button" type="submit" name="RemoveRule_{$handler.id_string}" value="{'Remove'|i18n('design/standard/notification')}" />
</div>


<p>
    {section name=Handlers loop=$handlers}
        {*Handler: {$Handlers:item.name}*}
        {include handler=$Handlers:item uri=concat( "design:notification/handler/",$Handlers:item.id_string,"/settings/edit.tpl")}
        {delimiter}<br/>{/delimiter}
    {/section}
    </p>
{/let}
