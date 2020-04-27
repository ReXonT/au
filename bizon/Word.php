<?php

class Word
{
    static function toUniversalFormat($word)
    {
        $word = trim($word);
        $word = mb_strtolower($word);
        return $word;
    }

    static function findAtLeastOne(array $haystack, $keywords)
    {
        $keywords_array = self::getPreparedWordsArray($keywords);

        foreach ($keywords_array as $keyword)
        {
            foreach ($haystack as $text)
            {
                $text = self::toUniversalFormat($text);
                if(preg_match('/'.$keyword.'/', $text))
                    return true;
            }
        }
        return false;
    }

    static function findAll(array $haystack, $keywords)
    {
        $keywords_array = self::getPreparedWordsArray($keywords);

        $found_words = 0;
        foreach ($keywords_array as $keyword)
        {
            foreach ($haystack as $text)
            {
                $text = self::toUniversalFormat($text);
                if(preg_match('/'.$keyword.'/', $text))
                {
                    $found_words++;
                    break;
                }
            }
        }

        if($found_words == count($keywords_array))
            return true;
        return false;
    }

    static function findExactMatch(array $haystack, $keywords)
    {
        $keywords = self::toUniversalFormat($keywords);

        foreach ($haystack as $text)
        {
            $text = self::toUniversalFormat($text);
            if($text === $keywords)
                return true;
        }
        return false;
    }

    static function getPreparedWordsArray($keywords)
    {
        $keywords_array = explode(',', $keywords);
        foreach ($keywords_array as $key => $keyword)
            $keywords_array[$key] = self::toUniversalFormat($keyword);

        return $keywords_array;
    }
}