<?php
namespace App\Support;

class NameCase
{
    public static function person(string $name): string
    {
        $name = trim(preg_replace('/\s+/', ' ', $name));
        $name = mb_strtolower($name, 'UTF-8');

        $particles = ['da','de','do','das','dos','e',"d'","d’"];

        $cap = function (string $w, bool $force = false): string {
            if ($w === '') return $w;
            if (!$force && in_array($w, ['da','de','do','das','dos','e',"d'","d’"], true)) return $w;

            // respeita hífen/apóstrofo
            return preg_replace_callback('/(^.|(?<=[-\'’]).)/u', function ($m) {
                return mb_strtoupper($m[0], 'UTF-8');
            }, $w);
        };

        $tokens = explode(' ', $name);
        $last = count($tokens) - 1;

        foreach ($tokens as $i => $t) {
            $force = ($i === 0 || $i === $last); // 1ª e última sempre capitaliza
            $tokens[$i] = $cap($t, $force);
        }

        // ajusta d' (minúsculo) antes do sobrenome capitalizado
        $out = implode(' ', $tokens);
        $out = preg_replace("/\bD([’'])/u", 'd$1', $out);

        return $out;
    }
}
