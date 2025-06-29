<div class="group-item mb-2">
    <div class="d-flex align-items-center p-2 {{ $group->is_current ? 'bg-primary text-white rounded' : '' }}"
         style="padding-left: {{ $group->level * 20 }}px !important;">
        <a href="{{ route('group', $group->id) }}" 
           class="{{ $group->is_current ? 'text-white fw-bold' : 'text-dark' }} text-decoration-none">
            {{ $group->name }}
        </a>
        <span class="badge bg-{{ $group->is_current ? 'light text-dark' : 'secondary' }} ms-2">
            {{ $group->total_products }}
        </span>
    </div>
    
    @if($group->children->isNotEmpty())
        <div class="children ms-4">
            @foreach($group->children as $child)
                @include('catalogue.partials.group-item', [
                    'group' => $child,
                    'currentGroupId' => $currentGroupId
                ])
            @endforeach
        </div>
    @endif
</div>