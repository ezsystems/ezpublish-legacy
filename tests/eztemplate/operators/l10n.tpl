time           - {1077552775|l10n( 'time' )}
shorttime      - {1077552775|l10n( 'shorttime' )}
date           - {1077552775|l10n( 'date' )}
shortdate      - {1077552775|l10n( 'shortdate' )}
datetime       - {1077552775|l10n( 'datetime' )}
shortdatetime  - {1077552775|l10n( 'shortdatetime' )}
currency       - {1234.567|l10n( 'currency' )}
clean_currency - {1234.567|l10n( 'clean_currency' )}
number         - {1234.567|l10n( 'number' )}
error          - {1234.567|l10n( 'error' )}


{let number=1234.567 timestamp=1077552775}
time           - {$timestamp|l10n( 'time' )}
shorttime      - {$timestamp|l10n( 'shorttime' )}
date           - {$timestamp|l10n( 'date' )}
shortdate      - {$timestamp|l10n( 'shortdate' )}
datetime       - {$timestamp|l10n( 'datetime' )}
shortdatetime  - {$timestamp|l10n( 'shortdatetime' )}
currency       - {$number|l10n( 'currency' )}
clean_currency - {$number|l10n( 'clean_currency' )}
number         - {$number|l10n( 'number' )}
error          - {$number|l10n( 'error' )}
{/let}


{let func=time timestamp=1077552775}
time           - {$timestamp|l10n( $func )}
{/let}
