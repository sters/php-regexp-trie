<?php
namespace RegexpTrie;

/**
 * RegexpTrie
 */
class RegexpTrie
{
    public static function union($strings = [])
    {
        return new static($strings);
    }

    public function __construct($strings = [])
    {
        $this->head = [];
        $this->add($strings);
    }

    public function add($str)
    {
        if (is_array($str)) {
            foreach ($str as $s) {
                $this->add($s);
            }

            return;
        }

        if (empty($str) || !is_string($str)) {
            throw new \InvalidArgumentException('$str must be string.');
        }

        $head = &$this->head;
        for ($i = 0, $m = mb_strlen($str); $i < $m; $i++) {
            $char = $str[$i];

            if (!isset($head[$char])) {
                $head[$char] = [];
            }

            $head = &$head[$char];
        }

        $head['end'] = true;

        return $this;
    }

    public function build(array $entry = null, $recursive = false)
    {
        if (is_null($entry)) {
            $entry = $this->head;
        }

        if (empty($entry)) {
            return null;
        }

        $alt = [];
        $cc = [];
        $q = false;

        foreach ($entry as $key => $value) {
            $qc = preg_quote($key, '/');

            if (empty($value) || $key === 'end') {
                $q = true;
                continue;
            }

            $recurse = $this->build($value, true);
            if (empty($recurse)) {
                $cc[] = $qc;
            } else {
                $alt[] = $qc . $recurse;
            }
        }

        $cconly = empty($alt);
        if (!empty($cc)) {
            $alt[] = count($cc) === 1 ? $cc[0] : ('[' . implode('', $cc) . ']');
        }

        var_dump($alt);
        $result = count($alt) === 1 ? $alt[0] : ('(?:' . implode('|', $alt) . ')');

        if ($q) {
            if ($cconly) {
                return $result;
            } else {
                return '(?:' . $result . ')';
            }
        } else {
            return $result;
        }

        if ($recursive === false) {
            $result = '/' . $result . '/';
        }

        return $result;
    }
}

