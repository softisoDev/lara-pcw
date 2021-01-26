<!-- save file modal content -->
<div class="modal fade bs-example-modal-sm" id="saveSearchModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="mySmallModalLabel">Save search</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::text('newFileName', null, array('class' => 'form-control', 'id' => 'newFileName', 'placeholder' => 'Search name')) !!}
                </div>
                <div class="form-group text-center">
                    <button class="btn btn-success" onclick="saveSearch()">Save</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
