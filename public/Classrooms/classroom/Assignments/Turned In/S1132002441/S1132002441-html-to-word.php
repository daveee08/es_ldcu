<?php

// We'll make sure errors and notices are disabled,
// otherwise this can interfere with the generated content
// and the exported file won't display
ini_set('display_errors', 0);

require_once('vendor/autoload.php');

$data = file_get_contents('sample.html');
$dom = new DOMDocument();
$dom->loadHTML($data);

// Now, extract the content we want to insert into our docx template

// 1 - The page title
$documentTitle = $dom->getElementById('title')->nodeValue;

// 2 - The article body content
$documentContent = $dom->getElementById('content')->nodeValue;

// Load the template processor
$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('template.docx');

// Swap out our variables for the HTML content
$templateProcessor->setValue('author', "Robin Metcalfe");
$templateProcessor->setValue('title', $documentTitle);
$templateProcessor->setValue('content', $documentContent);

header("Content-Description: File Transfer");
header('Content-Disposition: attachment; filename="generated.docx"');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');
$templateProcessor->saveAs("php://output");
exit;