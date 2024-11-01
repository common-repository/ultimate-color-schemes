jQuery(document).ready(function ($) {

  const ucsColorPicker = $('.wp-admin.profile-php #your-profile #color-picker');

  /**
   * Append a "Load More color schemes" button to the color picker in the user profile page.
   */
  ucsColorPicker.append('<button class="button button-primary ucs-load-more">Load More color schemes</button>');

  /**
   * Move the selected color option to the top of the color picker.
   */
  $('.wp-admin.profile-php #your-profile #color-picker .color-option.selected').each(function () {
    $('.wp-admin.profile-php #your-profile #color-picker').prepend($(this));
  });

  /**
   * Hide color options that exceed the initial display limit.
   */
  $('.wp-admin.profile-php #your-profile #color-picker .color-option').each(function () {
    if ($(this).index() > 17) {
      $(this).addClass('hidden');
    }
  });

  /**
   * Event handler for the "Load More color schemes" button.
   * Reveals hidden color options in batches of 8.
   * Removes the button if no more hidden options are left.
   */
  ucsColorPicker.on('click', '.ucs-load-more', function () {
    let hiddenCount = 0;
    const hiddenOptions = $('.wp-admin.profile-php #your-profile #color-picker .color-option.hidden');

    hiddenOptions.each(function () {
      hiddenCount++;
      if (hiddenCount <= 8) {
        $(this).removeClass('hidden');
      }
    });

    const lastHiddenOption = $('.wp-admin.profile-php #your-profile #color-picker .color-option.hidden');

    if (lastHiddenOption.length === 0) {
      $(this).remove();
    }

    return false;
  });
}); // end document ready