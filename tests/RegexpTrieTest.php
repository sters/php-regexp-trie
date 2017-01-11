<?php
namespace RegexpTrie\Test;

use PHPUnit\Framework\TestCase;
use RegexpTrie\RegexpTrie;

/**
 * RegexpTrie Testcase
 */
class RegexpTrieTest extends TestCase
{
    public function testUnion1()
    {
        $regexpTrie = RegexpTrie::union([
            'foo', 'bar', 'baz'
        ]);
        $regexp = $regexpTrie->toRegexp();

        $this->assertEquals(1, preg_match($regexp, 'foo'));
        $this->assertEquals(1, preg_match($regexp, 'bar'));
        $this->assertEquals(1, preg_match($regexp, 'baz'));
        $this->assertEquals(0, preg_match($regexp, 'FOO'));
    }

    public function testUnion2()
    {
        $regexpTrie = RegexpTrie::union([
            'foo', 'foobar', 'foobaz',
        ]);
        $regexp = '/\b' . $regexpTrie->build() . '\b/';

        $this->assertEquals(1, preg_match($regexp, 'foo'));
        $this->assertEquals(1, preg_match($regexp, 'foobar'));
        $this->assertEquals(1, preg_match($regexp, 'foobaz'));
        $this->assertEquals(1, preg_match($regexp, 'text foo text'));
        $this->assertEquals(1, preg_match($regexp, 'text foobar text'));
        $this->assertEquals(1, preg_match($regexp, 'text foobaz text'));
        $this->assertEquals(0, preg_match($regexp, 'bar'));
        $this->assertEquals(0, preg_match($regexp, 'baz'));
        $this->assertEquals(0, preg_match($regexp, 'text foobax text'));
    }

    public function testUnionFlatten()
    {
        $regexpTrie = RegexpTrie::union([
            ["foo", "bar"],
            ["hoge", "fuga"],
        ]);
        $regexp = $regexpTrie->toRegexp();

        $this->assertEquals(1, preg_match($regexp, 'foo'));
        $this->assertEquals(1, preg_match($regexp, 'bar'));
        $this->assertEquals(1, preg_match($regexp, 'hoge'));
        $this->assertEquals(1, preg_match($regexp, 'fuga'));
    }

    public function testUnionRx()
    {
        $this->assertEquals('/a/', RegexpTrie::union(['a'])->toRegexp());
        $this->assertEquals('/aa?/', RegexpTrie::union(['a', 'aa'])->toRegexp());
        $this->assertEquals('/[ab]/', RegexpTrie::union(['a', 'b'])->toRegexp());
        $this->assertEquals('/(?:foo|bar)/', RegexpTrie::union(['foo', 'bar'])->toRegexp());
        $this->assertEquals('/(?:foo|ba[rz])/', RegexpTrie::union(['foo', 'bar', 'baz'])->toRegexp());
    }

    public function testUnion()
    {
        $this->assertEquals('a', RegexpTrie::union(['a'])->build());
        $this->assertEquals('aa?', RegexpTrie::union(['a', 'aa'])->build());
        $this->assertEquals('[ab]', RegexpTrie::union(['a', 'b'])->build());
        $this->assertEquals('(?:foo|bar)', RegexpTrie::union(['foo', 'bar'])->build());
        $this->assertEquals('(?:foo|ba[rz])', RegexpTrie::union(['foo', 'bar', 'baz'])->build());
    }

    public function testUnionEmpty()
    {
        $this->assertNull(RegexpTrie::union()->build());
    }

    public function testInstance()
    {
        $regexpTrie = new RegexpTrie();
        $regexpTrie->add('foo')->add('bar')->add('baz');

        $this->assertEquals('/(?:foo|ba[rz])/', $regexpTrie->toRegexp());
    }

    public function testInstanceAddEmpty()
    {
        try {
            $regexpTrie = new RegexpTrie();
            $regexpTrie->add('');
        } catch (\InvalidArgumentException $ex) {
            $this->assertEquals('$str must be string.', $ex->getMessage());
        }
    }
}
