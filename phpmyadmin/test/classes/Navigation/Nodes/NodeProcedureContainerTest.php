<?php

declare(strict_types=1);

namespace PhpMyAdmin\Tests\Navigation\Nodes;

use PhpMyAdmin\Navigation\NodeFactory;
use PhpMyAdmin\Tests\AbstractTestCase;

class NodeProcedureContainerTest extends AbstractTestCase
{
    /**
     * SetUp for test cases
     */
    protected function setUp(): void
    {
        parent::setUp();
        parent::loadDefaultConfig();
        $GLOBALS['server'] = 0;
    }

    /**
     * Test for __construct
     */
    public function testConstructor(): void
    {
        $parent = NodeFactory::getInstance('NodeProcedureContainer');
        $this->assertArrayHasKey(
            'text',
            $parent->links
        );
        $this->assertStringContainsString(
            'index.php?route=/database/routines',
            $parent->links['text']
        );
        $this->assertEquals('procedures', $parent->realName);
    }
}
