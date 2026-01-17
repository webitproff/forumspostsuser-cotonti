<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=users.details.tags
Tags=users.details.tpl:{FORUMSPOSTSUSER_TAB},{USERS_DETAILS_FORUMSPOSTSUSER_URL},{USERS_DETAILS_FORUMSPOSTSUSER_COUNT}
[END_COT_EXT]          
==================== */
/**
 * forumspostsuser.users.details.php - File for the Plugin Users latest posts
 *
 * forumspostsuser plugin for Cotonti 0.9.26, PHP 8.4+
 * Filename: forumspostsuser.users.details.php
 *
 * Date: Jan 16Th, 2026
 * @package forumspostsuser
 * @version 2.0.1
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */
 
defined('COT_CODE') or die('Wrong URL.');

require_once cot_incfile('forumspostsuser', 'plug');
require_once cot_incfile('forums', 'module');
$tab = cot_import('tab', 'G', 'ALP');

// присваиваем шаблону имя части и/или локации расширения
$tpl_ExtCode = 'forumspostsuser'; // Extentions Code
$tpl_PartExt = 'users'; // area
$tpl_PartExtSecond = 'details'; // location

// Загружаем шаблон для админки плагина forumspostsuser
$mskin = cot_tplfile([$tpl_ExtCode, $tpl_PartExt, $tpl_PartExtSecond], 'plug', true);

// Создаём объект шаблона XTemplate с указанным файлом шаблона в $mskin выше
// объявляем как $tt, потому что будем встраивать наш шаблон $tt в строку $t 
// $t = {FORUMSPOSTSUSER_TAB} которую вешаем на users.details.tags и присваиваем как тег в шаблон users.details.tpl
$tt = new XTemplate($mskin);

$id = (int) ($id ?? $urr['user_id'] ?? cot_import('id', 'G', 'INT') ?? Cot::$usr['id'] ?? 0);
if ($id > 0 && empty($urr['user_id'])) {
    $urr['user_id'] = $id;
}

$disable = false;
if ($urr['user_id'] !== $id) {
    $sql = Cot::$db->query("SELECT user_id FROM " . Cot::$db->quoteTableName($db_users) . " WHERE user_id = " . Cot::$db->quote($id) . " LIMIT 1");
    if ($sql->rowCount() === 0) {
        $disable = true;
    } else {
        $urr['user_id'] = $id;
    }
}

$opt_array = [
    'm' => 'details',
    'id' => $urr['user_id'],
    'u' => $urr['user_name'],
    'tab' => 'forumspostsuser',
];

if (cot_module_active('forums') && !$disable) {
	
	$maxrowsperpage = (int)(Cot::$cfg['plugin']['forumspostsuser']['showpostsinlist'] ?? 5);
	$postscutinlist = (int)(Cot::$cfg['plugin']['forumspostsuser']['postscutinlist'] ?? 100);
	
	list($pg, $d, $durl) = cot_import_pagenav('d', $maxrowsperpage);


    $sql = Cot::$db->query("SELECT COUNT(*) FROM " . Cot::$db->quoteTableName($db_forum_posts) . " p JOIN " . 
                           Cot::$db->quoteTableName($db_forum_topics) . " t ON p.fp_topicid = t.ft_id WHERE p.fp_posterid = " . 
                           Cot::$db->quote($urr['user_id']));
    $totalpostsuser = $sql->fetchColumn();

    $pagenav = cot_pagenav(
        'users',
        $opt_array,
        $d,
        $totalpostsuser,
        $maxrowsperpage,
        'd',
        '',
        false,
        '',
        'plug',
        "r=forumspostsuser&id=" . $urr['user_id']
    );
	$tt->assign(cot_generatePaginationTags($pagenav));
    $sqlforumspostsuser = Cot::$db->query(
        "SELECT p.fp_id, p.fp_topicid, p.fp_updated, p.fp_text, t.ft_title, t.ft_id, t.ft_cat
         FROM " . Cot::$db->quoteTableName($db_forum_posts) . " p
         JOIN " . Cot::$db->quoteTableName($db_forum_topics) . " t ON p.fp_topicid = t.ft_id
         WHERE p.fp_posterid = " . Cot::$db->quote($urr['user_id']) . "
         ORDER BY p.fp_updated DESC
         LIMIT " . (int)$d . ", " . (int)(Cot::$cfg['plugin']['forumspostsuser']['showpostsinlist'] ?? 5)
    );

        $ii = 0;
        while ($row = $sqlforumspostsuser->fetch()) {
            if (cot_auth('forums', $row['ft_cat'], 'R')) {
                $ii++;
                $row['fp_text'] = cot_parse($row['fp_text'], Cot::$cfg['forums']['markup'] ?? false);
                $row['fp_text'] = preg_replace("'<[\/\!]*?[^<>]*?>'si", "", $row['fp_text']);
                $row['fp_text'] = cot_string_truncate($row['fp_text'], $postscutinlist, true, false, '...');

                $tt->assign([
                    "FORUMSPOSTSUSER_DATE" => cot_date('datetime_medium', $row['fp_updated']),
					"FORUMSPOSTSUSER_CATEGORY_SHORT" => isset(Cot::$structure['forums'][$row['ft_cat']]['title']) ? cot_rc_link(cot_url('forums', 'm=topics&s=' . $row['ft_cat']), htmlspecialchars(Cot::$structure['forums'][$row['ft_cat']]['title'])) : '',
                    "FORUMSPOSTSUSER_FORUMS_ID" => $row['fp_id'],
                    "FORUMSPOSTSUSER_FORUMS_TOPIC_ID" => $row['ft_id'],
                    "FORUMSPOSTSUSER_FORUMS_TITLE" => htmlspecialchars($row['ft_title'], ENT_QUOTES, 'UTF-8'),
                    "FORUMSPOSTSUSER_FORUMS_TEXT" => htmlspecialchars($row['fp_text'], ENT_QUOTES, 'UTF-8'),
                    "FORUMSPOSTSUSER_FORUMS_POST_URL" => cot_url('forums', ['m' => 'posts', 'q' => $row['ft_id']], '#' . $row['fp_id']),
                    "FORUMSPOSTSUSER_NUM" => $ii,
                ]);
                $tt->parse("FORUMSPOSTSUSER.POSTS_ROW.TOPIC");
            }
        }
		$tt->parse("FORUMSPOSTSUSER.POSTS_ROW");
	$tt->assign([
		'FORUMSPOSTSUSER_COUNT' => $totalpostsuser,
	]);

}
$t->assign([
    'USERS_DETAILS_FORUMSPOSTSUSER_COUNT' => $totalpostsuser,
    'USERS_DETAILS_FORUMSPOSTSUSER_URL' => cot_url('users', ['m' => 'details', 'id' => $urr['user_id'], 'u' => $urr['user_name'], 'tab' => 'forumspostsuser']),
]);

$tt->parse('FORUMSPOSTSUSER');

$t->assign('FORUMSPOSTSUSER_TAB', $tt->text('FORUMSPOSTSUSER'));