<?php
include_once 'phpQuery-onefile.php';
	class sitemapParser {
		public function __construct($sitemapURL = 'http://www.zapiter.ru/index.php?q=sitemap.xml') {
			phpQuery::newDocumentFileXML($sitemapURL);

			if (pq('sitemap')->length) {
				$currentMap = pq('loc');
				foreach ($currentMap as $currentURL) {
					phpQuery::newDocumentFileXML(pq($currentURL)->text());
					$this->updateDB(pq('loc'));
				}
			}
			elseif (pq('urlset')->length) {
				$this->updateDB(pq('loc'));
			} else {
				//нихуя
				return false;
			}

		}

		private static function updateDB($input) {
			$insertString = '';
			foreach ($input as $loc) {
				$insertString .= "(NULL, '" . pq($loc)->text() . "'),";
			}

			$insertString = substr($insertString, 0, strlen($insertString) - 1);


			$link = mysql_connect('localhost', 'mouratov_seo', '');
			mysql_query("SET NAMES 'utf8'");

			$insertTail = 'INSERT IGNORE INTO `mouratov_seo`.`sitemapCrawler_query` (`query_id`, `query_loc`) VALUES ';
			$insertString = $insertTail . $insertString;
			$q_result = mysql_query($insertString);

			if (!$q_result) {
				die('Invalid query: ' . mysql_error());
			}

			mysql_close($link);
		}

	}

?>