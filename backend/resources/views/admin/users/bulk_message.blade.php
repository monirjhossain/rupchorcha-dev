@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Bulk Message Users</h1>
    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('users.bulk_message') }}">
                @csrf
                <div class="form-group">
                    <label for="bulk_users">Select Users</label>
                    <select name="user_ids[]" id="bulk_users" class="form-control" multiple required>
                        <option value="all">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="bulk_message_type">Type</label>
                    <select name="message_type" id="bulk_message_type" class="form-control" required onchange="document.getElementById('smsApiKeyGroup').style.display = this.value === 'sms' ? 'block' : 'none';">
                        <option value="email">Email</option>
                        <option value="sms">SMS</option>
                    </select>
                </div>
                <div class="form-group" id="smsApiKeyGroup" style="display:none;">
                    <label for="sms_api_key">SMS API Key</label>
                    <input type="text" name="sms_api_key" id="sms_api_key" class="form-control" placeholder="Enter SMS API Key">
                </div>
                <div class="form-group">
                    <label for="bulk_message">Message</label>
                    <textarea name="message" id="bulk_message" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-success">Send</button>
            </form>
        </div>
    </div>
</div>
@endsection
