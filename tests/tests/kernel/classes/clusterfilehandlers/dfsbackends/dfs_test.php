<?php
class eZDFSFileHandlerDFSBackendTest extends ezpTestCase
{
    public function setUp()
    {
        if ( !file_exists( $this->path ) )
        {
            mkdir( $this->path );
        }
        $ini = eZINI::instance( 'file.ini' );
        $ini->setVariable( 'eZDFSClusteringSettings', 'MountPointPath', $this->path );
        $this->dfsbackend = new eZDFSFileHandlerDFSBackend();
    }

    /**
    * Tests if $path exists and that its contents matches $content
    **/
    protected function localFileIsValid( $path, $content = 'testcontent' )
    {
        return ( file_exists( $path ) && ( file_get_contents( $path ) == $content ) );
    }

    /**
     * Tests if local file $path exists on DFS and that its contents matches
     * $content
     **/
    protected function DFSFileIsValid( $path, $content = 'testcontent' )
    {
        $path = $this->makeDFSPath( $path );
        return ( file_exists( $path ) && ( file_get_contents( $path ) == $content ) );
    }

    protected function createDFSFile( $path, $content = 'testcontent' )
    {
        return $this->createFile( $this->makeDFSPath( $path ), $content );
    }

    protected function createLocalFile( $path, $content = 'testcontent' )
    {
        return $this->createFile( $path, $content );
    }

    protected function createFile( $path, $content )
    {
        return eZFile::create( basename( $path ), dirname( $path ), $content, false );
    }

    protected function makeDFSPath( $path )
    {
        return $this->path . $path;
    }

    public function testCopyFromDFSToDFS()
    {
        $srcFile = 'testCopyFromDFSToDFS';
        $tgtFile = 'testCopyFromDFSToDFS_copy';
        $this->createDFSFile( $srcFile );
        $this->dfsbackend->copyFromDFSToDFS( $srcFile, $tgtFile );

        $this->assertTrue( $this->DFSFileIsValid( $srcFile ), "Source file DFS://$srcFile no longer exists" );
        $this->assertTrue( $this->DFSFileIsValid( $tgtFile ), "Target file DFS://$tgtFile doesn't exist" );
    }

    public function testDeleteSingleFile()
    {
        $file = 'testDeleteSingleFile';
        $this->createDFSFile( $file );

        if ( !$this->DFSFileIsValid( $file ) )
        {
            $this->fail( "Test file DFS://$file was not created correctly" );
        }

        $this->dfsbackend->delete( $file );

        $this->assertFalse( $this->DFSFileIsValid( $file ), "Test file DFS://$file is still valid" );
    }

    public function testDeleteMultipleFiles()
    {
        for ( $i = 0, $files = array(); $i <= 10; $i++ )
        {
            $file = 'testDeleteSingleFile' . uniqid();
            $this->createDFSFile( $file );
            if ( !$this->DFSFileIsValid( $file ) )
            {
                $this->fail( "Test file DFS://$file was not created correctly" );
            }
            $files[] = $file;
        }


        $this->dfsbackend->delete( $files );

        foreach ( $files as $file )
        {
            $this->assertFalse( $this->DFSFileIsValid( $file ), "Test file DFS://$file is still valid" );
        }
    }

    public function testCopyFromDFS()
    {
        $file = 'var/testCopyFromDFSToFS';
        $this->createDFSFile( $file );

        $this->dfsbackend->copyFromDFS( $file );

        $this->assertTrue( $this->localFileIsValid( $file ), "Test file FS://$file is not valid" );
    }

    public function testCopyFromDFSWithNewName()
    {
        $srcFile = 'testCopyFromDFSToFS';
        $tgtFile = 'var/somesubfolder/' . $srcFile . '_localcopy';
        $this->createDFSFile( $srcFile );

        $this->dfsbackend->copyFromDFS( $srcFile, $tgtFile );

        $this->assertTrue( $this->localFileIsValid( $tgtFile ), "Test file FS://$tgtFile is not valid" );
    }

    public function testCopyToDFS()
    {
        $srcFile = 'var/testCopyToDFS';
        $this->createLocalFile( $srcFile );

        $this->dfsbackend->copyToDFS( $srcFile );

        $this->assertTrue( $this->DFSFileIsValid( $srcFile ), "DFS://$srcFile isn't valid" );
    }

    public function testCopyToDFSWithNewName()
    {
        $srcFile = 'var/testCopyToDFS';
        $dstFile = 'var/subfolder/testCopyToDFS_copy';
        $this->createLocalFile( $srcFile );

        $this->dfsbackend->copyToDFS( $srcFile, $dstFile );

        $this->assertTrue( $this->DFSFileIsValid( $dstFile ), "DFS://$dstFile isn't valid" );
    }

    public function testGetContents()
    {
        $file = 'testGetContents';
        $contents = md5( uniqid() );
        $this->createDFSFile( $file, $contents );

        $dfsContents = $this->dfsbackend->getContents( $file );
        $this->assertEquals( $dfsContents, $contents, "DFS://$file contents doesn't match the expected contents" );
    }

    public function testPassthrough()
    {
        $file = 'testPassthrough';
        $contents = '';
        for ( $i = 0; $i < 1000; $i++ )
        {
            $contents .= md5( uniqid() );
        }
        $this->createDFSFile( $file, $contents );

        ob_start();
        $this->dfsbackend->passthrough( $file );
        $passedContents = ob_get_contents();
        ob_end_clean();

        $this->assertEquals( $passedContents, $contents, "Contents passed from DFS://$file doesn't match the expected contents" );
    }

    public function testCreateFileOnDFS()
    {
        $filePath = 'var/testCreateFileOnDFS';
        $contents = md5( uniqid() );

        $this->dfsbackend->createFileOnDFS( $filePath, $contents );

        $this->assertTrue( $this->DFSFileIsValid( $filePath, $contents ), "DFS://$filePath is not valid" );
    }

    public function testRenameOnDFS()
    {
        $oldFile = 'var/testRenameOnDFS';
        $newFile = 'var/testRenameOnDFS_renamed';

        $this->createDFSFile( $oldFile );

        $this->dfsbackend->renameOnDFS( $oldFile, $newFile );
        $this->assertTrue( $this->DFSFileIsValid( $newFile ), "DFS://$newFile doesn't exist" );
        $this->assertFalse( $this->DFSFileIsValid( $oldFile ), "DFS://$oldFile still exists" );
    }

    /**
     * DFSBackend instance
     * @var eZDFSFileHandlerDFSBackend
     **/
    protected $dfsbackend;

    protected $path = 'var/testdfsmount/';
}

?>