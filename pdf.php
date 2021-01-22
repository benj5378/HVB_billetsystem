<?php

    require_once __DIR__ . '/vendor/autoload.php';

    $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];

    
    
    $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];

    $mpdf = new \Mpdf\Mpdf([
        'fontDir' => array_merge($fontDirs, [
            __DIR__ . '/fonts',
        ]),
        'fontdata' => $fontData + [
            'gilroy' => [
                'R' => 'Gilroy-Bold.ttf'
            ],
            'raleway' => [
                'R' => 'Raleway-Regular.ttf',
                'B' => 'Raleway-Bold.ttf'
            ],
            'gill' => [
                'R' => 'Gill-Sans-Regular.ttf',
                'RI' => 'Gill-Sans-Italic.ttf'
            ]
        ],
        'default_font' => 'gill'
    ]);
    $mpdf->AddFontDirectory("./fonts");
    $mpdf->WriteHTML(file_get_contents("ticketTemplate.html"));
    $mpdf->Output();

?>