@if($category->children()->count() != 0)
    <div class="form-group mt-2">
        {!! Form::label('subcategory', 'Subcategory') !!}
        {!! Form::select('category[]', $category->children()->pluck('name', 'id')->prepend('Without category', '0')->prepend('Please choose category', ''), (isset($selected) ? $selected:null), array('class' => 'form-control subcategory', 'onchange' => 'fetchSubcategory(this); setCategory(this)')) !!}
    </div>
@endif
