<?php

// $number = 1234.56;
function Thousandsep($number)
{
    // $english_format_number = number_format($number);
    $english_format_number = number_format($number, 2, '.', ',');
    return $english_format_number;
}

// english notation (default)

// 1,235

// French notation
// $nombre_format_francais = number_format($number, 2, ',', ' ');
// 1 234,56

// $number = 1234.5678;

// english notation without thousands separator
// $english_format_number = number_format($number, 2, '.', '');
// 1234.57

?>
