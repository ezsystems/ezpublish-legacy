{default month=false() show_week=false()}
{section show=$month}

<div class="calendar">

<table summary="{'Monthly calendar with links to each day's posts'|i18n( 'design/standard/navigator' )}">
<tr class="calendar-navigator">
    <th colspan="{sum( $month.weekdays|count, $show_week|choose( 0, 1 ) )}">
    <table class="calendar-navigator">
    <tr>
        {section show=is_array( $month.previous )}<td><div class="calendar-previous"><a href={$month.previous.link|ezurl} title="{$month.previous.month} {$month.previous.year}"><div class="calendar-arrow">&laquo;</div> </a></div></td>{/section}
        <td>
        <div class="calendar-date">
            <div class="calendar-month">{$month.month}</div>&nbsp;<div class="calendar-year">{$month.year}</div>
        </div>
        </td>
        {section show=is_array( $month.next )}<td><div class="calendar-next"><a href={$month.next.link|ezurl} title="{$month.next.month} {$month.next.year}"><div class="calendar-arrow">&raquo;</div> </a></div></td>{/section}
    </tr>
    </table>
    </th>
</tr>
<tr class="calendar-day-names">
    {section show=$show_week}<th>&nbsp;</th>{/section}
        {section var=weekday loop=$month.weekdays}
            <th class="calendar-day-{$weekday.item.class|wash}">{$weekday.item.day|wash}</th>
        {/section}
</tr>
{section var=week loop=$month.weeks}
<tr class="calendar-days">
    {section show=$show_week}<td class="calendar-week">{$week.key}</td>{/section}
    {section var=day loop=$week.item}
        {section show=is_boolean( $day.item )}
            <td class="calendar-empty">&nbsp;</td>
        {/section}
        {section show=is_numeric( $day.item )}
            <td>{$day.item}</td>
        {/section}
        {section show=is_array( $day.item )}
            {let day_number=$day.item.day
                 day_class=first_set( $day.item.class, false() )
                 day_link=first_set( $day.item.link, false() )
                 day_highlight=first_set( $day.item.highlight, false() )}
            <td{section show=$day_class|gt( 0 )} class="calendar-{$day_class|wash}"{/section}>{section show=$day_link|count|gt( 0 )}<a href={$day_link|ezurl}>{/section}{section show=and( is_boolean( $day_highlight ), $day_highlight )}<strong class="calendar-day-highlight">{/section}{$day_number}{section show=and( is_boolean( $day_highlight ), $day_highlight )}</strong>{/section}{section show=$day_link|count|gt( 0 )}</a>{/section}</td>
            {/let}
        {/section}
    {/section}
</tr>
{/section}
</table>

</div>

{/section}
{/default}
