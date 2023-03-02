<?php
class CSSTest extends Base
{
    /** TESTS WITH ONE FILE **/

    public function testPackOneDefaultRelative()
    {
        $file = $this->Packer->css('/resources/css/styles-1.css', 'css/styles-1.css')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1']);

        unlink($file);

        $file = $this->cache.'/css/styles-1.css';

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));
    }

    public function testPackOneDefaultAbsolute()
    {
        $file = $this->Packer->css('/resources/css/styles-1.css', '/cache/css/styles-1.css')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1']);

        unlink($file);

        $file = $this->cache.'/css/styles-1.css';

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));
    }

    public function testPackOneNoTimestampRelative()
    {
        $this->Packer->setConfig(['check_timestamps' => false]);

        $this->Packer->css('/resources/css/styles-1.css', 'css/styles-1.css');

        $file = $this->cache.'/css/styles-1.css';

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1']);

        unlink($file);

        $this->Packer->setConfig(['check_timestamps' => true]);
    }

    public function testPackOneNoTimestampAbsolute()
    {
        $this->Packer->setConfig(['check_timestamps' => false]);

        $this->Packer->css('/resources/css/styles-1.css', '/cache/css/styles-1.css');

        $file = $this->cache.'/css/styles-1.css';

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1']);

        unlink($file);

        $this->Packer->setConfig(['check_timestamps' => true]);
    }

    public function testPackOneAutonameRelative()
    {
        $file = $this->Packer->css('/resources/css/styles-1.css', 'css/')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1']);

        unlink($file);
    }

    public function testPackOneAutonameAbsolute()
    {
        $file = $this->Packer->css('/resources/css/styles-1.css', '/cache/css/')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1']);

        unlink($file);
    }

    /** TESTS WITH MULTIPLE FILES **/

    public function testPackMultipleDefaultRelative()
    {
        $file = $this->Packer->css([
            '/resources/css/styles-1.css',
            '/resources/css/styles-2.css'
        ], 'css/styles.css')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        unlink($file);

        $file = $this->cache.'/css/styles.css';

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));
    }

    public function testPackMultipleIssuesDefaultRelative()
    {
        $file = $this->Packer->css([
            '/resources/css/styles-1.css',
            '/resources/css/styles-2.css',
            '/resources/css/styles-3.css'
        ], 'css/styles.css')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, [
            '_TEST_INI_FILE1', '_TEST_END_FILE1',
            '_TEST_INI_FILE2', '_TEST_END_FILE2',
            '_TEST_INI_FILE3', '_TEST_END_FILE3'
        ]);

        unlink($file);

        $file = $this->cache.'/css/styles.css';

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));
    }

    public function testPackMultipleDefaultAbsolute()
    {
        $file = $this->Packer->css([
            '/resources/css/styles-1.css',
            '/resources/css/styles-2.css'
        ], '/cache/css/styles.css')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        unlink($file);

        $file = $this->cache.'/css/styles.css';

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));
    }

    public function testPackMultipleNoTimestampRelative()
    {
        $this->Packer->setConfig(['check_timestamps' => false]);

        $file = $this->Packer->css([
            '/resources/css/styles-1.css',
            '/resources/css/styles-2.css'
        ], 'css/styles.css');

        $file = $this->cache.'/css/styles.css';

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        unlink($file);

        $this->Packer->setConfig(['check_timestamps' => true]);
    }

    public function testPackMultipleNoTimestampAbsolute()
    {
        $this->Packer->setConfig(['check_timestamps' => false]);

        $file = $this->Packer->css([
            '/resources/css/styles-1.css',
            '/resources/css/styles-2.css'
        ], '/cache/css/styles.css');

        $file = $this->cache.'/css/styles.css';

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        unlink($file);

        $this->Packer->setConfig(['check_timestamps' => true]);
    }

    public function testPackMultipleAutonameRelative()
    {
        $file = $this->Packer->css([
            '/resources/css/styles-1.css',
            '/resources/css/styles-2.css'
        ], 'css/')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        unlink($file);
    }

    public function testPackMultipleAutonameAbsolute()
    {
        $file = $this->Packer->css([
            '/resources/css/styles-1.css',
            '/resources/css/styles-2.css'
        ], '/cache/css/')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        unlink($file);
    }

    public function testPackMultipleLocal()
    {
        $this->Packer->setConfig(['environment' => 'local']);

        $packed = $this->Packer->css([
            '/resources/css/styles-1.css',
            '/resources/css/styles-2.css'
        ], 'css/');

        $file = $packed->getFilePath();

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));

        $this->assertTrue(substr_count($packed->render(), 'rel="stylesheet"') === 2, 'Local environment get 2 tags to original files');

        $this->Packer->setConfig(['environment' => 'testing']);
    }

    /** TESTS DIRECTORY **/

    public function testPackDirectoryDefaultRelative()
    {
        $file = $this->Packer->cssDir('/resources/css/', 'css/all.css')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        unlink($file);

        $file = $this->cache.'/css/all.css';

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));
    }

    public function testPackDirectoryDefaultAbsolute()
    {
        $file = $this->Packer->cssDir('/resources/css/', '/cache/css/all.css')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        unlink($file);

        $file = $this->cache.'/css/all.css';

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));
    }

    public function testPackDirectoryNoTimestampRelative()
    {
        $this->Packer->setConfig(['check_timestamps' => false]);

        $this->Packer->cssDir('/resources/css/', 'css/all.css');

        $file = $this->cache.'/css/all.css';

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        unlink($file);

        $this->Packer->setConfig(['check_timestamps' => true]);
    }

    public function testPackDirectoryNoTimestampAbsolute()
    {
        $this->Packer->setConfig(['check_timestamps' => false]);

        $this->Packer->cssDir('/resources/css/', '/cache/css/all.css');

        $file = $this->cache.'/css/all.css';

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        unlink($file);

        $this->Packer->setConfig(['check_timestamps' => true]);
    }

    public function testPackDirectoryAutonameRelative()
    {
        $file = $this->Packer->cssDir('/resources/css/', 'css/')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        unlink($file);
    }

    public function testPackDirectoryAutonameAbsolute()
    {
        $file = $this->Packer->cssDir('/resources/css/', '/cache/css/')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        unlink($file);
    }

    public function testPackDirectoryAutonameAbsoluteRecursive()
    {
        $file = $this->Packer->cssDir('/resources/', '/cache/css/', true)->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        unlink($file);
    }
}
