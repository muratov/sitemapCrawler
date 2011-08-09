<?php
include_once 'phpQuery-onefile.php';
	phpQuery::newDocumentFileHTML('http://www.zapiter.ru/index.php?q=sitemap.xml');

	$results = pq('loc');
	foreach ($results as $loc) {
		echo $loc;
	}
?>