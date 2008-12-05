<?php
	if (!defined ('TYPO3_MODE')) die ('Access denied.');

	t3lib_extMgm::addUserTSConfig('options.saveDocNew.tx_ajaxsearch_config=1');

#	$TYPO3_CONF_VARS['EXTCONF']['cms']['db_layout']['addTables']['tx_ajaxsearch_config'][0] = array (
#		'fList' => 'title,charset,namespace,input',
#		'icon' => TRUE
#	);
	
	## Register eID
	$TYPO3_CONF_VARS['FE']['eID_include']['ajaxsearch'] = 'EXT:ajaxsearch/lib/ajaxsearch.php';
?>