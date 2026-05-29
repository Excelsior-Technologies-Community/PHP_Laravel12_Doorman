@if($invites->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 30px;">
                    <input type="checkbox" id="selectAllCheckbox" style="width: 18px; height: 18px;">
                </th>
                <th>ID</th>
                <th>Invite Code</th>
                <th>Created By</th>
                <th>Email</th>
                <th>Status</th>
                <th>Used / Max</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invites as $invite)
                @php
                    $used = $invite->used ?? $invite->uses ?? 0;
                    $maxUses = $invite->max_uses ?? $invite->max ?? 1;
                    $isRedeemed = $used >= $maxUses;
                    $isExpired = ($invite->valid_until && \Carbon\Carbon::parse($invite->valid_until)->isPast()) || ($invite->expires_at && \Carbon\Carbon::parse($invite->expires_at)->isPast());
                @endphp
                <tr>
                    <td>
                        <input type="checkbox" class="invite-checkbox" value="{{ $invite->id }}" onclick="toggleSelect({{ $invite->id }})">
                    </td>
                    <td>{{ $invite->id }}</td>
                    <td>
                        <span class="code-text">{{ $invite->code }}</span>
                    </td>
                    <td>{{ $invite->creator ? $invite->creator->name : 'System/Admin' }}</td>
                    <td>{{ $invite->for ?? 'Not specified' }}</td>
                    <td>
                        @if($isExpired)
                            <span class="badge badge-expired" style="background-color: #fecaca; color: #991b1b; padding: 0.25rem 0.5rem; rounded: 9999px; text-size: 0.75rem;">Expired</span>
                        @elseif($isRedeemed)
                            <span class="badge badge-redeemed">Redeemed</span>
                        @else
                            <span class="badge badge-active">Active</span>
                        @endif
                    </td>
                    <td>{{ $used }} / {{ $maxUses }}</td>
                    <td>{{ \Carbon\Carbon::parse($invite->created_at)->format('Y-m-d H:i') }}</td>
                    <td>
                        <div class="action-icons">
                            <span class="action-icon view" onclick="viewDetails({{ $invite->id }})">View</span>
                            <span class="action-icon delete" onclick="deleteInvite({{ $invite->id }}, '{{ $invite->code }}')">Delete</span>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="pagination">
        {{ $invites->appends(request()->query())->links() }}
    </div>

    <script>
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        if(selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.invite-checkbox');
                if(this.checked) {
                    checkboxes.forEach(cb => {
                        if(!cb.checked) {
                            cb.checked = true;
                            if(window.toggleSelect) toggleSelect(parseInt(cb.value));
                        }
                    });
                } else {
                    checkboxes.forEach(cb => {
                        if(cb.checked) {
                            cb.checked = false;
                            if(window.toggleSelect) toggleSelect(parseInt(cb.value));
                        }
                    });
                }
            });
        }
    </script>
@else
    <div class="empty-state">
        No invite codes found. Generate some invites from the dashboard!
    </div>
@endif