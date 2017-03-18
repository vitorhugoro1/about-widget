<?php
/*
Plugin Name: Widget Sobre Mim
Version: 0.1
Description: Criar um Widget para adicionar informações sobre o autor.
Author: Vitor Hugo Rodrigues Merencio
License: GPL2
*/

class wp_my_plugin extends WP_Widget {

	// constructor
	function wp_my_plugin() {
    parent::WP_Widget(false, $name = __('Widget Sobre Mim', 'wp_widget_plugin') );
    add_action( 'wp_enqueue_scripts', array( $this, 'style' ) );
	}

	// widget form creation
	function form($instance) {
	   if($instance) {
       $title = esc_attr($instance['title']);
       $img_url = esc_url($instance['img_url']);
       $style = esc_attr($instance['style']);
       $text = esc_textarea($instance['text']);
       $btn = esc_attr($instance['btn_link']);
       $text_align = esc_attr($instance['text_align']);
     } else {
       $title = 'Sobre mim';
       $img_url = '';
       $style = 'rounded';
       $text = '';
       $btn = 0;
       $text_align = 'center';
     }
     ?>
      <p>
        <label for="<?php echo $this->get_field_id('title'); ?>">Titulo</label>
        <input type="text" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" class="widefat" value="<?=$title?>">
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('img_url'); ?>">URL da Imagem</label>
        <input type="text" name="<?php echo $this->get_field_name('img_url'); ?>" id="<?php echo $this->get_field_id('img_url'); ?>" class="widefat" value="<?=$img_url?>" placeholder="Link para imagem">
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('style'); ?>">Modo de apresentação da imagem</label>
        <select class="widefat" name="<?php echo $this->get_field_name('style'); ?>" id="<?php echo $this->get_field_id('style'); ?>">
          <option value="rounded" <?php selected('rounded', $style) ?>>Rounded</option>
          <option value="flat" <?php selected('flat', $style) ?>>Flat</option>
        </select>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('text'); ?>">Texto</label>
        <textarea name="<?php echo $this->get_field_name('text'); ?>" id="<?php echo $this->get_field_id('text'); ?>" rows="10" class="widefat"><?=$text?></textarea>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('text_align'); ?>">Alinhamento do Texto</label>
        <select class="widefat" name="<?php echo $this->get_field_name('text_align'); ?>" id="<?php echo $this->get_field_id('text_align'); ?>">
          <option value="center" <?php selected('center', $text_align) ?>>Center</option>
          <option value="left" <?php selected('left', $text_align) ?>>Left</option>
          <option value="right" <?php selected('right', $text_align) ?>>Right</option>
          <option value="justify" <?php selected('justify', $text_align) ?>>Justify</option>
        </select>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('btn_link'); ?>">Link para página</label>
        <?php
        $args = array(
          'name'      => $this->get_field_name('btn_link'),
          'id'        => $this->get_field_id('btn_link'),
          'selected'  => $btn,
          'class'     => 'widefat',
          'show_option_none'      => 'Selecione uma página',
          'option_none_value'     => '',
        );

        wp_dropdown_pages($args);
        ?>
      </p>
     <?php
	}

  function style() {
    wp_enqueue_style(
      'about-widget',
      plugins_url('about-widget.css', __FILE__),
      array(),
      false,
      false
    );
  }

	// widget update
	function update($new_instance, $old_instance) {
    $instance = $old_instance;
      // Fields
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['text'] = strip_tags($new_instance['text']);
      $instance['text_align'] = strip_tags($new_instance['text_align']);
      $instance['img_url'] = strip_tags($new_instance['img_url']);
      $instance['style'] = strip_tags($new_instance['style']);
      $instance['btn_link'] = intval($new_instance['btn_link']);

     return $instance;
	}

	// widget display
	function widget($args, $instance) {
     extract( $args );
     // these are the widget options
     $title = apply_filters('widget_title', $instance['title']);
     $text = $instance['text'];
     $img_url = $instance['img_url'];
     $style = $instance['style'];
     $btn = $instance['btn_link'];
     $text_align = $instance['text_align'];
     echo $before_widget;
     // Display the widget
     echo '<div class="wp_widget_about_me">';

     // Check if title is set
     if ( $title ) {
        echo $before_title . $title . $after_title;
     }

     if($img_url){
       echo "<div class='{$style}'><img src='{$img_url}' alt='{$title}'></div>";
     }

     // Check if text is set
     if( $text ) {
        echo "<p class='wp_widget_about_me-text {$text_align}'>$text</p>";
     }
     // Check if textarea is set
     if( $btn ) {
       $link = get_permalink($btn);
       $link_title = get_the_title($btn);
       echo "<a href='{$link}' class='wp_widget_about_me-more' alt='{$link_title}'><span class='wp_widget_about_me-more-button'>Leia Mais</span></a>";
     }
     echo '</div>';
     echo $after_widget;
	}
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("wp_my_plugin");'));
