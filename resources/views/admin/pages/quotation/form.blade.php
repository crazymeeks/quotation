@extends('admin.layout.main')
@section('css')
@include('admin.layout.datatable-css')
<!-- Plugins css -->
<link href="/assets/plugins/timepicker/tempusdominus-bootstrap-4.css" rel="stylesheet" />
<link href="/assets/plugins/timepicker/bootstrap-material-datetimepicker.css" rel="stylesheet">
<link href="/assets/plugins/clockpicker/jquery-clockpicker.min.css" rel="stylesheet" />
<link href="/assets/plugins/colorpicker/asColorPicker.min.css" rel="stylesheet" type="text/css" />
<link href="/assets/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />

<link href="/assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
<link href="/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
<link href="/assets/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet" /> 

<link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="/assets/css/icons.css" rel="stylesheet" type="text/css">
<link href="/assets/css/style.css" rel="stylesheet" type="text/css">
<style>
.autocomplete-items {
    font-size: 17px;
    background: #fff;
    width: 96%;
    position: absolute;
    z-index: 1000;
}
.autocomplete-items div:hover {
    background-color: #e9e9e9;
    cursor: pointer;
}
.autocomplete-items div {
    border: 1px solid #d4d4d4;
}

#product-list-modal-table > tbody > tr {
    cursor: pointer;
    
}
#product-list-modal-table > tbody > tr:hover {
    background-color: #E2E7E8;
}
#quote-footer {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
}
.quote-footer__convertbtn > button {
    float: right;
}
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title">
            @if($quotation->status == null || $quotation->status == 'pending')
                Create new quotation
            @else
                Quotation details
            @endif
            </h4>
        </div>
    </div>
</div>
@include('admin.pages.quotation.modal.product-select')
@include('admin.pages.quotation.modal.edit-quote-item-containter')

@include('admin.pages.quotation.quote-form')
@include('admin.pages.quotation.quote-details')
@endsection

@section('js')
<!--Wysiwig js-->
<script src="/assets/js/jquery-validation-1.19.5/dist/jquery.validate.min.js"></script>
<script src="/assets/js/typeahead.jquery.min.js"></script>


<!-- Plugins js -->
<script src="/assets/plugins/timepicker/moment.js"></script>
<script src="/assets/plugins/timepicker/tempusdominus-bootstrap-4.js"></script>
<script src="/assets/plugins/timepicker/bootstrap-material-datetimepicker.js"></script>
<script src="/assets/plugins/clockpicker/jquery-clockpicker.min.js"></script>
<script src="/assets/plugins/colorpicker/jquery-asColor.js"></script>
<script src="/assets/plugins/colorpicker/jquery-asGradient.js"></script>
<script src="/assets/plugins/colorpicker/jquery-asColorPicker.min.js"></script>
<script src="/assets/plugins/select2/select2.min.js"></script>

<script src="/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<script src="/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
<script src="/assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js"></script>

<!-- Plugins Init js -->
<script src="/assets/pages/form-advanced.js"></script> 

<!-- App js -->
<script src="/assets/js/app.js"></script>
@include('admin.layout.datatable-js')


<script type="text/javascript">
    (function($){
        let customerId = null;
        /** id of item being edited */
        let itemId = null;
        let discount = <?php echo $quotation->percent_discount != null ? $quotation->percent_discount : 0; ?>;

        let func = debounce((e) => {
            let value = e.currentTarget.value;
            if (!value) {
                hideItems();
                customerId = null;
            } else {
                $.ajax({
                    url: "{{route('customer.typeahead.get')}}",
                    method: "GET",
                    data: {
                        q: value
                    },
                    success: function(response){
                        const {results} = response;
                        let html = '';
                        for(let i = 0; i < results.length; i++){
                            html += `<div data-id="${results[i].id}" data-value="${results[i].customer_name}" class="item">${results[i].customer_name}</div>`;
                        }
                        $('.autocomplete-items').html(html);
                    }
                });

            }
        }, 200);


        $('.typeahead').on('keyup', func);
        

        $('.autocomplete-items').on('click', '.item', function(evt){
            let value = $(this).data('value');
            customerId = $(this).data('id');
            $('#customer').val(value);
            hideItems();
        });

        /** Hide item on window click */
        $(window).on('click', function(){
            hideItems();
        });

        function hideItems(){
            $('.autocomplete-items').html('');
        }

        $('#product-list-modal-table').DataTable();

        $('#btn-add-to-quote').on('click', function(e){
            
            let product = $('#quote-product').val();
            let quantity = $('#quantity').val();
            
            $.ajax({
                url: "{{route('admin.quotation.product.add.post')}}",
                method: "POST",
                data: {
                    product: product,
                    quantity: quantity,
                    discount: discount,
                },
                success: function(response){
                    const {html} = response;
                    drawQuoteItemsHtml(html);
                    $('.quote-modal').modal('hide');
                }
            });
        });


        const applyDiscount = debounce((e) => {
            let $this = $(e.currentTarget);

            discount = $this.val();

            $.ajax({
                url: "{{route('admin.quotation.compute.discount')}}",
                method: "POST",
                data: {
                    discount: discount,
                },
                success: function(response){
                    const {html} = response;
                    drawQuoteItemsHtml(html);
                }
            });
        }, 200);

        $('#discount').on('keyup', applyDiscount);

        $('#btn-save-quote').on('click', function(e){

            let data = {
                address: $('#address').val(),
                contact_no: $('#contact_no').val(),
                discount: parseFloat(discount),
                code: $('#code').val(),
            };

            if (customerId) {
                data.customer_id = customerId;
            } else {
                data.customer = $('#customer').val();
            }

            $.ajax({
                url: "{{route('admin.quotation.post.save')}}",
                method: "POST",
                data: data,
                success: function(response){
                    const {message} = response;
                    toastr.success(message);
                    setTimeout(() => {
                        window.location.href = window.location.href;
                    }, 2000);
                },
                error: function(xhr, status, thrown){
                    const response = xhr.responseJSON;
                    const {message} = response;
                    toastr.error(message);
                }
            });
        });

        // edit quote item
        $('body').on('click', '.item-quote-edit-btn', function(e){
            itemId = $(this).data('id');
            
            $.ajax({
                url: "{{route('admin.quotation.product.post.edit.modal')}}",
                method: "POST",
                data: {
                    id: itemId
                },
                success: function(response){
                    const {html} = response;
                    $('.edit-quote-modal').find('.modal-body').html(html);
                    $('.edit-quote-modal').modal('show');
                }
            });
        });

        // update quote item quantity
        $('.edit-quote-modal').on('click', '#quote-item-update-btn', function(e){
            let quantity = $('.edit-quote-modal').find('#quantity').val();

            $.ajax({
                url: "{{route('admin.quotation.product.update.quantity')}}",
                method: "PUT",
                data: {
                    id: itemId,
                    quantity: quantity,
                    discount: discount,
                },
                success: function(response){
                    const {html} = response;
                    drawQuoteItemsHtml(html);
                    $('.edit-quote-modal').modal('hide');
                    toastr.success("Quote item updated.");
                },
                error: function(xhr, status, thrown){
                    console.log('error', xhr);
                    toastr.error("Error while updating item quantity. Please try again.");
                }
            });
        });

        // remove quote item
        $('body').on('click', '.item-quoute-delete-btn', function(e){
            let id = $(this).data('id');
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{route('admin.quotation.product.delete')}}",
                        method: "DELETE",
                        data: {
                            id: id,
                            discount: discount
                        },
                        success: function(response){
                            const {html} = response;
                            drawQuoteItemsHtml(html);
                            toastr.success("Quote item removed.");
                        }
                    });
                }
            });
        });

        // Convert quotation to order
        $('body').on('click', '#btn-convert-to-order', function(e){
            let data = {};
            if ($('#id').length > 0) {
                data.id = $('#id').val();
            }
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, convert!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{route('admin.quotation.post.convert.to.order')}}",
                        method: "POST",
                        data: data,
                        success: function(response){
                            const {html} = response;
                            drawQuoteItemsHtml(html);
                            toastr.success("Quote has been converted to order successfully.");
                        }
                    });
                }
            });
            
        });

        function drawQuoteItemsHtml(html) {
            $('#quote-table').html(html);
        }
    })(jQuery);
</script>
@endsection