<?php
// 基本設定 -----------------

function mytheme_setup()
{
  add_theme_support('post-thumbnails');
  add_theme_support('editor-styles');
  add_editor_style('editor-style.css');
  add_theme_support('wp-block-styles'); //グーテンベルグ由来のCSS
  add_theme_support('responsive-embeds'); //埋め込みコンテンツのレスポンシブ化
  add_theme_support('align-wide'); //幅広・全幅機能を有効化
  add_theme_support('editor-font-sizes', array(
    array(
      'name' => '小',
      'size' => '12.8',
      'slug' => 'small',
    ),
    array(
      'name' => '中',
      'size' => '16',
      'slug' => 'medium',
    ),
    array(
      'name' => '大',
      'size' => '20',
      'slug' => 'large',
    ),
  ));
  register_nav_menus(array(
    'primary' => 'メイン'
  ));
}
add_action('after_setup_theme', 'mytheme_setup');

// 自動整形を無効にする -----------------

remove_filter('the_content', 'wpautop'); // 記事の自動整形を無効にする
remove_filter('the_excerpt', 'wpautop'); // 抜粋の自動整形を無効にする

// ウィジェット -----------------

function mytheme_widgets()
{
  register_sidebar(array(
    'id' => 'sidebar-1',
    'name' => 'サイドメニュー',
    'before_widget' => '<section id="%1$s" class="widget %2$s">',
    'after_widget' => '</section>',
  ));
  register_sidebar(array(
    'id' => 'drawer',
    'name' => 'ドロワーウィジェット',
    'before_widget' => '',
    'after_widget' => '',
  ));
}
add_action('widgets_init', 'mytheme_widgets');

// CSSとJSの読み込み -----------------

function mytheme_enqueue()
{
  // google fonts
  wp_enqueue_style('googlefonts', 'https://fonts.googleapis.com/css?family=Noto+Sans+JP:400,700&display=swap', array(), null);
  // wp_enqueue_style('mytheme-style',get_stylesheet_uri());
  //編集用
  wp_enqueue_style('mtga-style', get_stylesheet_uri(), array(), date('U'));
  wp_enqueue_style('slick-style', get_template_directory_uri().'/slick.css', array(), null);
  wp_enqueue_style('slick-theme-style', get_template_directory_uri().'/slick-theme.css', array(), null);
  wp_enqueue_script('TweenMax',get_template_directory_uri().'/js/libs/TweenMax.min.js',array('jquery'),null,true);
  wp_enqueue_script('slick',get_template_directory_uri().'/js/libs/slick.js',array('jquery'),null,true);
  wp_enqueue_script('main',get_template_directory_uri().'/js/main.js',array('jquery'),null,true);
}
add_action('wp_enqueue_scripts', 'mytheme_enqueue');

//ブロック用のCSSとJSの読み込み
function mytheme_both()
{
  wp_enqueue_style(
    'mytheme-style-both',
    get_template_directory_uri() . '/style-both.css',
    array(),
    filemtime(get_template_directory() . '/style-boty.css')
  );
}
add_action('enqueue_block_assets', 'mytheme_both');

function myjs_enqueue()
{
  wp_enqueue_script(
    'myjs-style',
    get_template_directory_uri() . '/mystyle.js',
    array('wp-blocks', 'wp-dom-ready', 'wp-edit-post'),
    filemtime(get_template_directory() . 'mystyle.js')
  );
}
add_action('enqueue_block_editor_assets', 'myjs_enqueue');

// the_archive_title 余計な文字を削除 -----------------

add_filter( 'get_the_archive_title', function ($title) {
  if (is_category()) {
    $title = single_cat_title('',false);
  } elseif (is_tag()) {
    $title = single_tag_title('',false);
	} elseif (is_tax()) {
    $title = single_term_title('',false);
	} elseif (is_post_type_archive() ){
		$title = post_type_archive_title('',false);
	} elseif (is_date()) {
    $title = get_the_time('Y年n月');
	} elseif (is_search()) {
    $title = '検索結果：'.esc_html( get_search_query(false) );
	} elseif (is_404()) {
    $title = '「404」ページが見つかりません';
	} else {}
    return $title;
});

// body_class()にクラスを追加する -----------------

add_filter( 'body_class', 'add_class_names' );
function add_class_names( $classes ) {
  $classes[] = 'scrollbarFix';
  return $classes;
}

// 画像の参照パス images/  -----------------

function replace_img_path($arg) {
  $content = str_replace('"images/', '"' . get_template_directory_uri() . '/img/', $arg);
  return $content;
}
add_action('the_content', 'replace_img_path');

// ショートコード  -----------------

// add_shortcode('url', 'shortcode_url');
// function shortcode_tp($atts, $content = '/mtga')
// {
//   return get_template_directory_uri() . $content;
// }
function shortcode_url() {
  return get_bloginfo('url');
}
add_shortcode('url', 'shortcode_url');

// ショートコードで呼び出す関数
function Category_List ( $arg = array () ) {
  extract ( shortcode_atts ( array (
    'category'       => '1',
    'posts_per_page' => '5'
  ), $arg ) );

  $blog_posts = get_posts ( array (
    'category'       => $category,
    'posts_per_page' => $posts_per_page
  ));

  $html = Create_Html($blog_posts);
  return $html;
}
add_shortcode('post_list', 'Category_List');

// HTMLを生成する関数
function Create_Html ( $blog_posts ) {
  $html = '<dl>';
  foreach ( $blog_posts as $post ) {
    // 投稿日の場合
    $html .= '<dt>' . date ( 'Y.m.d', strtotime ( $post->post_date ) ) . '</dt>';
    // 更新日の場合
    // $html .= '<p>' . date ( 'Y.m.d', strtotime ( $post->post_modified ) ) . '</p>';
    $html .= '<dd><a href="' . get_permalink($post->ID) . '">' . $post->post_title . '</a></dd>';
  }
  $html .= '</dl>';
  return $html;
}

// 記事の最大表示数  -----------------

function my_pre_get_posts( $query ) {
  if ( is_admin() || ! $query -> is_main_query() ) return;
  if ( $query -> is_category(1) ) {
    $query -> set( 'posts_per_page', 6 );
    return;
  }
  if ( $query->is_archive() ) {
    $query->set( 'posts_per_page', 6 );
    return;
  }
}
add_action( 'pre_get_posts', 'my_pre_get_posts' );

// 自動整形を無効  -----------------

function my_customize_mce_options( $init ) {
  global $allowedposttags;

  $init['valid_elements']          = '*[*]';
  $init['extended_valid_elements'] = '*[*]';
  $init['valid_children']          = '+a[' . implode( '|', array_keys( $allowedposttags ) ) . ']';
  $init['indent']                  = true;
  $init['wpautop']                 = false;
  $init['force_p_newlines'] = false;
  $init['force_br_newlines'] = true;
  $init['forced_root_block'] = '';

  return $init;
}

add_filter( 'tiny_mce_before_init', 'my_customize_mce_options' );
