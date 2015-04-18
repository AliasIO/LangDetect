<?php

error_reporting(-1);

chdir(dirname(__FILE__));

require 'vendor/autoload.php';

try {
	$results = new \StdClass;

	$html = '';

	while ( $line = fgets(STDIN) ) {
		$html .= $line;
	}

	if ( $html ) {
		$doc = new \DOMDocument();

		libxml_use_internal_errors(true);

		$doc->loadHTML($html);

		libxml_clear_errors();

		$xpath = new \DOMXpath($doc);

		$textNodes = $xpath->query('//text()');

		$text = '';

		foreach ( $textNodes as $textNode ) {
			$text .= $textNode->textContent . "\n";
		}

		$detect = LanguageDetector\Detect::initByPath('vendor/crodas/languagedetector/example/datafile.php');

		$language = $detect->detect($text);

		if ( gettype($language) != 'string' ) {
			$language = null;
		}

		$results->languages = [ $language ];
	}

	echo json_encode($results) . "\n";
} catch ( \Exception $e ) {
	echo $e->getMessage() . "\n";

	exit(1);
}

exit(0);
