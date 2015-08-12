<?php
/**
 * @author Chukancev Nikita tpmanc <tpxtrime@mail.ru>
 */

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($mode == 'view') {
    $page = Registry::get('view')->getTemplateVars('page');

    if (empty($page['page_title'])) {$isEnabled = Registry::get('addons.tpmanc_seo_data.pageEnabled');
        if ($isEnabled === 'Y') {
            $newTitle = Registry::get('addons.tpmanc_seo_data.pageSeoTitle');
            $newDescription = Registry::get('addons.tpmanc_seo_data.pageSeoDescription');

            $newTitle = str_replace("{page}", $page['page'], $newTitle);
            $newDescription = str_replace("{page}", $page['page'], $newTitle);

            $parentPage = '';
            if (strpos($newTitle, "{parentPage}") !== false || strpos($newDescription, "{parentPage}") !== false) {
                $parentPage = fn_get_page_name($page['parent_id']);
            }
            $newTitle = str_replace("{parentPage}", $parentPage, $newTitle);
            $newDescription = str_replace("{parentPage}", $parentPage, $newDescription);
            
            Registry::get('view')->assign('page_title', $newTitle);
            Registry::get('view')->assign('meta_description', $newDescription);
        }
    }
}
?>