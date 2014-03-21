<?php
use Headzoo\CoinTalk\Pool,
    Headzoo\CoinTalk\Server;

class PoolTest
    extends PHPUnit_Framework_TestCase
{
    /**
     * The test fixture
     * @var Pool
     */
    protected $pool;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->pool = new Pool();
    }

    /**
     * @covers Headzoo\CoinTalk\Pool::add
     */
    public function testAdd()
    {
        $server = $this->getMockBuilder('Headzoo\CoinTalk\Server')
            ->disableOriginalConstructor()
            ->getMock();
        $this->pool->add($server);
        $this->assertEquals(1, $this->pool->count());
        $this->pool->add($server);
        $this->assertEquals(2, $this->pool->count());
    }

    /**
     * @covers Headzoo\CoinTalk\Pool::get
     */
    public function testGet()
    {
        $server1 = $this->getMockBuilder('Headzoo\CoinTalk\Server')
            ->disableOriginalConstructor()
            ->getMock();
        $server2 = $this->getMockBuilder('Headzoo\CoinTalk\Server')
            ->disableOriginalConstructor()
            ->getMock();
        $this->pool->add($server1);
        $this->pool->add($server2);

        $this->assertSame(
            $server1,
            $this->pool->get()
        );
        $this->assertSame(
            $server2,
            $this->pool->get()
        );
        $this->assertSame(
            $server1,
            $this->pool->get()
        );
    }

    /**
     * @covers Headzoo\CoinTalk\Pool::query
     */
    public function testQuery()
    {
        $conf = [
            "user" => "test",
            "pass" => "test",
            "host" => "127.0.0.1",
            "port" => 9336
        ];
        $server = new Server($conf);
        $this->pool->add($server);

        $conf = [
            "user" => "test",
            "pass" => "test",
            "host" => "127.0.0.1",
            "port" => 9335
        ];
        $server = new Server($conf);
        $this->pool->add($server);

        $info = $this->pool->query("getinfo");
        $this->assertArrayHasKey(
            "version",
            $info
        );

        $info = $this->pool->query("getinfo");
        $this->assertArrayHasKey(
            "version",
            $info
        );
    }
}
