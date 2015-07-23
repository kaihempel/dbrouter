<?php namespace Dbrouter\Config;

use PHPUnit_Framework_TestCase;

/**
 * Simple collection object.
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class ConfigTest extends PHPUnit_Framework_TestCase
{
    protected static $filePath      = '';
    protected static $wrongFilePath = '';

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        // Set testfile path

        self::$filePath         = __DIR__ . '/../../../build/test.php';
        self::$wrongFilePath    = __DIR__ . '/../../../build/test.txt';

        file_put_contents(self::$wrongFilePath, 'test');
    }

    public function setUp()
    {
        parent::setUp();

        // Try to set realistic file permissions

        if (file_exists(self::$filePath)) {
            exec('chmod 744 ' . self::$filePath);
        }
    }

    public function testNewConfig()
    {
        $config = new Config(self::$filePath);

        $this->assertInstanceOf('\Dbrouter\Config\Config', $config);

        $config['test'] = 'test';

        $this->assertEquals('test', $config['test']);

        $config->setValue('test', 'test2');

        $this->assertEquals('test2', $config['test']);
        $this->assertEquals('test2', $config->getValue('test'));
    }

    public function testFilePath()
    {
        $config = new Config(self::$filePath);

        $this->assertInstanceOf('\Dbrouter\Config\Config', $config);

        $config['test'] = 'test';

        $this->assertEquals(self::$filePath, $config->getFilePath());

        $newFilePath = substr(self::$filePath, 0, -8) . 'test2.php';
        $config->setFilePath($newFilePath);
        $this->assertEquals($newFilePath, $config->getFilePath());
    }

    /**
     * @expectedException \Dbrouter\Exception\Config\ConfigException
     */
    public function testFilePathException()
    {
        $config = new Config('/unknown/path/test.php');
    }

    public function testSaveInFile()
    {
        $config = new Config(self::$filePath);
        $config['test'] = 'test';

        $this->assertInstanceOf('\Dbrouter\Config\Config', $config);
        $this->assertEquals('test', $config['test']);

        $config->save(self::$filePath);

        $this->assertFileExists(self::$filePath);
    }

    /**
     * @depends testSaveInFile
     * @expectedException \Dbrouter\Exception\Config\ConfigException
     * @expectedExceptionMessage Given config file isn't readable!
     */
    public function testFilePathReadException()
    {
        // Prepare file as readonly

        exec('chmod 100 ' . self::$filePath);

        $config = new Config(self::$filePath);
    }

    /**
     * @depends testSaveInFile
     * @expectedException \Dbrouter\Exception\Config\ConfigException
     */
    public function testSaveInFileWriteException()
    {
        $config = new Config(self::$filePath);

        // Prepare file as readonly

        exec('chmod 400 ' . self::$filePath);


        $this->assertInstanceOf('\Dbrouter\Config\Config', $config);
        $config->save();
    }

    public function testReadFromFile()
    {
        $config = new Config(self::$filePath);

        $this->assertInstanceOf('\Dbrouter\Config\Config', $config);
        $this->assertTrue(isset($config['test']));
        $this->assertEquals('test', $config['test']);
    }

    /**
     * @expectedException \Dbrouter\Exception\Config\ConfigException
     * @expectedExceptionMessage Only PHP files are supported!
     */
    public function testReadFromFilePathException()
    {
        $config = new Config(self::$wrongFilePath);
    }

    /**
     * @expectedException \Dbrouter\Exception\Config\ConfigException
     * @expectedExceptionMessage Unexpected file content!
     */
    public function testReadFromFileWrongContent()
    {
        // Prepare file

        file_put_contents(self::$filePath, '<?php return "test"; ?>');

        $config = new Config(self::$filePath);
    }

    public static function tearDownAfterClass()
    {
        // delete testfile

        @unlink(self::$filePath);
        @unlink(self::$wrongFilePath);

        parent::tearDownAfterClass();
    }
}