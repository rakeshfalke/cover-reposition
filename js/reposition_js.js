function repositionCover() {
    $('.cover-wrapper').hide();
    $('.cover-resize-wrapper').show();
    $('.cover-resize-buttons').show();
    $('.default-buttons').hide();
    $('.screen-width').val($('.cover-resize-wrapper').width());
    $('.article-detail-hero-unit img')
    .css('cursor', 's-resize')
    .draggable({
        scroll: false,

        axis: "y",

        cursor: "s-resize",

        drag: function (event, ui) {
            y1 = $('.timeline-header-wrapper').height();
            y2 = $('.cover-resize-wrapper').find('img').height();

            if (ui.position.top >= 0) {
                ui.position.top = 0;
            }
            else
            if (ui.position.top <= (y1-y2)) {
                ui.position.top = y1-y2;
            }
        },

        stop: function(event, ui) {
            $('input.cover-position').val(ui.position.top);
        }
    });
}

function saveReposition() {

    if ($('input.cover-position').length == 1) {
        posY = $('input.cover-position').val();
        $('#cover-position-form').submit();
    }
}

function cancelReposition() {
    $('.cover-wrapper').show();
    //$('.cover-resize-wrapper').hide();
    $('.cover-resize-buttons').hide();
    $('.default-buttons').show();
    $('input.cover-position').val(0);
    $('.cover-resize-wrapper img').draggable('destroy').css('cursor','default');
}


 $(function(){
    $("#cover-position-form").submit(function(e){
      var postData = $(this).serializeArray();
      var formURL = $(this).attr("action");
      $.ajax(
      {
        url : formURL,
        type: "POST",
        dataType:  'json',
        data: postData,
        beforeSend: function() {
            alert('Before');
        },
        success: function(data, textStatus, jqXHR) {
            alert('success');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Failed with ajax error');
        }
      });
      e.preventDefault(); //STOP default action
      e.unbind(); //unbind. to stop multiple form submit.
    });
});
