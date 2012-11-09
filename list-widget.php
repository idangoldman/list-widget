<?php
/*
  Plugin Name: List Widget
  Plugin URI: https://github.com/idanm
  Description: List wordpress widget
  Author: idanm (Idan Mitrofanov)
  Author URI: https://github.com/idanm
  Version: 1.0
*/

class List_Widget extends WP_Widget {
  private $textdomain = 'list_widget_textdomain';

  public function __construct() {
    parent::__construct(
      'list-widget',
      'List',
      array(
        'description' => __('List Widget', $this->textdomain)
      )
    );

    $this->type = array('ul', 'ol');
  }
  public function form($instance) {
    $instance['type'] = $instance['type'] ? $instance['type'] : "ul";


    $output = '<div class="list-widget">';

      // Title
      $output .= '<p>';
        $output .= '<label for="'. $this->get_field_id('title') .'">';
          $output .= __('Title:', $this->textdomain);
        $output .= ' </label>';
        $output .= '<input type="text" id="'. $this->get_field_id('title') .'" name="'. $this->get_field_name('title') .'" value="'. $instance['title'] .'"/>';
      $output .= '</p>';

      // Type
      $output .= '<p>';
        $output .= '<label for="'. $this->get_field_id('type') .'">';
          $output .= __('Type:', $this->textdomain);
        $output .= ' </label>';
        $output .= '<select id="'. $this->get_field_id('type') .'" name="'. $this->get_field_name('type') .'">';
          foreach ($this->type as $key) {
            $selected = $instance['type'] == $key ? 'selected=selected' : '';
            $output .= '<option value="'. $key .'"'. $selected .'>'. $key .'</option>';
          }
        $output .= '</select>';
      $output .= '</p>';

      // List
      $output .= '<'. $instance['type'] .'>';

        $i = 0;
        if ($instance['item_'. $i]) {
          while ($instance['item_'. $i]) {
            $output .= '<li><input type="text" name="'. $this->get_field_name('item_'. $i) .'" value="'. $instance['item_'. $i] .'" /></li>';
            $i++;
          }
          $output .= '<li><input type="text" name="'. $this->get_field_name('item_'. $i) .'" value="" /></li>';
        } else {
          $output .= '<li><input type="text" name="'. $this->get_field_name('item_'. $i) .'" value="" /></li>';
        }

      $output .= '</'. $instance['type'] .'>';

    echo $output .'</div>';
  }
  public function update($new_instance, $old_instance) {
    $old_instance = array(); $i = 0;

      foreach ($new_instance as $key => $value) {
        if ($new_instance[$key] != "") {
          if ("item_" == substr($key, 0, 5)) {
            $old_instance['item_'. $i] = strip_tags($new_instance[$key]);
            $i++;
          } else {
            $old_instance[$key] = strip_tags($new_instance[$key]);
          }
        }
      }

    return $old_instance;
  }
  public function widget($args, $instance) {
    extract($args);

    $output .= '<'. $instance['type'] .' id="" class="list-widget">';
      $i = 0;
      while ($instance['item_'. $i]) {
        $output .= '<li>'. $instance['item_'. $i] .'</li>';
        $i++;
      }
    $output .= '</'. $instance['type'] .'>';

    echo $before_widget .
            $before_title . $instance["title"] . $after_title .
            $output .
          $after_widget;
  }
}

function scripts() {
  wp_register_script('list_widget_script',plugins_url('list-widget.admin.js',__FILE__));
  wp_enqueue_script('list_widget_script');
  wp_register_style('list_widget_style',plugins_url('list-widget.admin.css',__FILE__));
  wp_enqueue_style('list_widget_style');
}

  add_action('admin_enqueue_scripts', 'scripts');
  add_action('widgets_init', create_function('', 'register_widget("List_Widget");'));
?>