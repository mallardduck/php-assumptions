<?php

namespace tests\PhpAssumptions;

use PhpAssumptions\Analyser;
use PhpAssumptions\Output\OutputInterface;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\Parser;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTestCase;

class AnalyserTest extends ProphecyTestCase
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var NodeTraverser
     */
    private $nodeTraverser;

    /**
     * @var Analyser
     */
    private $analyser;

    /**
     * @var Node
     */
    private $node;

    public function setUp()
    {
        $this->node = $this->prophesize(Node::class);
        $this->parser = $this->prophesize(Parser::class);
        $this->output = $this->prophesize(OutputInterface::class);
        $this->nodeTraverser = $this->prophesize(NodeTraverser::class);
        $this->analyser = new Analyser(
            $this->parser->reveal(),
            $this->nodeTraverser->reveal(),
            $this->output->reveal()
        );
    }

    /**
     * @test
     */
    public function itShouldAnalyseAllFiles()
    {
        $files = [fixture('MyClass.php')];
        $nodes = [$this->node];

        $this->parser->parse(Argument::type('string'))->shouldBeCalled()->willReturn($nodes);

        $this->nodeTraverser->traverse($nodes)->shouldBeCalled();

        $this->analyser->analyse($files);
    }
}
