<!-- description modal content -->
<div class="modal fade bs-example-modal-lg" id="descriptionModal-{{$dataId}}" tabindex="-1" role="dialog"
     aria-labelledby="largeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-fluid">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Descriptions</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div id="accordion-description" class="accordion" role="tablist" aria-multiselectable="true">
                    @if(is_array($descriptions))
                        @foreach($descriptions as $k => $v)
                            <div class="card">
                                <div class="card-header" role="tab" id="heading{{$k}}">
                                    <div class="input-group">
                                        {{--  Description radio input  --}}
                                        <input type="radio"  @if($loop->index == 0) checked @endif
                                        class="form-control" name="products[{{$dataId}}][description]"
                                               value="{{$v->content}}" id="radio-desc-{{$dataId}}-{{$k}}">
                                        <label for="radio-desc-{{$dataId}}-{{$k}}"></label>

                                        <h5 class="mb-0">
                                            <a data-toggle="collapse" data-parent="#accordion-description"
                                               href="#collapseexa{{$k}}"
                                               aria-expanded="true" aria-controls="collapseexa{{$k}}">
                                                Description #{{$k}}
                                            </a>
                                        </h5>
                                    </div>
                                </div>

                                <div id="collapseexa{{$k}}" class="collapse show" role="tabpanel"
                                     aria-labelledby="headingOne">
                                    <div class="card-body">
                                        <p><strong>Source: </strong><a href="#">{{$v->source}}</a></p>
                                        <p>{{$v->content}}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <input type="radio" class="form-control" checked name="products[{{$dataId}}][description]"
                               value="" id="radio-desc-{{$dataId}}">
                        <label for="radio-desc-{{$dataId}}"></label>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
