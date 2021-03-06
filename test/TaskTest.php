<?php
/* (c) Anton Medvedev <anton@elfet.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer;

use Deployer\Task\TaskFactory;

class TaskTest extends DeployerTester
{
    public function testRun()
    {
        $mock = $this->getMock('stdClass', ['callback']);
        $mock->expects($this->exactly(1))
            ->method('callback')
            ->will($this->returnValue(true));

        $task = new Task(function () use ($mock) {
            $mock->callback();
        });

        $task->get()[0]->run();
    }

    public function testDescription()
    {
        $task = new Task(function () {
        });
        $task->description('desc');

        $this->assertEquals('desc', $task->getDescription());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFactoryInvalidArgumentException()
    {
        TaskFactory::create(null);
    }

    public function testAfter()
    {
        $mock = $this->getMock('stdClass', ['callback']);
        $mock->expects($this->exactly(2))
            ->method('callback')
            ->will($this->returnValue(true));

        task('task', function () {
        });

        after('task', function () use ($mock) {
            $mock->callback();
        });

        task('after', function () use ($mock) {
            $mock->callback();
        });

        after('task', 'after');

        $this->runCommand('task');
    }

    public function testBefore()
    {
        $mock = $this->getMock('stdClass', ['callback']);
        $mock->expects($this->exactly(2))
            ->method('callback')
            ->will($this->returnValue(true));

        task('task', function () {
        });

        before('task', function () use ($mock) {
            $mock->callback();
        });

        task('before', function () use ($mock) {
            $mock->callback();
        });

        after('task', 'before');

        $this->runCommand('task');
    }
}
 