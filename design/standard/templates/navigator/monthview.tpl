{default month=false() show_week=false()}
{section show=$month}

<table class="calendar" summary="Monthly calendar with links to each day's posts" border="0">
<tr>
  <th colspan="{sum( $month.weekdays|count, $show_week|choose( 0, 1 ) )}">
    <div class="navigator">
    {section show=is_array( $month.previous )}<div class="previous"><a href={$month.previous.link|ezurl} title="{$month.previous.month} {$month.previous.year}">&laquo; </a></div>{/section}
    <div class="date">
      <div class="month"><strong>{$month.month}</strong></div><div class="year">{$month.year}</div>
    </div>
    {section show=is_array( $month.next )}<div class="next"><a href={$month.next.link|ezurl} title="{$month.next.month} {$month.next.year}">&raquo; </a></div>{/section}
    </div>
  </th>
</tr>
  <tr>
  {section show=$show_week}<th>&nbsp;</th>{/section}
  {section var=weekday loop=$month.weekdays}
    <th class="{$weekday.item.class|wash}">{$weekday.item.day|wash}</th>
  {/section}
  </tr>
{section var=week loop=$month.weeks}
  <tr>
  {section show=$show_week}<td class="week">{$week.key}</td>{/section}
  {section var=day loop=$week.item}
    {section show=is_boolean( $day.item )}
      <td>&nbsp;</td>
    {/section}
    {section show=is_numeric( $day.item )}
      <td>{$day.item}</td>  
    {/section}
    {section show=is_array( $day.item )}
      {let day_number=$day.item.day
           day_class=first_set( $day.item.class, false() )
           day_link=first_set( $day.item.link, false() )
           day_highlight=first_set( $day.item.highlight, false() )}
        <td{section show=$day_class|gt(0)} class="{$day_class|wash}"{/section}>{section show=$day_link|gt(0)}<a href={$day_link|ezurl}>{/section}{section show=and(is_boolean($day_highlight),$day_highlight)}<strong>{/section}{$day_number}{section show=and(is_boolean($day_highlight),$day_highlight)}</strong>{/section}{section show=$day_link|gt(0)}</a>{/section}</td>
      {/let}
    {/section}
  {/section}
  </tr>
{/section}

</table>

{/section}
{/default}
