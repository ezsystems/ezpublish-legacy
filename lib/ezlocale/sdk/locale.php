<?php

include( "lib/ezlocale/sdk/init.php" );

// printSelectBox( eZLocale::languageList(), eZLocale::countryList(), $current_lang, $current_country );
?>
<form action="" method="post" name="LocaleSelection">
Enter locale: <input type="text" name="Locale" value="<?print( $localeString );?>" />

<table><tr><th>Type</th><th>Result</th></tr>
<tr><td colspan="2"><b>Locale</b></td></tr>
<?
$infoList = array( 'LanguageCode' => $locale->languageCode(),
                   'Language' => $locale->languageName(),
                   'CountryCode' => $locale->countryCode(),
                   'Country' => $locale->countryName(),
                   'Time' => $locale->formatTime(),
                   'ShortTime' => $locale->formatShortTime(),
                   'Date' => $locale->formatDate(),
                   'ShortDate' => $locale->formatShortDate(),
                   'DateTime' => $locale->formatDateTime(),
                   'ShortDateTime' => $locale->formatShortDateTime(),
                   'MondayFirst' => ( $locale->isMondayFirst() ? "Yes" : "No" ),
                   'Number' => ( $locale->formatNumber( 1234567.89 ) . " / " . $locale->formatNumber( -1234567.89 ) ),
                   'Currency' => ( $locale->formatCurrency( 123456789.00 ) . " / " . $locale->formatCurrency( -123456789.00 ) ) );

foreach( $infoList as $infoName => $infoText )
{
    print( "<tr><td>$infoName</td><td>$infoText</td></tr>\n" );
}

// print( "<tr><td>WeekDays</td><td>" );
// print_r( $locale->weekDays() );
// print( "</td></tr>" );
// print( "<tr><td>WeekDayNames</td><td>" );
// print_r( $locale->weekDayNames( false ) );
// print( "</td></tr>" );
// print( "<tr><td>WeekDayShortNames</td><td>" );
// print_r( $locale->weekDayNames( true ) );
// print( "</td></tr>" );

?>
</table>
</form>
