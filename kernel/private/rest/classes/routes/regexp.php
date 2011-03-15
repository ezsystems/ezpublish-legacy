<?php
/**
 * File containing ezpMvcRegexpRoute class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 */

/**
 * Override of ezcMvcRegexpRoute.
 * Necessary to be able to be mixed with rails-like routes
 */
class ezpMvcRegexpRoute extends ezcMvcRegexpRoute
{
    /**
     * Little fix to allow mixed regexp and rails routes in the router
     * @see lib/ezc/MvcTools/src/routes/ezcMvcRegexpRoute::prefix()
     */
    public function prefix( $prefix )
    {
        // Detect the Regexp delimiter
        $patternDelim = $this->pattern[0];

        // Add the Regexp delimiter to the prefix
        $prefix = $patternDelim . $prefix . $patternDelim;
        parent::prefix( $prefix );
    }
}
?>
