@foreach($categories as $category)
    <a href="{{ route('category', $category->slug) }}" 
       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
       style="padding-left: {{ $level * 20 }}px;">
        <div>
            <i class="fas fa-folder-open me-2 text-primary"></i> 
            {{ $category->name }}
        </div>
        <span class="badge bg-primary rounded-pill">{{ $category->articles_count_recursive }}</span>
    </a>
    
    @if(isset($category->children) && $category->children->isNotEmpty())
        @include('partials.categories-tree', ['categories' => $category->children, 'level' => $level + 1])
    @endif
@endforeach