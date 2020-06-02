<?php



namespace App\Helpers;



class ConvertToLang

{

    public function convertToLang($langInput)
    {
        $langList = explode('-', $langInput);
        $langOutput = '';

        foreach($langList as $lang)
        {
            switch($lang) {
                case 'vi':
                    $langOutput .= 'Vietnamese->';
                break;
                case 'en':
                    $langOutput .= 'English->';
                break;
                case 'ja':
                    $langOutput .= 'Japanese->';
                break;
            }
        }

        $langOutput = preg_replace('/->$/', '', $langOutput);

        return $langOutput;
    }
}