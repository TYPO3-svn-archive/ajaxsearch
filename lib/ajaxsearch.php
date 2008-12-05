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
	
	
	class tx_ajaxsearch extends tslib_pibase {
	
		function main() {
			// DB connect
			tslib_eidtools::connectDB();

			// Post data
			$arrPost	= t3lib_div::_POST();
			
			// Config array
			$resConf	= $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'tx_ajaxsearch_config', 'uid='.intval($arrPost['ajaxsearch_uid']).' AND hidden=0 AND deleted=0');
			$arrConf	= $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resConf);
			
			// Local locallang
			$LL			= t3lib_div::readLLfile(t3lib_extMgm::extPath('ajaxsearch').'lib/locallang.xml', $arrConf['language']);
			
			// Prepare input
			$input		= $arrPost['value'];
			$input		= strtolower($arrConf['charset'])!='utf8'?utf8_decode($input):$input;
			$input		= $GLOBALS['TYPO3_DB']->quoteStr($input, $arrConf['dbtable']);
		
			// Prepare query
			$dbQuery	= str_replace('###SWORD###', $input, $arrConf['dbquery']);
			$res		= $GLOBALS['TYPO3_DB']->sql_query($dbQuery);
			
			// Raw result
			$arrResult	= array();
			while (($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))) {
				$arrResult[] = $row;
			}

			// Prepare choises
			$choices	= '';
			if (sizeof($arrResult)>0) {
				// Show all hits
				$choices	.= $arrConf['showall']!=0?'<li id="tx-ajaxsearch-showall">'.$LL[$arrConf['language']]['showAll'].'</li>':'';
				
				// Limit choises
				$end = sizeof($arrResult)>$arrConf['dblimit']?$arrConf['dblimit']:sizeof($arrResult)-1;
				
				// Highlight input string
				for ($x=0; $x<=$end; $x++) {
					$choise		 = eregi_replace('('.$input.')', '<strong>\\1</strong>', $arrResult[$x]['title']);
					
					// Show description
					if ($arrConf['showdesc'] != 0) {
						$choise  = $choise.'<br/>'.$arrResult[$x]['text'];
					}
					// Link "good luck" choise & Highlighting
					if ($arrConf['mode'] == 2) {
						$highlight = $arrConf['highlight']!=0?'&no_cache=1&sword_list[0]='.$input:false; 
						$choise  = '<a href="index.php?id='.$arrResult[$x]['uid'].$highlight.'">'.$choise.'</a>';
					}
					
					$choices	.= '<li id="c'.($x+1).'">'.$choise.'</li>';
				}
			} else {
				$choices		 = '<li class="noresult">'.$LL[$arrConf['language']]['noResult'].'</li>';
			}
		
			// Setup doctype - especially for MSIE
			$content = '<?xml version="1.0" encoding="ISO-8859-1"?>';
			$content = '<ul>'.$choices.'</ul>';
		
			// Header
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Last-Modified: '.gmdate( "D, d M Y H:i:s" ).'GMT');
			header('Cache-Control: no-cache, must-revalidate');
			header('Pragma: no-cache');
			header('Content-Type: text/xml; charset='.$charset);
			header('Content-Length: '.strlen($content));
			// Setup xml file IE - especially for MSIE
			header('Content-Disposition: inline; filename=ajaxsearch.xml');
		
			// Return
			echo $content;
			exit;
		}
	}
	
	$output = t3lib_div::makeInstance('tx_ajaxsearch');
	$output->main();
?>