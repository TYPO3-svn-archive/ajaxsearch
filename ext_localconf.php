<?php
	if (!defined ('TYPO3_MODE')) die ('Access denied.');

	t3lib_extMgm::addUserTSConfig('options.saveDocNew.tx_ajaxsearch_config=1');

#	$TYPO3_CONF_VARS['EXTCONF']['cms']['db_layout']['addTables']['tx_ajaxsearch_config'][0] = array (
#		'fList' => 'title,charset,namespace,input',
#		'icon' => TRUE
#	);
	
	## Register eID
	$TYPO3_CONF_VARS['FE']['eID_include']['ajaxsearch'] = 'EXT:ajaxsearch/lib/ajaxsearch.php';
	
	# Add Frontend Plugin
	t3lib_extMgm::addPItoST43($_EXTKEY,'pi1/class.tx_ajaxsearch_pi1.php','_pi1','list_type',1);
?>