<table {section show=ne($classification|trim,'')}class="{$classification|wash}"{section-else}class="renderedtable"{/section} border="{$border}" cellpadding="2" cellspacing="0" width="{$width}">
{$rows}
</table>
