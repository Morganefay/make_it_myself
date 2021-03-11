jQuery(function($){

    $('#comment-form').on('submit', function (event) {
        event.preventDefault();

        const url = new URL(window.location.href);

        $.ajax({
            'url': `${url.pathname}/add-comment`,
            'method': 'post',
            'data': new FormData(event.currentTarget),
            'processData': false,
            'contentType': false,
            'success': html => {
                $('.commentContainer').prepend(html);
                $('#add-comment-success-modal').modal('show');
                $('#comment-form').trigger('reset');
            }
        });
    });
});
