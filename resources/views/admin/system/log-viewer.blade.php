@extends('admin.layouts.app')

@section('title', 'عرض Log - ' . $filename)

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-alt"></i> عرض Log: {{ $filename }}
        </h1>
        <div>
            <a href="{{ route('admin.system.download-log', $filename) }}" class="btn btn-success">
                <i class="fas fa-download"></i> تنزيل
            </a>
            <a href="{{ route('admin.system.maintenance') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right"></i> عودة
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="log-viewer" style="background-color: #1e1e1e; color: #d4d4d4; padding: 20px; border-radius: 5px; max-height: 800px; overflow-y: auto; font-family: 'Courier New', monospace; font-size: 12px;">
                @if(is_array($content) && count($content) > 0)
                    @foreach($content as $line)
                        <div class="log-line" style="margin-bottom: 5px; white-space: pre-wrap; word-wrap: break-word;">
                            @if(str_contains($line, 'ERROR'))
                                <span style="color: #f44336;">{{ $line }}</span>
                            @elseif(str_contains($line, 'WARNING'))
                                <span style="color: #ff9800;">{{ $line }}</span>
                            @elseif(str_contains($line, 'INFO'))
                                <span style="color: #2196f3;">{{ $line }}</span>
                            @elseif(str_contains($line, 'DEBUG'))
                                <span style="color: #9e9e9e;">{{ $line }}</span>
                            @else
                                {{ $line }}
                            @endif
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">الملف فارغ أو لا يحتوي على محتوى</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

