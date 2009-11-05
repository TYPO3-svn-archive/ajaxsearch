<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Pascal Hinz <hinz@elemente.ms>
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

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'AjaxSearch' for the 'ajaxsearch' extension.
 *
 * @author Pascal Hinz <hinz@elemente.ms>
 * @package TYPO3
 * @subpackage tx_ajaxsearch
 */
class tx_ajaxsearch_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_ajaxsearch_pi1';        // Same as class name
	var $scriptRelPath = 'pi1/class.tx_ajaxsearch_pi1.php';    // Path to this script relative to the extension dir.
	var $extKey        = 'ajaxsearch';    // The extension key.
	var $pi_checkCHash = true;
	var $templateCode  = '';
	var $templateMarker = '###VIEW_SEARCHFORM###';

	// flexform values
	var $ajaxSearchConfigurationUid	= 0;
	var $ajaxSearchResultPageUid	= 0;
	var $ajaxSearchLegendLabel		= '';
	
	// ajaxsearch configuration values
	var $ajaxSearchConfigurationTable = 'tx_ajaxsearch_config';
	
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param    string        $content: The PlugIn content
	 * @param    array        $conf: The PlugIn configuration
	 * @return    The content that is displayed on the website
	 */
	function main($content,$conf)    {
		$this->conf=$conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		
		// init flexform
		$this->pi_initPIflexForm();
		
		// get values
		$this->ajaxSearchConfigurationUid	= (int) $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'ajaxsearch_config');
 		$this->ajaxSearchResultPageUid		= (int) $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'ajaxsearch_resultpage');
 		$this->ajaxSearchLegendLabel		= (string) $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'ajaxsearch_legend');
		
 		// set template
 		$this->templateCode = $this->cObj->fileResource($this->conf['template']);
 		
 		// render template
 		$content = $this->renderSearchForm();
 		
		// return
		return $this->pi_wrapInBaseClass($content);
	}
	
	
	/**
	 * Render the search form
	 *
	 * @return unknown
	 */
	function renderSearchForm() {
		if(!$this->templateCode || !$this->ajaxSearchConfigurationUid || !$this->templateMarker || !$this->ajaxSearchResultPageUid) { return ''; }

		$markerArray = array(
			'###ACTION###'					=> $this->pi_getPageLink($this->ajaxSearchResultPageUid, '', array()),
			'###UID###'						=> $this->cObj->data['uid'],
			'###CONFIGURATION###'			=> $this->ajaxSearchConfigurationUid,
			'###LEGEND###'					=> $this->ajaxSearchLegendLabel,
			'###LABEL_SEARCHWORD###'		=> $this->pi_getLL('labelSearchWord'),
			'###LABEL_SEARCHWORDTITLE###'	=> $this->pi_getLL('labelSearchWordTitle'),
			'###LABEL_SEARCH###'			=> $this->pi_getLL('labelSearch'),
			'###LABEL_SEARCHTITLE###'		=> $this->pi_getLL('labelSearchTitle'),
		);
		$content = $this->cObj->getSubpart($this->templateCode, $this->templateMarker);
		$content = $this->cObj->substituteMarkerArray($content, $markerArray);
		return $content;
	}

}

?>