<?php
class JSTest extends Base
{
    /** TESTS WITH ONE FILE **/

    public function testPackOneDefaultRelative()
    {
        $file = $this->Packer->js('/resources/js/scripts-1.js', 'js/scripts-1.js')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1']);

        if (is_file($file)) {
            unlink($file);
        }

        $file = $this->cache.'/js/scripts-1.js';

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));
    }

    public function testPackOneDefaultAbsolute()
    {
        $file = $this->Packer->js('/resources/js/scripts-1.js', '/cache/js/scripts-1.js')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1']);

        if (is_file($file)) {
            unlink($file);
        }

        $file = $this->cache.'/js/scripts-1.js';

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));
    }

    public function testPackOneNoTimestampRelative()
    {
        $this->Packer->setConfig(['check_timestamps' => false]);

        $this->Packer->js('/resources/js/scripts-1.js', 'js/scripts-1.js');

        $file = $this->cache.'/js/scripts-1.js';

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1']);

        if (is_file($file)) {
            unlink($file);
        }

        $this->Packer->setConfig(['check_timestamps' => true]);
    }

    public function testPackOneNoTimestampAbsolute()
    {
        $this->Packer->setConfig(['check_timestamps' => false]);

        $this->Packer->js('/resources/js/scripts-1.js', '/cache/js/scripts-1.js');

        $file = $this->cache.'/js/scripts-1.js';

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1']);

        if (is_file($file)) {
            unlink($file);
        }

        $this->Packer->setConfig(['check_timestamps' => true]);
    }

    public function testPackOneAutonameRelative()
    {
        $file = $this->Packer->js('/resources/js/scripts-1.js', 'js/')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1']);

        if (is_file($file)) {
            unlink($file);
        }
    }

    public function testPackOneAutonameAbsolute()
    {
        $file = $this->Packer->js('/resources/js/scripts-1.js', '/cache/js/')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1']);

        if (is_file($file)) {
            unlink($file);
        }
    }

    /** TESTS WITH MULTIPLE FILES **/

    public function testPackMultipleDefaultRelative()
    {
        $file = $this->Packer->js([
            '/resources/js/scripts-1.js',
            '/resources/js/scripts-2.js'
        ], 'js/scripts.js')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        if (is_file($file)) {
            unlink($file);
        }

        $file = $this->cache.'/js/scripts.js';

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));
    }

    public function testPackMultipleDefaultAbsolute()
    {
        $file = $this->Packer->js([
            '/resources/js/scripts-1.js',
            '/resources/js/scripts-2.js'
        ], '/cache/js/scripts.js')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        if (is_file($file)) {
            unlink($file);
        }

        $file = $this->cache.'/js/scripts.js';

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));
    }

    public function testPackMultipleNoTimestampRelative()
    {
        $this->Packer->setConfig(['check_timestamps' => false]);

        $file = $this->Packer->js([
            '/resources/js/scripts-1.js',
            '/resources/js/scripts-2.js'
        ], 'js/scripts.js');

        $file = $this->cache.'/js/scripts.js';

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        if (is_file($file)) {
            unlink($file);
        }

        $this->Packer->setConfig(['check_timestamps' => true]);
    }

    public function testPackMultipleNoTimestampAbsolute()
    {
        $this->Packer->setConfig(['check_timestamps' => false]);

        $file = $this->Packer->js([
            '/resources/js/scripts-1.js',
            '/resources/js/scripts-2.js'
        ], '/cache/js/scripts.js');

        $file = $this->cache.'/js/scripts.js';

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        if (is_file($file)) {
            unlink($file);
        }

        $this->Packer->setConfig(['check_timestamps' => true]);
    }

    public function testPackMultipleAutonameRelative()
    {
        $file = $this->Packer->js([
            '/resources/js/scripts-1.js',
            '/resources/js/scripts-2.js'
        ], 'js/')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        if (is_file($file)) {
            unlink($file);
        }
    }

    public function testPackMultipleAutonameAbsolute()
    {
        $file = $this->Packer->js([
            '/resources/js/scripts-1.js',
            '/resources/js/scripts-2.js'
        ], '/cache/js/')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        if (is_file($file)) {
            unlink($file);
        }
    }

    public function testPackMultipleLocal()
    {
        $this->Packer->setConfig(['environment' => 'local']);

        $packed = $this->Packer->js([
            '/resources/js/scripts-1.js',
            '/resources/js/scripts-2.js'
        ], 'js/');

        $file = $packed->getFilePath();

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));

        $this->assertTrue(substr_count($packed->render(), '</script>') === 2, 'Local environment get 2 tags to original files');

        if (is_file($file)) {
            unlink($file);
        }

        $this->Packer->setConfig(['environment' => 'testing']);
    }

    /** TESTS DIRECTORY **/

    public function testPackDirectoryDefaultRelative()
    {
        $file = $this->Packer->jsDir('/resources/js/', 'js/all.js')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        if (is_file($file)) {
            unlink($file);
        }

        $file = $this->cache.'/js/all.js';

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));
    }

    public function testPackDirectoryDefaultAbsolute()
    {
        $file = $this->Packer->jsDir('/resources/js/', '/cache/js/all.js')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        if (is_file($file)) {
            unlink($file);
        }

        $file = $this->cache.'/js/all.js';

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));
    }

    public function testPackDirectoryNoTimestampRelative()
    {
        $this->Packer->setConfig(['check_timestamps' => false]);

        $this->Packer->jsDir('/resources/js/', 'js/all.js');

        $file = $this->cache.'/js/all.js';

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        if (is_file($file)) {
            unlink($file);
        }

        $this->Packer->setConfig(['check_timestamps' => true]);
    }

    public function testPackDirectoryNoTimestampAbsolute()
    {
        $this->Packer->setConfig(['check_timestamps' => false]);

        $this->Packer->jsDir('/resources/js/', '/cache/js/all.js');

        $file = $this->cache.'/js/all.js';

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        if (is_file($file)) {
            unlink($file);
        }

        $this->Packer->setConfig(['check_timestamps' => true]);
    }

    public function testPackDirectoryAutonameRelative()
    {
        $file = $this->Packer->jsDir('/resources/js/', 'js/')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        if (is_file($file)) {
            unlink($file);
        }
    }

    public function testPackDirectoryAutonameAbsolute()
    {
        $file = $this->Packer->jsDir('/resources/js/', '/cache/js/')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        if (is_file($file)) {
            unlink($file);
        }
    }

    public function testPackDirectoryAutonameAbsoluteRecursive()
    {
        $file = $this->Packer->jsDir('/resources/', '/cache/js/', true)->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        if (is_file($file)) {
            unlink($file);
        }
    }
}
