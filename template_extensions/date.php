<?php

class ezpTemplateDateFunctions implements ezcTemplateCustomFunction
{
    public static function getCustomFunctionDefinition($name)
    {
        return ezpTemplateFunctions::mapFunction( __CLASS__, $name );
    }

    /**
     * Formats misc. numbers (times, dates, currencies, numbers, etc.).
     */
    public static function l10n($input, $type, $localeString = false, $symbol = false )
    {
        if ( $type === null )
        {
            return $input;
        }

        $locale = $localeString ? eZLocale::instance( $localeString ) : eZLocale::instance();

        $method = $locale->getFormattingFunction( $type );
        if ( !$method )
            return $input;

        if ( $type == 'currency' )
        {
            if ( $symbol === false )
            {
                $symbol = $locale->attribute( 'currency_symbol' );
            }

            $input = $locale->$method( $input, $symbol );
        }
        else
        {
            $input = $locale->$method( $input );
        }

        return $input;
    }

    /**
     * Returns the locale object for the given locale name.
     */
    public static function locale(/*string*/ $localeName)
    {
        return eZLocale::instance( $localeName );
    }

    /**
     * Formats dates/times according to settings in "datetime.ini".
     */
    public static function datetime($datetime, /*string*/ $class, $data = false)
    {
        $locale = eZLocale::instance();
        if ( $class == 'custom' )
            return $locale->formatDateTimeType( $data, $datetime );
        $dtINI = eZINI::instance( 'datetime.ini' );
        $formats = $dtINI->variable( 'ClassSettings', 'Formats' );
        if ( array_key_exists( $class, $formats ) )
        {
            $classFormat = $formats[$class];
            return $locale->formatDateTimeType( $classFormat, $datetime );
        }
        ezpTemplateFunctions::runtimeError( "datetime: Class '$class' is not defined in datetime.ini" );
    }

    /**
     * Generates the UNIX timestamp of a given date/time.
     */
    public static function maketime($hour = false, $minute = false, $second = false, $month = false, $day = false, $year = false, $dst = false)
    {
        $params = func_get_args();
        while ( count( $params ) > 0 && $params[count($params)-1] === false )
        {
            array_pop( $params );
        }
        if ( count( $params ) == 0 )
            return time();
        else
            return call_user_func_array( 'mktime', $params );
    }

    /**
     * Generates the UNIX timestamp of a given date.
     */
    public static function makedate($month = false, $day = false, $year = false, $dst = false)
    {
        $params = func_get_args();
        while ( count( $params ) > 0 && $params[count($params)-1] === false )
        {
            array_pop( $params );
        }
        $params = array_merge( array( 0, 0, 0 ), $params );
        return call_user_func_array( 'mktime', $params );
    }

    /**
     * Converts a UNIX timestamp to a human friendly structure.
     */
    public static function gettime($timestamp = false)
    {
        if ( !$timestamp )
            $timestamp = time();

        $info = getdate( $timestamp );
        $week = date( 'W', $timestamp );
        if ( $info['wday'] == 0 )
            ++$week;
        return array( 'seconds' => $info['seconds'],
                      'minutes' => $info['minutes'],
                      'hours' => $info['hours'],
                      'day' => $info['mday'],
                      'month' => $info['mon'],
                      'year' => $info['year'],
                      'weeknumber' => $week,
                      'weekday' => $info['wday'],
                      'yearday' => $info['yday'],
                      'epoch' => $info[0] );
    }

}

?>
