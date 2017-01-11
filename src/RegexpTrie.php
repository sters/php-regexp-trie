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

            return $this;
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

        $head['end'] = false;

        return $this;
    }

    public function build(array $entry = null)
    {
        if (is_null($entry)) {
            $entry = $this->head;
        }

        if (empty($entry) || (count($entry) === 1 && isset($entry['end']))) {
            return null;
        }

        $alt = [];
        $cc = [];
        $q = false;

        foreach ($entry as $key => $value) {
            $qc = preg_quote($key, '/');

            if (empty($value)) {
                $q = true;
                continue;
            }

            $recurse = $this->build($value);
            if (is_null($recurse)) {
                $cc[] = $qc;
            } else {
                $alt[] = $qc . $recurse;
            }
        }

        $cconly = empty($alt);
        if (!empty($cc)) {
            $alt[] = count($cc) === 1 ? $cc[0] : ('[' . implode('', $cc) . ']');
        }

        $result = count($alt) === 1 ? $alt[0] : ('(?:' . implode('|', $alt) . ')');

        if ($q) {
            if ($cconly) {
                $result = $result . '?';
            } else {
                $result = '(?:' . $result . ')?';
            }
        }

        return $result;
    }

    public function toRegexp()
    {
        return '/' . $this->build() . '/';
    }
}

