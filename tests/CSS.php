<?php
use Illuminate\Foundation\Testing\TestCase;
use Laravel\Packer\Packer;

class CSSTest extends TestCase
{
    /**
     * Creates the application.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        $unitTesting = true;

        $testEnvironment = 'testing';

        return require __DIR__.'/../../../../bootstrap/start.php';
    }

    public function setUp()
    {
        $this->createApplication();

        $config = require __DIR__.'/../src/config/config.php';

        $config['base_folder'] = '/storage/tmp/packed/';
        $config['ignore_environemnts'] = [];

        $this->Packer = new Packer($config);

        $base = public_path('storage/tmp');

        if (is_dir($base) && is_writable($base)) {
            self::delTree($base);
        }

        if (!is_dir($base) && !(@mkdir($base, 0755, true))) {
            throw new \Exception(sprintf('Base folder to tests %s could not be created', $this->base));
        }

        if (!is_dir($base.'/original/') && !(@mkdir($base.'/original/', 0755, true))) {
            throw new \Exception(sprintf('Base folder to tests %s could not be created', $base.'/original/'));
        }

        if (!is_dir($base.'/packed/') && !(@mkdir($base.'/packed/', 0755, true))) {
            throw new \Exception(sprintf('Base folder to tests %s could not be created', $base.'/packed/'));
        }

        foreach (glob(__DIR__.'/resources/*') as $file) {
            copy($file, $base.'/original/'.basename($file));
        }

        $this->base = $base;
    }

    public static function delTree($dir)
    {
        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            (is_dir($file = $dir.'/'.$file)) ? self::delTree($file) : unlink($file);
        }

        return rmdir($dir);
    }

    public function checkContents($file, array $tests)
    {
        $file = file_get_contents($file);

        foreach ($tests as $test) {
            $this->assertTrue(strstr($file, $test) ? true : false);
        }
    }

    /** TESTS WITH ONE FILE **/

    public function testPackOneDefaultRelative()
    {
        $file = $this->Packer->css('/storage/tmp/original/styles-1.css', 'css/styles-1.css')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1']);

        if (is_file($file)) {
            unlink($file);
        }

        $file = $this->base.'/css/packed/styles-1.css';

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));
    }

    public function testPackOneDefaultAbsolute()
    {
        $file = $this->Packer->css('/storage/tmp/original/styles-1.css', '/storage/tmp/packed/css/styles-1.css')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1']);

        if (is_file($file)) {
            unlink($file);
        }

        $file = $this->base.'/packed/css/styles-1.css';

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));
    }

    public function testPackOneNoTimestampRelative()
    {
        $this->Packer->setConfig(['check_timestamps' => false]);

        $this->Packer->css('/storage/tmp/original/styles-1.css', 'css/styles-1.css');

        $file = $this->base.'/packed/css/styles-1.css';

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

        $this->Packer->css('/storage/tmp/original/styles-1.css', '/storage/tmp/packed/css/styles-1.css');

        $file = $this->base.'/packed/css/styles-1.css';

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1']);

        if (is_file($file)) {
            unlink($file);
        }

        $this->Packer->setConfig(['check_timestamps' => true]);
    }

    public function testPackOneAutonameRelative()
    {
        $file = $this->Packer->css('/storage/tmp/original/styles-1.css', 'css/')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1']);

        if (is_file($file)) {
            unlink($file);
        }
    }

    public function testPackOneAutonameAbsolute()
    {
        $file = $this->Packer->css('/storage/tmp/original/styles-1.css', '/storage/tmp/packed/css/')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1']);

        if (is_file($file)) {
            unlink($file);
        }
    }

    /** TESTS WITH MULTIPLE FILES **/

    public function testPackMultipleDefaultRelative()
    {
        $file = $this->Packer->css([
            '/storage/tmp/original/styles-1.css',
            '/storage/tmp/original/styles-2.css'
        ], 'css/styles.css')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        if (is_file($file)) {
            unlink($file);
        }

        $file = $this->base.'/css/packed/styles.css';

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));
    }

    public function testPackMultipleDefaultAbsolute()
    {
        $file = $this->Packer->css([
            '/storage/tmp/original/styles-1.css',
            '/storage/tmp/original/styles-2.css'
        ], '/storage/tmp/packed/css/styles.css')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        if (is_file($file)) {
            unlink($file);
        }

        $file = $this->base.'/packed/css/styles.css';

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));
    }

    public function testPackMultipleNoTimestampRelative()
    {
        $this->Packer->setConfig(['check_timestamps' => false]);

        $file = $this->Packer->css([
            '/storage/tmp/original/styles-1.css',
            '/storage/tmp/original/styles-2.css'
        ], 'css/styles.css');

        $file = $this->base.'/packed/css/styles.css';

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

        $file = $this->Packer->css([
            '/storage/tmp/original/styles-1.css',
            '/storage/tmp/original/styles-2.css'
        ], '/storage/tmp/packed/css/styles.css');

        $file = $this->base.'/packed/css/styles.css';

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        if (is_file($file)) {
            unlink($file);
        }

        $this->Packer->setConfig(['check_timestamps' => true]);
    }

    public function testPackMultipleAutonameRelative()
    {
        $file = $this->Packer->css([
            '/storage/tmp/original/styles-1.css',
            '/storage/tmp/original/styles-2.css'
        ], 'css/')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        if (is_file($file)) {
            unlink($file);
        }
    }

    public function testPackMultipleAutonameAbsolute()
    {
        $file = $this->Packer->css([
            '/storage/tmp/original/styles-1.css',
            '/storage/tmp/original/styles-2.css'
        ], '/storage/tmp/packed/css/')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        if (is_file($file)) {
            unlink($file);
        }
    }

    public function testPackMultipleLocal()
    {
        $this->Packer->setConfig([
            'environment' => 'local',
            'ignore_environemnts' => ['local']
        ]);

        $packed = $this->Packer->css([
            '/storage/tmp/original/styles-1.css',
            '/storage/tmp/original/styles-2.css'
        ], 'css/');

        $file = $packed->getFilePath();

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));

        $this->assertTrue(substr_count($packed->render(), 'rel="stylesheet"') === 2, 'Local environment get 2 tags to original files');

        if (is_file($file)) {
            unlink($file);
        }

        $this->Packer->setConfig([
            'environment' => 'testing',
            'ignore_environemnts' => []
        ]);
    }

    /** TESTS DIRECTORY **/

    public function testPackDirectoryDefaultRelative()
    {
        $file = $this->Packer->cssDir('/storage/tmp/original/', 'css/all.css')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        if (is_file($file)) {
            unlink($file);
        }

        $file = $this->base.'/css/packed/all.css';

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));
    }

    public function testPackDirectoryDefaultAbsolute()
    {
        $file = $this->Packer->cssDir('/storage/tmp/original/', '/storage/tmp/packed/css/all.css')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        if (is_file($file)) {
            unlink($file);
        }

        $file = $this->base.'/packed/css/all.css';

        $this->assertFileNotExists($file, sprintf('File %s not exists', $file));
    }

    public function testPackDirectoryNoTimestampRelative()
    {
        $this->Packer->setConfig(['check_timestamps' => false]);

        $this->Packer->cssDir('/storage/tmp/original/', 'css/all.css');

        $file = $this->base.'/packed/css/all.css';

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

        $this->Packer->cssDir('/storage/tmp/original/', '/storage/tmp/packed/css/all.css');

        $file = $this->base.'/packed/css/all.css';

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        if (is_file($file)) {
            unlink($file);
        }

        $this->Packer->setConfig(['check_timestamps' => true]);
    }

    public function testPackDirectoryAutonameRelative()
    {
        $file = $this->Packer->cssDir('/storage/tmp/original/', 'css/')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        if (is_file($file)) {
            unlink($file);
        }
    }

    public function testPackDirectoryAutonameAbsolute()
    {
        $file = $this->Packer->cssDir('/storage/tmp/original/', '/storage/tmp/packed/css/')->getFilePath();

        $this->assertFileExists($file, sprintf('File %s was created successfully', $file));

        $this->checkContents($file, ['_TEST_INI_FILE1', '_TEST_END_FILE1', '_TEST_INI_FILE2', '_TEST_END_FILE2']);

        if (is_file($file)) {
            unlink($file);
        }
    }
}
