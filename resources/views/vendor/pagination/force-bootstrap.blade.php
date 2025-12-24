@if ($paginator->hasPages())
    @include('pagination::bootstrap-4')
@else
    <nav>
        <ul class="pagination justify-content-end">
            <li class="page-item active disabled">
                <span class="page-link">1</span>
            </li>
        </ul>
    </nav>
@endif
