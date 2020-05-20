<?php
/**
 * Tests for PhpMyAdmin\Navigation\Nodes\NodeDatabase class
 */
declare(strict_types=1);

namespace PhpMyAdmin\Tests\Navigation\Nodes;

use PhpMyAdmin\Navigation\NodeFactory;
use PhpMyAdmin\Navigation\Nodes\NodeDatabase;
use PhpMyAdmin\Tests\PmaTestCase;

/**
 * Tests for PhpMyAdmin\Navigation\Nodes\NodeDatabase class
 */
class NodeDatabaseTest extends PmaTestCase
{
    /**
     * SetUp for test cases
     */
    protected function setUp(): void
    {
        parent::setUp();
        $GLOBALS['server'] = 0;
        $GLOBALS['cfg']['DefaultTabDatabase'] = 'structure';
        $GLOBALS['cfg']['MaxNavigationItems'] = 250;
        $GLOBALS['cfg']['Server'] = [];
        $GLOBALS['cfg']['Server']['DisableIS'] = true;
    }

    /**
     * Test for __construct
     *
     * @return void
     */
    public function testConstructor()
    {
        $parent = NodeFactory::getInstance('NodeDatabase');
        $this->assertArrayHasKey(
            'text',
            $parent->links
        );
        $this->assertStringContainsString(
            'index.php?route=/database/structure',
            $parent->links['text']
        );
        $this->assertStringContainsString('database', $parent->classes);
    }

    /**
     * Test for getPresence
     *
     * @return void
     */
    public function testGetPresence()
    {
        $parent = NodeFactory::getInstance('NodeDatabase');
        $this->assertEquals(
            2,
            $parent->getPresence('tables')
        );
        $this->assertEquals(
            0,
            $parent->getPresence('views')
        );
        $this->assertEquals(
            1,
            $parent->getPresence('functions')
        );
        $this->assertEquals(
            0,
            $parent->getPresence('procedures')
        );
        $this->assertEquals(
            0,
            $parent->getPresence('events')
        );
    }

    /**
     * Test for getData
     *
     * @return void
     */
    public function testGetData()
    {
        $parent = NodeFactory::getInstance('NodeDatabase');

        $tables = $parent->getData('tables', 0);
        $this->assertContains(
            'test1',
            $tables
        );
        $this->assertContains(
            'test2',
            $tables
        );

        $views = $parent->getData('views', 0);
        $this->assertEmpty($views);

        $functions = $parent->getData('functions', 0);
        $this->assertContains(
            'testFunction',
            $functions
        );
        $this->assertCount(1, $functions);

        $this->assertEmpty($parent->getData('procedures', 0));
        $this->assertEmpty($parent->getData('events', 0));
    }

    /**
     * Test for setHiddenCount and getHiddenCount
     *
     * @return void
     */
    public function testHiddenCount()
    {
        /** @var NodeDatabase $parent */
        $parent = NodeFactory::getInstance('NodeDatabase');

        $parent->setHiddenCount(3);
        $this->assertEquals(
            3,
            $parent->getHiddenCount()
        );
    }
}
