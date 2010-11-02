{let month=$month_overview_data|month_overview('published', 1193446660)}
{$month.year}

{section var=weekday loop=$month.weekdays}
{$weekday.item.day}

{/section}
<br/>
{$month.month}

{/let}
