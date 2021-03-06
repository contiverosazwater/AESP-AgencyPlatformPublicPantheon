<?php
//if (include_once "/var/www/html_template/profiles/openpublic/themes/omega/alpha/theme-settings.php") { echo "Included"; }
function comm_form_system_theme_settings_alter(&$form, &$form_state) {
  //Add the css to fix the color module's goofy form
  drupal_add_css(drupal_get_path('theme', 'comm') . '/css/colors-admin.css');

  //Fix color module rendering of new colors added to .inc file
  comm_theme_settings_add_new_colors();
  /*
   * Sliver show/hide
   */
  $show_sliver = isset($theme_settings['show_sliver']) ? $theme_settings['show_sliver'] : NULL;
  //Add the checkbox to the form
  $form['sliver'] = array(
    '#type' => 'fieldset',
    '#title' => t('Sliver banner settings'),
    '#description' => t('If toggled on, the sliver banner will be displayed.'),
    '#attributes' => array('class' => array('theme-settings-bottom')),
  );
  $form['sliver']['show_sliver'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display the sliver banner'),
    '#default_value' => theme_get_setting('show_sliver'),
    '#tree' => FALSE,
    '#description' => t('Check here if you want the theme to display the sliver banner.')
  );
  /*
   $form['flyout_logo'] = array(
   '#type' => 'fieldset',
   '#title' => t('Flyout Menu Logo'),
   '#description' => t('If toggled on, the sliver banner will be displayed.'),
   '#attributes' => array('class' => array('theme-settings-bottom')),
   );
   $form['flyout_logo']['show_logo'] = array(
   '#type' => 'checkbox',
   '#title' => t('Display the Flyout Logo'),
   '#default_value' => theme_get_setting('show_logo'),
   '#tree' => FALSE,
   '#description' => t('Check here if you want the theme to display the sliver banner.')
   );*/

  /*
   * Background Image
   */
  $background_path = isset($theme_settings['background_path']) ? $theme_settings['background_path'] : NULL;

  // If $background_path is a public:// URI, display the path relative to the files directory. (Stream wrappers are not end-user friendly)
  if (file_uri_scheme($background_path) == 'public') {
    $background_path = file_uri_target($background_path);
  }
  //Add the background image fields to the form
  $form['background_image'] = array(
    '#type' => 'fieldset',
    '#title' => t('Background image settings'),
    '#description' => t('If toggled on, the following background will be displayed.'),
    '#attributes' => array('class' => array('theme-settings-bottom')),
  );
  $form['background_image']['default_background'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use the default background'),
    '#default_value' => theme_get_setting('default_background'),
    '#tree' => FALSE,
    '#description' => t('Check here if you want the theme to use the background supplied with it.')
  );
  $form['background_image']['settings'] = array(
    '#type' => 'container',
    '#states' => array(
      // Hide the background settings when using the default background.
      'invisible' => array('input[name="default_background"]' => array('checked' => TRUE), ), ),
  );
  $form['background_image']['settings']['background_path'] = array(
    '#type' => 'textfield',
    '#title' => t('Path to custom background'),
    '#description' => t('The path to the file you would like to use as your background file instead of the default background.'),
    '#default_value' => theme_get_setting('background_path'), //isset(theme_get_setting('background_path')) ? theme_get_setting('background_path') : NULL,
  );
  $form['background_image']['settings']['background_file'] = array(
    '#type' => 'file',
    '#title' => t('Upload background image'),
    '#maxlength' => 40,
    '#description' => t("If you don't have direct file access to the server, use this field to upload your background image."),
    '#element_validate' => array('_comm_theme_settings_validate'),
  );

  /*
   // Flyout Text that displays below the menu
   $form['flyout_nav'] = array(
   '#type' => 'fieldset',
   '#title' => t('Flyout Nav Menu Text'),
   '#description' => t('If toggled on, the following text will be displayed.'),
   '#attributes' => array('class' => array('theme-flyout-nav')),
   );
   $form['flyout_nav']['use_flyout_text'] = array(
   '#type' => 'checkbox',
   '#title' => t('Use the text below in the flyout menu.'),
   '#default_value' => theme_get_setting('use_flyout_text'),
   '#tree' => FALSE,
   '#description' => t('Check here if you want to display the text.')
   );
   $flyout_nav_text = theme_get_setting('flyout_nav_text');
   $form['flyout_nav']['flyout_nav_text'] = array(
   '#type' => 'text_format',
   '#title' => t('Flyout text Area'),
   '#default_value' => $flyout_nav_text['value'],
   '#format' => $flyout_nav_text['format'],
   );*/

  //Toggle display of the branding block in the footer
  /*
   $form['footer_branding'] = array(
   '#type' => 'fieldset',
   '#title' => t('Footer Branding settings'),
   '#description' => t('If toggled on, the branding block will be displayed.'),
   '#attributes' => array('class' => array('theme-settings-bottom')),
   );
   $form['footer_branding']['display_footer_branding'] = array(
   '#type' => 'checkbox',
   '#title' => t('Display the branding block in the footer'),
   '#default_value' => theme_get_setting('display_footer_branding'),
   '#tree' => FALSE,
   '#description' => t('Check here if you want to display the branding  block in the footer.')
   );*/

  //Capture the information for the Contact Us section in the footer
  $form['footer_contact_us'] = array(
    '#type' => 'fieldset',
    '#title' => t('Flyout "Contact Us" settings'),
    '#description' => t('If toggled on, the Contact Us block will be displayed.'),
    '#attributes' => array('class' => array('theme-settings-bottom')),
  );
  $form['footer_contact_us']['display_footer_contact'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display the Contact Us block in the flyout region.'),
    '#default_value' => theme_get_setting('display_footer_contact'),
    '#tree' => FALSE,
    '#description' => t('Check here if you want to display the Contact Us block in the flyout region.')
  );
  $form['footer_contact_us']['settings'] = array(
    '#type' => 'container',
    '#states' => array(
      // Hide the settings when not displaying the contact us block.
      'invisible' => array('input[name="display_footer_contact"]' => array('checked' => FALSE), ), ),
  );
  $form['footer_contact_us']['settings']['footer_contact_us_title'] = array(
    '#type' => 'textfield',
    '#title' => t('Title'),
    '#description' => t('Title for the Contact Us block.'),
    '#default_value' => theme_get_setting('footer_contact_us_title'),
  );
  $form['footer_contact_us']['settings']['footer_contact_us_title_link'] = array(
    '#type' => 'textfield',
    '#title' => t('Title Link'),
    '#description' => t('Link for the title in the Contact Us block. (Recommend linking to the contact form.)'),
    '#default_value' => theme_get_setting('footer_contact_us_title_link'),
  );
  $form['footer_contact_us']['settings']['footer_contact_us_comm_title'] = array(
    '#type' => 'textfield',
    '#title' => t('Agency Title'),
    '#description' => t('Commercial title for the Contact Us block.'),
    '#default_value' => theme_get_setting('footer_contact_us_comm_title'),
  );
  $form['footer_contact_us']['settings']['footer_contact_us_address_1'] = array(
    '#type' => 'textfield',
    '#title' => t('Address Line 1'),
    '#description' => t('Address line 1 for the Contact Us block.'),
    '#default_value' => theme_get_setting('footer_contact_us_address_1'),
  );
  $form['footer_contact_us']['settings']['footer_contact_us_address_2'] = array(
    '#type' => 'textfield',
    '#title' => t('Address Line 2'),
    '#description' => t('Address line 2 for the Contact Us block.'),
    '#default_value' => theme_get_setting('footer_contact_us_address_2'),
  );
  $form['footer_contact_us']['settings']['footer_contact_us_phone'] = array(
    '#type' => 'textfield',
    '#title' => t('Phone Number'),
    '#description' => t('Phone number for the Contact Us block.'),
    '#default_value' => theme_get_setting('footer_contact_us_phone'),
  );
  $form['footer_contact_us']['settings']['footer_contact_us_fax'] = array(
    '#type' => 'textfield',
    '#title' => t('Fax Number'),
    '#description' => t('Fax number for the Contact Us block.'),
    '#default_value' => theme_get_setting('footer_contact_us_fax'),
  );
  $form['footer_contact_us']['settings']['footer_contact_us_map_link'] = array(
    '#type' => 'textfield',
    '#title' => t('Map Link'),
    '#description' => t('Link for the map in the Contact Us block.'),
    '#default_value' => theme_get_setting('footer_contact_us_map_link'),
  );
  $form['footer_contact_us']['settings']['footer_contact_us_map_path'] = array(
    '#type' => 'textfield',
    '#title' => t('Path to map image'),
    '#description' => t('The path to the file you would like to use as your map image. (150px x 150px)'),
    '#default_value' => theme_get_setting('footer_contact_us_map_path'),
  );
  $form['footer_contact_us']['settings']['footer_contact_us_map_image'] = array(
    '#type' => 'file',
    '#title' => t('Upload map image'),
    '#maxlength' => 40,
    '#description' => t("Use this field to upload your map image. (150px x 150px.  Will be resized if necessary.)"),
    '#element_validate' => array('_comm_theme_settings_map'),
  );
  //End Contact Us

  
  //Sticky Nav Block
  $sticky_nav_path = isset($theme_settings['sticky_nav_path']) ? $theme_settings['sticky_nav_path'] : NULL;

  // If $sticky_nav_path is a public:// URI, display the path relative to the files directory. (Stream wrappers are not end-user friendly)
  if (file_uri_scheme($sticky_nav_path) == 'public') {
    $sticky_nav_path = file_uri_target($sticky_nav_path);
  }
  //Add the sticky_nav image fields to the form
  $form['sticky_nav_image'] = array(
    '#type' => 'fieldset',
    '#title' => t('Sticky Nav Menu Logo settings'),
    '#description' => t('The Sticky Nav is the top bar that contains links and menu icon.'),
    '#attributes' => array('class' => array('theme-sticky-nav')),
  );
  $form['sticky_nav_image']['use_sticky_nav'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use a logo for the sticky nav.'),
    '#default_value' => theme_get_setting('use_sticky_nav'),
    '#tree' => FALSE,
    '#description' => t('Check here if you want to display this image in the sticky nav.')
  );
  $form['sticky_nav_image']['settings']['sticky_nav_path'] = array(
    '#type' => 'textfield',
    '#title' => t('Path to custom sticky nav icon'),
    '#description' => t('The path to the file you would like to use as your sticky nav logo file.'),
    '#default_value' => theme_get_setting('sticky_nav_path'),
  );
  $form['sticky_nav_image']['settings']['sticky_nav_file'] = array(
    '#type' => 'file',
    '#title' => t('Upload background image'),
    '#maxlength' => 40,
    '#description' => t("If you don't have direct file access to the server, use this field to upload your logo."),
    '#element_validate' => array('_comm_theme_settings_flyout'),
  );

  $stickyMobile = theme_get_setting('sticky_nav_mobile');
  if ($stickyMobile != null) {
    $form['sticky_nav_image']['sticky_nav_mobile'] = array(
      '#type' => 'radios',
      '#title' => t('When to display the sticky nav.'),
      '#default_value' => theme_get_setting('sticky_nav_mobile'),
      '#options' => array(
        1 => 'Display the Sticky Nav only in mobile display',
        0 => 'Display the Sticky Nav in desktop and mobile displays',
      )
    );
  }
  else {
    $form['sticky_nav_image']['sticky_nav_mobile'] = array(
      '#type' => 'radios',
      '#title' => t('When to display the sticky nav.'),
      '#default_value' => 1,
      '#options' => array(
        1 => 'Display the Sticky Nav only in mobile display',
        0 => 'Display the Sticky Nav in desktop and mobile displays',
      )
    );
  }
  //End Sticky Nav Block

  return $form;

}

function _comm_theme_settings_flyout($form, &$form_state) {
  $validators = array('file_validate_is_image' => array());
  $file = file_save_upload('sticky_nav_file', $validators);
  $map_file = file_save_upload('footer_contact_us_map_image', $validators);

  if (isset($file)) {
    // File upload was attempted.
    if ($file) {
      // Move the file, into the Drupal file system
      if ($filename = file_unmanaged_copy($file -> uri, 'public://background_images', FILE_EXISTS_RENAME)) {
        unset($form['sticky_nav_image']['settings']['sticky_nav_path']);

        //$form['sticky_nav_image']['settings']['default_sticky_nav']['#value'] = 0;
        $form['sticky_nav_image']['settings']['sticky_nav_path']['#value'] = $filename;
        //$form['sticky_nav_image']['settings']['toggle_sticky_nav']['#value'] = 1;

        $form['default_sticky_nav']['#parents'] = array('default_sticky_nav');

        $form['sticky_nav_path']['#parents'] = array('sticky_nav_path');
        form_set_value($form['sticky_nav_path'], file_create_url($filename), $form_state);

        watchdog('Theme Settings', 'New sticky_nav Image uploaded: ' . file_create_url($filename));
        drupal_set_message('Sticky Nav Image uploaded.  URL: ' . file_create_url($filename));

      }
      else {
        form_set_error('file', t('Failed to write the uploaded file the site\'s file folder.'));
      }

    }
    else {
      // File upload failed.
      form_set_error('sticky_nav_file', t('The image could not be uploaded.'));
    }
  }
}

function _comm_theme_settings_validate($form, &$form_state) {
  $validators = array('file_validate_is_image' => array());
  $file = file_save_upload('background_file', $validators);
  $map_file = file_save_upload('footer_contact_us_map_image', $validators);

  if (isset($file)) {
    // File upload was attempted.
    if ($file) {
      // Move the file, into the Drupal file system
      if ($filename = file_unmanaged_copy($file -> uri, 'public://background_images', FILE_EXISTS_RENAME)) {
        unset($form['background_image']['settings']['background_path']);

        //$form['background_image']['settings']['default_background']['#value'] = 0;
        $form['background_image']['settings']['background_path']['#value'] = $filename;
        //$form['background_image']['settings']['toggle_background']['#value'] = 1;

        $form['default_background']['#parents'] = array('default_background');
        form_set_value($form['default_background'], 0, $form_state);

        $form['background_path']['#parents'] = array('background_path');
        form_set_value($form['background_path'], file_create_url($filename), $form_state);

        watchdog('Theme Settings', 'New Background Image uploaded: ' . file_create_url($filename));
        drupal_set_message('Background Image uploaded.  URL: ' . file_create_url($filename));

      }
      else {
        form_set_error('file', t('Failed to write the uploaded file the site\'s file folder.'));
      }

    }
    else {
      // File upload failed.
      form_set_error('background_file', t('The image could not be uploaded.'));
    }
  }
}

function _comm_theme_settings_map($form, &$form_state) {
  $validators = array('file_validate_is_image' => array());
  $file = file_save_upload('background_file', $validators);
  $map_file = file_save_upload('footer_contact_us_map_image', $validators);
  //Map image
  if (isset($map_file)) {
    // File upload was attempted.
    if ($map_file) {
      // Move the file, into the Drupal file system
      if ($map_filename = file_unmanaged_copy($map_file -> uri, 'public://background_images', FILE_EXISTS_RENAME)) {

        //resize, if necessary
        $map_resized = image_load($map_file -> uri);
        list($map_width, $map_height) = getimagesize($map_file -> uri);
        if (($map_width > 150) || ($map_height > 150)) {
          if ($map_width / $map_height >= 1) {
            image_scale($map_resized, 150);
          }
          else {
            image_scale($map_resized, null, 150);
          }
          image_save($map_resized, $map_filename, FILE_EXISTS_RENAME);
          $map_resized -> status = FILE_STATUS_PERMANENT;
          drupal_set_message('Uploaded image was greater than 150px.  Image has been resized.');
        }
        //end resize

        unset($form['footer_contact_us']['settings']['footer_contact_us_map_path']);

        $form['footer_contact_us']['settings']['footer_contact_us_map_path']['#value'] = $map_filename;

        $form['footer_contact_us_map_path']['#parents'] = array('footer_contact_us_map_path');
        form_set_value($form['footer_contact_us_map_path'], file_create_url($map_filename), $form_state);

        watchdog('Theme Settings', 'New Footer Map Image uploaded: ' . file_create_url($map_filename));
        drupal_set_message('Map Image uploaded.  URL: ' . file_create_url($map_filename));

      }
      else {
        form_set_error('file', t('Failed to write the uploaded file the site\'s file folder.'));
      }

    }
    else {
      // File upload failed.
      form_set_error('background_file', t('The image could not be uploaded.'));
    }
  }

}

function _comm_theme_settings_submit(&$form, &$form_state) {
  //
  //
  //
  //
  //
  //   REMOVE?
  /*
   drupal_set_message('Entering submit function<br /><pre>' . print_r($form['background_image']['settings']['background_image_upload'], TRUE) . '</pre>');

   // If the user uploaded a new logo or favicon, save it to a permanent location
   // and use it in place of the default theme-provided file.
   if ($file = $form['background_image']['settings']['background_path']) {
   unset($form['background_image']['settings']['background_file']);

   $filename = file_unmanaged_copy($file->filepath, 'public://background_images');

   $form['default_background'] = 0;
   $form['background_path'] = $filename;
   $form['toggle_background'] = 1;
   }
   else {
   drupal_set_message('Did not save image.');
   }
   */
}

function comm_background_setting($form, &$form_state) {
  //
  //
  //
  //
  //
  //   REMOVE?
  /*
   drupal_set_message('Validate function:');
   if ($file = file_save_upload($form['background_image_upload'])) {
   $parts = pathinfo($file->filename);
   $filename = (! empty($key)) ? str_replace('/', '_', $key) .'_background.'. $parts['extension'] : 'background.'. $parts['extension'];
   if (file_copy($file, $filename)) {
   $form['default_background'] = 0;
   $form['background_path'] = $file->filepath;
   $form['toggle_background'] = 1;
   }
   }
   */
}

/*
 *  Color Module Integration
 */
function comm_theme_settings_add_new_colors() {
  // Add in any new colors that are defined in default scheme
  // but are not defined in the saved palette values.
  // Supplements logic in color.module color_scheme_form().
  $theme = 'comm';
  $info = color_get_info($theme);
  $names = $info['fields'];
  $palette = color_get_palette($theme);
  //calls variable_get
  $default = color_get_palette($theme, TRUE);
  $new = array();
  foreach ($default as $name => $value) {
    if (!isset($palette[$name]) && isset($names[$name])) {
      $palette[$name] = $default[$name];
      $new[] = $names[$name];
    }
  }
  if (count($new)) {
    drupal_set_message(t('One or more new colors are now available: @colors', array('@colors' => implode(', ', $new))));
    variable_set('color_' . $theme . '_palette', $palette);
  }
}
