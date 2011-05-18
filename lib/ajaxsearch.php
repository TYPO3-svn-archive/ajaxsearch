<?php
	/***************************************************************
	*  Copyright notice
	*
	*  (c) 2007 Andre Steiling (steiling@pilotprojekt.com)
	*  All rights reserved
	*
	*  This script is part of the TYPO3 project. The TYPO3 project is
	*  free software; you can redistribute it and/or modify
	*  it under the terms of the GNU General Public License as published by
	*  the Free Software Foundation; either version 2 of the License, or
	*  (at your option) any later version.
	*
	*  The GNU General Public License can be found at
	*  http://www.gnu.org/copyleft/gpl.html.
	*
	*  This script is distributed in the hope that it will be useful,
	*  but WITHOUT ANY WARRANTY; without even the implied warranty of
	*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	*  GNU General Public License for more details.
	*
	*  This copyright notice MUST APPEAR in all copies of the script!
	***************************************************************/

	/**
	* 'eID script' for the 'ajaxsearch' extension.
	*
	* @author Andre Steiling <steiling@elemente.ms>
	*/


	// Exit, if script is called directly (must be included via eID in index_ts.php)
	if (!defined ('PATH_typo3conf')) die ('Could not access this script directly!');

	
    require_once(PATH_tslib.'class.tslib_pibase.php');
	require_once(PATH_t3lib.'class.t3lib_befunc.php');
	
	
	class tx_ajaxsearch extends tslib_pibase {
	
		var $cacheArrResult = array(); // required for fill parameter markers, it's needed that the result is cached
		
		function main() {
			// DB connect
			tslib_eidtools::connectDB();

			// Post data
			$arrPost		= t3lib_div::_POST();
			
			// Config array
			$resConf		= $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'tx_ajaxsearch_config', 'uid='.intval($arrPost['ajaxsearch_uid']).' AND hidden=0 AND deleted=0');
			$arrConf		= $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resConf);
			
			// Search/Replace values
			$arrSearch		= array();
			$arrReplace		= array();
			
			// Locallang
			$LL				= t3lib_div::readLLfile(t3lib_extMgm::extPath('ajaxsearch').'lib/locallang.xml', strtolower($arrConf['language']));
			
			// Limit search to pagetree, siehe http://typo3blogger.de/kommaseparierte-liste-von-seiten-in-typo3/
			// Only marker ###PAGES### is replaced, WHERE clause has to be completed in record "AJAX configuration".
			if ($arrConf['pages']) {
				$treeLib		= t3lib_div::makeInstance('t3lib_queryGenerator');
				$pages			= $treeLib->getTreeList($arrConf['pages'], $arrConf['recursive'], 0, 1);
				$arrSearch[]	= '###PAGES###';
				$arrReplace[]	= $pages;	
			}

			// Search word
			$input			= $arrPost['value'];
			$input			= strtolower($arrConf['charset'])!='utf-8'?utf8_decode($input):$input;
			$input			= $GLOBALS['TYPO3_DB']->quoteStr($input, $arrConf['dbtable']);
			$arrSearch[]	= '###SWORD###';
			$arrReplace[]	= $input;
		
			// Database query
			$dbQuery		= str_replace($arrSearch, $arrReplace, $arrConf['dbquery']);
			$res			= $GLOBALS['TYPO3_DB']->sql_query($dbQuery);
			
			// Raw result
			$arrResult	= array();
			while (($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))) {
				$arrResult[] = $row;
			}

			// Prepare choises
			$choices	= '';
			if (sizeof($arrResult)>0) {
				// Show all hits
				$choices	.= $arrConf['showall']!=0?'<li id="tx-ajaxsearch-showall-'.time().'">'.$LL[$arrConf['language']]['showAll'].'</li>':'';
				
				// Limit choises
				$end = sizeof($arrResult)>$arrConf['dblimit']?$arrConf['dblimit']:sizeof($arrResult)-1;
				
				// Highlight input string
				for ($x=0; $x<=$end; $x++) {
					$choise		 = preg_replace('/('.$input.')/', '<strong>\\1</strong>', $arrResult[$x]['title']);
					
					// Show description
					if ($arrConf['showdesc'] != 0) {
						$choise  = $choise.'<br/>'.$arrResult[$x]['text'];
					}
					// Link "good luck" choise & Highlighting
					if ($arrConf['mode'] == 2) {
						$highlight	= $arrConf['highlight']!=0?'&no_cache=1&sword_list[0]='.$input:false;
						$this->cacheArrResult = $arrResult[$x];
						$parameters	= $arrConf['parameters']?'&'.preg_replace_callback("/###([a-zA-Z_0-9]+)###/i", array($this,'setParameterMarkers'),$arrConf['parameters']):'';
						$choise		= '<a href="index.php?id='.($arrConf['resultpage']?$arrConf['resultpage']:$arrResult[$x]['uid']).$highlight.$parameters.'">'.$choise.'</a>';
					}
					
					$choices	.= '<li id="c'.($x+1).'">'.$choise.'</li>';
				}
			} else {
				$choices		 = '<li class="noresult">'.$LL[$arrConf['language']]['noResult'].'</li>';
			}
		
			// Setup doctype - especially for MSIE
			// AST, 04.04.11: Warum eigentlich? Der Autocompleter erwarte eine unsortierte Liste, irgendwie ist das XML-Präfix hier hinein gerutscht ...
			// Funzt jedenfalls in allen IEs auch ohne und DANN auch wieder im FF 4!
//			$content = '<?xml version="1.0" encoding="'.$arrConf['charset'].'" '?'>';
			$content.= '<ul>'.$choices.'</ul>';
		
			// Header
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Last-Modified: '.gmdate( "D, d M Y H:i:s" ).'GMT');
			header('Cache-Control: no-cache, must-revalidate');
			header('Pragma: no-cache');
			header('Content-Type: text/xml; charset='.$arrConf['charset']);
			header('Content-Length: '.strlen($content));
			// Setup xml file IE - especially for MSIE
			header('Content-Disposition: inline; filename=ajaxsearch.xml');
		
			// Return
			echo $content;
			exit;
		}
		
		/**
		 * Methode to fill the parameter markers
		 *
		 * @param array $parameter
		 * @param unknown_type $test
		 * @return unknown
		 */
		function setParameterMarkers($parameter) {
			return $this->cacheArrResult[strtolower($parameter[1])];
		}
		
	}
	
	$output = t3lib_div::makeInstance('tx_ajaxsearch');
	$output->main();
?>