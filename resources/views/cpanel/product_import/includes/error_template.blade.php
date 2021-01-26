@foreach($errors as $error)
    @if($loop->index <= 10)
        <div class="alert alert-danger alert-rounded"> {{$error}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif
@endforeach
