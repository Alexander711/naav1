<?php
defined('SYSPATH') or die('No direct script access.');
class Stemmer
{
    private static $_instance = NULL;
    const VOWEL = '/аеиоуыэюя/u';
    const PERFECTIVEGROUND = '/((ив|ивши|ившись|ыв|ывши|ывшись)|((?<=[ая])(в|вши|вшись)))$/u';
    const REFLEXIVE = '/(с[яь])$/u';
    const ADJECTIVE = '/(ее|ие|ые|ое|ими|ыми|ей|ий|ый|ой|ем|им|ым|ом|его|ого|еых|ую|юю|ая|яя|ою|ею)$/u';
    const PARTICIPLE = '/((ивш|ывш|ующ)|((?<=[ая])(ем|нн|вш|ющ|щ)))$/u';
    const VERB = '/((ила|ыла|ена|ейте|уйте|ите|или|ыли|ей|уй|ил|ыл|им|ым|ены|ить|ыть|ишь|ую|ю)|((?<=[ая])(ла|на|ете|йте|ли|й|л|ем|н|ло|но|ет|ют|ны|ть|ешь|нно)))$/u';
    const NOUN = '/(а|ев|ов|ие|ье|е|иями|ями|ами|еи|ии|и|ией|ей|ой|ий|й|и|ы|ь|ию|ью|ю|ия|ья|я)$/u';
    const RVRE = '/^(.*?[аеиоуыэюя])(.*)$/u';
    const DERIVATIONAL = '/[^аеиоуыэюя][аеиоуыэюя]+[^аеиоуыэюя]+[аеиоуыэюя].*(?<=о)сть?$/u';

    protected function _s(&$s, $re, $to)
    {
        $orig = $s;
        $s = preg_replace($re, $to, $s);
        return $orig !== $s;
    }

    protected function _m($s, $re)
    {
        return preg_match($re, $s);
    }
    private function __construct() { }
    public static function getInstance()
    {
        if (self::$_instance === NULL)
            self::$_instance = new Stemmer();
        return self::$_instance;
    }

    public function stem_word($word)
    {
        $word = mb_strtolower($word);
        $word = str_replace('ё', 'е', $word);


        $stem = $word;
        do {
          if (!preg_match(self::RVRE, $word, $p)) break;
          $start = $p[1];
          $RV = $p[2];
          if (!$RV) break;

          # Step 1
         if (!$this->_s($RV, self::PERFECTIVEGROUND, '')) {
              $this->_s($RV, self::REFLEXIVE, '');

              if ($this->_s($RV, self::ADJECTIVE, '')) {
                  $this->_s($RV, self::PARTICIPLE, '');
              } else {
                  if (!$this->_s($RV, self::VERB, ''))
                      $this->_s($RV, self::NOUN, '');
              }
          }

          # Step 2
         $this->_s($RV, '/и$/u', '');

          # Step 3
         if ($this->_m($RV, self::DERIVATIONAL))
              $this->_s($RV, '/ость?$/u', '');

          # Step 4
         if (!$this->_s($RV, '/ь$/u', '')) {
              $this->_s($RV, '/ейше?/u', '');
              $this->_s($RV, '/нн$/u', 'н');
          }

          $stem = $start.$RV;
        } while(false);

        return $stem;
    }



}