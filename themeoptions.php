<?php

/* Plug-in for theme option handling
 * The Admin Options page tests for the presence of this file in a theme folder
 * If it is present it is linked to with a require_once call.
 * If it is not present, no theme options are displayed.
 */

require_once(SERVERPATH . '/' . ZENFOLDER . '/admin-functions.php');

class ThemeOptions {

	function __construct() {

		$me = basename(dirname(__FILE__));
		setThemeOptionDefault('albums_per_row', 3);
		setThemeOptionDefault('albums_per_page', 9);
		setThemeOptionDefault('images_per_row', 5);
		setThemeOptionDefault('images_per_page', 20);
		setThemeOptionDefault('thumb_size', 150);
		setThemeOptionDefault('thumb_crop', 1);
		setThemeOptionDefault('thumb_crop_width', 150);
		setThemeOptionDefault('thumb_crop_height', 150);
		setThemeOptionDefault('image_size', 700);
		setThemeOptionDefault('image_use_side', 'longest');
		setThemeOptionDefault('custom_index_page', 'gallery');

		setThemeOptionDefault('use_image_logo_filename', 'banniere3.jpg');
		setThemeOptionDefault('show_image_logo_on_image', false);
		setThemeOptionDefault('css_style', 'dark');
		setThemeOptionDefault('links_style', 'default');
		setThemeOptionDefault('zenpage_homepage', NULL);
		setThemeOptionDefault('show_archive', false);
		setThemeOptionDefault('allow_search', true);
		setThemeOptionDefault('show_tag', true);
		setThemeOptionDefault('image_statistic', 'random');
		setThemeOptionDefault('use_galleriffic', true);
		setThemeOptionDefault('galleriffic_delai', 3000);
		setThemeOptionDefault('use_colorbox_album', false);
		setThemeOptionDefault('use_colorbox_image', false);
		setThemeOptionDefault('show_exif', true);

		enableExtension('colorbox_js', 5|THEME_PLUGIN);
		setOption('colorbox_' . $me . '_album', 1);
		setOption('colorbox_' . $me . '_archive', 1);
		setOption('colorbox_' . $me . '_contact', 1);
		setOption('colorbox_' . $me . '_favorites', 1);
		setOption('colorbox_' . $me . '_gallery', 1);
		setOption('colorbox_' . $me . '_image', 1);
		setOption('colorbox_' . $me . '_index', 1);
		setOption('colorbox_' . $me . '_news', 1);
		setOption('colorbox_' . $me . '_pages', 1);
		setOption('colorbox_' . $me . '_password', 1);
		setOption('colorbox_' . $me . '_register', 1);
		setOption('colorbox_' . $me . '_search', 1);

		if (class_exists('cacheManager')) {
			cacheManager::deleteCacheSizes($me);
			cacheManager::addDefaultThumbSize();
			cacheManager::addDefaultSizedImageSize();
			if (getOption('use_galleriffic')) {
				cacheManager::addThemeCacheSize($me, 85, 85, 85, 85, 85, NULL, NULL, true);
				cacheManager::addThemeCacheSize($me, 555, NULL, NULL, NULL, NULL, NULL, NULL, false);
			}
		}
	}

	function getOptionsDisabled() {
		return array('thumb_size', 'image_size', 'custom_index_page');
	}

	function getOptionsSupported() {

		$unpublishedpages = query_full_array("SELECT title, titlelink FROM " . prefix('pages') . " WHERE `show` != 1 ORDER by `sort_order`");
		$unpub_list = array();
		foreach ($unpublishedpages as $page) {
			$unpub_list[get_language_string($page['title'])] = $page['titlelink'];
		}

		return array(
			gettext('徽标文件位置') => array('order' => 0, 'key' => 'use_image_logo_filename', 'type' => OPTION_TYPE_TEXTBOX, 'multilingual' => 0, 'desc' => gettext_th('徽标区域的图像文件：输入位于themes/zpArdoise/images/中的图像文件的完整文件名（包括扩展名）（例如banniere1.jpg）。', 'zpArdoise')),
			gettext('是否显示徽标') => array('order' => 1, 'key' => 'show_image_logo_on_image', 'type' => OPTION_TYPE_CHECKBOX, 'desc' => gettext_th('选中以在图像页面上显示徽标。', 'zpArdoise')),
			gettext('风格') => array('order' => 2, 'key' => 'css_style', 'type' => OPTION_TYPE_CUSTOM, 'desc' => gettext_th('选择Dark或Light作为网站的颜色样式。', 'zpArdoise')),
			gettext('颜色') => array('order' => 3, 'key' => 'color_style', 'type' => OPTION_TYPE_CUSTOM, 'desc' => gettext_th('选择链接的颜色：选择“默认”会使用“暗”或“浅”样式的默认颜色，选择“自定义”可以使用自定义值。同时您还可以通过编辑文件theme/zpArdoise/css/custom.css来自定义这些值。', 'zpArdoise')),
			gettext('主页') => array('order' => 4, 'key' => 'zenpage_homepage', 'type' => OPTION_TYPE_SELECTOR, 'selections' => $unpub_list, 'null_selection' => gettext('none'), 'desc' => gettext("在此选择任何<em>未发布的Zenpage页面</em>（由<em>titlelink</em>列出）作为您网站的主页，而不是正常的图库索引。")
																																																. "<p class='notebox'>" . gettext("<strong>注意：</strong>这会覆盖<em>索引页面上的新闻</em>选项，并且必须为此功能设置主题！有关详细信息，请访问主题教程。") . "</p>"),
			gettext('显示记录模式链接') => array('order' => 5, 'key' => 'show_archive', 'type' => OPTION_TYPE_CHECKBOX, 'desc' => gettext_th('显示记录模式的菜单链接。', 'zpArdoise')),
			gettext('允许搜索') => array('order' => 6, 'key' => 'allow_search', 'type' => OPTION_TYPE_CHECKBOX, 'desc' => gettext('启用搜索表单。')),
			gettext('显示标签') => array('order' => 7, 'key' => 'show_tag', 'type' => OPTION_TYPE_CHECKBOX, 'desc' => gettext_th('显示包含图库所有标记的标记云。', 'zpArdoise')),
			gettext('显示相册底部图像条') => array('order' => 8, 'key' => 'image_statistic', 'type' => OPTION_TYPE_CUSTOM, 'desc' => gettext_th('根据所选选项在“图库”页面上显示一条缩略图。注意：对于除随机以外的任何内容，都必须激活image_album_statistics插件。', 'zpArdoise')),
			gettext('使用Galleriffic脚本') => array('order' => 9, 'key' => 'use_galleriffic', 'type' => OPTION_TYPE_CHECKBOX, 'desc' => gettext_th('选中此项可以使用Galleriffic脚本。否则使用标准显示。当浏览器禁用javascript时，会使用标准显示。', 'zpArdoise')),
			gettext('Galleriffic幻灯片延迟') => array('order' => 10, 'key' => 'galleriffic_delai', 'type' => OPTION_TYPE_TEXTBOX, 'desc' => gettext_th('如果使用了Galleriffic，请以毫秒为单位输入画廊幻灯片的延迟（例如3000）。PS：1秒等于1000毫秒。', 'zpArdoise')),
			gettext('在相册页面中使用Colorbox') => array('order' => 11, 'key' => 'use_colorbox_album', 'type' => OPTION_TYPE_CHECKBOX, 'desc' => gettext_th('在启用Galleriffic的情况下，选中此项Colorbox将在相册页面中显示完整尺寸的图像。注意：在这种情况下，永远不会使用图像页面！', 'zpArdoise')),
			gettext('在图像页面中使用Colorbox') => array('order' => 12, 'key' => 'use_colorbox_image', 'type' => OPTION_TYPE_CHECKBOX, 'desc' => gettext_th('在启用Colorbox的情况下，选中此项Colorbox将会在图像页面显示完整尺寸的图像。', 'zpArdoise')),
			gettext('显示图像EXIF数据') => array('order' => 13, 'key' => 'show_exif', 'type' => OPTION_TYPE_CHECKBOX, 'desc' => gettext_th('在图像页面上显示主要的EXIF数据（Model，FocalLength，FNumber，ExposureTime，ISOSpeedRatings）。请记住，您必须在管理>选项>图像>元数据。上检查这些EXIF数据。', 'zpArdoise'))
		);
	}

	function handleOption($option, $currentValue) {

		if ($option == 'css_style') {
			echo '<select style="width: 200px;" id="' . $option . '" name="' . $option . '"' . ">\n";
			echo '<option value="dark"';
				if ($currentValue == 'dark') {
					echo ' selected="selected">黑暗风格</option>\n';
				} else {
					echo '>黑暗风格</option>\n';
				}
			echo '<option value="light"';
				if ($currentValue == 'light') {
					echo ' selected="selected">明亮风格</option>\n';
				} else {
					echo '>明亮风格</option>\n';
				}
			echo "</select>\n";
		}

		if ($option == 'color_style') {
			echo '<select style="width: 200px;" id="' . $option . '" name="' . $option . '"' . ">\n";
			echo '<option value="default"';
				if ($currentValue == 'default') {
					echo ' selected="selected">使用主题默认</option>\n';
				} else {
					echo '>使用主题默认</option>\n';
				}
			echo '<option value="custom"';
				if ($currentValue == 'custom') {
					echo ' selected="selected">使用风格默认</option>\n';
				} else {
					echo '>使用风格默认</option>\n';
				}
			echo "</select>\n";
		}

		if ($option == 'image_statistic') {
			echo '<select style="width: 200px;" id="' . $option . '" name="' . $option . '"' . ">\n";
			echo '<option value="none"';
				if ($currentValue == 'none') {
					echo ' selected="selected">无/不显示/关闭</option>\n';
				} else {
					echo '>无/不显示/关闭</option>\n';
				}
			echo '<option value="random"';
				if ($currentValue == 'random') {
					echo ' selected="selected">随机显示</option>\n';
				} else {
					echo '>随机显示</option>\n';
				}
			echo '<option value="popular"';
				if ($currentValue == 'popular') {
					echo ' selected="selected">显示流行的</option>\n';
				} else {
					echo '>显示流行的</option>\n';
				}
			echo '<option value="latest"';
				if ($currentValue == 'latest') {
					echo ' selected="selected">显示最近的相册</option>\n';
				} else {
					echo '>显示最近的相册</option>\n';
				}
			echo '<option value="latest-date"';
				if ($currentValue == 'latest-date') {
					echo ' selected="selected">显示最新的相片</option>\n';
				} else {
					echo '>显示最新的相片</option>\n';
				}
			echo '<option value="latest-mtime"';
				if ($currentValue == 'latest-mtime') {
					echo ' selected="selected">显示最近修改过相册</option>\n';
				} else {
					echo '>显示最近修改过相册</option>\n';
				}
			echo '<option value="mostrated"';
				if ($currentValue == 'mostrated') {
					echo ' selected="selected">显示评分高的</option>\n';
				} else {
					echo '>显示评分高的</option>\n';
				}
			echo '<option value="toprated"';
				if ($currentValue == 'toprated') {
					echo ' selected="selected">显示评分最高的(Top)</option>\n';
				} else {
					echo '>显示评分最高的(Top)</option>\n';
				}
			echo "</select>\n";
		}
	}
}
?>
