<?php
/**
 * File containing ezpRestDebugPHPFormatter class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */
class ezpRestDebugPHPFormatter implements ezcDebugOutputFormatter
{
    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * Returns a string containing the formatted output based on $timerData and $writerData.
     *
     * @param array(ezcDebugStructure) $timerData
     * @param array $writerData
     * @return string
     */
    public function generateOutput( array $writerData, array $timerData )
    {
        return array(
            'Logs'      => $this->getLog( $writerData ),
            'Timer'     => $this->getTimingsAccumulator( $timerData )
        );
    }

    /**
     * Returns a PHP array containing the output based on $writerData.
     *
     * @param array $writerData
     * @return array
     */
    public function getLog( array $writerData )
    {
        $result = array();

        foreach( $writerData as $w )
        {
            $result[$w->verbosity.': '.$w->source.'::'.$w->category] = $w->message;
        }

        if ( isset( $w->stackTrace ) )
        {
            $result['stackTrace'] = $this->formatStackTrace( $w->stackTrace );
        }

        return $result;
    }

    /**
     * Returns a PHP array containing stdClass objects, based on $timerData.
     *
     * @param array(ezcDebugStructure) $timerData
     * @return array(stdClass)
     */
    public function getTimingsAccumulator( array $timerData )
    {
        $groups = $this->getGroups( $timerData );
        $res = array();

        if ( sizeof( $groups ) > 0 )
        {
            foreach ( $groups as $groupName => $group )
            {
                $groupStats = new stdClass();

                // Calculate the total time.
                foreach ( $group->elements as $name => $element )
                {
                    $elapsedTime = sprintf( '%.5f', $element->elapsedTime );
                    $percent = sprintf( '%.2f', (100 * ($element->elapsedTime / $group->elapsedTime ) ) );
                    $average = sprintf( '%.5f', ( $element->elapsedTime / $element->count ) );
                    $groupInfos = new stdClass();
                    $groupInfos->name = $name;
                    $groupInfos->elapsed = $elapsedTime;
                    $groupInfos->percent = $percent.' %';
                    $groupInfos->count = $element->count;
                    $groupInfos->average = $average;
                    $groupInfos->switches = array();

                    foreach ( $element->switchTime as $switch )
                    {
                        $switchInfos = new stdClass();
                        $elapsedTime = sprintf( '%.5f', $switch->time - $element->startTime );
                        $percent = sprintf( '%.2f', ( 100 * ( $elapsedTime / $group->elapsedTime ) ) );
                        $switchInfos->name = $switch->name;
                        $switchInfos->elapsed = $elapsedTime;
                        $switchInfos->percent = $percent.' %';

                        $groupInfos->switches[] = $switchInfos;
                    }

                    $groupStats->elements[] = $groupInfos;
                }

                if ( $group->count > 1 )
                {
                    $elapsedTime = sprintf( '%.5f', $group->elapsedTime );
                    $average = sprintf( '%.5f', ( $group->elapsedTime / $group->count ) );
                    $groupStats->totalElapsed = $elapsedTime;
                    $groupStats->count = $group->count;
                    $groupStats->average = $average;
                }

                $res[$groupName] = $groupStats;
            }
        }

        return $res;
    }

    /**
     * Returns the timer groups of the given $timers.
     *
     * @param array(ezcDebugStructure) $timers
     * @return array(ezcDebugStructure)
     */
    private function getGroups( array $timers )
    {
        $groups = array();
        foreach ( $timers as $time )
        {
            if ( !isset( $groups[$time->group] ) )
            {
                $groups[$time->group] = new ezcDebugStructure();
                $groups[$time->group]->elements = array();
                $groups[$time->group]->count = 0;
                $groups[$time->group]->elapsedTime = 0;
                $groups[$time->group]->startTime = INF;  // Infinite high number.
                $groups[$time->group]->stopTime = 0;
            }

            // $groups[$time->group]->elements[] = $time;
            $this->addElement( $groups[$time->group]->elements, $time );

            $groups[$time->group]->count++;
            $groups[$time->group]->elapsedTime += $time->elapsedTime;
            $groups[$time->group]->startTime = min( $groups[$time->group]->startTime, $time->startTime );
            $groups[$time->group]->stopTime = max( $groups[$time->group]->stopTime, $time->stopTime );
        }

        return $groups;
    }

    /**
     * Prepares $element to contain $timeStruct information.
     *
     * @param array $element
     * @param ezcDebugTimerStruct $timeStruct
     */
    private function addElement( &$element, $timeStruct )
    {
        if ( !isset( $element[$timeStruct->name] ) )
        {
            $element[$timeStruct->name] = new ezcDebugStructure();

            $element[$timeStruct->name]->count = 0;
            $element[$timeStruct->name]->elapsedTime = 0;
            $element[$timeStruct->name]->startTime = INF;
            $element[$timeStruct->name]->stopTime = 0;
        }

        $element[$timeStruct->name]->count++;
        $element[$timeStruct->name]->elapsedTime += $timeStruct->elapsedTime;
        $element[$timeStruct->name]->startTime = min( $element[$timeStruct->name]->startTime, $timeStruct->startTime );
        $element[$timeStruct->name]->stopTime = max( $element[$timeStruct->name]->stopTime, $timeStruct->stopTime );


        $element[$timeStruct->name]->switchTime = $timeStruct->switchTime;
    }

    /**
     * Returns a PHP array representation of the given $stackTrace.
     *
     * Iterates through the given $stackTrace and returns an HTML formatted
     * string representation.
     *
     * @param ezcDebugStacktraceIterator $stackTrace
     * @return string
     */
    public function formatStackTrace( ezcDebugStacktraceIterator $stackTrace )
    {
        $res = array();
        foreach ( $stackTrace as $index => $element )
        {
            $function = ( isset( $element['class'] ) ? "{$element['class']}::" : '' )
            . $element['function'] . '('
            . implode( ', ', $element['params'] )
            . ')';
            $location = "{$element['file']}:{$element['line']}";
            $res[$index] = $function.' ('.$location.')';
        }
        return  $res;
    }
}
?>
