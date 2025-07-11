<?php
class Logger
{

    public static function log($message, $eol = PHP_EOL)
    {
        echo $message . $eol ;
    }

    /*
        $list - list of arrays, the keys will be columns, the values will be cells of the table
    */
    public static function tblMap(array $list, $sep = ', ') {
        // collect all possible keys
        $keys = [];
        foreach ($list as $k=>$v) {
            $keys = array_merge($keys, array_keys($v));
        }
        $keys = array_unique($keys);

        $col_sizes = [];
        foreach ($keys as $k) {
            $col_sizes[$k] = strlen($k . $sep);
        }
        foreach ($list as $row) {
            foreach ($keys as $key) {
                if (isset($row[$key]) and strlen($row[$key] . $sep)>$col_sizes[$key]) {
                    $col_sizes[$key] = strlen($row[$key] . $sep);
                }
            }
        }

        // print headers line
        $s = '';
        foreach ($keys as $h) {
            $s .= str_pad($h.$sep, $col_sizes[$h], ' ', STR_PAD_LEFT) ;
        }
        $s = substr($s, 0, -1*strlen($sep));
        self::log($s);

        // print rows
        foreach ($list as $row) {
            $s = '';
            foreach ($keys as $key) {
                if (!isset($row[$key])) {
                    $s .= str_pad('-' . $sep, $col_sizes[$key], ' ', STR_PAD_LEFT);
                } else {
                    $s .= str_pad($row[$key] . $sep, $col_sizes[$key], ' ', STR_PAD_LEFT);
                }
            }
            $s = substr($s, 0, -1*strlen($sep));
            self::log($s);
        }
    }

    public static function companies($companies, $mask = [], $addons=[], $sep=', ') {
        // делаем плоский список атрибутов по каждой компании
        $lines = [];
        foreach ($companies as $company) {
            $l = [];
            foreach ($company->toArray() as $key=>$value) {
                if (empty($mask) or in_array($key, $mask)) {
                    $l[$key] = $value;
                }
            }
            $lines[] = array_merge($l, $addons);;
        }
        self::tblMap($lines, $sep);
    }

}