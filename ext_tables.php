<?php
	if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
	
	t3lib_div::loadTCA('tt_content');
	$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key,pages,recursive';
	$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1']='pi_flexform';
	
	t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:'.$_EXTKEY.'/flexform_ds.xml');
	
	t3lib_extMgm::addPlugin(array('LLL:EXT:ajaxsearch/locallang_db.xml:tt_content.list_type_pi1', $_EXTKEY.'_pi1'),'list_type');
	
	t3lib_extMgm::allowTableOnStandardPages('tx_ajaxsearch_config');
	
	$TCA['tx_ajaxsearch_config'] = array (
		'ctrl' => array (
			'title'     => 'LLL:EXT:ajaxsearch/locallang_db.xml:tx_ajaxsearch_config',		
			'label'     => 'title',	
			'tstamp'    => 'tstamp',
			'crdate'    => 'crdate',
			'cruser_id' => 'cruser_id',
			'default_sortby' => 'ORDER BY title',	
			'delete' => 'deleted',	
			'enablecolumns' => array (		
				'disabled' => 'hidden',
			),
			'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
			'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_ajaxsearch_config.png',
		),
		'feInterface' => array (
			'fe_admin_fieldList' => 'hidden, title, charset, language, mode, showall, showdesc, highlight, resultpage, dbquery, dbfield, dblimit',
		)
	);
?>