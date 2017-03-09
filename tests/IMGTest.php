<?php
class IMGTest extends Base
{
    /** TESTS WITH ONE FILE **/

    public function testPackOneMissingFormat()
    {
        $file = null;

        $this->Packer->setConfig(['images_fake' => false]);

        $file = $this->Packer->img('/resources/img/image-1.bmp', 'resizeCrop,400,400', 'img/image-1.bmp')->getFilePath();

        $this->assertTrue($file === false);

        $this->Packer->setConfig(['images_fake' => true]);
    }

    /**
     * @expectedException Eusonlito\LaravelPacker\Exceptions\InvalidArgument
     */
    public function testPackMultipeException()
    {
        $file = null;

        $file = $this->Packer->img([
            '/resources/img/image-1.png',
            '/resources/img/image-1.png'
        ], 'resizeCrop,400,400', 'img/image-1.jpg')->getFilePath();

        $this->assertTrue($file === null);
    }

    public function testPackOneDefaultRelative()
    {
        $file = $this->Packer->img('/resources/img/image-1.png', 'resizeCrop,400,400', 'img/image-1.png')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $oldfile = $this->Packer->path('public', '/resources/img/image-1.png');

        $this->assertFalse(file_get_contents($oldfile) === file_get_contents($file));

        unlink($file);
    }

    public function testPackOneDefaultAbsolute()
    {
        $file = $this->Packer->img('/resources/img/image-1.png', 'resizeCrop,400,400', '/cache/img/image-1.png')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $oldfile = $this->Packer->path('public', '/resources/img/image-1.png');

        $this->assertFalse(file_get_contents($oldfile) === file_get_contents($file));

        unlink($file);
    }

    public function testPackOneNoTimestampRelative()
    {
        $this->Packer->setConfig([
            'check_timestamps' => false
        ]);

        $file = $this->Packer->img('/resources/img/image-1.png', 'resizeCrop,400,400', '/cache/img/image-1.png')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $oldfile = $this->Packer->path('public', '/resources/img/image-1.png');

        $this->assertFalse(file_get_contents($oldfile) === file_get_contents($file));

        $file = $this->cache.'/img/image-1.png';

        $this->assertFileExists($file, sprintf('File %s exists', $file));

        $this->Packer->setConfig([
            'check_timestamps' => true
        ]);
    }

    public function testPackOneNoTimestampRelativeMissing()
    {
        $this->Packer->setConfig([
            'check_timestamps' => false,
            'images_fake' => false
        ]);

        $file = $this->Packer->img('/resources/img/NOTEXISTS.png', 'resizeCrop,400,400', '/cache/img/NOTEXISTS.png')->getFilePath();

        $this->assertFalse($file);

        $file = $this->cache.'/img/NOTEXISTS.png';

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));

        $this->Packer->setConfig([
            'check_timestamps' => true,
            'images_fake' => true
        ]);
    }

    public function testPackOneNoTimestampRelativeFake()
    {
        $this->Packer->setConfig([
            'check_timestamps' => false
        ]);

        $oldfile = $this->Packer->path('public', '/resources/img/NOTEXISTS.png');

        $this->assertFileNotExists($oldfile, sprintf('File %s not exists', $oldfile));

        $file = $this->Packer->img('/resources/img/NOTEXISTS.png', 'resizeCrop,400,400', '/cache/img/image-FAKE.png')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $file = $this->cache.'/img/image-FAKE.png';

        $this->assertFileExists($file, sprintf('File %s was created successfully from Fake', $file));

        $this->assertTrue(filesize($file) > 0);

        $this->Packer->setConfig([
            'check_timestamps' => true
        ]);
    }
}
