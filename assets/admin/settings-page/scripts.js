jQuery(document).ready(function ($) {
  const ucsWrap = $('.ultimate-color-schemes');

  /**
   * Event handler for the "+" button in the ultimate color schemes section.
   * Duplicates the color scheme selection wrap.
   */
  ucsWrap.on('click', '.ultimate-color-schemes__plus', function () {
    let wrapSelect = $('.ultimate-color-schemes__wrap--select');
    const content = wrapSelect.html();
    const itemsLength = wrapSelect.length;

    if (itemsLength === 1 && wrapSelect.hasClass('hidden')) {
      wrapSelect.removeClass('hidden');
    } else {
      $(this).before('<div class="ultimate-color-schemes__wrap ultimate-color-schemes__wrap--select">' + content + '</div>');
      wrapSelect = $(this).prev('.ultimate-color-schemes__wrap--select');
    }

    wrapSelect.find('select').val('administrator');
    wrapSelect.find('.ultimate-color-schemes__scheme').removeClass('active');
    wrapSelect.find('input[type="radio"]').attr('checked', false);
    wrapSelect.find('.ultimate-color-schemes__schemes').addClass('active');
    wrapSelect.find('.ultimate-color-schemes__current--edit').addClass('active');
    wrapSelect.find('.ultimate-color-schemes__scheme:first').addClass('active');
    wrapSelect.find('.ultimate-color-schemes__scheme:first input[type="radio"]').attr('checked', true);
    const name = wrapSelect.find('.ultimate-color-schemes__scheme:first .ultimate-color-schemes__scheme--select p').text();
    wrapSelect.find('.ultimate-color-schemes__current > p').text(name);
    const palette = wrapSelect.find('.ultimate-color-schemes__scheme:first .ultimate-color-schemes__scheme--palette').html();
    wrapSelect.find('.ultimate-color-schemes__current--palette').html(palette);
  });

  /**
   * Event handler for the "-" button in the ultimate color schemes section.
   * Removes the color scheme selection wrap if more than one exists.
   */
  ucsWrap.on('click', '.ultimate-color-schemes__remove', function () {
    const wrapSelect = $('.ultimate-color-schemes__wrap--select');
    if (wrapSelect.length === 1) {
      wrapSelect.addClass('hidden');
      return false;
    }

    $(this).closest('.ultimate-color-schemes__wrap').remove();
  });

  /**
   * Event handler for selecting a color scheme in the ultimate color schemes section.
   * Marks the selected color scheme as active and updates the corresponding input.
   */
  ucsWrap.on('click', '.ultimate-color-schemes__scheme', function () {
    $(this).closest('.ultimate-color-schemes__schemes').find('.ultimate-color-schemes__scheme').removeClass('active');
    $(this).addClass('active');

    $(this).closest('.ultimate-color-schemes__schemes').find('input[type="radio"]').attr('checked', false);
    $(this).find('input[type="radio"]').attr('checked', true);

    const name = $(this).find('.ultimate-color-schemes__scheme--select p').text();
    $(this).closest('.ultimate-color-schemes__color').find('.ultimate-color-schemes__current > p').text(name);
    const palette = $(this).find('.ultimate-color-schemes__scheme--palette').html();
    $(this).closest('.ultimate-color-schemes__color').find('.ultimate-color-schemes__current--palette').html(palette);
  });

  /**
   * Event handler for the "Save Changes" button in the ultimate color schemes section.
   * Submits the form.
   */
  ucsWrap.on('click', '.ultimate-color-schemes__save', function () {
    let data = {};
    const mainForm = $('.ultimate-color-schemes__form');
    const nonceValue = mainForm.find('input[name="ucs_nonce"]').val();
    mainForm.find('.ultimate-color-schemes__wrap--select:not(.hidden)').each(function () {
      const userRole = $(this).find('select').val();
      let colorScheme = $(this).find('input[type="radio"]:checked').val();
      if (!colorScheme) {
        colorScheme = 'fresh';
      }
      colorScheme = colorScheme.toLowerCase();
      data[userRole] = colorScheme;
    });

    $.ajax({
      url: ucs_ajax_object.ajax_url,
      type: 'POST',
      data: {
        action: 'ultimate_color_schemes_save',
        nonce: nonceValue,
        data: data
      },
      success: function (response) {
        if (true === response.success) {
          $('.ultimate-color-schemes__success').addClass('active');
          setTimeout(function () {
            location.reload();
          }, 1000);
        } else {
          console.log('Error');
        }
      }
    });
  });

  $('.ultimate-color-schemes__form').on('submit', function () {
    return false;
  });

  ucsWrap.on('click', '.ultimate-color-schemes__current--edit', function () {
    const colorsWrapper = $(this).closest('.ultimate-color-schemes__color').find('.ultimate-color-schemes__schemes');
    $(this).toggleClass('active');
    if(colorsWrapper.hasClass('active')) {
      colorsWrapper.removeClass('active');
    } else {
      colorsWrapper.addClass('active');
    }
  });
}); // end document ready