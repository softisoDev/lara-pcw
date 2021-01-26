<!-- sample modal content -->
<div class="modal fade bs-example-modal-lg" id="imagesModal-{{$dataId}}" tabindex="-1" role="dialog"
     aria-labelledby="largeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-fluid">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Images</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <select name="products[{{$dataId}}][images][]" id="imgselect-{{$dataId}}" multiple="multiple" class="image-picker">
                {{-- Parse images --}}
                @foreach($images as $image)
                    @if($loop->index < 8)
                        <option selected data-product-id="{{$dataId}}" data-img-src="{{$image}}" value="{{$image}}">Set as primary Image</option>
                    @endif
                @endforeach
            </select>
            {!! Form::hidden("products[{$dataId}][primaryImage]", reset($images)) !!}
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

