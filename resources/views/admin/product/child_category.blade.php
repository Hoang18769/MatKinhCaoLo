<option value="{{ $child->id_category }}">{{ $prefix }} {{ $child->name_category }}</option>
@if ($child->children)
    @foreach ($child->children as $subChild)
        @include('admin.product.child_category', ['child' => $subChild, 'prefix' => $prefix . '-'])
    @endforeach
@endif
