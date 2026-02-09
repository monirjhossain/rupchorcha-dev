@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Bulk SMS</h1>
        <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">Back to Users</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="font-weight-bold">Send Bulk SMS to Users</span>
            <small class="text-muted">Use carefully &mdash; charges apply per SMS.</small>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('users.bulk_sms') }}">
                @csrf

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="user_search">Filter Users</label>
                            <input type="text" id="user_search" class="form-control" placeholder="Search by name or email">
                            <small class="form-text text-muted">Type to quickly filter the user list below.</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="role_filter">Role</label>
                            <select id="role_filter" class="form-control">
                                <option value="">All roles</option>
                                <option value="customer">Customer</option>
                                <option value="admin">Admin</option>
                                <option value="super_admin">Super Admin</option>
                            </select>
                            <small class="form-text text-muted">Filter by user role (optional).</small>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="btn-group w-100 mb-3">
                            <button type="button" id="select_all_btn" class="btn btn-outline-primary btn-sm">Select All</button>
                            <button type="button" id="clear_selection_btn" class="btn btn-outline-secondary btn-sm">Clear</button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Send To</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="recipient_scope" id="scope_selected" value="selected" checked>
                        <label class="form-check-label" for="scope_selected">
                            Selected users only
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="recipient_scope" id="scope_all" value="all">
                        <label class="form-check-label" for="scope_all">
                            All users with phone number
                        </label>
                    </div>
                    <small class="form-text text-muted">Choose whether to send to everyone or only selected users below.</small>
                </div>

                <div id="user_table_wrapper" class="table-responsive mb-3">
                    <table class="table table-bordered table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width: 40px;"><input type="checkbox" id="select_all_users"></th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}" data-role="{{ $user->role }}">
                                    <td>
                                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="user-checkbox">
                                    </td>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $user->role ?? '')) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <small class="form-text text-muted mt-1" id="selected_count">0 users selected</small>
                </div>

                <input type="hidden" name="user_ids[]" value="all" id="user_ids_all" disabled>

                <hr>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="sms_api_key">SMS API Key</label>
                        <input type="text" name="sms_api_key" id="sms_api_key" class="form-control" placeholder="Enter SMS API Key" value="{{ old('sms_api_key') }}" required>
                        <small class="form-text text-muted">Use the API key provided by your SMS gateway provider.</small>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="sms_sender_id">Sender ID (optional)</label>
                        <input type="text" id="sms_sender_id" class="form-control" placeholder="e.g. RUPCHORCHA" disabled>
                        <small class="form-text text-muted">Configured in your SMS provider panel (shown here for reference).</small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="sms_message">Message</label>
                    <textarea name="sms_message" id="sms_message" class="form-control" rows="4" maxlength="480" required>{{ old('sms_message') }}</textarea>
                    <small class="form-text text-muted">
                        Keep it short and clear. Standard SMS segment is 160 characters.
                        <span id="char_count" class="ml-2">0 / 480</span>
                    </small>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted small">
                        This will send SMS only to users with a valid phone number.
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane mr-1"></i> Send Bulk SMS
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function() {
        var userSearch = document.getElementById('user_search');
        var roleFilter = document.getElementById('role_filter');
        var userTableWrapper = document.getElementById('user_table_wrapper');
        var selectedCount = document.getElementById('selected_count');
        var selectAllUsersCheckbox = document.getElementById('select_all_users');
        var selectAllBtn = document.getElementById('select_all_btn');
        var clearSelectionBtn = document.getElementById('clear_selection_btn');
        var scopeSelected = document.getElementById('scope_selected');
        var scopeAll = document.getElementById('scope_all');
        var userIdsAll = document.getElementById('user_ids_all');
        var smsMessage = document.getElementById('sms_message');
        var charCount = document.getElementById('char_count');

        function updateSelectedCount() {
            if (!userTableWrapper || !selectedCount) return;
            var checkboxes = userTableWrapper.querySelectorAll('tbody .user-checkbox');
            var count = 0;
            checkboxes.forEach(function(cb) {
                if (cb.checked) {
                    count++;
                }
            });
            selectedCount.textContent = count + ' users selected';
        }

        function applyFilters() {
            if (!userTableWrapper) return;
            var term = (userSearch.value || '').toLowerCase();
            var role = roleFilter.value;
            var rows = userTableWrapper.querySelectorAll('tbody tr');

            rows.forEach(function(row) {
                var name = row.getAttribute('data-name') || '';
                var email = row.getAttribute('data-email') || '';
                var rowRole = row.getAttribute('data-role') || '';

                var matchesSearch = !term || name.indexOf(term) !== -1 || email.indexOf(term) !== -1;
                var matchesRole = !role || rowRole === role;

                row.style.display = (matchesSearch && matchesRole) ? '' : 'none';
            });
        }

        if (userSearch) {
            userSearch.addEventListener('input', applyFilters);
        }

        if (roleFilter) {
            roleFilter.addEventListener('change', applyFilters);
        }

        if (userTableWrapper) {
            userTableWrapper.addEventListener('change', function(e) {
                if (e.target.classList.contains('user-checkbox')) {
                    updateSelectedCount();
                }
            });
        }

        if (selectAllUsersCheckbox && userTableWrapper) {
            selectAllUsersCheckbox.addEventListener('change', function() {
                var checked = this.checked;
                var rows = userTableWrapper.querySelectorAll('tbody tr');
                rows.forEach(function(row) {
                    if (row.style.display === 'none') return;
                    var cb = row.querySelector('.user-checkbox');
                    if (cb && !cb.disabled) {
                        cb.checked = checked;
                    }
                });
                updateSelectedCount();
            });
        }

        if (selectAllBtn && userTableWrapper) {
            selectAllBtn.addEventListener('click', function() {
                var rows = userTableWrapper.querySelectorAll('tbody tr');
                rows.forEach(function(row) {
                    if (row.style.display === 'none') return;
                    var cb = row.querySelector('.user-checkbox');
                    if (cb && !cb.disabled) {
                        cb.checked = true;
                    }
                });
                if (selectAllUsersCheckbox && !selectAllUsersCheckbox.disabled) {
                    selectAllUsersCheckbox.checked = true;
                }
                updateSelectedCount();
            });
        }

        if (clearSelectionBtn && userTableWrapper) {
            clearSelectionBtn.addEventListener('click', function() {
                var rows = userTableWrapper.querySelectorAll('tbody tr');
                rows.forEach(function(row) {
                    var cb = row.querySelector('.user-checkbox');
                    if (cb) {
                        cb.checked = false;
                    }
                });
                if (selectAllUsersCheckbox) {
                    selectAllUsersCheckbox.checked = false;
                }
                updateSelectedCount();
            });
        }

        function updateScope() {
            if (!userTableWrapper || !userIdsAll) return;
            var checkboxes = userTableWrapper.querySelectorAll('.user-checkbox');

            if (scopeAll && scopeAll.checked) {
                // Disable per-user selection, enable hidden "all" input
                checkboxes.forEach(function(cb) {
                    cb.checked = false;
                    cb.disabled = true;
                });
                userIdsAll.disabled = false;
                if (selectAllUsersCheckbox) {
                    selectAllUsersCheckbox.checked = false;
                    selectAllUsersCheckbox.disabled = true;
                }
            } else {
                // Enable per-user selection, disable hidden "all" input
                checkboxes.forEach(function(cb) {
                    cb.disabled = false;
                });
                userIdsAll.disabled = true;
                if (selectAllUsersCheckbox) {
                    selectAllUsersCheckbox.disabled = false;
                }
            }
            updateSelectedCount();
        }

        if (scopeSelected) {
            scopeSelected.addEventListener('change', updateScope);
        }
        if (scopeAll) {
            scopeAll.addEventListener('change', updateScope);
        }

        if (smsMessage && charCount) {
            var updateCharCount = function() {
                var len = smsMessage.value.length;
                charCount.textContent = len + ' / 480';
            };
            smsMessage.addEventListener('input', updateCharCount);
            updateCharCount();
        }

        // Initialize on page load
        updateSelectedCount();
        applyFilters();
        updateScope();
    })();
</script>
@endpush
