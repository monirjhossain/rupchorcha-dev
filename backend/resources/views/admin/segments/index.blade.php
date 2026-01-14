@extends('layouts.admin')

@section('title', 'Customer Segments')

@section('content')
<div class="container-fluid" x-data="segmentManager()">
    <h2 class="mb-4">Customer Segments</h2>
    <button class="btn btn-primary mb-3" @click="openCreateModal">Create Segment</button>
    <div class="card mb-4">
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>User Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="segment in segments" :key="segment.id">
                        <tr>
                            <td x-text="segment.name"></td>
                            <td x-text="segment.description"></td>
                            <td>
                                <button class="btn btn-link p-0" @click="previewUsers(segment)">Preview</button>
                                <span x-text="segment.user_count ?? '-'" class="ml-2"></span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info" @click="editSegment(segment)">Edit</button>
                                <button class="btn btn-sm btn-danger" @click="deleteSegment(segment)">Delete</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div class="modal fade" tabindex="-1" role="dialog" :class="{show: showModal}" style="display: none;" x-show="showModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" x-text="modalTitle"></h5>
                    <button type="button" class="close" @click="closeModal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveSegment">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" x-model="form.name" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" x-model="form.description"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Rules</label>
                            <div class="mb-2">
                                <label>Min Orders</label>
                                <input type="number" class="form-control" x-model.number="form.rules.min_orders">
                            </div>
                            <div class="mb-2">
                                <label>Last Purchase (days ago)</label>
                                <input type="number" class="form-control" x-model.number="form.rules.last_purchase_days">
                            </div>
                            <div class="mb-2">
                                <label>Min Spent</label>
                                <input type="number" class="form-control" x-model.number="form.rules.min_spent">
                            </div>
                            <div class="mb-2">
                                <label>Location</label>
                                <input type="text" class="form-control" x-model="form.rules.location">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- User Preview Modal -->
    <div class="modal fade" tabindex="-1" role="dialog" :class="{show: showUserModal}" style="display: none;" x-show="showUserModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Segment Users</h5>
                    <button type="button" class="close" @click="closeUserModal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div x-show="loadingUsers">Loading...</div>
                    <table class="table table-sm table-bordered" x-show="!loadingUsers">
                        <thead><tr><th>Name</th><th>Email</th><th>Phone</th></tr></thead>
                        <tbody>
                            <template x-for="user in previewUsersList" :key="user.id">
                                <tr>
                                    <td x-text="user.name"></td>
                                    <td x-text="user.email"></td>
                                    <td x-text="user.phone"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    <div class="mt-2">
                        <span x-text="'Page ' + previewPage + ' of ' + previewLastPage"></span>
                        <button class="btn btn-sm btn-secondary ml-2" @click="prevPreviewPage" :disabled="previewPage <= 1">Prev</button>
                        <button class="btn btn-sm btn-secondary ml-2" @click="nextPreviewPage" :disabled="previewPage >= previewLastPage">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function segmentManager() {
    return {
        segments: @json($segments),
        showModal: false,
        showUserModal: false,
        modalTitle: '',
        form: { id: null, name: '', description: '', rules: {} },
        previewUsersList: [],
        previewPage: 1,
        previewLastPage: 1,
        loadingUsers: false,
        openCreateModal() {
            this.modalTitle = 'Create Segment';
            this.form = { id: null, name: '', description: '', rules: {} };
            this.showModal = true;
        },
        editSegment(segment) {
            this.modalTitle = 'Edit Segment';
            this.form = JSON.parse(JSON.stringify(segment));
            this.showModal = true;
        },
        closeModal() { this.showModal = false; },
        saveSegment() {
            let url = this.form.id ? `/admin/segments/${this.form.id}` : '/admin/segments';
            let method = this.form.id ? 'PUT' : 'POST';
            fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(this.form)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (this.form.id) {
                        let idx = this.segments.findIndex(s => s.id === this.form.id);
                        if (idx !== -1) this.segments[idx] = data.segment;
                    } else {
                        this.segments.unshift(data.segment);
                    }
                    this.closeModal();
                }
            });
        },
        deleteSegment(segment) {
            if (!confirm('Delete this segment?')) return;
            fetch(`/admin/segments/${segment.id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.segments = this.segments.filter(s => s.id !== segment.id);
                }
            });
        },
        previewUsers(segment) {
            this.loadingUsers = true;
            this.showUserModal = true;
            this.previewPage = 1;
            this.fetchPreviewUsers(segment, 1);
        },
        fetchPreviewUsers(segment, page) {
            fetch(`/admin/segments/${segment.id}/preview?per_page=10&page=${page}`)
                .then(res => res.json())
                .then(data => {
                    this.previewUsersList = data.users;
                    this.previewPage = data.current_page;
                    this.previewLastPage = data.last_page;
                    this.loadingUsers = false;
                });
        },
        nextPreviewPage() {
            if (this.previewPage < this.previewLastPage) {
                let segment = this.segments.find(s => s.id === this.form.id) || this.segments[0];
                this.fetchPreviewUsers(segment, this.previewPage + 1);
            }
        },
        prevPreviewPage() {
            if (this.previewPage > 1) {
                let segment = this.segments.find(s => s.id === this.form.id) || this.segments[0];
                this.fetchPreviewUsers(segment, this.previewPage - 1);
            }
        },
        closeUserModal() { this.showUserModal = false; }
    }
}
</script>
@endpush
