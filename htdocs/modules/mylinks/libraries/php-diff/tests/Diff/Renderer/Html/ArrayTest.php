<?php

namespace Tests\Diff\Renderer\Html;

class ArrayTest extends \PHPUnit_Framework_TestCase
{
    public function testRenderSimpleDelete()
    {
        $htmlRenderer       = new \Diff_Renderer_Html_Array();
        $htmlRenderer->diff = new \Diff(['a'], []);

        $result = $htmlRenderer->render();

        static::assertEquals([
                                 [
                                     [
                                         'tag'     => 'delete',
                                         'base'    => [
                                             'offset' => 0,
                                             'lines'  => [
                                                 'a'
                                             ]
                                         ],
                                         'changed' => [
                                             'offset' => 0,
                                             'lines'  => []
                                         ]
                                     ]
                                 ]
                             ], $result);
    }

    public function testRenderFixesSpaces()
    {
        $htmlRenderer       = new \Diff_Renderer_Html_Array();
        $htmlRenderer->diff = new \Diff(['    a'], ['a']);

        $result = $htmlRenderer->render();

        static::assertEquals([
                                 [
                                     [
                                         'tag'     => 'replace',
                                         'base'    => [
                                             'offset' => 0,
                                             'lines'  => [
                                                 '<del>&nbsp; &nbsp;</del>a',
                                             ]
                                         ],
                                         'changed' => [
                                             'offset' => 0,
                                             'lines'  => [
                                                 '<ins></ins>a'
                                             ]
                                         ]
                                     ]
                                 ]
                             ], $result);
    }
}
