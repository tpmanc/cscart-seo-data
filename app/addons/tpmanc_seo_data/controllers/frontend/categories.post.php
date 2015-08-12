<?php
/**
 * @author Chukancev Nikita tpmanc <tpxtrime@mail.ru>
 */

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($mode == 'view') {
    $categoryInfo = Registry::get('view')->getTemplateVars('category_data');

    if (empty($categoryInfo['page_title'])) {
        // ---------- SEO TITLE ----------
        $newTitle = Registry::get('addons.tpmanc_seo_data.categorySeoTitle');
        $newDescription = Registry::get('addons.tpmanc_seo_data.categorySeoDescription');

        // category path
        $categoryPath = '';
        if (strpos($newTitle, '{categoryPath}') !== false || strpos($newDescription, '{categoryPath}') !== false) {
            $items = Registry::get('view')->getTemplateVars('breadcrumbs');
            foreach ($items as $item) {
                $categoryPath .= $item['title'] . ' :: ';
            } 
        }
        $newTitle = str_replace('{categoryPath}', rtrim($categoryPath, ' :: '), $newTitle);
        $newDescription = str_replace('{categoryPath}', rtrim($categoryPath, ' :: '), $newDescription);

        // category
        $newTitle = str_replace('{category}', $categoryInfo['category'], $newTitle);
        $newDescription = str_replace('{category}', $categoryInfo['category'], $newDescription);

        // product count
        $newTitle = str_replace('{productCount}', $categoryInfo['product_count'], $newTitle);
        $newDescription = str_replace('{productCount}', $categoryInfo['product_count'], $newDescription);

        Registry::get('view')->assign('page_title', $newTitle);
        Registry::get('view')->assign('meta_description', $newDescription);
    }
}

?>