<?php
/**
 * File containing the ezpAutoloadCliOutput class
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
 * Utility class for providing CLI output and incremental progress information.
 * 
 * @package kernel
 */
class ezpAutoloadCliOutput
{
    /**
     * The console output object
     *
     * @var ezcConsoleOutput
     */
    protected $output = null;

    /**
     * Object controlling progress information for the file search phase.
     *
     * @var ezcConsoleStatusBar
     */
    protected $fileSearchProgress = null;
    /**
     * Object controlling progress infromation for tokenization / class search phase.
     *
     * @var ezcConsoleProgressbar
     */
    protected $tokenizerProgress = null;

    /**
     * Array holding information used to collect statistics in the different phases.
     *
     * @var array
     */
    protected $data = null;

    public function __construct()
    {
        $this->output = new ezcConsoleOutput();
        $this->output->formats->warning->color = 'red';

        $this->data = array();
        $this->data['phase1'] = array();
        $this->data['phase2'] = array();
    }

    /**
     * Outputs a <var>$message</var> on the CLI, and formats it according to type.
     * Currently <var>$type</var> of "normal" and "warning" is supported.
     *
     * @param string $message 
     * @param string $type 
     * @return void
     */
    public function outputCli( $message, $type )
    {
        if ( $type == 'normal' )
        {
            $this->output->outputLine( $message );
            $this->output->outputLine();
        }
        else if ( $type == 'warning' )
        {
            $this->output->outputLine( "Warning: ", 'warning' );
            $this->output->outputLine( $message);
            $this->output->outputLine();
        }
    }

    /**
     * Sets up the class for handling progress information for file searching phase.
     *
     * @return void
     */
    public function initPhase1()
    {
        $this->fileSearchProgress = new ezcConsoleStatusbar( $this->output );
        $this->fileSearchProgress->setOptions( array( 'successChar' => '.' ) );
    }

    /**
     * Pushes an progress update for file searching phase.
     *
     * @return void
     */
    public function progressUpdatePhase1()
    {
        $this->fileSearchProgress->add( true );
    }

    /**
     * Closes down progress update for phase 1, also inserts some newlines
     * to make sure output is displayed nicely.
     *
     * @return void
     */
    public function finishPhase1()
    {
        $this->output->outputLine();
        $this->output->outputLine();
    }

    /**
     * Sets up the class for displaying progress information for class search phase.
     * 
     * This method expects the total file count to be present in the internal
     * $data array.
     * 
     * Example:
     * <code>
     *     $statArray = array( 'nFiles' => count( $fileList ),
     *                         'classCount' => 0,
     *                         'classAdded' => 0,
     *                       );
     * </code>
     * 
     * This array can set via the updateData() function.
     * 
     * @see function updateData
     * @return void
     */
    public function initPhase2()
    {
        // In this implementation we are using a progress bar to show the
        // progress of the tokenizing process.

        $this->tokenizerProgress = new ezcConsoleProgressbar( $this->output, $this->data['phase2']['nFiles'] );
    }

    /**
     * Pushes a progress update for class search phase.
     *
     * @return void
     */
    public function progressUpdatePhase2()
    {
        $this->tokenizerProgress->advance();
    }

    /**
     * Finishes progress output for class search phase.
     * 
     * Also inserts some extra newlines to make the output clearer.
     *
     * @return void
     */
    public function finishPhase2()
    {
        $this->tokenizerProgress->finish();
        $this->output->outputLine();
        $this->output->outputLine();
    }

    /**
     * Returns data array used to keep statistical information for each <var>$phase</var>.
     *
     * @param int $phase
     * @see eZAutoloadGenerator::OUTPUT_PROGRESS_PHASE1
     * @see eZAutoloadGenerator::OUTPUT_PROGRESS_PHASE2
     * @return array
     */
    public function getData( $phase )
    {
        switch( $phase )
        {
            case eZAutoloadGenerator::OUTPUT_PROGRESS_PHASE1:
                return $this->data['phase1'];
                break;

            case eZAutoloadGenerator::OUTPUT_PROGRESS_PHASE2:
                return $this->data['phase2'];
                break;
        }
    }

    /**
     * Updates the internal data array for each <var>$phase</var>
     *
     * @param int $phase 
     * @param array $data 
     * @return void
     */
    public function updateData( $phase, $data )
    {
        $phase = 'phase' . $phase;
        $this->data[$phase] = $data;
    }

    /**
     * Calls the correct phase progress update method depending on <var>$phase</var>
     *
     * @param int $phase 
     * @return void
     */
    public function updateProgress( $phase )
    {
        switch( $phase )
        {
            case eZAutoloadGenerator::OUTPUT_PROGRESS_PHASE1:
                $this->progressUpdatePhase1();
                break;

            case eZAutoloadGenerator::OUTPUT_PROGRESS_PHASE2:
                $this->progressUpdatePhase2();
                break;
        }
    }
}
?>