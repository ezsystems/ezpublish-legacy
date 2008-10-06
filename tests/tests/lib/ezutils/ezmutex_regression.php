<?php

class eZMutexRegression extends ezpTestCase
{
    /**
     * Test scenario for issue #13622: mutex - windows file are not remove
     *
     * Test Outline
     * ------------
     * 1. Create a mutex for test.txt
     * 2. Lock the mutex.
     * 3. Unlock the mutex.
     *
     * @result: the mutex lock file is not removed.
     * @expected: the mutex lock file is removed.
     * @link http://issues.ez.no/13622
     */
    public function testUnlock()
    {
        $scriptMutex = new eZMutex( 'test.txt' );

        $lockTS = $scriptMutex->lockTS();

        $lockFileName = $this->readAttribute( $scriptMutex, 'FileName' );

        if ( $lockTS === false && $scriptMutex->lock() )
        {
            $scriptMutex->setMeta( 'pid', getmypid() );
            $scriptMutex->unlock();
            $this->assertFalse( file_exists( $lockFileName ), 'Mutex lock file was not removed.' );
        }
        else
        {
            $this->fail( "Unable to create a locking mutex. This might be the result of the failure of a previous test. Please remove the lock file $lockFileName from the file system and run the test again." );
        }
    }
}

?>