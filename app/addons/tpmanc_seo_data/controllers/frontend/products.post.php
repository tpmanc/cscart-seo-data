<?php
/**
 * @author Chukancev Nikita tpmanc <tpxtrime@mail.ru>
 */

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($mode == 'view') {
    $productInfo = Registry::get('view')->getTemplateVars('product');

    if (empty($productInfo['page_title'])) {
        $newTitle = Registry::get('addons.tpmanc_seo_data.productSeoTitle');

        // category path
        if (strpos($newTitle, "{categoryPath}") !== false) {
            $items = Registry::get('view')->getTemplateVars('breadcrumbs');
            $categoryPath = "";
            foreach ($items as $item) {
                if (strpos($item['link'], "categories.view") !== false) {
                    $categoryPath .= $item['title'] . " :: ";
                }
            }
            $newTitle = str_replace("{categoryPath}", rtrim($categoryPath," :: "), $newTitle);
        }

        // category
        if (strpos($newTitle, "{category}") !== false) {
            $newTitle = str_replace("{category}", fn_get_category_name($productInfo['main_category']), $newTitle);
        }

        // product
        $newTitle = str_replace("{product}", $productInfo['product'], $newTitle);

        // product code
        $newTitle = str_replace("{productCode}", $productInfo['product_code'], $newTitle);

        // price
        $currentCurrency = Registry::get('currencies.' . CART_PRIMARY_CURRENCY);
        $price = fn_format_price($productInfo['base_price']);
        $price = sprintf("%.".$currentCurrency['decimals']."f", $price);
        $newTitle = str_replace("{price}", $price, $newTitle);

        Registry::get('view')->assign('page_title', $newTitle);
    }
}

?>