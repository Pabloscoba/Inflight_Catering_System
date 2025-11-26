@extends('layouts.app')

@section('page-title', 'Database Backup')

@section('content')
<div style="padding: 32px;">
    <!-- Page Header -->
    <div style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: #1a202c; margin-bottom: 8px;">Database Backup</h1>
            <p style="color: #718096;">Create and manage database backups</p>
        </div>
        <form action="{{ route('admin.backup.create') }}" method="POST">
            @csrf
            <button 
                type="submit"
                style="background: #10b981; color: white; padding: 12px 24px; border-radius: 6px; font-weight: 600; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px;"
            >
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create New Backup
            </button>
        </form>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div style="background: #10b981; color: white; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="background: #ef4444; color: white; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
        {{ session('error') }}
    </div>
    @endif

    <!-- Info Box -->
    <div style="background: #eff6ff; border-left: 4px solid #3b82f6; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
        <div style="display: flex; gap: 12px;">
            <svg style="width: 24px; height: 24px; color: #3b82f6; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h3 style="font-weight: 600; color: #1e40af; margin-bottom: 4px;">Important Information</h3>
                <p style="color: #1e40af; font-size: 14px;">
                    Database backups are stored in <code style="background: white; padding: 2px 6px; border-radius: 4px;">storage/app/backups</code>. 
                    It's recommended to download and store backups in a secure location regularly.
                </p>
            </div>
        </div>
    </div>

    <!-- Backups List -->
    <div class="card">
        @if($backups->isEmpty())
        <div style="text-align: center; padding: 64px 32px;">
            <svg style="width: 64px; height: 64px; color: #d1d5db; margin: 0 auto 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
            </svg>
            <h3 style="font-size: 18px; font-weight: 600; color: #6b7280; margin-bottom: 8px;">No backups found</h3>
            <p style="color: #9ca3af; font-size: 14px;">Create your first database backup to get started.</p>
        </div>
        @else
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #374151; font-size: 13px;">Backup File</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #374151; font-size: 13px;">Size</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #374151; font-size: 13px;">Created Date</th>
                        <th style="padding: 12px 16px; text-align: right; font-weight: 600; color: #374151; font-size: 13px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($backups as $backup)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 16px; color: #1f2937;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <svg style="width: 20px; height: 20px; color: #6b7280;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                                </svg>
                                <span style="font-family: monospace; font-size: 13px;">{{ $backup['name'] }}</span>
                            </div>
                        </td>
                        <td style="padding: 16px; color: #6b7280; font-size: 14px;">
                            {{ $backup['size'] }}
                        </td>
                        <td style="padding: 16px; color: #6b7280; font-size: 14px;">
                            {{ $backup['date'] }}
                        </td>
                        <td style="padding: 16px; text-align: right;">
                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                <!-- Download Button -->
                                <a 
                                    href="{{ route('admin.backup.download', $backup['name']) }}"
                                    style="background: #3b82f6; color: white; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;"
                                >
                                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Download
                                </a>

                                <!-- Delete Form -->
                                <form action="{{ route('admin.backup.delete', $backup['name']) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this backup?');">
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit"
                                        style="background: #ef4444; color: white; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 500; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;"
                                    >
                                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <!-- Warning Box -->
    <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 16px; border-radius: 8px; margin-top: 24px;">
        <div style="display: flex; gap: 12px;">
            <svg style="width: 24px; height: 24px; color: #d97706; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <h3 style="font-weight: 600; color: #92400e; margin-bottom: 4px;">Backup Best Practices</h3>
                <ul style="color: #92400e; font-size: 14px; margin: 0; padding-left: 20px;">
                    <li>Create backups regularly (daily or weekly depending on data changes)</li>
                    <li>Download and store backups in multiple secure locations</li>
                    <li>Test backup restoration periodically to ensure data integrity</li>
                    <li>Keep at least 3 recent backups before deleting old ones</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
