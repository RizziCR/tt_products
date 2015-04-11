<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "tt_products".
 ***************************************************************/


$EM_CONF[$_EXTKEY] = array(
	'title' => 'Shop System',
	'description' => 'New versions at ttproducts.de. Documented in E-Book "Der TYPO3-Webshop" - Shop with listing in multiple languages, with order tracking, photo gallery, DAM, product variants, configurable costs, credit card payment and bank accounts, bill, creditpoint, voucher system and gift certificates..',
	'category' => 'plugin',
	'shy' => 0,
	'dependencies' => 'cms,div2007,table,tsparser',
	'conflicts' => 'ast_rteproducts,onet_ttproducts_rte,c3bi_cookie_at_login',
	'suggests' => array(
	),
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => 0,
	'uploadfolder' => 0,
	'createDirs' => 'uploads/tx_ttproducts/datasheet,uploads/tx_ttproducts/rte,fileadmin/data/bill,fileadmin/data/delivery,fileadmin/img',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'author' => 'Franz Holzinger',
	'author_email' => 'franz@ttproducts.de',
	'author_company' => 'jambage.com',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'version' => '2.7.17',
	'_md5_values_when_last_written' => 'a:221:{s:9:"ChangeLog";s:4:"5883";s:31:"class.tx_ttproducts_wizicon.php";s:4:"1cff";s:16:"contributors.txt";s:4:"a796";s:16:"ext_autoload.php";s:4:"d823";s:21:"ext_conf_template.txt";s:4:"a394";s:12:"ext_icon.gif";s:4:"eb61";s:17:"ext_localconf.php";s:4:"5a49";s:14:"ext_tables.php";s:4:"73d1";s:14:"ext_tables.sql";s:4:"7813";s:19:"flexform_ds_pi1.xml";s:4:"dae5";s:13:"locallang.xml";s:4:"ea36";s:24:"locallang_csh_ttprod.php";s:4:"c998";s:25:"locallang_csh_ttproda.php";s:4:"026a";s:26:"locallang_csh_ttprodac.php";s:4:"c02a";s:25:"locallang_csh_ttprodc.php";s:4:"cfa4";s:26:"locallang_csh_ttprodca.php";s:4:"268a";s:25:"locallang_csh_ttprode.php";s:4:"013d";s:25:"locallang_csh_ttprodo.php";s:4:"c2ab";s:25:"locallang_csh_ttprodt.php";s:4:"e7bf";s:16:"locallang_db.xml";s:4:"5c90";s:7:"tca.php";s:4:"ef1c";s:30:"api/class.tx_ttproducts_ts.php";s:4:"ba94";s:35:"cache/class.tx_ttproducts_cache.php";s:4:"539e";s:49:"control/class.tx_ttproducts_activity_finalize.php";s:4:"4fe4";s:39:"control/class.tx_ttproducts_control.php";s:4:"0d2e";s:44:"control/class.tx_ttproducts_control_memo.php";s:4:"a705";s:47:"control/class.tx_ttproducts_control_session.php";s:4:"89cb";s:42:"control/class.tx_ttproducts_javascript.php";s:4:"a635";s:36:"control/class.tx_ttproducts_main.php";s:4:"f4fd";s:14:"doc/manual.sxw";s:4:"4f0b";s:32:"eid/class.tx_ttproducts_ajax.php";s:4:"4b31";s:30:"eid/class.tx_ttproducts_db.php";s:4:"0b50";s:31:"eid/class.tx_ttproducts_eid.php";s:4:"d79a";s:33:"hooks/class.tx_ttproducts_cms.php";s:4:"75ac";s:38:"hooks/class.tx_ttproducts_hooks_fe.php";s:4:"58fb";s:40:"lib/class.tx_ttproducts_billdelivery.php";s:4:"3382";s:34:"lib/class.tx_ttproducts_config.php";s:4:"7031";s:44:"lib/class.tx_ttproducts_creditpoints_div.php";s:4:"40a2";s:31:"lib/class.tx_ttproducts_css.php";s:4:"80ad";s:31:"lib/class.tx_ttproducts_csv.php";s:4:"c0a2";s:41:"lib/class.tx_ttproducts_discountprice.php";s:4:"b14d";s:37:"lib/class.tx_ttproducts_email_div.php";s:4:"c1c5";s:36:"lib/class.tx_ttproducts_form_div.php";s:4:"8d37";s:37:"lib/class.tx_ttproducts_gifts_div.php";s:4:"7cf6";s:36:"lib/class.tx_ttproducts_language.php";s:4:"5015";s:38:"lib/class.tx_ttproducts_paymentlib.php";s:4:"dbcc";s:43:"lib/class.tx_ttproducts_paymentshipping.php";s:4:"b55d";s:37:"lib/class.tx_ttproducts_pricecalc.php";s:4:"e581";s:42:"lib/class.tx_ttproducts_pricecalc_base.php";s:4:"1f19";s:43:"lib/class.tx_ttproducts_pricetablescalc.php";s:4:"8457";s:31:"lib/class.tx_ttproducts_sql.php";s:4:"41d3";s:34:"lib/class.tx_ttproducts_tables.php";s:4:"779a";s:36:"lib/class.tx_ttproducts_tracking.php";s:4:"9c03";s:48:"marker/class.tx_ttproducts_javascript_marker.php";s:4:"bce4";s:37:"marker/class.tx_ttproducts_marker.php";s:4:"9209";s:44:"marker/class.tx_ttproducts_subpartmarker.php";s:4:"5d06";s:37:"model/class.tx_ttproducts_account.php";s:4:"252e";s:37:"model/class.tx_ttproducts_address.php";s:4:"0d96";s:37:"model/class.tx_ttproducts_article.php";s:4:"cbf4";s:42:"model/class.tx_ttproducts_article_base.php";s:4:"f63e";s:39:"model/class.tx_ttproducts_attribute.php";s:4:"0c52";s:37:"model/class.tx_ttproducts_bank_de.php";s:4:"6107";s:36:"model/class.tx_ttproducts_basket.php";s:4:"ef46";s:34:"model/class.tx_ttproducts_card.php";s:4:"7eaa";s:38:"model/class.tx_ttproducts_category.php";s:4:"a493";s:43:"model/class.tx_ttproducts_category_base.php";s:4:"4128";s:37:"model/class.tx_ttproducts_content.php";s:4:"0780";s:37:"model/class.tx_ttproducts_country.php";s:4:"53bd";s:33:"model/class.tx_ttproducts_dam.php";s:4:"0468";s:41:"model/class.tx_ttproducts_damcategory.php";s:4:"b51c";s:35:"model/class.tx_ttproducts_email.php";s:4:"ac1e";s:45:"model/class.tx_ttproducts_graduated_price.php";s:4:"7c6c";s:44:"model/class.tx_ttproducts_model_activity.php";s:4:"77c4";s:35:"model/class.tx_ttproducts_order.php";s:4:"3c5d";s:42:"model/class.tx_ttproducts_orderaddress.php";s:4:"9d60";s:34:"model/class.tx_ttproducts_page.php";s:4:"00b3";s:38:"model/class.tx_ttproducts_pid_list.php";s:4:"f89b";s:37:"model/class.tx_ttproducts_product.php";s:4:"2b7f";s:40:"model/class.tx_ttproducts_table_base.php";s:4:"36e1";s:41:"model/class.tx_ttproducts_table_label.php";s:4:"fed5";s:34:"model/class.tx_ttproducts_text.php";s:4:"16f8";s:37:"model/class.tx_ttproducts_variant.php";s:4:"b876";s:43:"model/class.tx_ttproducts_variant_dummy.php";s:4:"9d56";s:37:"model/class.tx_ttproducts_voucher.php";s:4:"91da";s:39:"model/int.tx_ttproducts_variant_int.php";s:4:"2a25";s:46:"model/field/class.tx_ttproducts_field_base.php";s:4:"b9e0";s:54:"model/field/class.tx_ttproducts_field_creditpoints.php";s:4:"2f07";s:51:"model/field/class.tx_ttproducts_field_datafield.php";s:4:"c8ec";s:57:"model/field/class.tx_ttproducts_field_graduated_price.php";s:4:"f4e6";s:47:"model/field/class.tx_ttproducts_field_image.php";s:4:"f4ef";s:49:"model/field/class.tx_ttproducts_field_instock.php";s:4:"d609";s:47:"model/field/class.tx_ttproducts_field_media.php";s:4:"2df2";s:46:"model/field/class.tx_ttproducts_field_note.php";s:4:"5294";s:47:"model/field/class.tx_ttproducts_field_price.php";s:4:"e4ee";s:46:"model/field/class.tx_ttproducts_field_text.php";s:4:"7ac9";s:49:"model/field/interface.tx_ttproducts_field_int.php";s:4:"f701";s:31:"pi1/class.tx_ttproducts_pi1.php";s:4:"0e6c";s:36:"pi1/class.tx_ttproducts_pi1_base.php";s:4:"9902";s:17:"pi1/locallang.xml";s:4:"90b4";s:20:"pi1/payment_DIBS.php";s:4:"e1b0";s:32:"pi1/products_comp_calcScript.inc";s:4:"a85e";s:23:"res/icons/be/ce_wiz.gif";s:4:"a6c1";s:28:"res/icons/be/productlist.gif";s:4:"a6c1";s:24:"res/icons/fe/AddItem.gif";s:4:"287d";s:24:"res/icons/fe/addmemo.png";s:4:"c76f";s:21:"res/icons/fe/amex.gif";s:4:"22e1";s:32:"res/icons/fe/availableDemand.gif";s:4:"bf3a";s:35:"res/icons/fe/availableImmediate.gif";s:4:"7f1d";s:31:"res/icons/fe/availableShort.gif";s:4:"1737";s:23:"res/icons/fe/basket.gif";s:4:"ca3d";s:34:"res/icons/fe/Cart-Icon-AddItem.gif";s:4:"e76c";s:40:"res/icons/fe/Cart-Icon-AddRemoveItem.gif";s:4:"9b18";s:37:"res/icons/fe/Cart-Icon-RemoveItem.gif";s:4:"b9cc";s:26:"res/icons/fe/Cart-Icon.gif";s:4:"988a";s:24:"res/icons/fe/delmemo.png";s:4:"b1da";s:25:"res/icons/fe/discover.gif";s:4:"91c4";s:27:"res/icons/fe/mastercard.gif";s:4:"2fe1";s:28:"res/icons/fe/minibasket1.gif";s:4:"a960";s:27:"res/icons/fe/RemoveItem.gif";s:4:"e28f";s:35:"res/icons/fe/ttproducts_help_en.png";s:4:"5326";s:21:"res/icons/fe/visa.gif";s:4:"28c6";s:41:"res/icons/table/sys_products_accounts.gif";s:4:"cab5";s:38:"res/icons/table/sys_products_cards.gif";s:4:"f9d0";s:39:"res/icons/table/sys_products_orders.gif";s:4:"b279";s:31:"res/icons/table/tt_products.gif";s:4:"1ebd";s:40:"res/icons/table/tt_products_articles.gif";s:4:"e779";s:49:"res/icons/table/tt_products_articles_language.gif";s:4:"20e5";s:35:"res/icons/table/tt_products_cat.gif";s:4:"b6f5";s:44:"res/icons/table/tt_products_cat_language.gif";s:4:"4aee";s:38:"res/icons/table/tt_products_emails.gif";s:4:"8cea";s:40:"res/icons/table/tt_products_language.gif";s:4:"9570";s:37:"res/icons/table/tt_products_texts.gif";s:4:"4a8e";s:46:"res/icons/table/tt_products_texts_language.gif";s:4:"522f";s:20:"static/editorcfg.txt";s:4:"4dd7";s:31:"static/css_styled/constants.txt";s:4:"9314";s:27:"static/css_styled/setup.txt";s:4:"cabc";s:30:"static/old_style/constants.txt";s:4:"169e";s:26:"static/old_style/setup.txt";s:4:"015b";s:26:"static/share/constants.txt";s:4:"943a";s:16:"template/agb.txt";s:4:"d76f";s:38:"template/example_template_bill_de.tmpl";s:4:"0e13";s:35:"template/payment_DIBS_template.tmpl";s:4:"990a";s:38:"template/payment_DIBS_template_uk.tmpl";s:4:"2fdc";s:24:"template/paymentlib.tmpl";s:4:"c894";s:29:"template/products_css_de.html";s:4:"13c2";s:29:"template/products_css_en.html";s:4:"6298";s:38:"template/products_css_variants_de.html";s:4:"c460";s:25:"template/products_de.html";s:4:"a647";s:27:"template/products_help.tmpl";s:4:"7486";s:31:"template/products_template.tmpl";s:4:"6916";s:34:"template/products_template_dk.tmpl";s:4:"6b1f";s:34:"template/products_template_fi.tmpl";s:4:"7c85";s:34:"template/products_template_fr.tmpl";s:4:"f847";s:40:"template/products_template_htmlmail.tmpl";s:4:"aa8a";s:34:"template/products_template_it.html";s:4:"96fa";s:34:"template/products_template_se.tmpl";s:4:"82d8";s:24:"template/tt_products.css";s:4:"d0ae";s:32:"template/tt_products_example.css";s:4:"ca34";s:37:"template/tt_products_example_css.html";s:4:"092b";s:39:"template/meerwijn/detail_cadeaubon.tmpl";s:4:"c263";s:40:"template/meerwijn/detail_geschenken.tmpl";s:4:"b695";s:40:"template/meerwijn/detail_kurkenshop.tmpl";s:4:"0fad";s:38:"template/meerwijn/detail_shopabox.tmpl";s:4:"21a3";s:36:"template/meerwijn/detail_wijnen.tmpl";s:4:"63be";s:37:"template/meerwijn/product_detail.tmpl";s:4:"9e4a";s:45:"template/meerwijn/product_proefpakketten.tmpl";s:4:"9afd";s:32:"template/meerwijn/producten.tmpl";s:4:"103a";s:33:"template/meerwijn/shop-a-box.tmpl";s:4:"f580";s:40:"template/meerwijn/totaal_geschenken.tmpl";s:4:"15ca";s:40:"template/meerwijn/totaal_kurkenshop.tmpl";s:4:"1306";s:38:"template/meerwijn/totaal_shopabox.tmpl";s:4:"f87b";s:36:"template/meerwijn/totaal_wijnen.tmpl";s:4:"5ee1";s:31:"template/meerwijn/tracking.tmpl";s:4:"aadb";s:34:"template/meerwijn/winkelwagen.tmpl";s:4:"ff1b";s:35:"template/meerwijn/js/FormManager.js";s:4:"3ccc";s:41:"view/class.tx_ttproducts_account_view.php";s:4:"a574";s:41:"view/class.tx_ttproducts_address_view.php";s:4:"f479";s:46:"view/class.tx_ttproducts_article_base_view.php";s:4:"c834";s:41:"view/class.tx_ttproducts_article_view.php";s:4:"6253";s:40:"view/class.tx_ttproducts_basket_view.php";s:4:"b915";s:44:"view/class.tx_ttproducts_basketitem_view.php";s:4:"43a7";s:38:"view/class.tx_ttproducts_card_view.php";s:4:"3c02";s:37:"view/class.tx_ttproducts_cat_view.php";s:4:"4fcb";s:47:"view/class.tx_ttproducts_category_base_view.php";s:4:"4aeb";s:42:"view/class.tx_ttproducts_category_view.php";s:4:"49c6";s:41:"view/class.tx_ttproducts_catlist_view.php";s:4:"5a3d";s:46:"view/class.tx_ttproducts_catlist_view_base.php";s:4:"96bf";s:41:"view/class.tx_ttproducts_country_view.php";s:4:"424e";s:42:"view/class.tx_ttproducts_currency_view.php";s:4:"ee78";s:37:"view/class.tx_ttproducts_dam_view.php";s:4:"96fc";s:45:"view/class.tx_ttproducts_damcategory_view.php";s:4:"7cf6";s:49:"view/class.tx_ttproducts_graduated_price_view.php";s:4:"d30e";s:38:"view/class.tx_ttproducts_info_view.php";s:4:"0596";s:38:"view/class.tx_ttproducts_list_view.php";s:4:"c087";s:38:"view/class.tx_ttproducts_memo_view.php";s:4:"512d";s:41:"view/class.tx_ttproducts_menucat_view.php";s:4:"9875";s:39:"view/class.tx_ttproducts_order_view.php";s:4:"0754";s:46:"view/class.tx_ttproducts_orderaddress_view.php";s:4:"9bfa";s:38:"view/class.tx_ttproducts_page_view.php";s:4:"fd44";s:41:"view/class.tx_ttproducts_product_view.php";s:4:"2ede";s:43:"view/class.tx_ttproducts_selectcat_view.php";s:4:"d25a";s:40:"view/class.tx_ttproducts_single_view.php";s:4:"968a";s:44:"view/class.tx_ttproducts_table_base_view.php";s:4:"feff";s:38:"view/class.tx_ttproducts_text_view.php";s:4:"2ac6";s:37:"view/class.tx_ttproducts_url_view.php";s:4:"dd55";s:47:"view/class.tx_ttproducts_variant_dummy_view.php";s:4:"b233";s:41:"view/class.tx_ttproducts_variant_view.php";s:4:"8115";s:41:"view/class.tx_ttproducts_voucher_view.php";s:4:"4ec5";s:49:"view/interface.tx_ttproducts_variant_view_int.php";s:4:"0552";s:50:"view/field/class.tx_ttproducts_field_base_view.php";s:4:"7bb6";s:55:"view/field/class.tx_ttproducts_field_datafield_view.php";s:4:"c874";s:61:"view/field/class.tx_ttproducts_field_graduated_price_view.php";s:4:"d1dd";s:51:"view/field/class.tx_ttproducts_field_image_view.php";s:4:"b398";s:53:"view/field/class.tx_ttproducts_field_instock_view.php";s:4:"d754";s:51:"view/field/class.tx_ttproducts_field_media_view.php";s:4:"4a9e";s:50:"view/field/class.tx_ttproducts_field_note_view.php";s:4:"9c50";s:51:"view/field/class.tx_ttproducts_field_price_view.php";s:4:"5bea";s:50:"view/field/class.tx_ttproducts_field_text_view.php";s:4:"9b21";s:53:"view/field/interface.tx_ttproducts_field_view_int.php";s:4:"fb5d";s:38:"widgets/class.tx_ttproducts_latest.php";s:4:"c5d8";s:18:"widgets/labels.xml";s:4:"35d9";}',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'div2007' => '1.1.5-0.0.0',
			'php' => '5.1.2-5.6.99',
			'table' => '0.3.0-0.0.0',
			'tsparser' => '',
			'typo3' => '4.5.0-6.2.99',
		),
		'conflicts' => array(
			'ast_rteproducts' => '',
			'onet_ttproducts_rte' => '',
			'c3bi_cookie_at_login' => '',
		),
		'suggests' => array(
		),
	),
);

?>