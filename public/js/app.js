function execFunc(functionName/*, args */) {
    var args = [].slice.call(arguments).splice(1),
        namespaces = functionName.split("."),
        func = namespaces.pop(),
        context = window;

    if (namespaces.length) {
        for (var i = 0; i < namespaces.length; i++) {
            context = context[namespaces[i]];
        }
    }

    if (context) {
        return context[func].apply(context, args);
    }
}

//jQuery(document).ready(function($) {

    /**
     * Lightbox
     */
    if (typeof $.fn.ekkoLightbox != 'undefined') {
        $(document).on('click', '[data-toggle="lightbox"]', function (event) {
            event.preventDefault();
            $(this).ekkoLightbox();
        });
    }

    /**
     * Refresh captcha
     */
    $(document).on('click', '[data-toggle=captcha-refresh]', function(e) {
        e.preventDefault();

        var url = $(this).data('url') || $(this).prop('href');

        if (url) {
            $(this).closest('.captcha-row').find('img:first').attr('src', url + '?' + (new Date()).getTime());
        }

        return false;
    });

    /**
     * Ajax Modal Form
     */
    if (typeof $.fn.modal != 'undefined') {
        $(document).on('click', '[data-toggle="modal-form"]', function (event) {
            event.preventDefault();

            var $this = $(this),
                size = $this.data('size') || 'lg';

            var $modal = $('<div class="modal fade modal-form" tabindex="-1" role="dialog">' +
                '<div class="modal-dialog modal-' + size + '" role="document">' +
                '<div class="modal-content"></div>' +
                '<div class="modal-overlay"><div class="spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>' +
                '</div></div>').appendTo('body');

            $modal.on('show.bs.modal', function (e) {
                $modal.addClass('loading');
                var url = $this.attr('href') || $this.data('url');

                if (url) {
                    $modal.find('.modal-content').load(url, function (r, status, xhr) {
                        $modal.removeClass('loading');
                        if (status == 'error') {
                            $(this).html('<div class="alert alert-danger" style="margin: 0">' +
                                '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
                                + xhr.status + " " + xhr.statusText +
                                '</div>');
                        } else {
                            var onInit = $this.data('init');
                            if (onInit) {
                                execFunc(onInit);
                            }

                            var $form = $(this).find('form:first');

                            $form.ajaxForm({
                                beforeSubmit: function (arr, $form) {
                                    $modal.addClass('loading');
                                    $form.find('.has-danger').removeClass('has-danger');
                                    $form.find('.feedback-error').remove();
                                },
                                complete: function () {
                                    $modal.removeClass('loading');
                                },
                                error: function (xhr) {
                                    if (xhr.responseJSON) {
                                        if (xhr.status == 302) {
                                            var url = xhr.responseJSON.redirect;
                                            if (url) {
                                                document.location.href = url;
                                            }

                                            return;
                                        } else if (xhr.status = 422) {
                                            $.each(xhr.responseJSON, function (k, v) {
                                                if (k == 'captcha') {
                                                    var el = $modal.find('[name=' + k + ']').first().closest('.captcha-row');
                                                } else {
                                                    var el = $modal.find('[name=' + k + ']').first();
                                                }
                                                el.after(
                                                    '<div class="form-control-feedback feedback-error">' + v[0] + '</div>'
                                                ).closest('.form-group').addClass('has-danger');
                                            });

                                            return;
                                        }
                                    }

                                    $modal.find('.modal-content').html('<div class="alert alert-danger" style="margin: 0">' +
                                        '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
                                        + xhr.status + " " + xhr.statusText +
                                        '</div>');
                                }
                            });

                            $(this).find('[type=submit]').on('click', function (e) {
                                e.preventDefault();
                                $form.submit();
                            });
                        }
                    });
                }
            });

            $modal.on('hidden.bs.modal', function (e) {
                $(this).remove();
            });

            $modal.modal();
        });
    }

    /**
     * Ajax forms
     */
    if (typeof $.fn.ajaxForm != 'undefined') {
        $.fn.setupAjaxForm = function () {
            var form = this;

            this.prepend('<div class="block-overlay"><div class="spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>');

            this.ajaxForm({
                beforeSubmit: function (arr, $form) {
                    if ($form.hasClass('loading')) {
                        return false;
                    }

                    $form.addClass('loading');
                    $form.find('.has-danger').removeClass('has-danger');
                    $form.find('.feedback-error').remove();
                    $form.find('[type=submit]').prop('disabled', true);
                },
                complete: function () {
                    form.removeClass('loading');
                    form.find('[type=submit]').prop('disabled', false);
                    form.find('[data-toggle=captcha-refresh]').click();
                },
                error: function (xhr) {
                    if (xhr.responseJSON) {
                        if (xhr.status == 302) {
                            var url = xhr.responseJSON.redirect;
                            if (url) {
                                document.location.href = url;
                            }

                            return;
                        } else if (xhr.status = 422) {
                            $.each(xhr.responseJSON, function (k, v) {
                                if (k == 'captcha') {
                                    var el = form.find('[name=' + k + ']').first().closest('.captcha-row');
                                } else {
                                    var el = form.find('[name=' + k + ']').first();
                                }
                                el.after(
                                    '<div class="form-control-feedback feedback-error">' + v[0] + '</div>'
                                ).closest('.form-group').addClass('has-danger');
                            });

                            return;
                        }
                    }

                    if (typeof $.notify != 'undefined') {
                        $.notify({message: xhr.status + " " + xhr.statusText},{type: 'danger'});

                        return;
                    }

                    alert(xhr.status + " " + xhr.statusText);
                },

                success: function (data) {
                    form[0].reset();

                    if (data && data.message) {
                        if (typeof $.notify != 'undefined') {
                            $.notify({message: data.message},{type: 'success'});

                            return;
                        }

                        alert(data.message);
                    }
                }
            });

            return this;
        };

        $('form.ajax-form').setupAjaxForm();
    }
//})(jQuery);
