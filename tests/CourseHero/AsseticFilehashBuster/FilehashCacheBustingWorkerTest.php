<?php

namespace CourseHero\AsseticFilehashBuster\Tests;

use \Assetic\Asset\AssetCollection;
use \CourseHero\AsseticFilehashBuster\FileHashCacheBustingWorker;
use \PHPUnit_Framework_TestCase;

class FilehashCacheBustingWorkerTest extends PHPUnit_Framework_TestCase
{
    private $worker;

    protected function setUp()
    {
        $this->worker = new FileHashCacheBustingWorker();
    }

    protected function tearDown()
    {
        $this->worker = null;
    }

    /**
     * @test
     */
    public function shouldHashIndividualFile(){
        $path = dirname(__FILE__);

        $asset = $this->getMock('Assetic\Asset\AssetInterface');
        $factory = $this->getMockBuilder('Assetic\Factory\AssetFactory')
            ->disableOriginalConstructor()
            ->getMock();
        $asset->expects($this->any())
            ->method('getTargetPath')
            ->will($this->returnValue('testAsset.txt'));
        $asset->expects($this->any())
            ->method('getSourceRoot')
            ->will($this->returnValue($path));
        $asset->expects($this->any())
            ->method('getSourcePath')
            ->will($this->returnValue('testAsset.txt'));


        $asset->expects($this->once())
            ->method('setTargetPath')
            ->with($this->equalTo('testAsset-51fe62f.txt'));

        $this->worker->process($asset, $factory);
    }

    /**
     * @test
     */
    public function shouldHashMultipleFiles(){
        $path = dirname(__FILE__);

        $factory = $this->getMockBuilder('Assetic\Factory\AssetFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $col = $this->getMock('Assetic\Asset\AssetCollectionInterface');
        $col->expects($this->any())
            ->method('getTargetPath')
            ->will($this->returnValue('collection.txt'));

        $asset = $this->getMock('Assetic\Asset\AssetInterface');
        $asset->expects($this->any())
            ->method('getSourceRoot')
            ->will($this->returnValue($path));
        $asset->expects($this->any())
            ->method('getSourcePath')
            ->will($this->returnValue('testAsset.txt'));

        $asset2 = $this->getMock('Assetic\Asset\AssetInterface');
        $asset2->expects($this->any())
            ->method('getSourceRoot')
            ->will($this->returnValue($path));
        $asset2->expects($this->any())
            ->method('getSourcePath')
            ->will($this->returnValue('testAsset2.txt'));

        $col->expects($this->atLeastOnce())
            ->method('all')
            ->willReturn([$asset, $asset2]);

        $col->expects($this->once())
            ->method('setTargetPath')
            ->with($this->equalTo('collection-a8371fd.txt'));

        $this->worker->process($col, $factory);
    }

    /**
     * @test
     */
    public function shouldFallbackToSourcePathIfFileDoesntExist(){
        $path = dirname(__FILE__);

        $asset = $this->getMock('Assetic\Asset\AssetInterface');
        $factory = $this->getMockBuilder('Assetic\Factory\AssetFactory')
            ->disableOriginalConstructor()
            ->getMock();
        $asset->expects($this->any())
            ->method('getTargetPath')
            ->will($this->returnValue('imaginaryAsset.txt'));

        $asset->expects($this->any())
            ->method('getSourceRoot')
            ->will($this->returnValue($path));
        $asset->expects($this->any())
            ->method('getSourcePath')
            ->will($this->returnValue('imaginaryAsset.txt'));


        $asset->expects($this->once())
            ->method('setTargetPath')
            ->with($this->equalTo('imaginaryAsset-e02df4c.txt'));

        $this->worker->process($asset, $factory);
    }

}
