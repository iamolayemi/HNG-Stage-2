/**
 * Resume - HNG Internship Cohort 8 Stage 2 Task.
 *
 * @author Olayemi Olatayo <olatayo.olayemi.peter@gmail.com>
 */
const ajax_url = '../requests.php';

$(function () {

    // js ajax forms
    $('.js_ajax-forms').on('submit', function (e) {
        e.preventDefault();
        const _this = $(this);
        const request = _this.attr('data-action');
        const request_data = new FormData(this);
        const submit = _this.find('button[type="submit"]');
        const response = _this.find('.js_ajax-response');
        let loading = submit.attr('data-loading');

        if (loading === '') {
            loading = 'Loading...';
        }

        /* make ajax requests */
        $.ajax({
            type: 'POST',
            url: ajax_url + '?action=' + request,
            cache: false,
            contentType: false,
            processData: false,
            data: request_data,
            beforeSend: function () {
                /* show loading */
                submit.data("text", submit.html())
                submit.prop('disabled', true);
                submit.html(loading);
            },
            success: function (data, statusText, xhr) {
                /* hide loading */
                submit.prop('disabled', false);
                submit.html(submit.data('text'));

                /* handle ajax response */
                if (data.success === true ) {
                    if (response.is(":visible")) response.hide();
                    response.removeClass('alert-danger').addClass('alert-success')
                        .html(data.message).slideDown();
                } else if (data.success === false) {
                    if (response.is(":visible")) response.hide();
                    response.html(data.message).slideDown();
                } else if (xhr.status === 200) { // if ajax request was successful and there is no error from the server i.e data.success !== false
                    response.removeClass('alert-danger').addClass('alert-success')
                        .html('Message has been sent successfully.').slideDown();
                } else {
                    console.log('Form submission could not be processed.')
                }
            },

            error: function () {
                /* hide loading */
                submit.prop('disabled', false);
                submit.html(submit.data('text'));

                /* handle ajax error */
                console.log('An unknown error occurred while processing your request.');
            }
        });
    });


});