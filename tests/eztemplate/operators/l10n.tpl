time           - {$timestamp|l10n( 'time' )}
shorttime      - {$timestamp|l10n( 'shorttime' )}
date           - {$timestamp|l10n( 'date' )}
shortdate      - {$timestamp|l10n( 'shortdate' )}
datetime       - {$timestamp|l10n( 'datetime' )}
shortdatetime  - {$timestamp|l10n( 'shortdatetime' )}
currency       - {1234.567|l10n( 'currency' )}
clean_currency - {1234.567|l10n( 'clean_currency' )}
number         - {1234.567|l10n( 'number' )}


{let number=1234.567}
number         - {$number|l10n( 'number' )}
{/let}


{let func=time}
time           - {$timestamp|l10n( $func )}
{/let}

