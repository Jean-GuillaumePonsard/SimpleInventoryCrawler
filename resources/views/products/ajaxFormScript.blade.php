@auth
    document.addEventListener('DOMContentLoaded', function()
    {
        addListeners();

        function addListeners()
        {
            $(function(){
                $(document).on('submit', '.addProduct', sendForm);
            });
            $(function(){
                $(document).on('submit', '.deleteProduct', sendForm);
            });
        }

        sendForm = function(e) {
            e.preventDefault();
            form = $(this);
            form.find('button[type=submit]').prop('disabled', true);

            $.ajax({
                method: $(this).attr('method'),
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json"
            })
                .done(function(data) {
                    // This function must be created by the template that use the form
                    actionOnSubmittedForm(form);
                })
                .fail(function(data) {
                    form.parents('.product.card').addClass('has-error');
                    $.each(data.responseJSON, function (key, value) {
                        if(key === "message") {
                            button = form.find('button[type=submit]');
                            small = form.find('small')
                            if(small.length) {
                                small.text('Error: ' + value);
                            } else {
                                button.after('<br><small class="text-danger">Error: ' + value + '</small>');
                            }
                        }
                    });
                });
        }
    });
@endauth