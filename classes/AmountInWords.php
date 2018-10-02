<?php namespace Initbiz\Money\Classes;

class AmountInWords
{
    public static function parse($amount, $locale="pl_PL")
    {
        if ($locale === "pl_PL") {
            return self::kwotaslownie($amount);
        }
    }

    public static function slowa()
    {
        return array(
            'minus',
            array( 'zero', 'jeden', 'dwa', 'trzy', 'cztery', 'pięć', 'sześć', 'siedem', 'osiem', 'dziewięć'),
            array( 'dziesięć', 'jedenaście', 'dwanaście', 'trzynaście', 'czternaście', 'piętnaście', 'szesnaście', 'siedemnaście', 'osiemnaście', 'dziewiętnaście'),
            array( 'dziesięć', 'dwadzieścia', 'trzydzieści', 'czterdzieści', 'pięćdziesiąt', 'sześćdziesiąt', 'siedemdziesiąt', 'osiemdziesiąt', 'dziewięćdziesiąt'),
            array( 'sto', 'dwieście', 'trzysta', 'czterysta', 'pięćset', 'sześćset', 'siedemset', 'osiemset', 'dziewięćset'),
            array( 'tysiąc', 'tysiące', 'tysięcy'),
            array( 'milion', 'miliony', 'milionów'),
            array( 'miliard', 'miliardy', 'miliardów'),
            array( 'bilion', 'biliony', 'bilionów'),
            array( 'biliard', 'biliardy', 'biliardów'),
            array( 'trylion', 'tryliony', 'trylionów'),
            array( 'tryliard', 'tryliardy', 'tryliardów'),
            array( 'kwadrylion', 'kwadryliony', 'kwadrylionów'),
            array( 'kwintylion', 'kwintyliony', 'kwintylionów'),
            array( 'sekstylion', 'sekstyliony', 'sekstylionów'),
            array( 'septylion', 'septyliony', 'septylionów'),
            array( 'oktylion', 'oktyliony', 'oktylionów'),
            array( 'nonylion', 'nonyliony', 'nonylionów'),
            array( 'decylion', 'decyliony', 'decylionów')
        );
    }

    public static function odmiana($odmiany, $int)
    {
        $txt = $odmiany[2];
        if ($int == 1) {
            $txt = $odmiany[0];
        }
        $jednosci = (int) substr($int, -1);
        $reszta = $int % 100;
        if (($jednosci > 1 && $jednosci < 5) &! ($reszta > 10 && $reszta < 20)) {
            $txt = $odmiany[1];
        }
        return $txt;
    }

    public static function liczba($int)
    {
        $slowa = self::slowa();
        $wynik = '';
        $j = abs((int) $int);

        if ($j == 0) {
            return $slowa[1][0];
        }
        $jednosci = $j % 10;
        $dziesiatki = ($j % 100 - $jednosci) / 10;
        $setki = ($j - $dziesiatki*10 - $jednosci) / 100;

        if ($setki > 0) {
            $wynik .= $slowa[4][$setki-1].' ';
        }

        if ($dziesiatki > 0) {
            if ($dziesiatki == 1) {
                $wynik .= $slowa[2][$jednosci].' ';
            } else {
                $wynik .= $slowa[3][$dziesiatki-1].' ';
            }
        }

        if ($jednosci > 0 && $dziesiatki != 1) {
            $wynik .= $slowa[1][$jednosci].' ';
        }
        return $wynik;
    }

    public static function slownie($int)
    {
        $slowa = self::slowa();

        $in = preg_replace('/[^-\d]+/', '', $int);
        $out = '';

        if ($in{0} == '-') {
            $in = substr($in, 1);
            $out = $slowa[0].' ';
        }

        $txt = str_split(strrev($in), 3);

        if ($in == 0) {
            $out = $slowa[1][0].' ';
        }

        for ($i = count($txt) - 1; $i >= 0; $i--) {
            $liczba = (int) strrev($txt[$i]);
            if ($liczba > 0) {
                if ($i == 0) {
                    $out .= self::liczba($liczba).' ';
                } else {
                    $out .= ($liczba > 1 ? self::liczba($liczba).' ' : '')
              .self::odmiana($slowa[4 + $i], $liczba).' ';
                }
            }
        }
        return trim($out);
    }

    public static function kwotaslownie($kwota)
    {
        $kwota = explode('.', $kwota);

        $zl = preg_replace('/[^-\d]+/', '', $kwota[0]);
        $gr = preg_replace('/[^\d]+/', '', substr(isset($kwota[1]) ? $kwota[1] : 0, 0, 2));
        while (strlen($gr) < 2) {
            $gr .= '0';
        }

        $slownie = self::slownie($zl) . ' ' .
                   self::odmiana(array('złoty', 'złote', 'złotych'), $zl) .
                   (intval($gr) == 0 ? '' :
                   ' ' . self::slownie($gr) . ' ' .
                   self::odmiana(array('grosz', 'grosze', 'groszy'), $gr));
        return $slownie;
    }
}
