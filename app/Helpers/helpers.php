<?php

if (!function_exists('asset_path')) {
    function asset_path($path = null)
    {
        $prefix = 'public/';
        return $prefix . $path;
    }
}


if (!function_exists('amountInWords')) {
    function amountInWords($amount)
    {
        $amount = number_format((float) $amount, 2, '.', '');
    
        [$rupees, $paise] = explode('.', $amount);
    
        $formatter = new NumberFormatter('en_IN', NumberFormatter::SPELLOUT);
    
        $rupeesWords = $formatter->format((int) $rupees);
    
        $result = ucfirst($rupeesWords) . ' Rupees';
    
        if ((int) $paise > 0) {
            $paiseWords = $formatter->format((int) $paise);
            $result .= ' and ' . $paiseWords . ' Paise';
        }
    
        return $result . ' Only';
    }
}




?>