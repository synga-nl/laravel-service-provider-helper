<?php
namespace Synga\ServiceProviderHelper\Parser\PrettyPrinter;

use PhpParser\Node\Expr;
use PhpParser\PrettyPrinter\Standard;

/**
 * Class ServiceProviderPrettyPrinter
 * @package Synga\ServiceProviderHelper\Parser\PrettyPrinter
 */
class ServiceProviderPrettyPrinter extends Standard
{
    /**
     * @param array $stmts
     * @return string
     */
    public function prettyPrint(array $stmts) {
        $this->preprocessNodes($stmts);

        return ltrim(str_replace("\n" . $this->noIndentToken, "\n", $this->pStmts($stmts, true)));
    }

    /**
     * @param Expr\ArrayItem $node
     * @return string
     */
    public function pExpr_ArrayItem(Expr\ArrayItem $node) {
        $result   = '';
        $comments = $node->getAttribute('comments', false);
        if ($comments != false && is_array($comments)) {
            foreach ($comments as $comment) {
                $splittedCommentLines = preg_split("/\R/", $comment);
                foreach ($splittedCommentLines as &$splittedCommentLine) {
                    $splittedCommentLine = trim($splittedCommentLine);
                }

                $comment = implode(PHP_EOL, $splittedCommentLines);

                $result .= PHP_EOL . PHP_EOL . $comment . PHP_EOL;
            }
        }

        $result .= PHP_EOL . (null !== $node->key ? $this->p($node->key) . ' => ' : '')
            . ($node->byRef ? '&' : '') . $this->p($node->value);

        return $result;
    }


    /**
     * @param Expr\Array_ $node
     * @return string
     */
    public function pExpr_Array(Expr\Array_ $node) {
        if ($this->options['shortArraySyntax']) {
            return '[' . $this->pCommaSeparated($node->items) . PHP_EOL . PHP_EOL . ']';
        } else {
            return 'array(' . $this->pCommaSeparated($node->items) . ')';
        }
    }
}