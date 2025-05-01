<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG <writeblabla@gmail.com>
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_IS_MOD_MUSIC')) {
    die('Stop!!!');
}

use NukeViet\Music\Config;
use NukeViet\Music\Resources;
use NukeViet\Music\Utils;

/**
 * nv_theme_gird_albums()
 *
 * @param mixed $array
 * @param bool $full_layout
 * @return
 */
function nv_theme_gird_albums($array, $full_layout = false)
{
    global $module_info, $module_upload, $op, $nv_Lang;

    $xtpl = new XTemplate('gird-albums.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('UNIQUEID', nv_genpass(6));
    $xtpl->assign('CLASS_ITEM', $full_layout ? 'col-xs-12 col-sm-6 col-md-4' : 'col-xs-12 col-sm-8 col-md-6');

    foreach ($array as $row) {
        $row['resource_avatar_thumb'] = nv_get_resource_url($row['resource_avatar'], 'album', true);
        $row['resource_avatar'] = nv_get_resource_url($row['resource_avatar'], 'album');

        $xtpl->assign('ROW', $row);

        $num_singers = sizeof($row['singers']);
        if ($num_singers > Config::getLimitSingersDisplayed()) {
            $xtpl->assign('VA_SINGERS', Config::getVariousArtists());

            foreach ($row['singers'] as $singer) {
                $xtpl->assign('SINGER', $singer);
                $xtpl->parse('main.loop.va_singer.loop');
            }

            $xtpl->parse('main.loop.va_singer');
        } elseif (!empty($row['singers'])) {
            $i = 0;
            foreach ($row['singers'] as $singer) {
                $i++;
                $xtpl->assign('SINGER', $singer);

                if ($i > 1) {
                    $xtpl->parse('main.loop.show_singer.loop.separate');
                }
                $xtpl->parse('main.loop.show_singer.loop');
            }
            $xtpl->parse('main.loop.show_singer');
        } else {
            $xtpl->assign('UNKNOW_SINGER', Config::getUnknowSinger());
            $xtpl->parse('main.loop.no_singer');
        }

        $xtpl->parse('main.loop');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * @param array $array
 * @return string
 */
function nv_theme_list_albums($array)
{
    global $module_info, $module_upload, $op, $nv_Lang;

    $xtpl = new XTemplate('list-albums.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('UNIQUEID', nv_genpass(6));

    foreach ($array as $row) {
        $row['resource_avatar_thumb'] = nv_get_resource_url($row['resource_avatar'], 'album', true);
        $row['resource_avatar'] = nv_get_resource_url($row['resource_avatar'], 'album');

        $xtpl->assign('ROW', $row);

        $num_singers = sizeof($row['singers']);
        if ($num_singers > Config::getLimitSingersDisplayed()) {
            $xtpl->assign('VA_SINGERS', Config::getVariousArtists());

            foreach ($row['singers'] as $singer) {
                $xtpl->assign('SINGER', $singer);
                $xtpl->parse('main.loop.va_singer.loop');
            }

            $xtpl->parse('main.loop.va_singer');
        } elseif (!empty($row['singers'])) {
            $i = 0;
            foreach ($row['singers'] as $singer) {
                $i++;
                $xtpl->assign('SINGER', $singer);

                if ($i > 1) {
                    $xtpl->parse('main.loop.show_singer.loop.separate');
                }
                $xtpl->parse('main.loop.show_singer.loop');
            }
            $xtpl->parse('main.loop.show_singer');
        } else {
            $xtpl->assign('UNKNOW_SINGER', Config::getUnknowSinger());
            $xtpl->parse('main.loop.no_singer');
        }

        $xtpl->parse('main.loop');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_gird_videos()
 *
 * @param mixed $array
 * @param bool $full_layout
 * @return
 */
function nv_theme_gird_videos($array, $full_layout = false)
{
    global $module_info, $module_upload, $op, $nv_Lang;

    $xtpl = new XTemplate('gird-videos.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('UNIQUEID', nv_genpass(6));
    $xtpl->assign('CLASS_ITEM', $full_layout ? 'col-xs-12 col-sm-6 col-md-4' : 'col-xs-12 col-sm-8 col-md-6');

    if (is_file(NV_ROOTDIR . '/themes/' . $module_info['template'] . '/images/' . $module_info['module_theme'] . '/pix-16-9.gif')) {
        $pix_image = NV_STATIC_URL . 'themes/' . $module_info['template'] . '/images/' . $module_info['module_theme'] . '/pix-16-9.gif';
    } else {
        $pix_image = NV_STATIC_URL . 'themes/default/images/' . $module_info['module_theme'] . '/pix-16-9.gif';
    }
    $xtpl->assign('PIX_IMAGE', $pix_image);

    foreach ($array as $row) {
        $row['resource_avatar_thumb'] = nv_get_resource_url($row['resource_avatar'], 'video', true);
        $row['resource_avatar'] = nv_get_resource_url($row['resource_avatar'], 'video');

        $xtpl->assign('ROW', $row);

        $num_singers = sizeof($row['singers']);
        if ($num_singers > Config::getLimitSingersDisplayed()) {
            $xtpl->assign('VA_SINGERS', Config::getVariousArtists());

            foreach ($row['singers'] as $singer) {
                $xtpl->assign('SINGER', $singer);
                $xtpl->parse('main.loop.va_singer.loop');
            }

            $xtpl->parse('main.loop.va_singer');
        } elseif (!empty($row['singers'])) {
            $i = 0;
            foreach ($row['singers'] as $singer) {
                $i++;
                $xtpl->assign('SINGER', $singer);

                if ($i > 1) {
                    $xtpl->parse('main.loop.show_singer.loop.separate');
                }
                $xtpl->parse('main.loop.show_singer.loop');
            }
            $xtpl->parse('main.loop.show_singer');
        } else {
            $xtpl->assign('UNKNOW_SINGER', Config::getUnknowSinger());
            $xtpl->parse('main.loop.no_singer');
        }

        $xtpl->parse('main.loop');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_gird_singers()
 *
 * @param mixed $array_singers
 * @param mixed $nation_id
 * @param mixed $alphabet
 * @param mixed $generate_page
 * @return
 */
function nv_theme_gird_singers($array_singers, $nation_id, $alphabet, $generate_page)
{
    global $module_info, $module_upload, $op, $global_array_nation, $array_alphabets, $nv_Lang;

    $xtpl = new XTemplate('gird-singers.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

    $xtpl->assign('NATION_ALL_LINK', Resources::getModFullLinkEncode() . $module_info['alias']['list-singers']);
    if (empty($nation_id)) {
        $xtpl->parse('main.nav.all_active');
    }

    foreach ($global_array_nation as $nation) {
        $array = $nation->toArray();
        $array['nation_link'] = Resources::getModFullLinkEncode() . $module_info['alias']['list-singers'] . '/' . $nation->getAlias() . '-' . $nation->getCode();
        $xtpl->assign('NATION', $array);

        if ($nation->getId() == $nation_id) {
            $xtpl->parse('main.nav.loop.active');
        }

        $xtpl->parse('main.nav.loop');
    }
    $xtpl->parse('main.nav');

    $base_alphabet_url = Resources::getModFullLinkEncode() . $module_info['alias']['list-singers'];
    if (!empty($nation_id)) {
        $base_alphabet_url .= '/' . $global_array_nation[$nation_id]->getAlias() . '-' . $global_array_nation[$nation_id]->getCode();
    }

    $xtpl->assign('ALPHABET_ALL_TITLE', '#');
    $xtpl->assign('ALPHABET_ALL_LINK', $base_alphabet_url);
    if (empty($alphabet)) {
        $xtpl->parse('main.alphabet.all_active');
    }

    foreach ($array_alphabets as $alphabet_i) {
        $xtpl->assign('ALPHABET_TITLE', $alphabet_i);
        $xtpl->assign('ALPHABET_LINK', $base_alphabet_url . '/' . $alphabet_i);

        if ($alphabet_i == $alphabet) {
            $xtpl->parse('main.alphabet.loop.active');
        }

        $xtpl->parse('main.alphabet.loop');
    }
    $xtpl->parse('main.alphabet');

    foreach ($array_singers as $row) {
        $row['resource_avatar_thumb'] = nv_get_resource_url($row['resource_avatar'], 'singer', true);
        $row['resource_avatar'] = nv_get_resource_url($row['resource_avatar'], 'singer');

        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.loop');
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_list_songs()
 *
 * @param mixed $array
 * @return
 */
function nv_theme_list_songs($array)
{
    global $module_info, $module_upload, $nv_Lang;

    $xtpl = new XTemplate('list-songs.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('UNIQUEID', nv_genpass(6));

    foreach ($array as $row) {
        $xtpl->assign('ROW', $row);

        $num_singers = sizeof($row['singers']);
        if ($num_singers > Config::getLimitSingersDisplayed()) {
            $xtpl->assign('VA_SINGERS', Config::getVariousArtists());

            foreach ($row['singers'] as $singer) {
                $xtpl->assign('SINGER', $singer);
                $xtpl->parse('main.loop.va_singer.loop');
            }

            $xtpl->parse('main.loop.va_singer');
        } elseif (!empty($row['singers'])) {
            $i = 0;
            foreach ($row['singers'] as $singer) {
                $i++;
                $xtpl->assign('SINGER', $singer);

                if ($i > 1) {
                    $xtpl->parse('main.loop.show_singer.loop.separate');
                }
                $xtpl->parse('main.loop.show_singer.loop');
            }
            $xtpl->parse('main.loop.show_singer');
        } else {
            $xtpl->assign('UNKNOW_SINGER', Config::getUnknowSinger());
            $xtpl->parse('main.loop.no_singer');
        }

        $xtpl->parse('main.loop');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * @param array $array
 * @return string
 */
function nv_theme_list_playlists($array)
{
    global $module_info, $module_upload, $op, $nv_Lang;

    $xtpl = new XTemplate('list-playlists.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('UNIQUEID', nv_genpass(6));

    foreach ($array as $row) {
        $row['resource_avatar_thumb'] = nv_get_resource_url($row['resource_avatar'], 'album', true);
        $row['resource_avatar'] = nv_get_resource_url($row['resource_avatar'], 'album');
        $row['num_songs'] = Utils::getFormatNumberView($row['num_songs']);

        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.loop');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * @param array $array
 * @param boolean $full_layout
 * @return string
 */
function nv_theme_gird_playlists($array, $full_layout = false)
{
    global $module_info, $module_upload, $op, $nv_Lang;

    $xtpl = new XTemplate('gird-playlists.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('UNIQUEID', nv_genpass(6));
    $xtpl->assign('CLASS_ITEM', $full_layout ? 'col-xs-12 col-sm-6 col-md-4' : 'col-xs-12 col-sm-8 col-md-6');

    foreach ($array as $row) {
        $row['resource_avatar_thumb'] = nv_get_resource_url($row['resource_avatar'], 'album', true);
        $row['resource_avatar'] = nv_get_resource_url($row['resource_avatar'], 'album');
        $row['num_songs'] = Utils::getFormatNumberView($row['num_songs']);

        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.loop');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_main()
 *
 * @param mixed $content_albums
 * @param mixed $content_videos
 * @param mixed $content_singers
 * @param mixed $content_songs
 * @return
 */
function nv_theme_main($content_albums, $content_videos, $content_singers, $content_songs)
{
    global $module_info, $module_upload, $nv_Lang;

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

    $contents = [];

    if (!empty($content_albums)) {
        $xtpl->assign('ALBUMS_LINK', Resources::getModFullLinkEncode() . $module_info['alias']['list-albums']);
        $xtpl->assign('ALBUMS_HTML', nv_theme_gird_albums($content_albums));
        $xtpl->parse('albums');
        $contents[Config::getHomeAlbumsWeight()] = $xtpl->text('albums');
    }

    if (!empty($content_singers)) {
        $xtpl->assign('SINGERS_LINK', Resources::getModFullLinkEncode() . $module_info['alias']['list-singers']);

        $i = 0;
        foreach ($content_singers as $singer) {
            $singer['resource_avatar_thumb'] = nv_get_resource_url($singer['resource_avatar'], 'singer', true);
            $singer['resource_avatar'] = nv_get_resource_url($singer['resource_avatar'], 'singer');

            if ($i == 0) {
                $singer['resource_avatar_thumb'] = $singer['resource_avatar'];
            }

            $xtpl->assign('SINGER', $singer);

            if ($i++ % 9 == 0) {
                $xtpl->parse('singers.loop.x2');
            } else {
                $xtpl->parse('singers.loop.x1');
            }

            $xtpl->parse('singers.loop');
        }

        $xtpl->parse('singers');
        $contents[Config::getHomeSingersWeight()] = $xtpl->text('singers');
    }

    if (!empty($content_songs)) {
        foreach ($content_songs as $row) {
            $row['resource_avatar_thumb'] = nv_get_resource_url($row['resource_avatar'], $row['resource_mode'], true);
            $row['resource_avatar'] = nv_get_resource_url($row['resource_avatar'], $row['resource_mode']);

            $xtpl->assign('ROW', $row);

            $num_singers = sizeof($row['singers']);
            if ($num_singers > Config::getLimitSingersDisplayed()) {
                $xtpl->assign('VA_SINGERS', Config::getVariousArtists());

                foreach ($row['singers'] as $singer) {
                    $xtpl->assign('SINGER', $singer);
                    $xtpl->parse('songs.loop.va_singer.loop');
                }

                $xtpl->parse('songs.loop.va_singer');
            } elseif (!empty($row['singers'])) {
                $i = 0;
                foreach ($row['singers'] as $singer) {
                    $i++;
                    $xtpl->assign('SINGER', $singer);

                    if ($i > 1) {
                        $xtpl->parse('songs.loop.show_singer.loop.separate');
                    }
                    $xtpl->parse('songs.loop.show_singer.loop');
                }
                $xtpl->parse('songs.loop.show_singer');
            } else {
                $xtpl->assign('UNKNOW_SINGER', Config::getUnknowSinger());
                $xtpl->parse('songs.loop.no_singer');
            }

            $xtpl->parse('songs.loop');
        }

        $xtpl->parse('songs');
        $contents[Config::getHomeSongsWeight()] = $xtpl->text('songs');
    }

    if (!empty($content_videos)) {
        $xtpl->assign('VIDEOS_LINK', Resources::getModFullLinkEncode() . $module_info['alias']['list-videos']);
        $xtpl->assign('VIDEOS_HTML', nv_theme_gird_videos($content_videos));
        $xtpl->parse('videos');
        $contents[Config::getHomeVideosWeight()] = $xtpl->text('videos');
    }

    // Sắp xếp lại theo thứ tự cấu hình
    ksort($contents);
    return implode("\n", $contents);
}

/**
 * nv_theme_page_list_albums()
 *
 * @param mixed $array
 * @param bool $is_detail_cat
 * @param mixed $generate_page
 * @return
 */
function nv_theme_page_list_albums($array, $is_detail_cat = false, $generate_page = '')
{
    global $module_info, $nv_Lang;

    $xtpl = new XTemplate('page-list-albums.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

    $codePrefix = Config::getCodePrefix();

    foreach ($array as $cat) {
        $xtpl->assign('CAT', $cat['cat']);
        $xtpl->assign('ALBUMS_HTML', nv_theme_gird_albums($cat['albums']));

        if (empty($is_detail_cat)) {
            $xtpl->assign('CAT_LINK', Resources::getModFullLinkEncode() . $module_info['alias']['list-albums'] . '/' . $cat['cat']['cat_alias'] . '-' . $codePrefix->getCat() . $cat['cat']['cat_code']);
            $xtpl->parse('main.loopcat.cat_link');
        } else {
            $xtpl->parse('main.loopcat.cat_text');
        }

        $xtpl->parse('main.loopcat');
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_page_list_videos()
 *
 * @param mixed $array
 * @param bool $is_detail_cat
 * @param mixed $generate_page
 * @return
 */
function nv_theme_page_list_videos($array, $is_detail_cat = false, $generate_page = '')
{
    global $module_info, $nv_Lang;

    $xtpl = new XTemplate('page-list-videos.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

    $codePrefix = Config::getCodePrefix();

    foreach ($array as $cat) {
        $xtpl->assign('CAT', $cat['cat']);
        $xtpl->assign('VIDEOS_HTML', nv_theme_gird_videos($cat['videos']));

        if (empty($is_detail_cat)) {
            $xtpl->assign('CAT_LINK', Resources::getModFullLinkEncode() . $module_info['alias']['list-videos'] . '/' . $cat['cat']['cat_alias'] . '-' . $codePrefix->getCat() . $cat['cat']['cat_code']);
            $xtpl->parse('main.loopcat.cat_link');
        } else {
            $xtpl->parse('main.loopcat.cat_text');
        }

        $xtpl->parse('main.loopcat');
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_view_singer()
 *
 * @param mixed $data_singer
 * @param mixed $request_tab
 * @param mixed $array_songs
 * @param mixed $array_videos
 * @param mixed $array_albums
 * @param mixed $generate_page
 * @return
 */
function nv_theme_view_singer($data_singer, $request_tab, $array_songs, $array_videos, $array_albums, $generate_page)
{
    global $module_info, $global_array_nation, $op, $nv_Lang;

    $xtpl = new XTemplate('view-singer.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

    $xtpl->assign('SINGER', $data_singer);

    // Hiển thị phần thông tin đầu
    if (!empty(Config::getViewSingerShowHeader())) {
        $xtpl->assign('HEADER_HTML', nv_theme_view_singer_header($data_singer, $request_tab));
        $xtpl->parse('main.header');
    }

    // Xem tiểu sử
    if ($request_tab == 'profile') {
        $num_info_had = 0;
        if (!empty($data_singer['singer_nickname'])) {
            $num_info_had++;
            $xtpl->parse('main.profile.singer_nickname');
        }
        if (!empty($data_singer['artist_realname'])) {
            $num_info_had++;
            $xtpl->parse('main.profile.artist_realname');
        }
        if (!empty($data_singer['artist_hometown'])) {
            $num_info_had++;
            $xtpl->parse('main.profile.artist_hometown');
        }
        if (!empty($data_singer['singer_prize'])) {
            $num_info_had++;
            $xtpl->parse('main.profile.singer_prize');
        }
        if (!empty($data_singer['singer_info'])) {
            $num_info_had++;
            $xtpl->parse('main.profile.singer_info');
        }

        if (isset($global_array_nation[$data_singer['nation_id']])) {
            $num_info_had++;
            $xtpl->assign('NATION_NAME', $global_array_nation[$data_singer['nation_id']]->getName());
            $xtpl->parse('main.profile.nation');
        }

        if (!empty($data_singer['artist_birthday']) and !empty($data_singer['artist_birthday_lev'])) {
            $num_info_had++;
            if ($data_singer['artist_birthday_lev'] == 3) {
                $format_date = 'd/m/Y';
            } elseif ($data_singer['artist_birthday_lev'] == 2) {
                $format_date = 'm/Y';
            } else {
                $format_date = 'Y';
            }
            $xtpl->assign('artist_birthday', nv_date($format_date, $data_singer['artist_birthday']));
            $xtpl->parse('main.profile.artist_birthday');
        }

        if (empty($num_info_had)) {
            $xtpl->assign('EMPTY_MESSAGE', sprintf($nv_Lang->getModule('view_singer_empty_profile'), $data_singer['artist_name']));
            $xtpl->parse('main.profile.empty');
        }

        $xtpl->parse('main.profile');
    }

    if (!empty($array_albums)) {
        $xtpl->assign('ALBUM_HTML', nv_theme_gird_albums($array_albums));

        if (empty($request_tab)) {
            $xtpl->assign('ALBUM_LINK', nv_get_view_singer_link($data_singer, true, 'album'));
            $xtpl->parse('main.albums.link');
        } else {
            $xtpl->parse('main.albums.text');
        }

        $xtpl->parse('main.albums');
    }

    if (!empty($array_songs)) {
        $xtpl->assign('SONG_HTML', nv_theme_list_songs($array_songs));

        if (empty($request_tab)) {
            $xtpl->assign('SONG_LINK', nv_get_view_singer_link($data_singer, true, 'song'));
            $xtpl->parse('main.songs.link');
        } else {
            $xtpl->parse('main.songs.text');
        }

        $xtpl->parse('main.songs');
    }

    if (!empty($array_videos)) {
        $xtpl->assign('VIDEO_HTML', nv_theme_gird_videos($array_videos));

        if (empty($request_tab)) {
            $xtpl->assign('VIDEO_LINK', nv_get_view_singer_link($data_singer, true, 'video'));
            $xtpl->parse('main.videos.link');
        } else {
            $xtpl->parse('main.videos.text');
        }

        $xtpl->parse('main.videos');
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_view_singer_header()
 *
 * @param mixed $data_singer
 * @param mixed $request_tab
 * @return
 */
function nv_theme_view_singer_header($data_singer, $request_tab)
{
    global $module_info, $module_upload, $nv_Lang;

    $xtpl = new XTemplate('view-singer-header.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

    $xtpl->assign('LINK_PROFILE_TAB', nv_get_view_singer_link($data_singer, true, 'profile'));
    $xtpl->assign('LINK_DEFAULT_TAB', nv_get_view_singer_link($data_singer));
    if (empty($request_tab)) {
        $xtpl->parse('main.active_default_tab');
    }

    // Các TAB
    $allTabs = Config::getSingerTabsAlias()->getAllTabs();
    foreach ($allTabs as $tab_key => $tab_alias) {
        $xtpl->assign('TAB_TITLE', $nv_Lang->getModule('view_singer_tab_' . $tab_key));
        $xtpl->assign('TAB_LINK', nv_get_view_singer_link($data_singer, true, $tab_key));

        if ($request_tab == $tab_key) {
            $xtpl->parse('main.tabloop.active');
        }

        $xtpl->parse('main.tabloop');
    }

    $data_singer['resource_avatar_thumb'] = nv_get_resource_url($data_singer['resource_avatar'], 'singer', true);
    $data_singer['resource_avatar'] = nv_get_resource_url($data_singer['resource_avatar'], 'singer');
    $data_singer['resource_cover'] = nv_get_resource_url($data_singer['resource_cover'], '----');
    $xtpl->assign('SINGER', $data_singer);

    if (!empty($data_singer['singer_info'])) {
        $xtpl->assign('HEADTEXT', nv_clean60(strip_tags($data_singer['singer_info']), Config::getViewSingerHeadtextLength()));
        $xtpl->parse('main.headtext');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_detail_song()
 *
 * @param mixed $array
 * @param mixed $content_comment
 * @param mixed $array_albums
 * @param mixed $array_videos
 * @return
 */
function nv_theme_detail_song($array, $content_comment, $array_albums, $array_videos)
{
    global $module_file, $module_info, $module_upload, $is_embed_mode, $module_data, $nv_Lang;

    $xtpl = new XTemplate('detail-song.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);
    $xtpl->assign('UNIQUEID', nv_genpass(6));
    $xtpl->assign('MODULE_DATA', $module_data);

    $xtpl->assign('PLAYER_DIR', NV_STATIC_URL . 'themes/default/images/' . $module_file . '/jwplayer/');

    $array['resource_avatar_thumb'] = nv_get_resource_url($array['resource_avatar'], 'singer', true);
    $array['resource_avatar'] = nv_get_resource_url($array['resource_avatar'], 'singer');
    $array['resource_cover'] = nv_get_resource_url($array['resource_cover'], '----');

    $xtpl->assign('SONG', $array);

    $song_full_name = $array['song_name'];
    $song_full_singer = [];

    // Xuất ca sĩ
    $num_singers = sizeof($array['singers']);
    if ($num_singers > Config::getLimitSingersDisplayed()) {
        $xtpl->assign('VA_SINGERS', Config::getVariousArtists());

        foreach ($array['singers'] as $singer) {
            $song_full_singer[] = $singer['artist_name'];
            $xtpl->assign('SINGER', $singer);
            $xtpl->parse('main.va_singer.loop');
        }

        $xtpl->parse('main.va_singer');
    } elseif (!empty($array['singers'])) {
        $i = 0;
        foreach ($array['singers'] as $singer) {
            $i++;
            $song_full_singer[] = $singer['artist_name'];
            $xtpl->assign('SINGER', $singer);

            if ($i > 1) {
                $xtpl->parse('main.show_singer.loop.separate');
            }
            $xtpl->parse('main.show_singer.loop');
        }
        $xtpl->parse('main.show_singer');
    } else {
        $song_full_singer[] = Config::getUnknowSinger();
        $xtpl->assign('UNKNOW_SINGER', Config::getUnknowSinger());
        $xtpl->parse('main.no_singer');
    }

    $xtpl->assign('SONG_FULL_NAME', str_replace('"', '\"', $song_full_name));
    $xtpl->assign('SONG_FULL_SINGER', str_replace('"', '\"', implode(', ', $song_full_singer)));

    // Xuất nhạc sĩ
    $num_authors = sizeof($array['authors']);
    if ($num_authors > Config::getLimitAuthorsDisplayed()) {
        $xtpl->assign('VA_AUTHORS', Config::getVariousArtistsAuthors());

        foreach ($array['authors'] as $author) {
            $xtpl->assign('AUTHOR', $author);
            $xtpl->parse('main.va_author.loop');
        }

        $xtpl->parse('main.va_author');
    } elseif (!empty($array['authors'])) {
        $i = 0;
        foreach ($array['authors'] as $author) {
            $i++;
            $xtpl->assign('AUTHOR', $author);

            if ($i > 1) {
                $xtpl->parse('main.show_author.loop.separate');
            }
            $xtpl->parse('main.show_author.loop');
        }
        $xtpl->parse('main.show_author');
    } else {
        $xtpl->assign('UNKNOW_AUTHOR', Config::getUnknowAuthor());
        $xtpl->parse('main.no_author');
    }

    // Xuất thể loại
    $num_cats = sizeof($array['cats']);
    if ($num_cats > 0) {
        $i = 0;
        foreach ($array['cats'] as $cat) {
            $i++;
            $xtpl->assign('CAT', $cat);

            if ($i > 1) {
                $xtpl->parse('main.show_cat.loop.separate');
            }
            $xtpl->parse('main.show_cat.loop');
        }
        $xtpl->parse('main.show_cat');
    } else {
        $xtpl->assign('UNKNOW_CAT', Config::getUnknowCat());
        $xtpl->parse('main.no_cat');
    }

    // Xử lý yêu thích
    if ($array['require_login']) {
        $xtpl->parse('main.require_login');
    }
    if ($array['favorited']) {
        $xtpl->parse('main.favorited');
    } else {
        $xtpl->parse('main.nofavorite');
    }

    // Video của bài hát
    if (!empty($array['video'])) {
        $xtpl->parse('main.video');
    }

    // Xuất đường dẫn cho player
    $i = 0;
    foreach ($array['filesdata'] as $_fileinfo) {
        $i++;
        $_fileinfo['resource_path'] = str_replace('"', '\"', $_fileinfo['resource_path']);
        $_fileinfo['quality_name'] = str_replace('"', '\"', $_fileinfo['quality_name']);
        $xtpl->assign('FILESDATA', $_fileinfo);
        if ($i > 1) {
            $xtpl->parse('main.player.filesdata.comma');
        }
        $xtpl->parse('main.player.filesdata');
    }

    // Lời bài hát
    if (!empty($array['captions'])) {
        $i = 0;
        foreach ($array['captions'] as $track) {
            $i++;
            $track['is_default'] = !empty($track['is_default']) ? 'true' : 'false';
            $xtpl->assign('TRACK', $track);
            if ($i > 1) {
                $xtpl->parse('main.player.tracks.loop.comma');
            }
            $xtpl->parse('main.player.tracks.loop');
        }

        $xtpl->parse('main.player.tracks');
    }

    $xtpl->parse('main.player');
    if ($is_embed_mode) {
        return $xtpl->text('main.player');
    }

    // Xuất các album liên quan
    if (!empty($array_albums)) {
        $xtpl->assign('ALBUM_HTML', nv_theme_gird_albums($array_albums));
        $xtpl->parse('main.albums');
    }

    // Xuất các video liên quan
    if (!empty($array_videos)) {
        $xtpl->assign('VIDEO_HTML', nv_theme_gird_videos($array_videos));
        $xtpl->parse('main.videos');
    }

    // Bình luận
    if (!empty($content_comment)) {
        $xtpl->assign('COMMENT_HTML', $content_comment);
        $xtpl->parse('main.comment');

        if (!empty($array['stat_comments'])) {
            $xtpl->assign('COMMENT_NUMS', Utils::getFormatNumberView($array['stat_comments']));
            $xtpl->parse('main.comment_btn.stat');
        }

        $xtpl->parse('main.comment_btn');
    }

    // Tab lời bài hát và sheet nhạc
    $array_tabs = [];
    if (!empty($array['caption_text'])) {
        $array_tabs[] = [
            'type' => 'text',
            'title' => $nv_Lang->getModule('lyric'),
            'data' => $array['caption_text']
        ];
    }
    if (!empty($array['caption_file'])) {
        if ($array['caption_file_ext'] == 'pdf') {
            $array_tabs[] = [
                'type' => 'pdf',
                'title' => $nv_Lang->getModule('sheet'),
                'data' => $array['caption_file']
            ];
        } else {
            $array_tabs[] = [
                'type' => 'iframe',
                'title' => $nv_Lang->getModule('sheet'),
                'data' => $array['caption_file']
            ];
        }
    }
    $num_tabs = sizeof($array_tabs);
    if (!empty($array_tabs)) {
        $stt = 0;
        foreach ($array_tabs as $tabdata) {
            $stt++;
            $xtpl->assign('TABDATA_ACTIVE1', $stt == 1 ? ' class="active"' : '');
            $xtpl->assign('TABDATA_ACTIVE2', $stt == 1 ? ' active' : '');
            $xtpl->assign('TABDATA', $tabdata);

            $xtpl->parse('main.lrtsheet_tabs.loopcontent.' . $tabdata['type']);
            $xtpl->parse('main.lrtsheet_tabs.loopcontent');

            if ($num_tabs > 1) {
                $xtpl->parse('main.lrtsheet_tabs.looptitle');
            }
        }
        $xtpl->parse('main.lrtsheet_tabs');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_detail_video()
 *
 * @param mixed $array
 * @param mixed $content_comment
 * @param mixed $array_albums
 * @param mixed $array_videos
 * @return
 */
function nv_theme_detail_video($array, $content_comment, $array_albums, $array_videos)
{
    global $module_file, $module_info, $module_upload, $is_embed_mode, $module_data, $nv_Lang;

    $xtpl = new XTemplate('detail-video.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);
    $xtpl->assign('UNIQUEID', nv_genpass(6));
    $xtpl->assign('MODULE_DATA', $module_data);

    $xtpl->assign('PLAYER_DIR', NV_STATIC_URL . 'themes/default/images/' . $module_file . '/jwplayer/');

    $array['resource_avatar_thumb'] = nv_get_resource_url($array['resource_avatar'], 'video', true);
    $array['resource_avatar'] = nv_get_resource_url($array['resource_avatar'], 'video');
    $array['resource_cover'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array['resource_cover'];

    $xtpl->assign('VIDEO', $array);

    $video_full_name = $array['video_name'];
    $video_full_singer = [];

    // Xuất ca sĩ
    $num_singers = sizeof($array['singers']);
    if ($num_singers > Config::getLimitSingersDisplayed()) {
        $xtpl->assign('VA_SINGERS', Config::getVariousArtists());

        foreach ($array['singers'] as $singer) {
            $video_full_singer[] = $singer['artist_name'];
            $xtpl->assign('SINGER', $singer);
            $xtpl->parse('main.va_singer.loop');
        }

        $xtpl->parse('main.va_singer');
    } elseif (!empty($array['singers'])) {
        $i = 0;
        foreach ($array['singers'] as $singer) {
            $i++;
            $video_full_singer[] = $singer['artist_name'];
            $xtpl->assign('SINGER', $singer);

            if ($i > 1) {
                $xtpl->parse('main.show_singer.loop.separate');
            }
            $xtpl->parse('main.show_singer.loop');
        }
        $xtpl->parse('main.show_singer');
    } else {
        $video_full_singer[] = Config::getUnknowSinger();
        $xtpl->assign('UNKNOW_SINGER', Config::getUnknowSinger());
        $xtpl->parse('main.no_singer');
    }

    $xtpl->assign('VIDEO_FULL_NAME', str_replace('"', '\"', $video_full_name));
    $xtpl->assign('VIDEO_FULL_SINGER', str_replace('"', '\"', implode(', ', $video_full_singer)));

    // Xuất nhạc sĩ
    $num_authors = sizeof($array['authors']);
    if ($num_authors > Config::getLimitAuthorsDisplayed()) {
        $xtpl->assign('VA_AUTHORS', Config::getVariousArtistsAuthors());

        foreach ($array['authors'] as $author) {
            $xtpl->assign('AUTHOR', $author);
            $xtpl->parse('main.va_author.loop');
        }

        $xtpl->parse('main.va_author');
    } elseif (!empty($array['authors'])) {
        $i = 0;
        foreach ($array['authors'] as $author) {
            $i++;
            $xtpl->assign('AUTHOR', $author);

            if ($i > 1) {
                $xtpl->parse('main.show_author.loop.separate');
            }
            $xtpl->parse('main.show_author.loop');
        }
        $xtpl->parse('main.show_author');
    } else {
        $xtpl->assign('UNKNOW_AUTHOR', Config::getUnknowAuthor());
        $xtpl->parse('main.no_author');
    }

    // Xuất thể loại
    $num_cats = sizeof($array['cats']);
    if ($num_cats > 0) {
        $i = 0;
        foreach ($array['cats'] as $cat) {
            $i++;
            $xtpl->assign('CAT', $cat);

            if ($i > 1) {
                $xtpl->parse('main.show_cat.loop.separate');
            }
            $xtpl->parse('main.show_cat.loop');
        }
        $xtpl->parse('main.show_cat');
    } else {
        $xtpl->assign('UNKNOW_CAT', Config::getUnknowCat());
        $xtpl->parse('main.no_cat');
    }

    // Bài hát của video
    if (!empty($array['song'])) {
        $xtpl->parse('main.song');
    }

    // Xử lý yêu thích
    if ($array['require_login']) {
        $xtpl->parse('main.require_login');
    }
    if ($array['favorited']) {
        $xtpl->parse('main.favorited');
    } else {
        $xtpl->parse('main.nofavorite');
    }

    if (empty($array['resource_yt'])) {
        // Xuất đường dẫn cho jwplayer
        $i = 0;
        foreach ($array['filesdata'] as $_fileinfo) {
            $i++;
            $_fileinfo['resource_path'] = str_replace('"', '\"', $_fileinfo['resource_path']);
            $_fileinfo['quality_name'] = str_replace('"', '\"', $_fileinfo['quality_name']);
            $xtpl->assign('FILESDATA', $_fileinfo);
            if ($i > 1) {
                $xtpl->parse('main.player.filesdata.comma');
            }
            $xtpl->parse('main.player.filesdata');
        }

        if ($is_embed_mode) {
            $xtpl->parse('main.player.embed');
            $xtpl->parse('main.player.embedplayer');
        } else {
            $xtpl->parse('main.player.fullplayer');
        }
        $xtpl->parse('main.player');
        if ($is_embed_mode) {
            return $xtpl->text('main.player');
        }
    } else {
        // Video từ Youtube
        $yotube_code = '';
        if (preg_match("/^(http(s)?\:)?\/\/([w]{3})?\.youtube[^\/]+\/watch\?v\=([^\&]+)\&?(.*?)$/is", $array['resource_yt'], $m)) {
            $yotube_code = $m[4];
        } else if (preg_match("/(http(s)?\:)?\/\/youtu?\.be[^\/]?\/([^\&]+)$/isU", $array['resource_yt'], $m)) {
            $yotube_code = $m[3];
        }
        if (!empty($yotube_code)) {
            $xtpl->assign('YOUTUBE_CODE', $yotube_code);
            $xtpl->parse('main.player_youtube');
        }
    }

    // Xuất các album liên quan
    if (!empty($array_albums)) {
        $xtpl->assign('ALBUM_HTML', nv_theme_gird_albums($array_albums));
        $xtpl->parse('main.albums');
    }

    // Xuất các video liên quan
    if (!empty($array_videos)) {
        $xtpl->assign('VIDEO_HTML', nv_theme_gird_videos($array_videos));
        $xtpl->parse('main.videos');
    }

    // Bình luận
    if (!empty($content_comment)) {
        $xtpl->assign('COMMENT_HTML', $content_comment);
        $xtpl->parse('main.comment');

        if (!empty($array['stat_comments'])) {
            $xtpl->assign('COMMENT_NUMS', Utils::getFormatNumberView($array['stat_comments']));
            $xtpl->parse('main.comment_btn.stat');
        }

        $xtpl->parse('main.comment_btn');
    }

    // Cho phép nhúng, tải về
    if (empty($array['resource_yt'])) {
        $xtpl->parse('main.allowed_download');
        $xtpl->parse('main.allowed_embed');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_detail_album()
 *
 * @param mixed $array
 * @param mixed $array_captions
 * @param mixed $content_comment
 * @param mixed $array_singer_albums
 * @param mixed $array_cat_albums
 * @return
 */
function nv_theme_detail_album($array, $array_captions, $content_comment, $array_singer_albums, $array_cat_albums)
{
    global $module_file, $module_info, $module_upload, $is_embed_mode, $nv_Lang;

    $xtpl = new XTemplate('detail-album.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);
    $xtpl->assign('UNIQUEID', nv_genpass(6));
    $xtpl->assign('PLUNIQUEID', nv_genpass(6));

    $xtpl->assign('PLAYER_DIR', NV_STATIC_URL . 'themes/default/images/' . $module_file . '/jwplayer/');
    $xtpl->assign('PLUGINS_DIR', NV_STATIC_URL . 'themes/default/images/' . $module_file . '/');

    $array['resource_avatar_thumb'] = nv_get_resource_url($array['resource_avatar'], 'album', true);
    $array['resource_avatar'] = nv_get_resource_url($array['resource_avatar'], 'album');
    $array['resource_cover'] = nv_get_resource_url($array['resource_cover'], '----');

    $xtpl->assign('ALBUM', $array);

    // Xuất ca sĩ
    $num_singers = sizeof($array['singers']);
    if ($num_singers > Config::getLimitSingersDisplayed()) {
        $xtpl->assign('VA_SINGERS', Config::getVariousArtists());

        foreach ($array['singers'] as $singer) {
            $xtpl->assign('SINGER', $singer);
            $xtpl->parse('main.va_singer.loop');
        }

        $xtpl->parse('main.va_singer');
    } elseif (!empty($array['singers'])) {
        $i = 0;
        foreach ($array['singers'] as $singer) {
            $i++;
            $xtpl->assign('SINGER', $singer);

            if ($i > 1) {
                $xtpl->parse('main.show_singer.loop.separate');
            }
            $xtpl->parse('main.show_singer.loop');
        }
        $xtpl->parse('main.show_singer');
    } else {
        $xtpl->assign('UNKNOW_SINGER', Config::getUnknowSinger());
        $xtpl->parse('main.no_singer');
    }

    // Xuất thể loại
    $num_cats = sizeof($array['cats']);
    if ($num_cats > 0) {
        $i = 0;
        foreach ($array['cats'] as $cat) {
            $i++;
            $xtpl->assign('CAT', $cat);

            if ($i > 1) {
                $xtpl->parse('main.show_cat.loop.separate');
            }
            $xtpl->parse('main.show_cat.loop');
        }
        $xtpl->parse('main.show_cat');
    } else {
        $xtpl->assign('UNKNOW_CAT', Config::getUnknowCat());
        $xtpl->parse('main.no_cat');
    }

    // Xử lý yêu thích
    if ($array['require_login']) {
        $xtpl->parse('main.require_login');
    }
    if ($array['favorited']) {
        $xtpl->parse('main.favorited');
    } else {
        $xtpl->parse('main.nofavorite');
    }

    // Xuất playlist bài hát
    $soi = 1;
    $soj = 0;
    $plindex = 0;
    foreach ($array['songs'] as $song) {
        $soj++;
        $xtpl->assign('PLSO_STT', $soi++);
        $xtpl->assign('PLSO_INDEX', $plindex);

        $song['resource_avatar_thumb'] = nv_get_resource_url($song['resource_avatar'], $song['resource_avatar_mode'], true);
        $song['resource_avatar'] = nv_get_resource_url($song['resource_avatar'], $song['resource_avatar_mode']);
        $song['resource_cover_thumb'] = nv_get_resource_url($song['resource_cover'], $song['resource_cover_mode'], true);
        $song['resource_cover'] = nv_get_resource_url($song['resource_cover'], $song['resource_cover_mode']);
        $song['song_name_data'] = str_replace('"', '\"', $song['song_name']);

        $xtpl->assign('PLSO_DATA', $song);
        $xtpl->assign('PLSO_LRTTOKEND', md5($song['song_code'] . NV_CHECK_SESSION));
        $xtpl->assign('PLSO_LINK_TARGET', $is_embed_mode ? ' target="_blank"' : '');

        $song_full_name = $song['song_name'];
        $song_full_singer = [];

        // Xuất ca sĩ
        $num_singers = sizeof($song['singers']);
        if ($num_singers > Config::getLimitSingersDisplayed()) {
            $xtpl->assign('PLSO_VA_SINGERS', Config::getVariousArtists());

            foreach ($song['singers'] as $singer) {
                $song_full_singer[] = $singer['artist_name'];
                $xtpl->assign('PLSO_SINGER', $singer);
                $xtpl->parse('main.player.playlist.loop.va_singer.loop');
            }

            $xtpl->parse('main.player.playlist.loop.va_singer');
        } elseif (!empty($song['singers'])) {
            $i = 0;
            foreach ($song['singers'] as $singer) {
                $i++;
                $song_full_singer[] = $singer['artist_name'];
                $xtpl->assign('PLSO_SINGER', $singer);

                if ($i > 1) {
                    $xtpl->parse('main.player.playlist.loop.show_singer.loop.separate');
                }
                $xtpl->parse('main.player.playlist.loop.show_singer.loop');
            }
            $xtpl->parse('main.player.playlist.loop.show_singer');
        } else {
            $song_full_singer[] = Config::getUnknowSinger();
            $xtpl->assign('PLSO_UNKNOW_SINGER', Config::getUnknowSinger());
            $xtpl->parse('main.player.playlist.loop.no_singer');
        }

        $xtpl->assign('PLSO_FULL_NAME', str_replace('"', '\"', $song_full_name));
        $xtpl->assign('PLSO_FULL_SINGER', str_replace('"', '\"', implode(', ', $song_full_singer)));

        if (!$is_embed_mode) {
            $xtpl->parse('main.player.playlist.loop.actions');
        }
        $xtpl->parse('main.player.playlist.loop');

        // Xuất playlist javascript
        $i = 0;
        foreach ($song['filesdata'] as $_fileinfo) {
            $i++;
            $_fileinfo['resource_path'] = str_replace('"', '\"', $_fileinfo['resource_path']);
            $_fileinfo['quality_name'] = str_replace('"', '\"', $_fileinfo['quality_name']);
            $xtpl->assign('FILESDATA', $_fileinfo);
            if ($i > 1) {
                $xtpl->parse('main.player.playlist_js.loop.filesdata.comma');
            }
            $xtpl->parse('main.player.playlist_js.loop.filesdata');
        }

        if (isset($array_captions[$song['song_id']])) {
            $i = 0;
            foreach ($array_captions[$song['song_id']] as $track) {
                $i++;
                $track['is_default'] = !empty($track['is_default']) ? 'true' : 'false';
                $xtpl->assign('TRACK', $track);
                if ($i > 1) {
                    $xtpl->parse('main.player.playlist_js.loop.tracks.loop.comma');
                }
                $xtpl->parse('main.player.playlist_js.loop.tracks.loop');
            }

            $xtpl->parse('main.player.playlist_js.loop.tracks');
        }

        if ($soj > 1) {
            $xtpl->parse('main.player.playlist_js.loop.comma');
        }

        $xtpl->parse('main.player.playlist_js.loop');
        $plindex++;
    }
    if ($is_embed_mode) {
        $xtpl->parse('main.player.playlist.embed');
    }
    $xtpl->parse('main.player.playlist');
    $xtpl->parse('main.player.playlist_js');

    $xtpl->parse('main.player');
    if ($is_embed_mode) {
        $xtpl->parse('main.player.playlist.embed');
        return $xtpl->text('main.player');
    }

    // Xuất các album cùng ca sĩ
    if (!empty($array_singer_albums)) {
        $xtpl->assign('SINGER_ALBUMS_HTML', nv_theme_gird_albums($array_singer_albums));
        $xtpl->parse('main.singer_albums');
    }

    // Xuất các album cùng chủ đề
    if (!empty($array_cat_albums)) {
        $xtpl->assign('CAT_ALBUMS_HTML', nv_theme_gird_albums($array_cat_albums));
        $xtpl->parse('main.cat_albums');
    }

    // Bình luận
    if (!empty($content_comment)) {
        $xtpl->assign('COMMENT_HTML', $content_comment);
        $xtpl->parse('main.comment');

        if (!empty($array['stat_comments'])) {
            $xtpl->assign('COMMENT_NUMS', Utils::getFormatNumberView($array['stat_comments']));
            $xtpl->parse('main.comment_btn.stat');
        }

        $xtpl->parse('main.comment_btn');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

function nv_theme_detail_playlist($array, $array_captions, $content_comment)
{
    global $module_file, $module_info, $module_upload, $is_embed_mode, $nv_Lang;

    $xtpl = new XTemplate('detail-playlist.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);
    $xtpl->assign('UNIQUEID', nv_genpass(6));
    $xtpl->assign('PLUNIQUEID', nv_genpass(6));

    $xtpl->assign('PLAYER_DIR', NV_STATIC_URL . 'themes/default/images/' . $module_file . '/jwplayer/');
    $xtpl->assign('PLUGINS_DIR', NV_STATIC_URL . 'themes/default/images/' . $module_file . '/');

    $array['resource_avatar_thumb'] = nv_get_resource_url($array['resource_avatar'], 'album', true);
    $array['resource_avatar'] = nv_get_resource_url($array['resource_avatar'], 'album');
    $array['resource_cover'] = nv_get_resource_url($array['resource_cover'], '----');

    $xtpl->assign('PLAYLIST', $array);

    // Xuất playlist bài hát
    $soi = 1;
    $soj = 0;
    $plindex = 0;
    foreach ($array['songs'] as $song) {
        $soj++;
        $xtpl->assign('PLSO_STT', $soi++);
        $xtpl->assign('PLSO_INDEX', $plindex);

        $song['resource_avatar_thumb'] = nv_get_resource_url($song['resource_avatar'], $song['resource_avatar_mode'], true);
        $song['resource_avatar'] = nv_get_resource_url($song['resource_avatar'], $song['resource_avatar_mode']);
        $song['resource_cover_thumb'] = nv_get_resource_url($song['resource_cover'], $song['resource_cover_mode'], true);
        $song['resource_cover'] = nv_get_resource_url($song['resource_cover'], $song['resource_cover_mode']);
        $song['song_name_data'] = str_replace('"', '\"', $song['song_name']);

        $xtpl->assign('PLSO_DATA', $song);
        $xtpl->assign('PLSO_LRTTOKEND', md5($song['song_code'] . NV_CHECK_SESSION));
        $xtpl->assign('PLSO_LINK_TARGET', $is_embed_mode ? ' target="_blank"' : '');

        $song_full_name = $song['song_name'];
        $song_full_singer = [];

        // Xuất ca sĩ
        $num_singers = sizeof($song['singers']);
        if ($num_singers > Config::getLimitSingersDisplayed()) {
            $xtpl->assign('PLSO_VA_SINGERS', Config::getVariousArtists());

            foreach ($song['singers'] as $singer) {
                $song_full_singer[] = $singer['artist_name'];
                $xtpl->assign('PLSO_SINGER', $singer);
                $xtpl->parse('main.player.playlist.loop.va_singer.loop');
            }

            $xtpl->parse('main.player.playlist.loop.va_singer');
        } elseif (!empty($song['singers'])) {
            $i = 0;
            foreach ($song['singers'] as $singer) {
                $i++;
                $song_full_singer[] = $singer['artist_name'];
                $xtpl->assign('PLSO_SINGER', $singer);

                if ($i > 1) {
                    $xtpl->parse('main.player.playlist.loop.show_singer.loop.separate');
                }
                $xtpl->parse('main.player.playlist.loop.show_singer.loop');
            }
            $xtpl->parse('main.player.playlist.loop.show_singer');
        } else {
            $song_full_singer[] = Config::getUnknowSinger();
            $xtpl->assign('PLSO_UNKNOW_SINGER', Config::getUnknowSinger());
            $xtpl->parse('main.player.playlist.loop.no_singer');
        }

        $xtpl->assign('PLSO_FULL_NAME', str_replace('"', '\"', $song_full_name));
        $xtpl->assign('PLSO_FULL_SINGER', str_replace('"', '\"', implode(', ', $song_full_singer)));

        if (!$is_embed_mode) {
            $xtpl->parse('main.player.playlist.loop.actions');
        }
        $xtpl->parse('main.player.playlist.loop');

        // Xuất playlist javascript
        $i = 0;
        foreach ($song['filesdata'] as $_fileinfo) {
            $i++;
            $_fileinfo['resource_path'] = str_replace('"', '\"', $_fileinfo['resource_path']);
            $_fileinfo['quality_name'] = str_replace('"', '\"', $_fileinfo['quality_name']);
            $xtpl->assign('FILESDATA', $_fileinfo);
            if ($i > 1) {
                $xtpl->parse('main.player.playlist_js.loop.filesdata.comma');
            }
            $xtpl->parse('main.player.playlist_js.loop.filesdata');
        }

        if (isset($array_captions[$song['song_id']])) {
            $i = 0;
            foreach ($array_captions[$song['song_id']] as $track) {
                $i++;
                $track['is_default'] = !empty($track['is_default']) ? 'true' : 'false';
                $xtpl->assign('TRACK', $track);
                if ($i > 1) {
                    $xtpl->parse('main.player.playlist_js.loop.tracks.loop.comma');
                }
                $xtpl->parse('main.player.playlist_js.loop.tracks.loop');
            }

            $xtpl->parse('main.player.playlist_js.loop.tracks');
        }

        if ($soj > 1) {
            $xtpl->parse('main.player.playlist_js.loop.comma');
        }

        $xtpl->parse('main.player.playlist_js.loop');
        $plindex++;
    }
    if ($is_embed_mode) {
        $xtpl->parse('main.player.playlist.embed');
    }
    $xtpl->parse('main.player.playlist');
    $xtpl->parse('main.player.playlist_js');

    $xtpl->parse('main.player');
    if ($is_embed_mode) {
        $xtpl->parse('main.player.playlist.embed');
        return $xtpl->text('main.player');
    }

    // Bình luận
    if (!empty($content_comment)) {
        $xtpl->assign('COMMENT_HTML', $content_comment);
        $xtpl->parse('main.comment');

        if (!empty($array['stat_comments'])) {
            $xtpl->assign('COMMENT_NUMS', Utils::getFormatNumberView($array['stat_comments']));
            $xtpl->parse('main.comment_btn.stat');
        }

        $xtpl->parse('main.comment_btn');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * @param array $row
 * @param array $array_resource
 * @return string
 */
function nv_theme_popover_download_song($row, $array_resource)
{
    global $module_info, $global_array_soquality, $nv_Lang;

    $xtpl = new XTemplate('download-song.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

    if (empty($array_resource)) {
        $xtpl->parse('main.nothing');
    } else {
        foreach ($global_array_soquality as $soquality) {
            if (isset($array_resource[$soquality['quality_id']])) {
                $resource = $array_resource[$soquality['quality_id']];
                $soquality['quality_name'] = $soquality[NV_LANG_DATA . '_quality_name'];
                $xtpl->assign('QUALITY', $soquality);
                $xtpl->assign('RESOURCE', $resource);

                if ($resource['resource_server_id'] < 0) {
                    $xtpl->parse('main.data.loop.icon_link');
                    $xtpl->parse('main.data.loop.target_blank');
                } else {
                    $xtpl->parse('main.data.loop.icon_down');
                    $xtpl->parse('main.data.loop.direct');
                }

                $xtpl->parse('main.data.loop');
            }
        }

        $xtpl->parse('main.data');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * @param array $row
 * @param array $array_resource
 * @return string
 */
function nv_theme_popover_download_video($row, $array_resource)
{
    global $module_info, $global_array_mvquality, $nv_Lang;

    $xtpl = new XTemplate('download-video.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

    if (empty($array_resource)) {
        $xtpl->parse('main.nothing');
    } else {
        foreach ($global_array_mvquality as $mvquality) {
            if (isset($array_resource[$mvquality['quality_id']])) {
                $resource = $array_resource[$mvquality['quality_id']];
                $mvquality['quality_name'] = $mvquality[NV_LANG_DATA . '_quality_name'];
                $xtpl->assign('QUALITY', $mvquality);
                $xtpl->assign('RESOURCE', $resource);

                if ($resource['resource_server_id'] < 0) {
                    $xtpl->parse('main.data.loop.icon_link');
                    $xtpl->parse('main.data.loop.target_blank');
                } else {
                    $xtpl->parse('main.data.loop.icon_down');
                    $xtpl->parse('main.data.loop.direct');
                }

                $xtpl->parse('main.data.loop');
            }
        }

        $xtpl->parse('main.data');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * @param array $row
 * @param array $array_playlist
 * @param array $array_playlist_added
 * @param string $redirect
 * @return string
 */
function nv_theme_popover_add_song_to_playlist($row, $array_playlist, $array_playlist_added, $redirect)
{
    global $module_info, $nv_Lang;

    $xtpl = new XTemplate('add-song-to-playlist.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

    $xtpl->assign('SONG', $row);

    if (!defined('NV_IS_USER')) {
        $xtpl->assign('LINK_LOGIN', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=login&nv_redirect=' . $redirect);
        $xtpl->parse('main.notlogin');
    } else {
        // Ẩn, hiện form tạo mới khi đã có dữ liệu hoặc chưa có playlist nào
        if (empty($array_playlist)) {
            $xtpl->parse('main.data.hide_add_btn');
        } else {
            $xtpl->parse('main.data.hide_add_form');

            // Xuất các playlist
            foreach ($array_playlist as $playlist) {
                $xtpl->assign('PLAYLIST', $playlist);

                // Icon công khai hay cá nhân
                if ($playlist['privacy']) {
                    $xtpl->parse('main.data.playlist.loop.public');
                } else {
                    $xtpl->parse('main.data.playlist.loop.private');
                }

                // Checked đã thêm hay chưa thêm
                if (in_array($playlist['playlist_id'], $array_playlist_added)) {
                    $xtpl->parse('main.data.playlist.loop.added');
                }

                $xtpl->parse('main.data.playlist.loop');
            }

            $xtpl->parse('main.data.playlist');
        }


        $xtpl->parse('main.data');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * @param string $request_tab
 * @param array $array_songs
 * @param array $array_videos
 * @param array $array_albums
 * @param array $array_playlists
 * @param string $generate_page
 * @return string
 */
function nv_theme_mymusic($request_tab, $array_songs, $array_videos, $array_albums, $array_playlists, $generate_page)
{
    global $module_info, $op, $user_info, $nv_Lang;

    $xtpl = new XTemplate('mymusic.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('UNIQUEID', nv_genpass(6));
    $xtpl->assign('TAB_LINK', Resources::getModFullLinkEncode() . $op);

    $xtpl->assign('USER_FULL_NAME', $user_info['full_name']);
    $xtpl->assign('USER_USERNAME', $user_info['username']);
    if (empty($user_info['photo'])) {
        $xtpl->assign('USER_PHOTO', NV_STATIC_URL . 'themes/' . $module_info['template'] . '/images/' . $module_info['module_theme'] . '/noavatar.png');
    } else {
        $xtpl->assign('USER_PHOTO', NV_BASE_SITEURL . $user_info['photo']);
    }

    $isFullPlayout = ((isset($module_info['layout_funcs'][$op]) and $module_info['layout_funcs'][$op] == 'main') and !empty($request_tab)) ? true : false;

    // Xuất bài hát yêu thích
    if (!empty($array_songs)) {
        $xtpl->assign('SONG_HTML', nv_theme_list_songs($array_songs));

        if (empty($request_tab)) {
            $xtpl->parse('main.tab_overview.song_data');
        } else {
            $xtpl->parse('main.tab_song.data');
        }
    } elseif (empty($request_tab)) {
        $xtpl->parse('main.tab_overview.song_empty');
    } else {
        $xtpl->parse('main.tab_song.empty');
    }

    // Xuất MV yêu thích
    if (!empty($array_videos)) {
        $xtpl->assign('VIDEOS_HTML', nv_theme_gird_videos($array_videos, $isFullPlayout));

        if (empty($request_tab)) {
            $xtpl->parse('main.tab_overview.video_data');
        } else {
            $xtpl->parse('main.tab_video.data');
        }
    } elseif (empty($request_tab)) {
        $xtpl->parse('main.tab_overview.video_empty');
    } else {
        $xtpl->parse('main.tab_video.empty');
    }

    // Xuất album yêu thích
    if (!empty($array_albums)) {
        if (empty($request_tab)) {
            $xtpl->assign('ALBUM_HTML', nv_theme_list_albums($array_albums));
            $xtpl->parse('main.tab_overview.album_data');
        } else {
            $xtpl->assign('ALBUM_HTML', nv_theme_gird_albums($array_albums, $isFullPlayout));
            $xtpl->parse('main.tab_album.data');
        }
    } elseif (empty($request_tab)) {
        $xtpl->parse('main.tab_overview.album_empty');
    } else {
        $xtpl->parse('main.tab_album.empty');
    }

    // Xuất các playlist
    if (!empty($array_playlists)) {
        if (empty($request_tab)) {
            $xtpl->assign('PLAYLIST_HTML', nv_theme_list_playlists($array_playlists));
            $xtpl->parse('main.tab_overview.playlist_data');
        } else {
            $xtpl->assign('PLAYLIST_HTML', nv_theme_gird_playlists($array_playlists, $isFullPlayout));
            $xtpl->parse('main.tab_playlist.data');
        }
    } elseif (empty($request_tab)) {
        $xtpl->parse('main.tab_overview.playlist_empty');
    } else {
        $xtpl->parse('main.tab_playlist.empty');
    }

    // Điều khiển các tab và nội dung của tab
    if ($request_tab == 'song') {
        $xtpl->parse('main.active_song');
        $xtpl->parse('main.tab_song');
    } elseif ($request_tab == 'album') {
        $xtpl->parse('main.active_album');
        $xtpl->parse('main.tab_album');
    } elseif ($request_tab == 'mv') {
        $xtpl->parse('main.active_video');
        $xtpl->parse('main.tab_video');
    } elseif ($request_tab == 'playlist') {
        $xtpl->parse('main.active_playlist');
        $xtpl->parse('main.tab_playlist');
    } else {
        $xtpl->parse('main.active_overview');
        $xtpl->parse('main.tab_overview');
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * @param string $file_url
 * @return string
 */
function nv_theme_viewpdf($file_url)
{
    global $nv_Lang;

    $xtpl = new XTemplate('viewer.tpl', NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/js/pdf.js');
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('PDF_JS_DIR', NV_STATIC_URL . NV_ASSETS_DIR . '/js/pdf.js/');
    $xtpl->assign('PDF_URL', $file_url);
    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * @param array $array_search
 * @param array $array_songs
 * @param array $array_videos
 * @param array $array_albums
 * @param array $array_artists
 * @param array $array_queries
 * @param string $generate_page
 * @return string
 */
function nv_theme_music_search($array_search, $array_songs, $array_videos, $array_albums, $array_artists, $array_queries, $generate_page)
{
    global $module_info, $global_array_cat, $op, $nv_Lang;

    $xtpl = new XTemplate('search.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('UNIQUEID', nv_genpass(6));

    $array_search['totals'] = Utils::getFormatNumberView($array_search['totals']);

    $xtpl->assign('SEARCH', $array_search);

    /*
     * Xây dựng các TABs
     */
    $array_tabs = [];
    $array_queries_tmp = $array_queries;
    unset($array_queries_tmp['type']);
    $array_tabs[] = [
        'active' => empty($array_search['type']) ? true : false,
        'link' => Resources::getModFullLinkEncode() . $op . '&amp;' . Utils::buildSearchQuery($array_queries_tmp),
        'title' => $nv_Lang->getModule('search_top'),
    ];
    $array_queries_tmp = $array_queries;
    // Bài hát
    $array_queries_tmp['type'] = 'song';
    $array_tabs['song'] = [
        'active' => $array_search['type'] == 'song' ? true : false,
        'link' => Resources::getModFullLinkEncode() . $op . '&amp;' . Utils::buildSearchQuery($array_queries_tmp),
        'title' => $nv_Lang->getModule('song'),
    ];
    // MV
    $array_queries_tmp['type'] = 'mv';
    $array_tabs['mv'] = [
        'active' => $array_search['type'] == 'mv' ? true : false,
        'link' => Resources::getModFullLinkEncode() . $op . '&amp;' . Utils::buildSearchQuery($array_queries_tmp),
        'title' => $nv_Lang->getModule('video_alias'),
    ];
    // Album
    $array_queries_tmp['type'] = 'album';
    $array_tabs['album'] = [
        'active' => $array_search['type'] == 'album' ? true : false,
        'link' => Resources::getModFullLinkEncode() . $op . '&amp;' . Utils::buildSearchQuery($array_queries_tmp),
        'title' => $nv_Lang->getModule('album'),
    ];
    // Nghệ sĩ
    $array_queries_tmp['type'] = 'artist';
    $array_tabs['artist'] = [
        'active' => $array_search['type'] == 'artist' ? true : false,
        'link' => Resources::getModFullLinkEncode() . $op . '&amp;' . Utils::buildSearchQuery($array_queries_tmp),
        'title' => $nv_Lang->getModule('artist'),
    ];

    $xtpl->assign('ALL_TABS', $array_tabs);
    foreach ($array_tabs as $tab) {
        $xtpl->assign('TAB', $tab);
        if ($tab['active']) {
            $xtpl->parse('main.tab.active');
        }
        $xtpl->parse('main.tab');
    }

    /*
     * Kết quả tìm kiếm bài hát
     */
    if (!empty($array_songs)) {
        $xtpl->assign('SONG_HTML', nv_theme_list_songs($array_songs));
        $xtpl->parse('main.result_songs');
    }

    /*
     * Kết quả tìm kiếm MV
     */
    if (!empty($array_videos)) {
        $xtpl->assign('VIDEO_HTML', nv_theme_gird_videos($array_videos));
        $xtpl->parse('main.result_videos');
    }

    /*
     * Kết quả tìm kiếm album
     */
    if (!empty($array_albums)) {
        $xtpl->assign('ALBUM_HTML', nv_theme_gird_albums($array_albums));
        $xtpl->parse('main.result_albums');
    }

    /*
     * Kết quả tìm kiếm nghệ sĩ
     */
    if (!empty($array_artists)) {
        foreach ($array_artists as $row) {
            $row['resource_avatar_thumb'] = nv_get_resource_url($row['resource_avatar'], 'singer', true);
            $row['resource_avatar'] = nv_get_resource_url($row['resource_avatar'], 'singer');

            $xtpl->assign('ROW', $row);
            $xtpl->parse('main.result_artists.loop');
        }
        $xtpl->parse('main.result_artists');
    }

    // Lọc theo thể loại
    foreach ($global_array_cat as $cat) {
        $array_queries_tmp = $array_queries;
        if (!isset($array_queries_tmp['genre'])) {
            $array_queries_tmp['genre'] = [];
        }
        $array_queries_tmp['genre'][$cat['cat_id']] = $cat['cat_code'];

        $cat['link'] = Resources::getModFullLinkEncode() . $op . '&amp;' . Utils::buildSearchQuery($array_queries_tmp);

        $xtpl->assign('CAT', $cat);
        if (!empty($array_queries['genre']) and isset($array_queries['genre'][$cat['cat_id']])) {
            $xtpl->parse('main.cat.checked');
        }
        $xtpl->parse('main.cat');
    }

    // Kiểu sắp xếp
    $array_queries_tmp = $array_queries;
    for ($i = 1; $i <= 3; $i++) {
        $array_queries_tmp['sort'] = $i;
        $xtpl->assign('SORT', [
            'title' => $nv_Lang->getModule('search_sortby' . $i),
            'link' => Resources::getModFullLinkEncode() . $op . '&amp;' . Utils::buildSearchQuery($array_queries_tmp)
        ]);
        if ((empty($array_queries['sort']) and $i == 1) or (!empty($array_queries['sort']) and $i == $array_queries['sort'])) {
            $xtpl->parse('main.sort.checked');
        }
        $xtpl->parse('main.sort');
    }

    // Xuất các điều kiện lọc
    if (!empty($array_queries['genre'])) {
        foreach ($array_queries['genre'] as $cat_id => $cat_code) {
            $array_queries_tmp = $array_queries;
            unset($array_queries_tmp['genre'][$cat_id]);
            $xtpl->assign('GENRE', [
                'title' => $global_array_cat[$cat_id]['cat_name'],
                'link' => Resources::getModFullLinkEncode() . $op . '&amp;' . Utils::buildSearchQuery($array_queries_tmp)
            ]);
            $xtpl->parse('main.genre.loop');
        }
        $xtpl->parse('main.genre');
    }

    // Phân trang
    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * @param array $array_playlist
 * @return string
 */
function nv_theme_manager_playlist($array_playlist)
{
    global $module_info, $nv_Lang;

    $xtpl = new XTemplate('manager-playlist.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

    $array_playlist['resource_avatar_thumb'] = nv_get_resource_url($array_playlist['resource_avatar'], 'album', true);
    $array_playlist['resource_avatar'] = nv_get_resource_url($array_playlist['resource_avatar'], 'album');
    $array_playlist['num_songs'] = Utils::getFormatNumberView($array_playlist['num_songs']);

    $xtpl->assign('DATA', $array_playlist);

    $xtpl->parse('main');
    return $xtpl->text('main');
}
