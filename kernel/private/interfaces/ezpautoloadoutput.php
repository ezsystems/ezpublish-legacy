<?php
/**
 * File containing the ezpAutoloadOutput interface
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
 * Interface for classes providing output for autoload generation.
 *
 * @package kernel
 */
interface ezpAutoloadOutput
{
    /**
     * Outputs a <var>$message</var> on the CLI, and formats it according to type.
     * Currently <var>$type</var> of "normal" and "warning" is supported.
     *
     * @param string $message
     * @param string $type
     * @return void
     */
    public function outputCli( $message, $type );

    /**
     * Sets up the class for handling progress information for file searching phase.
     *
     * @return void
     */
    public function initPhase1();

    /**
     * Pushes an progress update for file searching phase.
     *
     * @return void
     */
    public function progressUpdatePhase1();

    /**
     * Closes down progress update for phase 1
     *
     * @return void
     */
    public function finishPhase1();

    /**
     * Sets up the class for displaying progress information for class search phase.
     * @return void
     */
    public function initPhase2();

    /**
     * Pushes a progress update for class search phase.
     *
     * @return void
     */
    public function progressUpdatePhase2();

    /**
     * Finishes progress output for class search phase.
     *
     * @return void
     */
    public function finishPhase2();

    /**
     * Returns data array used to keep statistical information for each <var>$phase</var>.
     *
     * @param int $phase
     * @see eZAutoloadGenerator::OUTPUT_PROGRESS_PHASE1
     * @see eZAutoloadGenerator::OUTPUT_PROGRESS_PHASE2
     * @return array
     */
    public function getData( $phase );

    /**
     * Updates the internal data array for each <var>$phase</var>
     *
     * @param int $phase
     * @param array $data
     * @return void
     */
    public function updateData( $phase, $data );

    /**
     * Calls the correct phase progress update method depending on <var>$phase</var>
     *
     * @param int $phase
     * @return void
     */
    public function updateProgress( $phase );
}
?>