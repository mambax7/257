<?php

namespace GorHill\FineDiff;

/**
 * FineDiff class
 *
 * TODO: Document
 *
 */
class FineDiffHTML extends FineDiff
{
    public function renderDiffToHTML($textToEntities = true)
    {
        $in_offset = 0;
        ob_start();
        foreach ($this->edits as $edit) {
            $n = $edit->getFromLen();
            if ($edit instanceof FineDiffCopyOp) {
                FineDiff::renderDiffToHTMLFromOpcode('c', $this->from_text, $in_offset, $n, null, $textToEntities);
            } elseif ($edit instanceof FineDiffDeleteOp) {
                FineDiff::renderDiffToHTMLFromOpcode('d', $this->from_text, $in_offset, $n, null, $textToEntities);
            } elseif ($edit instanceof FineDiffInsertOp) {
                FineDiff::renderDiffToHTMLFromOpcode('i', $edit->getText(), 0, $edit->getToLen(), null, $textToEntities);
            } else /* if ( $edit instanceof FineDiffReplaceOp ) */ {
                FineDiff::renderDiffToHTMLFromOpcode('d', $this->from_text, $in_offset, $n, null, $textToEntities);
                FineDiff::renderDiffToHTMLFromOpcode('i', $edit->getText(), 0, $edit->getToLen(), null, $textToEntities);
            }
            $in_offset += $n;
        }

        return ob_get_clean();
    }

    /**------------------------------------------------------------------------
     * Render the diff to an HTML string
     */
    public static function renderDiffToHTMLFromOpcodes($from, $opcodes, $encoding = null, $textToEntities = true)
    {
        if (null === $encoding) {
            $encoding = mb_internal_encoding();
        }
        ob_start();
        FineDiff::renderFromOpcodes($from, $opcodes, ['\GorHill\FineDiff\FineDiffHTML', 'renderDiffToHTMLFromOpcode'], $encoding, $textToEntities);

        return ob_get_clean();
    }

    protected static function renderDiffToHTMLFromOpcode($opcode, $from, $from_offset, $from_len, $encoding = null, $textToEntities = true)
    {
        if (null === $encoding) {
            $encoding = mb_internal_encoding();
        }

        if ('c' === $opcode) {
            if ($textToEntities) {
                echo htmlentities(mb_substr($from, $from_offset, $from_len, $encoding));
            } else {
                echo mb_substr($from, $from_offset, $from_len, $encoding);
            }
        } elseif ('d' === $opcode) {
            $deletion = mb_substr($from, $from_offset, $from_len, $encoding);
            if (0 === strcspn($deletion, " \n\r")) { // no mb_ here is okay
                $deletion = str_replace(["\n", "\r"], ['\n', '\r'], $deletion);
            }
            if ($textToEntities) {
                echo '<del>', htmlspecialchars($deletion), '</del>';
            } else {
                echo '<del>', $deletion, '</del>';
            }
        } else /* if ( $opcode === 'i' ) */ {
            if ($textToEntities) {
                echo '<ins>', htmlspecialchars(mb_substr($from, $from_offset, $from_len, $encoding), ENT_QUOTES), '</ins>';
            } else {
                echo '<ins>', mb_substr($from, $from_offset, $from_len, $encoding), '</ins>';
            }
        }
    }
}
