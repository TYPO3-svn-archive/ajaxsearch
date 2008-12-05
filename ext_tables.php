<?php
	if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
	
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
			'fe_admin_fieldList' => 'hidden, title, charset, language, mode, showall, showdesc, highlight, dbquery, dbfield, dblimit',
		)
	);
?>