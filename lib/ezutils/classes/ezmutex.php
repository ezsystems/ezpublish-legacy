<?php
/**
 * File containing the eZMutex class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZMutex ezmutex.php
  \brief The class eZMutex provides a file based mutex. The mutex works across processes.

*/

class eZMutex
{
    public $MetaFileName;
    const STEAL_STRING = '_eZMutex_Steal';

    /**
     * Creates a mutex object for mutext <name>. The mutex is file based, and valid across PHP processes.
     * @param string $name
     */
    public function __construct( $name )
    {
        $this->Name = md5( $name );
        $mutexPath = eZDir::path( array( eZSys::cacheDirectory(),
                                         'ezmutex' ) );
        eZDir::mkdir( $mutexPath, false, true );
        $this->FileName = eZDir::path( array( $mutexPath,
                                              $this->Name ) );
        $this->MetaFileName = eZDir::path( array( $mutexPath,
                                                  $this->Name . '_meta' ) );
    }

    /*!
     \private
     Get file pointer
    */
    function fp()
    {
        if ( !isset( $GLOBALS['eZMutex_FP_' . $this->FileName] ) ||
             $GLOBALS['eZMutex_FP_' . $this->FileName] === false )
        {
            $GLOBALS['eZMutex_FP_' . $this->FileName] = fopen( $this->FileName, 'w' );
            if ( $GLOBALS['eZMutex_FP_' . $this->FileName] === false )
            {
                eZDebug::writeError( 'Failed to open file: ' . $this->FileName );
            }
        }
        return $GLOBALS['eZMutex_FP_' . $this->FileName];
    }

    /*!
     Test if mutex is locked.

     \return true if mutex is locked
    */
    function test()
    {
        if ( $fp = $this->fp() )
        {
            if ( flock( $fp, LOCK_EX | LOCK_NB ) )
            {
                flock( $fp, LOCK_UN );
                return false;
            }
        }
        return true;
    }

    /*!
     Lock. Blocks untill the mutex becomes available

     \return true if lock is successfull.
             false if it fails to require a lock.
    */
    function lock()
    {
        if ( $fp = $this->fp() )
        {
            if ( flock( $fp, LOCK_EX ) )
            {
                $this->clearMeta();
                $this->setMeta( 'timestamp', time() );
                return true;
            }
        }
        return false;
    }

    /*!
     Set metadata

     \param key
     \param value
    */
    function setMeta( $key, $value )
    {
        $content = array();
        if ( file_exists( $this->MetaFileName ) )
        {
            $content = unserialize( file_get_contents( $this->MetaFileName ) );
        }
        $content[$key] = $value;
        eZFile::create( $this->MetaFileName, false, serialize( $content), true );
    }

    /*!
     Read meta data

     \param key

     \return value, null if no value is associated with the key.
    */
    function meta( $key )
    {
        $content = array();
        if ( file_exists( $this->MetaFileName ) )
        {
            $content = unserialize( file_get_contents( $this->MetaFileName ) );
        }
        return isset( $content[$key] ) ? $content[$key] : null;
    }

    /*!
     Clear the meta data
    */
    function clearMeta()
    {
        $content = array();
        eZFile::create( $this->MetaFileName, false, serialize( $content), true );
    }

    /*!
     Unlock. Unlocks the mutex.

     \return true if the unlock is successfull.
    */
    function unlock()
    {
        if ( $fp = $this->fp() )
        {
            fclose( $fp );
            @unlink( $this->MetaFileName );
            @unlink( $this->FileName );
            $GLOBALS['eZMutex_FP_' . $this->FileName] = false;
        }
        return false;
    }

    /*!
     Get the timestamp of when the mutex was locked

     \return GMT timestamp of when the mutex was created.
             false if no lock exists.
    */
    function lockTS()
    {
        return $this->test() ? $this->meta( 'timestamp' ) : false;
    }

    /*!
     Steal. The function will aquire a lock on the mutex when it's stolen.
     <code>
     $myMutex = new eZMutex( 'myMutex' );
     if ( $myMutex->steal() )
     {
         // protected code goes here
         $myMutex->unlock();
     }
     </code>

     \param force. If this is set to true, the process will steal the mutex, even if other processes are in the
                   process of stealing it as well.

     \return false If the process is not able to steal the mutex.
             true if the mutex is successfully stolen.
    */
    function steal( $force = false )
    {
        $stealMutex = new eZMutex( $this->Name . eZMutex::STEAL_STRING );
        if ( !$force )
        {
            // Aquire a steal mutex, and steal the mutex.
            if ( $stealMutex->test() )
            {
                return false;
            }
            if ( $stealMutex->lock() )
            {
                $stealMutex->setMeta( 'pid', getmypid() );
                if ( $this->lock() )
                {
                    // sleep for 1 second in case lock has only been granted beacause a larger
                    // cleanup is in progress.
                    sleep( 1 );
                    $stealMutex->unlock();
                    return true;
                }
            }
        }
        else
        {
            $stealPid = $stealMutex->meta( 'pid' );
            if ( is_numeric( $stealPid ) &&
                 $stealPid != 0 &&
                 function_exists( 'posix_kill' ) )
            {
                eZDebug::writeNotice( 'Killing steal mutex process: ' . $stealPid );
                posix_kill( $stealPid, 9 );
            }

            // If other steal mutex exists, kill it, and create your own.
            $this->unlock();
            return $this->lock();
        }
        return false;
    }

    public $Name;
    public $FileName;
}

?>
