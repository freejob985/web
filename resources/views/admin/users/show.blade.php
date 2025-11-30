@extends('admin.layouts.app')

@section('content')
<div class="flex space-between mb-3">
  <strong>المستخدم: {{ $user->name }}</strong>
  <a href="{{ route('admin.users.index') }}" class="btn">عودة</a>
</div>
<div class="grid grid-3">
  <div class="card">
    <div class="mb-2">البريد: <strong>{{ $user->email }}</strong></div>
    <div class="mb-2">الهاتف: <strong>{{ $user->phone }}</strong></div>
    <div class="mb-2">أنشئ في: <strong>{{ $user->created_at }}</strong></div>
  </div>
  <div class="card" style="grid-column: span 2;">
    <div class="mb-3"><strong>عناوين المستخدم</strong></div>
    @forelse($user->addresses as $a)
      <div class="mb-2">{{ $a->full_address }} @if($a->is_default) <span class="muted">(افتراضي)</span>@endif</div>
    @empty
      <div class="muted">لا توجد عناوين</div>
    @endforelse
  </div>
</div>
@endsection
