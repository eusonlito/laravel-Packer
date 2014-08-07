<?php
class IMGTest extends Base
{
    /** TESTS WITH ONE FILE **/

    public function testPackOneMissingArgument()
    {
        $file = null;

        try {
            $file = $this->Packer->img('/resources/img/image-1.png', 'img/image-1.png')->getFilePath();
            $this->fail('Must fail with missing argument');
        } catch (Exception $e) {
            $this->assertTrue(strstr($e->getMessage(), 'Missing argument 3') ? true : false, 'Must return missing argument');
        }

        $this->assertTrue($file === null);

        $file = $this->cache.'/img/image-1.png';

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));
    }

    public function testPackOneMissingFormat()
    {
        $file = null;

        $this->setExpectedException('Laravel\Packer\Exceptions\InvalidArgument', 'valid images');

        $file = $this->Packer->img('/resources/img/image-1.bmp', 'img/image-1.bmp', 'resizeCrop,400,400')->getFilePath();

        $this->assertTrue($file === null);
    }

    public function testPackMultipeException()
    {
        $file = null;

        $this->setExpectedException('Laravel\Packer\Exceptions\InvalidArgument', 'supports strings');

        $file = $this->Packer->img([
            '/resources/img/image-1.png',
            '/resources/img/image-1.png'
        ], 'img/image-1.jpg', 'resizeCrop,400,400')->getFilePath();

        $this->assertTrue($file === null);
    }

    public function testPackOneDefaultRelative()
    {
        $file = $this->Packer->img('/resources/img/image-1.png', 'img/image-1.png', 'resizeCrop,400,400')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $oldfile = $this->Packer->path('public', '/resources/img/image-1.png');

        $this->assertFalse(file_get_contents($oldfile) === file_get_contents($file));

        unlink($file);

        $file = $this->cache.'/img/image-1.png';

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));
    }

    public function testPackOneDefaultAbsolute()
    {
        $file = $this->Packer->img('/resources/img/image-1.png', '/storage/cache/img/image-1.png', 'resizeCrop,400,400')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $oldfile = $this->Packer->path('public', '/resources/img/image-1.png');

        $this->assertFalse(file_get_contents($oldfile) === file_get_contents($file));

        unlink($file);

        $file = $this->cache.'/img/image-1.png';

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));
    }

    public function testPackOneNoTimestampRelative()
    {
        $this->Packer->setConfig(['check_timestamps' => false]);

        $file = $this->Packer->img('/resources/img/image-1.png', '/storage/cache/img/image-1.png', 'resizeCrop,400,400')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $oldfile = $this->Packer->path('public', '/resources/img/image-1.png');

        $this->assertFalse(file_get_contents($oldfile) === file_get_contents($file));

        $file = $this->cache.'/img/image-1.png';

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));

        unlink($file);

        $this->Packer->setConfig(['check_timestamps' => true]);
    }
}
