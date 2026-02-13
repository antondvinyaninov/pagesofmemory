<?php

if (!function_exists('expand_region_abbreviations')) {
    /**
     * Заменяет сокращения в названиях регионов на полные названия
     * 
     * @param string|null $text
     * @return string|null
     */
    function expand_region_abbreviations(?string $text): ?string
    {
        if (!$text) {
            return $text;
        }
        
        $replacements = [
            ' Респ' => ' Республика',
            ' обл' => ' область',
            ' край' => ' край',
            ' АО' => ' автономный округ',
            ' Аобл' => ' автономная область',
            ' г' => ' город',
        ];
        
        foreach ($replacements as $abbr => $full) {
            // Заменяем только если после сокращения нет других букв
            $text = preg_replace('/' . preg_quote($abbr, '/') . '(?![а-яА-Яa-zA-Z])/', $full, $text);
        }
        
        return $text;
    }
}
