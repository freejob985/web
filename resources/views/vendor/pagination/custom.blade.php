@if ($paginator->hasPages())
    <nav aria-label="Pagination Navigation" dir="rtl">
        <ul class="pagination pagination-custom justify-content-center mb-0">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">
                        <i class="fas fa-angle-double-right"></i>
                        <span class="d-none d-sm-inline ms-1">السابق</span>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <i class="fas fa-angle-double-right"></i>
                        <span class="d-none d-sm-inline ms-1">السابق</span>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        <span class="d-none d-sm-inline me-1">التالي</span>
                        <i class="fas fa-angle-double-left"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">
                        <span class="d-none d-sm-inline me-1">التالي</span>
                        <i class="fas fa-angle-double-left"></i>
                    </span>
                </li>
            @endif
        </ul>
        
        {{-- Results Info --}}
        <div class="pagination-info text-center mt-3">
            <p class="text-muted mb-0">
                <i class="fas fa-info-circle me-1"></i>
                عرض 
                <strong>{{ $paginator->firstItem() ?? 0 }}</strong>
                إلى 
                <strong>{{ $paginator->lastItem() ?? 0 }}</strong>
                من إجمالي 
                <strong>{{ $paginator->total() }}</strong>
                نتيجة
            </p>
        </div>
    </nav>

    <style>
        /* Custom Pagination Styles */
        .pagination-custom {
            gap: 8px;
            flex-wrap: wrap;
        }

        .pagination-custom .page-item {
            margin: 0;
        }

        .pagination-custom .page-link {
            border: 2px solid #e0e6ed;
            border-radius: 10px;
            color: #495057;
            font-weight: 600;
            padding: 10px 16px;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            min-width: 45px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pagination-custom .page-link:hover {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            border-color: #3498db;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
        }

        .pagination-custom .page-item.active .page-link {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border-color: #28a745;
            color: white;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
            transform: scale(1.1);
        }

        .pagination-custom .page-item.disabled .page-link {
            background: #f8f9fa;
            border-color: #e9ecef;
            color: #adb5bd;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .pagination-custom .page-link i {
            font-size: 14px;
        }

        .pagination-info {
            animation: fadeIn 0.5s ease;
            padding: 12px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            margin-top: 15px;
            border: 1px solid #dee2e6;
        }

        .pagination-info p {
            margin: 0;
            font-size: 14px;
            letter-spacing: 0.3px;
        }

        .pagination-info strong {
            color: #28a745;
            font-size: 15px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .pagination-custom .page-link {
                padding: 8px 12px;
                font-size: 13px;
                min-width: 38px;
            }
            
            .pagination-info p {
                font-size: 12px;
            }
        }
    </style>
@endif
