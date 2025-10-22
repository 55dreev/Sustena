@php use Illuminate\Support\Str; @endphp

@foreach($users as $u)
    @php
        $name   = $u->display_name ?? $u->name ?? 'User';
        $initial = strtoupper(Str::substr($name, 0, 1));
    @endphp
    <div class="user-item">
        <div class="user-avatar">{{ $initial }}</div>
        <div class="user-info">
            <div class="user-name">{{ $name }}</div>
            <div class="user-status">Online</div>
        </div>
    </div>
@endforeach
