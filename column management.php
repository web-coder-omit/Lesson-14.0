<?php
/**
 * Plugin Name: column management
 * Plugin URI:  Plugin URL Link
 * Author:      Plugin Author Name
 * Author URI:  Plugin Author Link
 * Description: This plugin make for pratice wich is "column management".
 * Version:     0.1.0
 * License:     GPL-2.0+
 * License URL: http://www.gnu.org/licenses/gpl-2.0.txt
 * text-domain: clm
 */
// Loaded languages file
function plugin_file_function(){
    load_plugin_textdomain('mb_fw', false, dirname(__FILE__) . "/languages");
}
add_action( 'tgmpa_register', 'mb_fw_fun_register_required_plugins' );
function clm_post_function($colums){
    print_r($colums);
    unset($colums['tags']);
    unset($colums['comments']);
    unset($colums['author']);
    unset($colums['date']);
    $colums['author'] = "Author";
    $colums['date'] = "date";
    $colums ['id'] = __("Post ID","clm");
    $colums ['thumbnail'] = __("Thumbnail","clm");
    $colums ['wordcount'] = __("Word count","clm");

    return $colums;
}
add_filter('manage_posts_columns','clm_post_function');

function clm_manage_function($colums,$post_id){
    if('id'==$colums){
        echo $post_id;
    }elseif('thumbnail' == $colums){
        $thumbnail = get_the_post_thumbnail($post_id,array(100,100));
        echo $thumbnail;
    }
    elseif('wordcount' == $colums){
        $_post = get_post($post_id);
        $content = $_post->post_content;
        $wordn = str_word_count(strip_tags($content));
        //$wordn = get_post_meta($post_id,'wordn',true);
        echo $wordn;
    }
}
add_action('manage_posts_custom_column','clm_manage_function',10,2);
function post_string_sortable_function($colums){
    $columns['wordcount'] = $wordn;
return $colums;
}
add_filter('manaage_edit-post_sortable_columns','post_string_sortable_function');
//
function coldemo_set_word_count(){
    $posts = get_posts(array(
        'posts_per_page'=>-1,
        'post_type'=>'post',
           'post_status' => 'any'
    ));
    foreach($post as $p){
        $content = $p->post_content;
        $wordn = str_word_count(strip_tags($content));
        update_post_meta($p->ID,'wordn',$wordn);
    }
}
add_action('init','coldemo_set_word_count');
//



function coldemo_sort_column_data($wpquery){
    if(!is_admin()){
        return;
    }
    $orderby = $wpquery->get('orderby');
    if('wordn' == $orderby){
        $wpquery->set('meta_key','wordn');
        $wpquery->set('orderby','meta_value_num');
        // $wpquery=>set('orderby','meta_value_num');
    }

}
add_action('pre_get_posts','coldemo_sort_column_data');
function save_post_word_count($post_id){
    $p = get_post($post_id);
    $content = $p->post_content;
    $wordn = str_word_count(strip_tags($content));
    update_post_meta($p->ID,'wordn',$wordn);
    // $content = $p->post_content;
    // $wordn = str_word_count(strip_tags($content));
    // update_post_meta($p->ID,'wordn',$wordn);
}
add_action('save_post','save_post_word_count');

function cm_filter(){
    if(isset($_GET['post_type']) && $_GET['post_type']!='post'){
        return;
    }
    ?>
        <select name="demofilter">
            <option value="0">Select status</option>
            <option value="0">Some Posts</option>
            <option value="0">Select posts++</option>
        </select>
    <?php
}
add_action('restrict_manage_posts','cm_filter');
function filter_data(){
    if(!is_admin()){
        return;
    }
    $filter_value = isset($_GET['demofilter']) ? $_GET['demofilter'] : '';
    if('1' == $filter_value){
        $wpquery->set('post__in',array(19));
    }
}
add_action('pre_get_posts','filter_data');

















?>