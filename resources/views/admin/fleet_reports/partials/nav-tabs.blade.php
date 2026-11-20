<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0 mb-0">
            <div class="card-body p-2 d-flex flex-wrap align-items-center justify-content-between gap-3">
                <ul class="nav nav-pills gap-1">
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 {{ request()->routeIs('admin-fleet-fuel*') ? 'active bg-primary text-white fw-bold shadow-sm' : 'text-dark' }}" 
                           href="{{ route('admin-fleet-fuel-reports') }}">
                            <i class="fa fa-gas-pump me-2"></i> Fuel Tracking Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 {{ request()->routeIs('admin-fleet-daily-expenses*') ? 'active bg-primary text-white fw-bold shadow-sm' : 'text-dark' }}" 
                           href="{{ route('admin-fleet-daily-expenses') }}">
                            <i class="fa fa-receipt me-2"></i> Daily Expense Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 {{ request()->routeIs('admin-fleet-repair*') ? 'active bg-primary text-white fw-bold shadow-sm' : 'text-dark' }}" 
                           href="{{ route('admin-fleet-repair-reports') }}">
                            <i class="fa fa-wrench me-2"></i> Repair & Service Reports
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2 me-2">
                    <span class="badge bg-light text-secondary border px-3 py-2 fs-6">
                        <i class="fa fa-eye me-1 text-primary"></i> View Only Mode
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
