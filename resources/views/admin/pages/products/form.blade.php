@extends('admin.layout.main')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title">Add new</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label">Area</label>
                            <div class="col-sm-10">
                                <input class="form-control" id="input-area" type="text" value="" placeholder="Area">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label">Product name</label>
                            <div class="col-sm-10">
                                <input class="form-control" id="input-product-name" type="text" value="" placeholder="Product name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label">Price</label>
                            <div class="col-sm-10">
                                <input class="form-control" id="input-price" type="number" value="" placeholder="Price">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label">Percent discount</label>
                            <div class="col-sm-10">
                                <input class="form-control" id="input-discount" type="number" value="" placeholder="Percent discount">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label">Short description</label>
                            <div class="col-sm-10">
                                <input class="form-control" id="input-short-description" type="text" value="" placeholder="Short description">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label">Description</label>
                            <div class="col-sm-10">
                                <textarea id="input-description"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                        <button type="button" class="btn btn-primary waves-effect waves-light">Save</button>
                        </div>
                    </div>
                </div>                                            
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection

@section('js')
<!--Wysiwig js-->
<script src="/assets/plugins/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    (function($){
        if($("#input-description").length > 0){
            tinymce.init({
                selector: "textarea#input-description",
                theme: "modern",
                height:300,
                plugins: [
                    "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                    "save table contextmenu directionality emoticons template paste textcolor"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
                style_formats: [
                    {title: 'Bold text', inline: 'b'},
                    {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                    {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                    {title: 'Example 1', inline: 'span', classes: 'example1'},
                    {title: 'Example 2', inline: 'span', classes: 'example2'},
                    {title: 'Table styles'},
                    {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
                ]
            });
        }
    })(jQuery);
</script>
@endsection