<?php
/***************************************************************
*  Copyright notice
*
*  (c) 1999-2008 Kasper Skårhøj (kasperYYYY@typo3.com)
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
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Part of the tt_products (Shop System) extension.
 *
 * Creates a list of products for the shopping basket in TYPO3.
 * Also controls basket, searching and payment.
 *
 *
 * $Id$
 *
 * @author	Kasper Skårhøj <kasperYYYY@typo3.com>
 * @author	René Fritz <r.fritz@colorcube.de>
 * @author	Franz Holzinger <franz@ttproducts.de>
 * @author	Klaus Zierer <zierer@pz-systeme.de>
 * @author	Milosz Klosowicz <typo3@miklobit.com>
 * @author	Els Verberne <verberne@bendoo.nl>
 * @package TYPO3
 * @subpackage tt_products
 * @see file tt_products/ext_typoscript_constants.txt
 * @see TSref
 *
 *
 */

require_once(PATH_BE_fh_library.'/sysext/cms/tslib/class.fhlibrary_pibase.php');
require_once(PATH_t3lib.'class.t3lib_parsehtml.php');


require_once(PATH_BE_table.'lib/class.tx_table_db.php');


require_once (PATH_BE_ttproducts.'control/class.tx_ttproducts_control.php');
require_once (PATH_BE_ttproducts.'model/class.tx_ttproducts_article.php');
require_once (PATH_BE_ttproducts.'model/class.tx_ttproducts_basket.php');
require_once (PATH_BE_ttproducts.'model/class.tx_ttproducts_category.php');
require_once (PATH_BE_ttproducts.'lib/class.tx_ttproducts_config.php');
require_once (PATH_BE_ttproducts.'model/class.tx_ttproducts_content.php');
require_once (PATH_BE_ttproducts.'lib/class.tx_ttproducts_creditpoints_div.php');
require_once (PATH_BE_ttproducts.'control/class.tx_ttproducts_javascript.php');
require_once (PATH_BE_ttproducts.'lib/class.tx_ttproducts_email.php');
require_once (PATH_BE_ttproducts.'model/class.tx_ttproducts_feuser.php');
require_once (PATH_BE_ttproducts.'lib/class.tx_ttproducts_gifts_div.php');
require_once (PATH_BE_ttproducts.'model/class.tx_ttproducts_page.php');
require_once (PATH_BE_ttproducts.'lib/class.tx_ttproducts_paymentshipping.php');
require_once (PATH_BE_ttproducts.'lib/class.tx_ttproducts_price.php');
require_once (PATH_BE_ttproducts.'lib/class.tx_ttproducts_pricecalc.php');
require_once (PATH_BE_ttproducts.'model/class.tx_ttproducts_product.php');
require_once (PATH_BE_ttproducts.'view/class.tx_ttproducts_single_view.php');


class tx_ttproducts_pi1 extends fhlibrary_pibase {
	var $prefixId = 'tx_ttproducts_pi1';	// Same as class name
	var $scriptRelPath = 'pi1/class.tx_ttproducts_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey = TT_PRODUCTS_EXTkey;	// The extension key.
	var $version;			// version number

	var $cObj;		// The backReference to the mother cObj object set at call time

		// Internal
	var $uid_list='';					// List of existing uid's from the basket, set by initBasket()
	var $pid_list;						// list of page ids
	var $orderRecord = array();			// Will hold the order record if fetched.

		// Internal: init():
	var $templateCode='';				// In init(), set to the content of the templateFile.
	var $templateFile;
	var $config=array();				// updated configuration
	var $cnf;							// object for configuration purposes
	var $tt_product_single=array();
	var $globalMarkerArray=array();
	var $externalCObject='';
		// mkl - multicurrency support
	var $currency = '';				// currency iso code for selected currency
	var $baseCurrency = '';			// currency iso code for default shop currency
	var $xrate = 1.0;				// currency exchange rate (currency/baseCurrency)

	var $mkl; 						// if compatible to mkl_products
	var $tt_products; 				// object of the type tx_ttproducts_product
	var $tt_products_articles;		// object of the type tx_table_db
	var $tt_products_cat; 			// object of the type tx_ttproducts_category

	var $tt_content; 				// object of the type tx_ttproducts_content
	var $page;	 					// object of the type tx_ttproducts_page
	var $paymentshipping; 			// object of the type tx_ttproducts_paymentshipping
	var $fe_users;					// object of the type tx_table_db
	var $price;	 					// object for price functions

	var $basket;					// basket object
	var $control;					// object for the control of the application
	var $singleView;				// single view object
	var $memoView;					// memo view and data object

	var $pi_checkCHash = TRUE;		// activate cHash
	var $pid;						// the page to which the script shall go

	var $xajax;						// xajax object
	var $javascript;				// JavaScript object
	var $codeArray;					// Codes
	var $tableArray;					// contains the table name as key and the part of the file name for the corresponding data and view
	var $template_suffix; 			// suffix for template subpart area markers
	var $bNoCachePossible = TRUE;	// if the cache may be turned off
	var $pageAsCategory;			// > 0 if pages are used as categories


	/*
	 * Escapes strings to be included in javascript
	 */
	function jsspecialchars($s) {
	   return preg_replace('/([\x1b-\x2f\x3a-\x40\x5b-\x60\x7b-\x7e])/e',
	       "'\\x'.(ord('\\1')<16? '0': '').dechex(ord('\\1'))",$s);
	}


	/**
	 * Main method. Call this from TypoScript by a USER cObject.
	 */
	function main($content,$conf)	{
		global $TSFE, $TYPO3_CONF_VARS;

		$this->conf = &$conf;
		$backPID = $this->piVars['backPID'];
		$backPID = ($backPID ? $backPID : t3lib_div::_GP('backPID'));

		$eInfo = $this->getExtensionInfo(TT_PRODUCTS_EXTkey);
		$this->version = $eInfo['version'];
		$this->pi_initPIflexForm();
		$config['code'] =
			$this->pi_getSetupOrFFvalue(
	 			$this->conf['code'],
	 			$this->conf['code.'],
				$this->conf['defaultCode'],
				$this->cObj->data['pi_flexform'],
				'display_mode',
				$TYPO3_CONF_VARS['EXTCONF'][TT_PRODUCTS_EXTkey]['useFlexforms']
				);

		$this->codeArray=t3lib_div::trimExplode(',', $config['code'],1);

			// initialise AJAX at the beginning because the AJAX functions can set piVars
		if (t3lib_extMgm::isLoaded('xajax')) {
			include_once(t3lib_extMgm::extPath('xajax').'class.tx_xajax.php');
			$this->xajax = t3lib_div::makeInstance('tx_xajax');
				// Decode form vars from utf8
			// $this->xajax->decodeUTF8InputOn();
				// Encoding of the response to iso-8859-1.
			$this->xajax->setCharEncoding('iso-8859-1');

				// Encoding of the response to utf-8.
			// $this->xajax->setCharEncoding('utf-8');
				// To prevent conflicts, prepend the extension prefix.
			// $this->xajax->setWrapperPrefix($this->prefixId);
			$this->xajax->setWrapperPrefix('');
				// Do you want messages in the status bar?
			// $this->xajax->statusMessagesOn();
				// Turn only on during testing
			// $this->xajax->debugOff();
			$reqURI = t3lib_div::getIndpEnv('TYPO3_REQUEST_SCRIPT') . '?' . t3lib_div::getIndpEnv('QUERY_STRING');
			// $reqURI .= $this->jsspecialchars('&no_cache=1');

			$this->xajax->setRequestURI($reqURI);
			$this->xajax->setWrapperPrefix('');
			$this->xajax->registerFunction(array('tt_products_showArticle',&$this,'tt_products_showArticle'));
			$this->xajax->registerFunction(array('tt_products_changeValue',&$this,'tt_products_changeValue'));
			$this->xajax->registerFunction(array('tt_products_processFormData',&$this,'tt_products_processFormData'));
		}

		// page where to go usually
		$this->pid = ($conf['PIDbasket'] && $conf['clickIntoBasket'] ? $conf['PIDbasket'] : ($backPID ? $backPID : $TSFE->id));

		if (t3lib_extMgm::isLoaded('xajax')) {
			if($_POST['xajax']){
				global $trans;
				$trans = $this;
				$this->xajax->processRequests();
				exit();
			}
		}

			// only for testing purposes
		if (is_array($conf['userFunc.']) && strstr($conf['userFunc.'],'callTime'))	{
			$this->bNoCachePossible = FALSE;
			foreach ($conf['userFunc.'] as $part => $setup) {
			    if(!strpos($part,'.')) {
				$content .= $this->cObj->cObjGetSingle($setup,$conf['userFunc.'][$part.'.']);
			    }
			}
		} else {
			$content = $this->doProcessing($content, false);
		}

		return $content;
	}


// some temporary speed testing functions
	function callTimeBasket() {
		$this->codeArray[] = 'BASKET';
		$content = $this->doProcessing($content, false);
		$content .= strftime('%D - %H:%M:%S',time());
	    return $content;
	}

	function callTimeSingle() {
		$this->codeArray[] = 'SINGLE';
		$content = $this->doProcessing($content, false);
		$content .= strftime('%D - %H:%M:%S',time());
	    return $content;
	}

	function callTimeCombined1() {
		$this->codeArray[] = 'BASKET';
		$this->codeArray[] = 'SINGLE';
		$content = $this->doProcessing($content, false);
		$content .= strftime('%D - %H:%M:%S',time());
	    return $content;
	}

	function callTimeCombined2() {
		$this->codeArray[] = 'SINGLE';
		$this->codeArray[] = 'BASKET';
		$content = $this->doProcessing($content, false);
		$content .= strftime('%D - %H:%M:%S',time());
	    return $content;
	}

	function set_no_cache() {
		global $TSFE;

		if ($this->bNoCachePossible)	{
			$TSFE->set_no_cache();
		}
	}

	function &getTemplateCode($theCode) {
		$templateCode = '';
		$templateFile = $this->cnf->getTemplateFile($theCode);
		if ($templateFile) {
			// template file is fetched. The whole template file from which the various subpart are extracted.
			$templateCode = $this->cObj->fileResource($templateFile);
		}

		if (!$templateFile || empty($templateCode)) {
			if ($this->conf['templateFile.'][$theCode])	{
				$tmplText = $theCode.'.';
			}
			$tmplText .= 'templateFile';
			$this->errorMessage .= $this->pi_getLL('no_template').' plugin.tt_products.'.$tmplText.' = ';
			$this->errorMessage .= ($this->conf['templateFile'] ? "'".$this->conf['templateFile']."'" : '""');
		} else {
				// Substitute Global Marker Array
			$templateCode = $this->cObj->substituteMarkerArrayCached($templateCode, $this->globalMarkerArray);
		}

		$this->templateFile = $templateFile;
		return $templateCode;
	}

	function doProcessing($content='', $bRunAjax = false)	{
		global $TSFE;
		global $TYPO3_CONF_VARS; // needed for include_once and PHP 5.1 which otherwise would not allow XCLASS for HtmlMail, DAM aso.
		$bStoreBasket = TRUE;

		if (!count($this->codeArray) && !$bRunAjax)	{
			$this->codeArray = array('HELP');
		}
		$this->init ($content, $this->config);

		if ((t3lib_extMgm::isLoaded('xajax')) && !$bRunAjax)	{
			$this->javascript->set('xajax');
		}

		$error_code = array();

		if (t3lib_div::_GP('mode_update'))	{
			$updateMode = 1;
		} else {
			$updateMode = 0;
		}
		if (count ($this->codeArray) == 1 && $this->codeArray[0] == 'OVERVIEW')	{
			$bStoreBasket = FALSE;
		}

		if (!$this->errorMessage) {
			$this->basket->init(
				$this,
				$this->cnf,
				$TSFE->fe_user->getKey('ses','recs'),
				$updateMode,
				$this->pid_list,
				$this->tt_content,
				$this->tt_products,
				$this->tt_products_articles,
				$this->tt_products_cat,
				$this->fe_users,
				$this->price,
				$this->paymentshipping,
				$bStoreBasket
			);
		}

		// *************************************
		// *** Listing items:
		// *************************************
		if (!$this->errorMessage) {
			$this->basket->getCalculatedBasket(); // get the basket->itemArray
			$this->templateCode=$this->getTemplateCode('BASKET');

			$this->control->init (
				$this,
				$this->cnf,
				$this->templateCode,
				$this->basket,
				$this->tt_products,
				$this->tt_products_articles,
				$this->tt_products_cat,
				$this->fe_users,
				$this->price,
				$this->paymentshipping
			);

			$content .= $this->control->doProcessing($this->codeArray, $this->errorMessage);
		}
		reset($this->codeArray);

		// $TSFE->set_no_cache(); uncomment this line if you have a problem with the cache
		while(!$this->errorMessage && list($key,$theCode)=each($this->codeArray))	{
			$theCode = (string) trim($theCode);
			$contentTmp = '';
			$this->templateCode=$this->getTemplateCode($theCode);
			switch($theCode)	{
				case 'SEARCH':
					$this->set_no_cache();
					// no break!
				case 'LIST':
				case 'LISTGIFTS':
				case 'LISTHIGHLIGHTS':
				case 'LISTNEWITEMS':
				case 'LISTOFFERS':
				case 'LISTARTICLES':
					if (count($this->tt_product_single) || !$this->conf['NoSingleViewOnList']) {
						$this->set_no_cache();
					}

					if (count($this->basket->itemArray)) {
						$this->set_no_cache();
					}
					$contentTmp = $this->products_display($theCode, $this->errorMessage, $error_code);
				break;
				case 'SELECTCAT':
				case 'SELECTAD':
				case 'LISTCAT':
					include_once (PATH_BE_ttproducts.'view/class.tx_ttproducts_catlist_view.php');
						// category view
					$categoryView = t3lib_div::makeInstance('tx_ttproducts_catlist_view');
					$categoryView->init($this, $this->cnf, $this->basket, $this->pid_list, $this->tt_content, $this->tt_products_cat, $this->pid);
					$htmlTagMain = $this->conf['displayCatListType'];

					if ($theCode == 'LISTCAT')	{
						$contentTmp = $categoryView->printView(
								$this->templateCode,
								$error_code,
								$this->pageAsCategory,
								$htmlTagMain,
								$this->template_suffix
						);
					} else {
						$templateArea = 'ITEM_CATEGORY_SELECT_TEMPLATE';
						if ($theCode == 'SELECTAD')	{
							if ($this->conf['table.']['tt_address.']['name'] != 'tt_address' ||
							t3lib_extMgm::isLoaded(TT_ADDRESS_EXTkey)) {
								include_once (PATH_BE_ttproducts.'model/class.tx_ttproducts_address.php');
								$categoryTable = t3lib_div::makeInstance('tx_ttproducts_address');
								$categoryTable->init(
									$this,
									$this->cnf,
									$this->conf['table.']['tt_address']
								);
								$templateArea = 'ITEM_ADDRESS_SELECT_TEMPLATE';
							} else {
								$message = $this->pibase->pi_getLL('extension_missing');
								$messageArr =  explode('|', $message);
								$this->errorMessage=$messageArr[0]. TT_ADDRESS_EXTkey .$messageArr[1];
							}
						} else {
							if ($this->pageAsCategory)	{
								$categoryTable = &$this->page;
							} else {
								$categoryTable = &$this->tt_products_cat;
							}
						}

						if (!$this->errorMessage)	{
							include_once (PATH_BE_ttproducts.'view/class.tx_ttproducts_selectcat_view.php');
								// category select view
							$selectCatView = t3lib_div::makeInstance('tx_ttproducts_selectcat_view');
							$selectCatView->init($this, $this->cnf, $this->basket, $this->pid_list, $this->tt_content, $categoryTable, $this->javascript, $this->pid);
							$contentTmp = $selectCatView->printView(
								$categoryView,
								$this->templateCode,
								$theCode,
								$error_code,
								$templateArea,
								$this->pageAsCategory,
								$this->templateSuffix
							);
						}
					}

				break;
				case 'SINGLE':
					if (count($this->basket->itemArray) || !$this->conf['NoSingleViewOnList'] &&
						!$this->conf['PIDitemDisplay'] && !$this->conf['PIDitemDisplay.']) {
						$this->set_no_cache();
					}
					$contentTmp = $this->products_display($theCode, $this->errorMessage, $error_code);
				break;
				case 'OVERVIEW':
					if (count($this->basket->itemArray)) {
						$this->set_no_cache();
					}
					break;
				case 'BASKET':
				case 'FINALIZE':
				case 'INFO':
				case 'PAYMENT':
					$this->set_no_cache();
						// nothing here any more. This work is done in the control processing before
						// This line is necessary because some activities might have overriden these CODEs
				break;
				case 'BILL':
				case 'DELIVERY':
					include_once (PATH_BE_ttproducts.'lib/class.tx_ttproducts_billdelivery.php');
					$this->set_no_cache();
					$contentTmp=$this->products_tracking($theCode);
				break;
				case 'TRACKING':
					include_once (PATH_BE_ttproducts.'lib/class.tx_ttproducts_tracking.php');
					$this->set_no_cache();
					$contentTmp=$this->products_tracking($theCode);
				break;
				case 'MEMO':
					$this->set_no_cache();
					include_once (PATH_BE_ttproducts.'view/class.tx_ttproducts_memo_view.php');

						// memo view: has to be called always because it reads parameters from the list
					$this->memoView = t3lib_div::makeInstance('tx_ttproducts_memo_view');
					$this->memoView->init(
						$this,
						$this->cnf,
						$this->basket,
						$this->pid_list,
						$this->tt_content,
						$this->tt_products,
						$this->tt_products_cat,
						$this->tt_products_articles,
						$this->fe_users,
						$this->pid,
						$this->LLkey,
						$this->conf['useArticles']
					);
					$contentTmp=$this->memoView->printView($this->templateCode, $error_code);
				break;
				case 'CURRENCY':
					include_once (PATH_BE_ttproducts.'view/class.tx_ttproducts_currency_view.php');
					$this->set_no_cache();
						// currency view
					$currencyView = t3lib_div::makeInstance('tx_ttproducts_currency_view');
					$currencyView->init($this, $this->cnf, $this->basket);

					$contentTmp=$currencyView->printView();
				break;
				case 'ORDERS':
					include_once (PATH_BE_ttproducts.'view/class.tx_ttproducts_order_view.php');
					$this->set_no_cache();

						// order view
					$orderView = t3lib_div::makeInstance('tx_ttproducts_order_view');
					$orderView->init($this, $this->cnf, $this->basket, $TSFE->fe_user->getKey('ses','recs'), $this->fe_users, $this->paymentshipping);
					$contentTmp=$orderView->printView($this->templateCode, $error_code);
				break;
				default:	// 'HELP'
					$this->set_no_cache();
					$contentTmp = 'error';
				break;
			}

			if ($error_code[0]) {
				$messageArr = array();
				$i = 0;
				foreach ($error_code as $key => $indice) {
					if ($key == 0) {
						$messageArr = explode('|', $message = $this->pi_getLL($indice));
						$contentTmp .= $this->pi_getLL('tt_products').' '.$messageArr[0];
					} else {
						$contentTmp .= $indice.$messageArr[$i];
					}
					$i++;
				}
				// $contentTmp.=$messageArr[0].intval($this->uid) .$messageArr[1];
			}

			if ($contentTmp == 'error') {
					$fileName = 'EXT:'.TT_PRODUCTS_EXTkey.'/template/products_help.tmpl';
					$helpTemplate = $this->cObj->fileResource($fileName);
					$content .= $this->pi_displayHelpPage($helpTemplate, $theCode);
					unset($this->errorMessage);
					break; // while
			} else {
				$idClass = 'tx-ttproducts-pi1-'.strtolower($theCode);
				$content .='<div id="'.$idClass.'">'.($contentTmp ? $contentTmp : '').'</div><!-- END: '.$idClass.' -->';
			}
		}

		if ($this->errorMessage) {
			$content = '<p><b>'.$this->errorMessage.'</b></p>';
		}
		$rc = ($bRunAjax || !intval($this->conf['wrapInBaseClass']) ? $content : $this->pi_wrapInBaseClass($content));
		return $rc;
	}


	/**
	 * does the initialization stuff
	 *
	 * @param		string		  content string
	 * @param		string		  configuration array
	 * @param		string		  modified configuration array
	 * @return	  void
 	 */
	function init (&$content, &$config) {
		global $TSFE;
		global $TYPO3_CONF_VARS;

		$this->pi_setPiVarDefaults();

		// corrections for not initialized deprecated 'AlwaysInStock'
		if ($this->conf['alwaysInStock'] == '{$plugin.tt_products.alwaysInStock}')	{
			$this->conf['alwaysInStock'] = '';
		}
		if ($this->conf['AlwaysInStock'] == '{$plugin.tt_products.AlwaysInStock}')	{
			$this->conf['AlwaysInStock'] = '';
		}

		$this->conf['alwaysInStock'] = ($this->conf['alwaysInStock'] ? $this->conf['alwaysInStock']: $this->conf['AlwaysInStock']);

 		if ($this->conf['TAXmode'] == '' ||  $this->conf['TAXmode'] == '{$plugin.tt_products.TAXmode}')	{
			$this->conf['TAXmode'] == 1;
		}
		$this->cnf = t3lib_div::makeInstance('tx_ttproducts_config');
		$this->cnf->init(
			$this->conf,
			$config
		);

		$typoVersion = t3lib_div::int_from_ver($GLOBALS['TYPO_VERSION']);
		if ($typoVersion < 3008000)	{
			$dblangfile = 'locallang_tca.php';
		} else {
			$dblangfile = 'locallang_db.xml';
		}
		tx_fhlibrary_language::pi_loadLL($this,'EXT:tt_products/'.$dblangfile);
		tx_fhlibrary_language::pi_loadLL($this,'EXT:tt_products/pi1/locallang.xml');

			// get all extending TCAs
		if (is_array($TYPO3_CONF_VARS['EXTCONF'][TT_PRODUCTS_EXTkey]['extendingTCA']))	{
			$this->pi_loadTcaAdditions($TYPO3_CONF_VARS['EXTCONF'][TT_PRODUCTS_EXTkey]['extendingTCA']);
		}

			// basket
		$this->basket = &t3lib_div::getUserObj('&tx_ttproducts_basket');
		$this->initTables();

		// *************************************
		// *** getting configuration values:
		// *************************************

		$this->pageAsCategory = $TYPO3_CONF_VARS['EXTCONF'][TT_PRODUCTS_EXTkey]['pageAsCategory'];

		// get template suffix string
		$this->template_suffix = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'template_suffix');
		$this->template_suffix = strtoupper($this->template_suffix);
		$this->template_suffix = ($this->template_suffix ? '_'.$this->template_suffix : '');

		$config['limit'] = $this->conf['limit'] ? $this->conf['limit'] : 50;
		$config['limitImage'] = t3lib_div::intInRange($this->conf['limitImage'],0,50);
		$config['limitImage'] = $config['limitImage'] ? $config['limitImage'] : 1;
		$config['limitImageSingle'] = t3lib_div::intInRange($this->conf['limitImageSingle'],0,50);
		$config['limitImageSingle'] = $config['limitImageSingle'] ? $config['limitImageSingle'] : 1;
		$recursive = ($this->cObj->data['recursive'] ? $this->cObj->data['recursive']: $this->conf['recursive']);
		$config['recursive'] = t3lib_div::intInRange($recursive,0,100);
		if ($this->conf['PIDstoreRoot'])	{
			$config['storeRootPid'] = $this->conf['PIDstoreRoot'];
		} else {
			foreach ($TSFE->tmpl->rootLine as $k => $row)	{
				if ($row['doktype'] == 1)	{
					$config['storeRootPid'] = $row['uid'];
					break;
				}
			}
		}
		$config['priceNoReseller'] = $this->conf['priceNoReseller'] ? t3lib_div::intInRange($this->conf['priceNoReseller'],2,2) : NULL;
		$tmp = $this->cObj->stdWrap($this->conf['pid_list'],$this->conf['pid_list.']);
		$pid_list = ($this->cObj->data['pages'] ? $this->cObj->data['pages'] : ($this->conf['pid_list.'] ? trim($this->cObj->stdWrap($this->conf['pid_list'],$this->conf['pid_list.'])) : ''));
		$pid_list = $config['pid_list'] = ($pid_list ? $pid_list : $this->conf['pid_list']);
		$this->pid_list = ($pid_list ? $pid_list : $config['storeRootPid']);

			// If the current record should be displayed.
		$config['displayCurrentRecord'] = $this->conf['displayCurrentRecord'];
		if ($config['displayCurrentRecord'])	{
			$config['code']='SINGLE';
			$this->tt_product_single['product'] = true;
		} else {
			$tmp = ($this->piVars['product'] ? $this->piVars['product'] : t3lib_div::_GP('tt_products'));
			if (isset($tmp) && is_array($tmp) && isset($tmp['product']))	{	// upwards compatible to tt_products parameter
				$tmp = $tmp['product'];
			}
			if (t3lib_div::testInt($tmp))	{
				$this->tt_product_single['product'] = $tmp;
			}
			$tmp = ($this->piVars['article'] ? $this->piVars['article'] : '');
			if (t3lib_div::testInt($tmp))	{
				$this->tt_product_single['article'] = $tmp;
			}
		}

			// pages
		$this->page = tx_ttproducts_page::createPageTable(
			$this,
			$this->cnf,
			$this->tt_content,
			$this->LLkey,
			$this->conf['table.']['pages'],
			$this->conf['conf.']['pages.'],
			$this->page,
			$this->pid_list,
			$this->config['recursive']
		);

			// JavaScript
		$this->javascript = t3lib_div::makeInstance('tx_ttproducts_javascript');
		$this->javascript->init(
			$this,
			$this->cnf,
			$this->page,
			$this->xajax
		);

		$this->marker = t3lib_div::makeInstance('tx_ttproducts_marker');
		$this->marker->init(
			$this,
			$this->cnf,
			$this->basket
		);

		$this->globalMarkerArray = $this->marker->getGlobalMarkers();

			// basket view
		$this->control = t3lib_div::makeInstance('tx_ttproducts_control');

			// price
		$this->price = t3lib_div::makeInstance('tx_ttproducts_price');
		$this->price->init(
			$this,
			$this->conf,
			$this->cnf,
			$this->basket,
			$this->paymentshipping
		);

		$this->tableArray = array ('static_countries' => 'country');

			// This cObject may be used to call a function which manipulates the shopping basket based on settings in an external order system. The output is included in the top of the order (HTML) on the basket-page.
		$this->externalCObject = $this->getExternalCObject('externalProcessing');

	} // init


	/**
	 * Getting the table definitions
	 */
	function initTables()	{
		$this->tt_content = t3lib_div::makeInstance('tx_ttproducts_content');
		$this->tt_content->init();

		$this->fe_users = t3lib_div::makeInstance('tx_ttproducts_feuser');
		$this->fe_users->init(
			$this,
			$this->cnf,
			$this->conf['table.']['fe_users']
		);

			// paymentshipping
		$this->paymentshipping = t3lib_div::makeInstance('tx_ttproducts_paymentshipping');
		$this->paymentshipping->init(
			$this,
			$this->cnf,
			$this->basket,
			$this->fe_users
		);

		$this->tt_products_cat = t3lib_div::makeInstance('tx_ttproducts_category');
		$this->tt_products_cat->init(
			$this,
			$this->cnf,
			$this->tt_content,
			$this->LLkey,
			$this->conf['table.']['tt_products_cat'],
			'parent_category',
			'cat'
		);

		$this->tt_products = t3lib_div::makeInstance('tx_ttproducts_product');
		$this->tt_products->init(
			$this,
			$this->cnf,
			$this->tt_content,
			$this->paymentshipping,
			$this->LLkey,
			$this->conf['table.']['tt_products'],
			$this->conf['useArticles']
		);

		$this->tt_products_articles = t3lib_div::makeInstance('tx_ttproducts_article');
		$this->tt_products_articles->init(
			$this,
			$this->cnf,
			$this->tt_products,
			$this->tt_content,
			$this->paymentshipping,
			$this->LLkey,
			$this->conf['table.']['tt_products_articles'],
			$this->conf['useArticles']
		);
	} // initTables


	function tt_products_changeValue($arg)        {
		 // do some stuff based on $arg like query data from a database and
		 // put it into a variable like $ajax_content

	     $ajax_content = $arg;  // here will use the argument as output

	     // Instantiate the tx_xajax_response object
		$objResponse = new tx_xajax_response('iso-8859-1');
	     $objResponse->addAssign('change','value', $ajax_content);

	     //return the XML response generated by the tx_xajax_response object
	     return $objResponse->getXML();
	}


	function tt_products_processFormData($arg)        {
	     $xajax_content = '<br /><br /><strong>submitted values</strong><br /><br />
	                      '.t3lib_div::view_array($arg);  // here will output the Array, it looks like the $_POST/$_GET, which would be generated.

	     // Instantiate the tx_xajax_response object
		$objResponse = new tx_xajax_response('iso-8859-1');
	     $objResponse->addAssign('formDiv','innerHTML', $xajax_content);

	     //return the XML response generated by the tx_xajax_response object
	     return $objResponse->getXML();
	}


		// XAJAX functions cannot be in classes
	function tt_products_ShowArticle($data)	{
        // We put our incomming data to the regular piVars
        $this->piVars = array_merge($this->piVars, $data[$this->prefixId]);

		$this->excludeCode = '';

	    // Instantiate the tx_xajax_response object
		$objResponse = new tx_xajax_response('iso-8859-1');

		$artListKey = array_search('SELECTCAT', $this->codeArray);
		if ($this->codeArray[$artListKey] == 'SELECTCAT')	{
			unset($this->codeArray[$artListKey]);
		}
		if (count($this->codeArray))	{
			foreach ($this->codeArray as $k => $code)	{
				if ($code != 'LISTARTICLES')	{
					$idClass = 'tx-ttproducts-pi1-'.strtolower($code);
					unset($this->codeArray[$k]);
					// $objResponse->addAssign($idClass, 'innerHTML', '');
				}
			}
		}

		$idClass = ($idClass ? $idClass : 'ArticleId');

	    $content = $this->doProcessing('',true);
	    // $content = '<br /><strong>Content from AJAX server</strong><br />';

		$objResponse->addAssign($idClass,'innerHTML', $content);

	    //return the XML response generated by the tx_xajax_response object
	    return $objResponse->getXML();
	}


	/**
	 * Order tracking
	 *
	 *
	 * @param	integer		Code: TRACKING, BILL or DELIVERY
	 * @return	void
	 * @see enableFields()
	 */

	function products_tracking($theCode)	{ // t3lib_div::_GP('tracking')
		global $TSFE;
		global $TYPO3_CONF_VARS;

		$updateCode = '';

		include_once (PATH_BE_ttproducts.'marker/class.tx_ttproducts_marker.php');

		$markerObj = t3lib_div::makeInstance('tx_ttproducts_marker');
		$markerObj->init($this, $this->conf, $this->config, $this->basket);

		$trackingCode = t3lib_div::_GP('tracking');
		$admin = $this->shopAdmin($updateCode);

		$msgSubpart = '';
		if ($trackingCode || $admin)	{		// Tracking number must be set
			include_once (PATH_BE_ttproducts.'model/class.tx_ttproducts_order.php');

				// order
			$order = &t3lib_div::getUserObj('tx_ttproducts_order');
			$order->init(
				$this->pibase,
				$this->cnf,
				$this->tt_products,
				$this->tt_products_articles,
				$this->tt_products_cat,
				$this->basket,
				$this->conf['useArticles']
			);

			$orderRow = $order->getRecord('',$trackingCode);
			if (is_array($orderRow) || $admin)	{		// If order is associated with tracking id.
				if (!is_array($orderRow)) {
					$orderRow=array('uid'=>0);
				}
				switch ($theCode) {
					case 'TRACKING':
				 		$tracking = t3lib_div::makeInstance('tx_ttproducts_tracking');
				 		$tracking->init(
				 			$this,
				 			$this->cnf,
				 			$this->basket,
				 			$order,
				 			$this->price
				 		);
						$orderRecord = t3lib_div::_GP('orderRecord');
						$content = $tracking->getTrackingInformation($orderRow, $this->templateCode, $trackingCode, $updateCode, $orderRecord, $admin);
						break;
					case 'BILL':
				 		$bill = t3lib_div::makeInstance('tx_ttproducts_billdelivery');
				 		$bill->init(
				 			$this,
				 			$this->cnf,
				 			$this->basket,
				 			$this->tt_products,
				 			$this->tt_products_cat,
				 			$this->tt_content,
				 			$order,
				 			$this->price,
							'bill'
						);
						$content = $bill->getInformation($orderRow, $this->templateCode,$trackingCode);
						break;
					case 'DELIVERY':
				 		$delivery = t3lib_div::makeInstance('tx_ttproducts_billdelivery');
				 		$delivery->init(
				 			$this,
				 			$this->cnf,
				 			$this->basket,
				 			$this->tt_products,
				 			$this->tt_products_cat,
				 			$this->tt_content,
				 			$order,
				 			$this->price,
							'delivery'
						);
						$content = $delivery->getInformation(
							$orderRow,
							$this->templateCode,
							$trackingCode
						);
						break;
					default:
						debug('error in '.TT_PRODUCTS_EXTkey.' calling function products_tracking with $theCode = "'.$theCode.'"');
				}
			} else {	// ... else output error page
				$msgSubpart = '###TRACKING_WRONG_NUMBER###';
			}
		} else {	// No tracking number - show form with tracking number
			$msgSubpart = '###TRACKING_ENTER_NUMBER###';
		}

		if ($msgSubpart)	{
			$content=$this->cObj->getSubpart($this->templateCode,$markerObj->spMarker($msgSubpart));
			if (!$TSFE->beUserLogin)	{
				$content = $this->cObj->substituteSubpart($content,'###ADMIN_CONTROL###','');
			}
		}

		$markerArray=array();
		$markerArray['###FORM_URL###'] = $this->pi_getPageLink($TSFE->id,'',$markerObj->getLinkParams('',array(),true)) ; // Add FORM_URL to globalMarkerArray, linking to self.
		$content= $this->cObj->substituteMarkerArray($content, $markerArray);

		return $content;
	}  // products_tracking


	/**
	 * Returns 1 if user is a shop admin
	 */
	function shopAdmin(&$updateCode)	{
		$admin=0;
		if ($GLOBALS['TSFE']->beUserLogin)	{
			$updateCode = t3lib_div::_GP('update_code');
			if ($updateCode == $this->conf['update_code'])	{
				$admin = 1;		// Means that the administrator of the website is authenticated.
			}
		}
		return $admin;
	}


	/**
	 * Get External CObjects
	 */
	function getExternalCObject($mConfKey)	{
		if ($this->conf[$mConfKey] && $this->conf[$mConfKey.'.'])	{
			$this->cObj->regObj = &$this;
			return $this->cObj->cObjGetSingle($this->conf[$mConfKey],$this->conf[$mConfKey.'.'],'/'.$mConfKey.'/').'';
		}
	}


	function load_noLinkExtCobj()	{
		if ($this->conf['externalProcessing_final'] || is_array($this->conf['externalProcessing_final.']))	{	// If there is given another cObject for the final order confirmation template!
			$this->externalCObject = $this->getExternalCObject('externalProcessing_final');
		}
	} // load_noLinkExtCobj

	function setSingleFromList ($bValue)	{
		$this->bSingleFromList = $bValue;
	}

	function getSingleFromList ()	{
		return $this->bSingleFromList;
	}

	/**
	 * Displaying single products/ the products list / searching
	 */
	function products_display($theCode, &$errorMessage, &$error_code)	{
		global $TSFE;
		global $TYPO3_CONF_VARS;

		if ((($theCode=='SEARCH') || (strstr($theCode,'LIST'))) && ($theCode != 'LISTARTICLES') && count($this->tt_product_single) && !$this->conf['NoSingleViewOnList'] && !$this->getSingleFromList())	{
			$this->setSingleFromList(TRUE);
			$bSingleFromList = TRUE;
		}

		if (($theCode=='SINGLE') || $bSingleFromList) {
			if (!$this->tt_product_single) {
				$this->tt_product_single['product'] = $this->conf['defaultProductID'];
			}
			$extVars = $this->piVars['variants'];
			$extVars = ($extVars ? $extVars : t3lib_div::_GP('ttp_extvars'));
				// performing query:

			if (!is_object($this->singleView)) {
				// List single product:
				$this->singleView = t3lib_div::makeInstance('tx_ttproducts_single_view');
				$this->singleView->init (
					$this,
					$this->cnf,
					$this->basket,
					$this->page,
					$this->tt_content,
					$this->tt_products,
					$this->tt_products_articles,
					$this->tt_products_cat,
					$this->fe_users,
					$this->tt_product_single,
					$extVars,
					$this->pid,
					$this->LLkey,
					$this->conf['useArticles']
				);
			}
			$content = $this->singleView->printView(
				$this->templateCode,
				$error_code,
				$this->pageAsCategory,
				$this->template_suffix
			);
		} else {
			include_once (PATH_BE_ttproducts.'view/class.tx_ttproducts_list_view.php');

			// List all products:
			$listView = t3lib_div::makeInstance('tx_ttproducts_list_view');
			$listView->init (
				$this,
				$this->cnf,
				$this->basket,
				$this->page,
				$this->tt_content,
				$this->tt_products,
				$this->tt_products_articles,
				$this->tt_products_cat,
				$this->fe_users,
				$this->pid,
				$this->LLkey,
				$this->conf['useArticles']
			);
			if ($theCode == 'LISTARTICLES' && $this->conf['useArticles'])	{
				$templateArea = 'ARTICLE_LIST_TEMPLATE';
			} else {
				$templateArea = 'ITEM_LIST_TEMPLATE';
			}

			$allowedItems = '';
			$bAllPages = false;
			$templateArea = '###'.$templateArea.$this->template_suffix.'###';
			$content = $listView->printView(
				$this->templateCode,
				$theCode,
				$allowedItems,
				$bAllPages,
				$error_code,
				$templateArea,
				$this->pageAsCategory
			);
		}

		return $content;
	}	// products_display
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tt_products/pi1/class.tx_ttproducts_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tt_products/pi1/class.tx_ttproducts_pi1.php']);
}


?>
