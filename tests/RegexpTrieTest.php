<?php
namespace RegexpTrie\Test;

use PHPUnit\Framework\TestCase;
use RegexpTrie\RegexpTrie;

/**
 * RegexpTrie Testcase
 */
class RegexpTrieTest extends TestCase
{
    /*public function testTrie1()
    {
        $regexpTrie = RegexpTrie::union([
            'foo', 'bar', 'baz'
        ]);
        $regexp = $regexpTrie->build();

        $this->assertEquals(1, preg_match($regexp, 'foo'));
        $this->assertEquals(1, preg_match($regexp, 'bar'));
        $this->assertEquals(1, preg_match($regexp, 'baz'));
        $this->assertEquals(0, preg_match($regexp, 'FOO'));
    }*/

    public function testTrie2()
    {
        $regexpTrie = RegexpTrie::union([
            'foo', 'foobar', 'foobaz',
        ]);
        $regexp = $regexpTrie->build();
        var_dump($regexp);

        $this->assertEquals(1, preg_match($regexp, 'foo'));
        $this->assertEquals(1, preg_match($regexp, 'foobar'));
        $this->assertEquals(1, preg_match($regexp, 'foobaz'));
        $this->assertEquals(1, preg_match($regexp, 'text foo text'));
        $this->assertEquals(1, preg_match($regexp, 'text foobar text'));
        $this->assertEquals(1, preg_match($regexp, 'text foobaz text'));
        $this->assertEquals(0, preg_match($regexp, 'bar'));
        $this->assertEquals(0, preg_match($regexp, 'baz'));
    }
}
