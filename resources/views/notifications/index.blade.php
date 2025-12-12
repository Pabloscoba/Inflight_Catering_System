@extends('layouts.app')

@section('page-title', 'Notifications')
@section('page-description', 'View and manage your notifications')

@section('content')
<style>
    .notification-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        gap: 16px;
        align-items: start;
    }
    .notification-card:hover {
        background: #f8f9fa;
        border-color: #0066cc;
    }
    .notification-card.unread {
        background: #f8f9ff;
        border-left: 4px solid #0066cc;
    }
    .notification-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .notification-content {
        flex: 1;
        min-width: 0;
    }
    .notification-title {
        font-size: 15px;
        font-weight: 600;
        color: #212529;
        margin-bottom: 4px;
    }
    .notification-message {
        font-size: 14px;
        color: #6c757d;
        line-height: 1.5;
        margin-bottom: 8px;
    }
    .notification-time {
        font-size: 12px;
        color: #adb5bd;
    }
    .notification-actions {
        display: flex;
        gap: 8px;
        margin-top: 8px;
    }
    .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-primary-sm {
        background: #0066cc;
        color: white;
    }
    .btn-primary-sm:hover {
        background: #0052a3;
    }
    .btn-ghost-sm {
        background: transparent;
        color: #6c757d;
    }
    .btn-ghost-sm:hover {
        background: #f1f3f5;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }
    .empty-state svg {
        width: 80px;
        height: 80px;
        margin-bottom: 16px;
        color: #dee2e6;
    }
    .filter-tabs {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
        border-bottom: 1px solid #e9ecef;
    }
    .filter-tab {
        padding: 12px 16px;
        font-size: 14px;
        font-weight: 500;
        color: #6c757d;
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
    }
    .filter-tab:hover {
        color: #212529;
    }
    .filter-tab.active {
        color: #0066cc;
        border-bottom-color: #0066cc;
    }
</style>

<div style="max-width: 900px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 4px;">All Notifications</h2>
            <p style="color: #6c757d; font-size: 14px;">Stay updated with your workflow activities</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <form method="POST" action="{{ route('notifications.mark-all-read') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn-sm btn-ghost-sm">Mark all as read</button>
            </form>
            <form method="POST" action="{{ route('notifications.clear-read') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn-sm btn-ghost-sm">Clear read</button>
            </form>
        </div>
    </div>

    <div class="filter-tabs">
        <button class="filter-tab active" onclick="filterNotifications('all')">
            All ({{ $notifications->total() }})
        </button>
        <button class="filter-tab" onclick="filterNotifications('unread')">
            Unread ({{ auth()->user()->unreadNotifications->count() }})
        </button>
        <button class="filter-tab" onclick="filterNotifications('read')">
            Read ({{ auth()->user()->readNotifications()->count() }})
        </button>
    </div>

    @if($notifications->isEmpty())
    <div class="empty-state">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 8px;">No notifications</h3>
        <p>You're all caught up! We'll notify you when something new happens.</p>
    </div>
    @else
    <div id="notifications-container">
        @foreach($notifications as $notification)
        @php
            $data = $notification->data;
            $isUnread = is_null($notification->read_at);
            
            $iconColors = [
                'blue' => '#0066cc',
                'green' => '#28a745',
                'red' => '#dc3545',
                'orange' => '#fd7e14',
                'purple' => '#6f42c1'
            ];
            
            $color = $iconColors[$data['color'] ?? 'blue'] ?? '#6c757d';
        @endphp
        
        <div class="notification-card {{ $isUnread ? 'unread' : '' }}" data-read="{{ $isUnread ? 'false' : 'true' }}" onclick="handleNotificationClick('{{ $notification->id }}', '{{ $data['action_url'] ?? '#' }}')">
            <div class="notification-icon" style="background: {{ $color }}15;">
                <svg style="width: 24px; height: 24px; color: {{ $color }};" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z"/>
                </svg>
            </div>
            <div class="notification-content">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <h3 class="notification-title">{{ $data['title'] ?? 'Notification' }}</h3>
                    @if($isUnread)
                    <span style="width: 10px; height: 10px; background: #0066cc; border-radius: 50%; display: inline-block; margin-top: 4px;"></span>
                    @endif
                </div>
                <p class="notification-message">{{ $data['message'] ?? 'No message' }}</p>
                <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                
                <div class="notification-actions" onclick="event.stopPropagation();">
                    @if($isUnread)
                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-sm btn-ghost-sm">Mark as read</button>
                    </form>
                    @endif
                    <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-sm btn-ghost-sm">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
    <div style="margin-top: 24px; display: flex; justify-content: center;">
        {{ $notifications->links() }}
    </div>
    @endif
    @endif
</div>

<script>
    function filterNotifications(filter) {
        // Update active tab
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        event.target.classList.add('active');
        
        // Filter notifications
        const notifications = document.querySelectorAll('.notification-card');
        notifications.forEach(card => {
            const isUnread = card.dataset.read === 'false';
            if (filter === 'all') {
                card.style.display = 'flex';
            } else if (filter === 'unread') {
                card.style.display = isUnread ? 'flex' : 'none';
            } else if (filter === 'read') {
                card.style.display = !isUnread ? 'flex' : 'none';
            }
        });
    }
    
    function handleNotificationClick(notificationId, actionUrl) {
        // Mark as read and redirect
        fetch(`/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(() => {
            if (actionUrl && actionUrl !== '#') {
                window.location.href = actionUrl;
            } else {
                location.reload();
            }
        });
    }
</script>
@endsection
