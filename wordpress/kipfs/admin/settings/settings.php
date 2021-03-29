<?php
// Class derived from :
// https://www.smashingmagazine.com/2016/04/three-approaches-to-adding-configurable-fields-to-your-plugin/
// https://github.com/rayman813/smashing-custom-fields/blob/master/smashing-fields-approach-1/smashing-fields.php

class Kipfs_Settings
{
  private $slug = 'ipfs_settings';

  public function __construct()
  {
    // Hook into the admin menu
    add_action('admin_menu', array($this, 'page_create'));

    // Add Settings and Fields
    add_action('admin_init', array($this, 'sections_create'));
    add_action('admin_init', array($this, 'fields_create'));
  }

  public function page_create()
  {
    // Add the submenu item and page
    $parent_slug = 'nfts';
    $page_title = __('NFTs settings', 'kipfs');
    $menu_title = __('NFTs Settings', 'kipfs');
    $capability = 'upload_files';
    $menu_slug = $this->slug;
    $callback = array($this, 'page_content');
    $position = 100;

    add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $callback, $position);
  }

  public function page_content()
  {
    echo '<div class="wrap">';
    echo '<h2>' . __('NFTs Kredeum', 'kipfs') . '</h2>';

    echo '<form action="options.php" method="POST">';
    settings_fields($this->slug);
    do_settings_sections($this->slug);
    submit_button();
    echo '</form></div>';
  }

  public function sections_create()
  {
    add_settings_section('first_section', __('Settings', 'kipfs'), array($this, 'section_callback'), $this->slug);
  }

  public function section_callback($arguments)
  {
    switch ($arguments['id']) {
      case 'first_section':
        echo '<p>' . __('Here you can set IPFS options', 'kipfs') . '</p>';
        break;
    }
  }

  public function fields_create()
  {
    $fields = kipfs_fields($this->slug);

    foreach ($fields as $field) {
      add_settings_field($field['uid'], $field['label'], array($this, 'field_callback'), $this->slug, $field['section'], $field);
      register_setting($this->slug, $field['uid']);
    }
  }

  public function field_callback($arguments)
  {
    $value = get_option($arguments['uid']);
    if (!$value) $value = $arguments['default'];

    switch ($arguments['type']) {
      case 'metamask':
        wp_nonce_field('nonce_action', 'nonce_field');
        printf('<kredeum-metamask />');
        // printf(' - %1$s', get_user_meta(get_current_user_id(), 'ADDR', true));
        break;
      case 'text':
      case 'password':
      case 'number':
        printf('<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value);
        break;
      case 'textarea':
        printf('<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>', $arguments['uid'], $arguments['placeholder'], $value);
        break;
      case 'select':
      case 'multiselect':
        if (!empty($arguments['options']) && is_array($arguments['options'])) {
          $attributes = '';
          $options_markup = '';
          foreach ($arguments['options'] as $key => $label) {
            $selected = is_array($value) ? selected($value[array_search($key, $value, true)], $key, false) : "";
            $options_markup .= sprintf('<option value="%s" %s>%s</option>', $key, $selected, $label);
          }
          if ($arguments['type'] === 'multiselect') {
            $attributes = ' multiple="multiple" ';
          }
          printf('<select name="%1$s[]" id="%1$s" %2$s>%3$s</select>', $arguments['uid'], $attributes, $options_markup);
        }
        break;
      case 'radio':
      case 'checkbox':
        if (!empty($arguments['options']) && is_array($arguments['options'])) {
          $options_markup = '';
          $iterator = 0;
          foreach ($arguments['options'] as $key => $label) {
            $iterator++;
            $options_markup .= sprintf('<label for="%1$s_%6$s"><input id="%1$s_%6$s" name="%1$s[]" type="%2$s" value="%3$s" %4$s /> %5$s</label><br/>', $arguments['uid'], $arguments['type'], $key, checked($value[array_search($key, $value, true)], $key, false), $label, $iterator);
          }
          printf('<fieldset>%s</fieldset>', $options_markup);
        }
        break;
    }

    if (array_key_exists('helper', $arguments)) {
      printf('<span class="helper"> %s</span>', $arguments['helper']);
    }

    if (array_key_exists('supplimental', $arguments)) {
      printf('<p class="description">%s</p>', $$arguments['supplimental']);
    }
  }
}
new Kipfs_Settings();
