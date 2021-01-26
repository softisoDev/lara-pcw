<div class="modal fade" id="mediaUploadModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="mediaUploadModal"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {!! Form::open(array('method' => 'POST', 'route' => 'admin.product.source.media.upload', 'id' => 'media-upload-form', 'enctype' => 'multipart/form-data')) !!}
            <div class="modal-header">
                <h4 class="modal-title" id="mediaUploadModal">Media modal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12" id="error-area">

                    </div>
                </div>

                {!! Form::hidden('source_name', null, array('id' => 'source-name')) !!}
                <div class="form-group">
                    {!! Form::file('image', array('class'=>'form-control')) !!}
                </div>

            </div>
            <div class="modal-footer">
                {!! Form::submit('Upload', array('class' => 'btn btn-primary')) !!}
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
