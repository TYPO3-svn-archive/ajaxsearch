<?php
	if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
	
	$TCA['tx_ajaxsearch_config'] = array (
		'ctrl' => $TCA['tx_ajaxsearch_config']['ctrl'],
		'interface' => array (
			'showRecordFieldList' => 'hidden,title,charset,language,mode,showall,showdesc,highlight,dbquery,dblimit'
		),
		'feInterface' => $TCA['tx_ajaxsearch_config']['feInterface'],
		'columns' => array (
			'hidden' => array (		
				'exclude' => 1,
				'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
				'config'  => array (
					'type'    => 'check',
					'default' => '0'
				)
			),
			'title' => Array (		
				'exclude' => 0,
				'label' => 'LLL:EXT:ajaxsearch/locallang_db.xml:tx_ajaxsearch_config.title',		
				'config' => Array (
					'type' => 'input',	
					'size' => '30',	
					'max' => '50',	
					'eval' => 'required,trim,nospace',
				)
			),
			'charset' => Array (		
				'exclude' => 0,		
				'label' => 'LLL:EXT:ajaxsearch/locallang_db.xml:tx_ajaxsearch_config.charset',		
				'config' => Array (
					'type' => 'input',	
					'size' => '30',	
					'max' => '10',	
					'eval' => 'required,trim,nospace',
				)
			),
			'language' => Array (		
				'exclude' => 0,		
				'label' => 'LLL:EXT:ajaxsearch/locallang_db.xml:tx_ajaxsearch_config.language',		
				'config' => Array (
					'type' => 'input',	
					'size' => '30',	
					'max' => '5',	
					'eval' => 'required,trim,nospace',
				)
			),
			'mode' => Array (        
	            'exclude' => 0,        
	            'label' => 'LLL:EXT:ajaxsearch/locallang_db.xml:tx_ajaxsearch_config.mode',        
	            'config' => Array (
	                'type' => 'radio',
	                'items' => Array (
	                    Array('LLL:EXT:ajaxsearch/locallang_db.xml:tx_ajaxsearch_config.mode.I.0', '1'),
	                    Array('LLL:EXT:ajaxsearch/locallang_db.xml:tx_ajaxsearch_config.mode.I.1', '2'),
	                ),
	            )
	        ),
			'showall' => Array (
				'exclude' => 0,
				'label' => 'LLL:EXT:ajaxsearch/locallang_db.xml:tx_ajaxsearch_config.showall',
				'config' => Array (
					'type' => 'check',
					'eval' => 'required',
				)
			),
			'showdesc' => Array (
				'exclude' => 0,
				'label' => 'LLL:EXT:ajaxsearch/locallang_db.xml:tx_ajaxsearch_config.showdesc',
				'config' => Array (
					'type' => 'check',
				)
			),
			'highlight' => Array (
				'exclude' => 0,
				'label' => 'LLL:EXT:ajaxsearch/locallang_db.xml:tx_ajaxsearch_config.highlight',
				'config' => Array (
					'type' => 'check',
				)
			),
			'dbquery' => Array (		
				'exclude' => 0,		
				'label' => 'LLL:EXT:ajaxsearch/locallang_db.xml:tx_ajaxsearch_config.dbquery',		
				'config' => Array (
					'type' => 'text',
					'cols' => '30',	
					'rows' => '5',
				)
			),
			'dblimit' => Array (		
				'exclude' => 0,		
				'label' => 'LLL:EXT:ajaxsearch/locallang_db.xml:tx_ajaxsearch_config.dblimit',		
				'config' => Array (
					'type' => 'input',	
					'size' => '30',	
					'max' => '4',	
					'range' => Array ('lower'=>0,'upper'=>1000),	
					'eval' => 'required,int,nospace',
				)
			),
		),
		'types' => array (
			'0' => array('showitem' => 'hidden;;1;;1-1-1, title;;;;4-4-4, charset;;;;1-1-1, language, mode;;;;4-4-4, showall, showdesc, highlight, dbquery;;;;1-1-1, dblimit')
		),
		'palettes' => array (
			'1' => array('showitem' => '')
		)
	);
?>