<?php

/**
 * @Project NUKEVIET MUSIC 4.X
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Language Tiếng Việt
 * @Createdate Sun, 26 Feb 2017 14:04:32 GMT
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE'))
    die('Stop!!!');

$lang_translator['author'] = 'Phan Tan Dung (phantandung92@gmail.com)';
$lang_translator['createdate'] = '04/03/2010, 15:22';
$lang_translator['copyright'] = '@Copyright (C) 2011 Free Ware';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['not_available'] = 'N/A';
$lang_module['successfully_saved'] = 'Dữ liệu đã được lưu lại, hệ thống sẽ chuyển trang trong giây lát';
$lang_module['save'] = 'Lưu lại';

$lang_module['validate_number'] = 'Vui lòng chỉ nhập số';
$lang_module['validate_number_min0'] = 'Vui lòng chỉ nhập số tự nhiên, tối thiểu là 0';
$lang_module['validate_number_min1'] = 'Vui lòng chỉ nhập số tự nhiên, tối thiểu là 1';
$lang_module['validate_alias_lowercase_min1max30'] = 'Vui lòng nhập trong phạm vi từ 1 đến 30 ký tự. Chỉ bao gồm chữ cái từ a-z và dấu -';
$lang_module['validate_alias_lowercase_len2'] = 'Vui lòng nhập 2 ký tự. Chỉ bao gồm chữ cái từ a-z';
$lang_module['validate_alias_lowercase_max50'] = 'Vui lòng chỉ nhập chữ cái từ a-z và dấu -. Tối đa 50 ký tự và không bắt đầu bằng dấu -';

$lang_module['config'] = 'Cấu hình module';
$lang_module['config_note'] = 'Chú ý: Cấu hình này được áp dụng cho <strong>%s</strong>. Nếu module có cài đặt ở các ngôn ngữ khác, bạn cần cấu hình riêng ở từng ngôn ngữ tương ứng. Nếu không thiết đặt, hệ thống tự động lấy cấu hình mặc định';
$lang_module['config_mainpage'] = 'Cấu hình hiển thị trang chủ';
$lang_module['config_available_if_choose'] = 'Có tác dụng nếu chọn hiển thị ở phần trên';
$lang_module['config_structre_data_page_title'] = 'Cấu hình tiêu đề, mô tả các trang và dữ liệu có cấu trúc';
$lang_module['config_display'] = 'Cấu hình hiển thị chung';
$lang_module['config_urls_system'] = 'Cấu hình URL';
$lang_module['config_alert_change'] = 'Các giá trị ở nhóm cấu hình này chỉ nên chọn một lần duy nhất trước khi site hoạt động. Thay đổi trong khi site đang hoạt động sẽ dẫn tới lỗi ở các máy chủ tìm kiếm';
$lang_module['config_view_singer'] = 'Cấu hình tại trang xem chi tiết ca sĩ';
$lang_module['config_list_albums'] = 'Cấu hình tại trang xem danh sách album';

$lang_module['limit_singers_displayed'] = 'Số ca sĩ tối đa hiển thị';
$lang_module['limit_singers_displayed_help'] = 'Nếu một bài hát, video, album có nhiều ca sĩ thể hiện hơn giá trị đã chọn, hệ thống sẽ hiển thị ca sĩ là giá trị ở ô bên dưới. Người dùng nhấp vào để hiển thị danh sách các ca sĩ thể hiện';
$lang_module['various_artists'] = 'Nhiều ca sĩ';
$lang_module['various_artists_help'] = 'Nội dung sẽ hiển thị nếu bài hát, album, video có nhiều hơn số ca sĩ thể hiện được chọn bên trên';
$lang_module['unknow_singer'] = 'Ca sĩ không xác định';
$lang_module['unknow_singer_help'] = 'Nội dung sẽ hiển thị nếu bài hát, album, video chưa có/chưa biết ca sĩ thể hiện';

$lang_module['home_albums_display'] = 'Hiển thị các albums ở trang chủ';
$lang_module['home_singers_display'] = 'Hiển thị các ca sĩ ở trang chủ';
$lang_module['home_songs_display'] = 'Hiển thị các bài hát ở trang chủ';
$lang_module['home_videos_display'] = 'Hiển thị các videos ở trang chủ';
$lang_module['home_albums_weight'] = 'Vị trí albums';
$lang_module['home_singers_weight'] = 'Vị trí ca sĩ';
$lang_module['home_songs_weight'] = 'Vị trí bài hát';
$lang_module['home_videos_weight'] = 'Vị trí videos';
$lang_module['home_albums_nums'] = 'Số albums';
$lang_module['home_singers_nums'] = 'Số ca sĩ';
$lang_module['home_songs_nums'] = 'Số bài hát';
$lang_module['home_videos_nums'] = 'Số videos';

$lang_module['arr_code_prefix_singer'] = 'Tiếp đầu tố mã ca sĩ';
$lang_module['arr_code_prefix_playlist'] = 'Tiếp đầu tố mã Playlist';
$lang_module['arr_code_prefix_album'] = 'Tiếp đầu tố mã album';
$lang_module['arr_code_prefix_video'] = 'Tiếp đầu tố mã video';
$lang_module['arr_code_prefix_cat'] = 'Tiếp đầu tố mã danh mục';
$lang_module['arr_code_prefix_song'] = 'Tiếp đầu tố mã bài hát';
$lang_module['arr_op_alias_prefix_song'] = 'Tiếp đầu tố alias bài hát';
$lang_module['arr_op_alias_prefix_album'] = 'Tiếp đầu tố alias album';
$lang_module['arr_view_singer_tabs_alias_song'] = 'Tiếp đầu tố alias bài hát';
$lang_module['arr_view_singer_tabs_alias_video'] = 'Tiếp đầu tố alias video';
$lang_module['arr_view_singer_tabs_alias_album'] = 'Tiếp đầu tố alias album';
$lang_module['arr_view_singer_tabs_alias_profile'] = 'Tiếp đầu tố alias tiểu sử';

$lang_module['view_singer_show_header'] = 'Hiển thị phần đầu';
$lang_module['view_singer_show_header_help'] = 'Nếu không hiển thị phần thông tin đầu tại trang xem chi tiết ca sĩ bạn có thể thêm block module.view_singer_header để thay thế. Khuyến cáo nên chọn không hiển thị, sau đó thêm block để hiển thị nội dung lớn hơn';
$lang_module['view_singer_headtext_length'] = 'Số ký tự thông tin ca sĩ';
$lang_module['view_singer_headtext_length_help'] = 'Số ký tự tối đa hiển thị thông tin ca sĩ ở phần đầu (cắt từ phần thông tin khác). Nếu để giá trị là 0 thì toàn bộ thông tin khác sẽ được hiển thị';
$lang_module['view_singer_main_num_songs'] = 'Số bài hát ở trang chính';
$lang_module['view_singer_main_num_videos'] = 'Số video ở trang chính';
$lang_module['view_singer_main_num_albums'] = 'Số album ở trang chính';
$lang_module['view_singer_detail_num_songs'] = 'Số bài hát ở tab bài hát';
$lang_module['view_singer_detail_num_videos'] = 'Số video ở tab video';
$lang_module['view_singer_detail_num_albums'] = 'Số album ở tab album';

$lang_module['funcs_album'] = 'Trang danh sách các album';
$lang_module['funcs_sitetitle'] = 'Tiêu đề trang';
$lang_module['funcs_sitetitle_help'] = 'Nội dung của thẻ &lt;title&gt;&lt;/title&gt;';
$lang_module['funcs_keywords'] = 'Từ khóa';
$lang_module['funcs_keywords_help'] = 'Nội dung của META KEYWORDS';
$lang_module['funcs_description'] = 'Mô tả trang';
$lang_module['funcs_description_help'] = 'Nội dung của META DESCRIPTION';
$lang_module['funcs_note'] = 'Nếu không nhập các giá trị tại đây hệ thống tự sinh ra theo quy định của người lập trình';

$lang_module['gird_albums_percat_nums'] = 'Số album mỗi danh mục';
$lang_module['gird_albums_percat_nums_help'] = 'Số album hiển thị ở mỗi danh mục tại trang chính';
$lang_module['gird_albums_incat_nums'] = 'Số album ở danh mục';
$lang_module['gird_albums_incat_nums_help'] = 'Số album hiển thị mỗi trang khi xem từng danh mục';
