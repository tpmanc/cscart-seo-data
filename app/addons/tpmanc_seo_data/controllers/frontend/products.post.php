<?php
/**
 * @author Chukancev Nikita tpmanc <tpxtrime@mail.ru>
 */

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($mode == 'view') {
    $productInfo = Registry::get('view')->getTemplateVars('product');

    if (empty($productInfo['page_title'])) {
        // ---------- SEO TITLE ----------
        $isEnabled = Registry::get('addons.tpmanc_seo_data.productEnabled');
        if ($isEnabled === 'Y') {
            $newTitle = Registry::get('addons.tpmanc_seo_data.productSeoTitle');
            $newDescription = Registry::get('addons.tpmanc_seo_data.productSeoDescription');

            // category path
            $categoryPath = '';
            if (strpos($newTitle, '{categoryPath}') !== false || strpos($newDescription, '{categoryPath}') !== false) {
                $items = Registry::get('view')->getTemplateVars('breadcrumbs');
                foreach ($items as $item) {
                    if (strpos($item['link'], 'categories.view') !== false) {
                        $categoryPath .= $item['title'] . ' :: ';
                    }
                } 
            }
            $newTitle = str_replace('{categoryPath}', rtrim($categoryPath, ' :: '), $newTitle);
            $newDescription = str_replace('{categoryPath}', rtrim($categoryPath, ' :: '), $newDescription);

            // category
            $categoryName = '';
            if (strpos($newTitle, '{category}') !== false || strpos($newDescription, '{category}') !== false) {
                $categoryName = fn_get_category_name($productInfo['main_category']);
            }
            $newTitle = str_replace('{category}', $categoryName, $newTitle);
            $newDescription = str_replace('{category}', $categoryName, $newDescription);

            // product
            $newTitle = str_replace('{product}', $productInfo['product'], $newTitle);
            $newDescription = str_replace('{product}', $productInfo['product'], $newDescription);

            // product code
            $newTitle = str_replace('{productCode}', $productInfo['product_code'], $newTitle);
            $newDescription = str_replace('{productCode}', $productInfo['product_code'], $newDescription);

            // price
            $currentCurrency = Registry::get('currencies.' . CART_PRIMARY_CURRENCY);
            $price = fn_format_price($productInfo['base_price']);
            $price = sprintf('%.' . $currentCurrency['decimals'] . 'f', $price);
            $newTitle = str_replace("{price}", $price, $newTitle);
            $newDescription = str_replace("{price}", $price, $newDescription);

            Registry::get('view')->assign('page_title', $newTitle);
            Registry::get('view')->assign('meta_description', $newDescription);
        }
    }
}

?>