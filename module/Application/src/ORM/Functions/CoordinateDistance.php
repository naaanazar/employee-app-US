<?php

namespace Application\ORM\Functions;

use Doctrine\ORM\Query\{
    AST\Functions\FunctionNode, AST\InputParameter, Lexer, SqlWalker, Parser
};

/**
 * Class Range
 * @package Application\ORM\Functions
 */
class CoordinateDistance extends FunctionNode
{

    /**
     * @var InputParameter
     */
    private $lat1;

    /**
     * @var InputParameter
     */
    private $lat2;

    /**
     * @var InputParameter
     */
    private $lng1;

    /**
     * @var InputParameter
     */
    private $lng2;

    /**
     * @param SqlWalker $sqlWalker
     * @return string
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        return 'coordinate_distance('
            . $this->lat1->dispatch($sqlWalker)
            . ','
            . $this->lng1->dispatch($sqlWalker)
            . ','
            . $this->lat2->dispatch($sqlWalker)
            . ','
            . $this->lng2->dispatch($sqlWalker)
            . ')';
    }

    /**
     * @param Parser $parser
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->lat1 = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->lng1 = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->lat2 = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->lng2 = $parser->StringPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }


}