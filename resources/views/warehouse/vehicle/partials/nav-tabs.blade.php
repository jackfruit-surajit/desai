<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-transparent border-0 mb-0">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <ul class="nav nav-pills p-1 rounded" style="background: rgba(16, 28, 76, 0.6); border: 1px solid rgba(255, 255, 255, 0.15);">
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2 {{ request()->routeIs('warehouse-vehicle-fuel*') ? 'active bg-primary text-white fw-bold' : 'text-white-50' }}" 
                           href="{{ route('warehouse-vehicle-fuel-logs') }}">
                            <i class="fa fa-gas-pump me-2"></i> Fuel Tracking
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2 {{ request()->routeIs('warehouse-vehicle-daily-expense*') ? 'active bg-primary text-white fw-bold' : 'text-white-50' }}" 
                           href="{{ route('warehouse-vehicle-daily-expenses') }}">
                            <i class="fa fa-receipt me-2"></i> Daily Expenses
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-4 py-2 {{ request()->routeIs('warehouse-vehicle-repair*') ? 'active bg-primary text-white fw-bold' : 'text-white-50' }}" 
                           href="{{ route('warehouse-vehicle-repair-logs') }}">
                            <i class="fa fa-wrench me-2"></i> Repair & Service
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    @if(request()->routeIs('warehouse-vehicle-fuel*'))
                        <a href="{{ route('warehouse-vehicle-fuel-log-create') }}" class="btn btn-primary">
                            <i class="fa fa-plus me-1"></i> Add Fuel Entry
                        </a>
                    @elseif(request()->routeIs('warehouse-vehicle-daily-expense*'))
                        <a href="{{ route('warehouse-vehicle-daily-expense-create') }}" class="btn btn-primary">
                            <i class="fa fa-plus me-1"></i> Add Expense Entry
                        </a>
                    @elseif(request()->routeIs('warehouse-vehicle-repair*'))
                        <a href="{{ route('warehouse-vehicle-repair-log-create') }}" class="btn btn-primary">
                            <i class="fa fa-plus me-1"></i> Add Repair Entry
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
