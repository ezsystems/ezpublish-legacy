<?php

// include( "lib/ezlocale/sdk/init.php" );

// // printSelectBox( eZLocale::languageList(), eZLocale::countryList(), $current_lang, $current_country );
// ?&gt;
// <form action="" method="post" name="LocaleSelection">
// Enter locale: <input type="text" name="Locale" value="<?print( $localeString );?&gt;" />

// <table><tr><th>Type</th><th>Result</th></tr>
// <tr><td colspan="2"><b>Locale</b></td></tr>
// <?php
// $infoList = array( 'LanguageCode' => $locale->languageCode(),
//                    'Language' => $locale->languageName(),
//                    'LanguageComment' => $locale->languageComment(),
//                    'Content-Language' => $locale->httpLocaleCode(),
//                    'CountryCode' => $locale->countryCode(),
//                    'CountryVariation' => $locale->countryVariation(),
//                    'Country' => $locale->countryName(),
//                    'CountryComment' => $locale->countryComment(),
//                    'Time' => $locale->formatTime(),
//                    'ShortTime' => $locale->formatShortTime(),
//                    'Date' => $locale->formatDate(),
//                    'ShortDate' => $locale->formatShortDate(),
//                    'DateTime' => $locale->formatDateTime(),
//                    'ShortDateTime' => $locale->formatShortDateTime(),
//                    'MondayFirst' => ( $locale->isMondayFirst() ? "Yes" : "No" ),
//                    'Number' => ( $locale->formatNumber( 1234567.89 ) . " / " . $locale->formatNumber( -1234567.89 ) ),
//                    'Currency' => ( $locale->formatCurrency( 123456789.00 ) . " / " . $locale->formatCurrency( -123456789.00 ) ) );

// foreach( $infoList as $infoName => $infoText )
// {
//     print( "<tr><td>$infoName</td><td>$infoText</td></tr>\n" );
// }

// // print( "<tr><td>WeekDays</td><td>" );
// // print_r( $locale->weekDays() );
// // print( "</td></tr>" );
// // print( "<tr><td>WeekDayNames</td><td>" );
// // print_r( $locale->weekDayNames( false ) );
// // print( "</td></tr>" );
// // print( "<tr><td>WeekDayShortNames</td><td>" );
// // print_r( $locale->weekDayNames( true ) );
// // print( "</td></tr>" );

// </table>
// </form>
?>

<h1>Locale settings</h1>

<p>
The following code reads some locale settings for the nor-NO locale.
</p>

<pre class="example">
include_once( "lib/ezlocale/classes/ezlocale.php" );
$locale =& eZLocale::instance( "nor-NO" );
$infoList = array( 'LanguageCode' =&gt; $locale-&gt;languageCode(),
                   'Language' =&gt; $locale-&gt;languageName(),
                   'LanguageComment' =&gt; $locale-&gt;languageComment(),
                   'Content-Language' =&gt; $locale-&gt;httpLocaleCode(),
                   'CountryCode' =&gt; $locale-&gt;countryCode(),
                   'CountryVariation' =&gt; $locale-&gt;countryVariation(),
                   'Country' =&gt; $locale-&gt;countryName(),
                   'CountryComment' =&gt; $locale-&gt;countryComment(),
                   'Time' =&gt; $locale-&gt;formatTime(),
                   'ShortTime' =&gt; $locale-&gt;formatShortTime(),
                   'Date' =&gt; $locale-&gt;formatDate(),
                   'ShortDate' =&gt; $locale-&gt;formatShortDate(),
                   'DateTime' =&gt; $locale-&gt;formatDateTime(),
                   'ShortDateTime' =&gt; $locale-&gt;formatShortDateTime(),
                   'MondayFirst' =&gt; ( $locale-&gt;isMondayFirst() ? "Yes" : "No" ),
                   'Number' =&gt; ( $locale-&gt;formatNumber( 1234567.89 ) . " / " . $locale-&gt;formatNumber( -1234567.89 ) ),
                   'Currency' =&gt; ( $locale-&gt;formatCurrency( 123456789.00 ) . " / " . $locale-&gt;formatCurrency( -123456789.00 ) ) );

print( "&lt;table&gt;&lt;tr&gt;&lt;th&gt;Type&lt;/th&gt;&lt;th&gt;Result&lt;/th&gt;&lt;/tr&gt;
&lt;tr&gt;&lt;td colspan="2"&gt;&lt;b&gt;Locale&lt;/b&gt;&lt;/td&gt;&lt;/tr&gt;" );
foreach( $infoList as $infoName =&gt; $infoText )
{
    print( "&lt;tr&gt;&lt;td&gt;$infoName&lt;/td&gt;&lt;td&gt;$infoText&lt;/td&gt;&lt;/tr&gt;\n" );
}
print( "&lt;/table&gt;" );
</pre>

<p>
This will produce something like this:
</p>

<table>
<tr><th>Type</th><th>Result</th></tr>
<tr><td colspan="2"><b>Locale</b></td></tr>
<tr><td>LanguageCode</td><td>nor</td></tr>
<tr><td>Language</td><td>Norsk (Bokmål)</td></tr>
<tr><td>LanguageComment</td><td></td></tr>
<tr><td>Content-Language</td><td>no-bokmaal</td></tr>
<tr><td>CountryCode</td><td>NO</td></tr>
<tr><td>CountryVariation</td><td></td></tr>
<tr><td>Country</td><td>Norway</td></tr>
<tr><td>CountryComment</td><td></td></tr>
<tr><td>Time</td><td>16:11:24</td></tr>
<tr><td>ShortTime</td><td>16:11</td></tr>
<tr><td>Date</td><td>07. februar 2003</td></tr>
<tr><td>ShortDate</td><td>07.02.2003</td></tr>
<tr><td>DateTime</td><td>07. februar 2003 16:11:24</td></tr>
<tr><td>ShortDateTime</td><td>07.02.2003 16:11</td></tr>
<tr><td>MondayFirst</td><td>Yes</td></tr>
<tr><td>Number</td><td>1.234.567,89 / -1.234.567,89</td></tr>
<tr><td>Currency</td><td>kr 123.456.789,00 / kr -123.456.789,00</td></tr>
</table>
