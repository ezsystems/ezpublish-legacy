<?php

class ezpTemplateCalendarFunctions implements ezcTemplateCustomFunction
{
    public static function getCustomFunctionDefinition($name)
    {
        return ezpTemplateFunctions::mapFunction( __CLASS__, $name );
    }

    /**
     * Generates a structure that can be used to build a calendar.
     */
    public static function month_overview($monthArray, $fieldGrouping, $dateTimestamp, $optional = array() )
    {
        return MonthOverview::getOverview($monthArray, $fieldGrouping, $dateTimestamp, $optional);
    }
}


?>
