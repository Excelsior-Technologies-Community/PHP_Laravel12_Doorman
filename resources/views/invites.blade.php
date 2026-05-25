<!DOCTYPE html>
<html>
<head>
    <title>Invite Dashboard | Laravel 12 Doorman</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background: #f5f7fa;
            padding: 30px 20px;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .dashboard-header {
            background: #1a1a2e;
            color: white;
            padding: 25px 30px;
        }

        .dashboard-header h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .dashboard-header p {
            color: #aaa;
            font-size: 14px;
        }

        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px 30px;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }

        .stat-card {
            background: white;
            padding: 15px 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .stat-label {
            font-size: 13px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a2e;
        }

        .chart-section {
            padding: 20px 30px;
            background: white;
            border-bottom: 1px solid #e9ecef;
        }

        .chart-container {
            max-width: 600px;
            margin: 0 auto;
        }

        .chart-title {
            font-size: 14px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 15px;
            text-align: center;
        }

        .chart-bars {
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            height: 200px;
            gap: 10px;
        }

        .chart-bar-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .chart-bar {
            width: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 4px 4px 0 0;
            transition: height 0.3s;
            min-height: 2px;
        }

        .chart-label {
            font-size: 11px;
            color: #6c757d;
            text-align: center;
        }

        .chart-value {
            font-size: 12px;
            font-weight: 600;
            color: #495057;
        }

        .action-buttons {
            padding: 15px 30px;
            background: white;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5a67d8;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        .search-section {
            padding: 0 30px 20px 30px;
            background: white;
        }

        .search-box {
            position: relative;
            max-width: 400px;
        }

        .search-box input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .search-box input:focus {
            outline: none;
            border-color: #667eea;
        }

        .table-container {
            padding: 0 30px 30px 30px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 15px 12px;
            background: #f8f9fa;
            font-weight: 600;
            font-size: 13px;
            color: #495057;
            border-bottom: 2px solid #e9ecef;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #e9ecef;
            font-size: 14px;
            color: #212529;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-active {
            background: #d4edda;
            color: #155724;
        }

        .badge-redeemed {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-expired {
            background: #fff3cd;
            color: #856404;
        }

        .code-text {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            background: #f8f9fa;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 13px;
        }

        .checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .action-icons {
            display: flex;
            gap: 8px;
        }

        .action-icon {
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            transition: all 0.2s;
        }

        .action-icon.view {
            background: #17a2b8;
            color: white;
        }

        .action-icon.view:hover {
            background: #138496;
        }

        .action-icon.delete {
            background: #dc3545;
            color: white;
        }

        .action-icon.delete:hover {
            background: #c82333;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 8px;
        }

        .pagination a, .pagination span {
            padding: 8px 14px;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            text-decoration: none;
            color: #667eea;
            font-size: 14px;
        }

        .pagination .active span {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s;
        }

        .modal-content {
            background: white;
            margin: 50px auto;
            padding: 0;
            width: 90%;
            max-width: 500px;
            border-radius: 12px;
            animation: slideIn 0.3s;
        }

        .modal-header {
            padding: 20px 25px;
            background: #1a1a2e;
            color: white;
            border-radius: 12px 12px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
        }

        .close {
            color: white;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #ddd;
        }

        .modal-body {
            padding: 25px;
        }

        .detail-row {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .detail-label {
            font-weight: 600;
            width: 120px;
            color: #495057;
        }

        .detail-value {
            flex: 1;
            color: #212529;
            word-break: break-all;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .selected-info {
            font-size: 13px;
            color: #667eea;
        }

        @media (max-width: 768px) {
            .dashboard-stats {
                grid-template-columns: repeat(2, 1fr);
            }
            .table-container {
                padding: 0 15px 15px 15px;
            }
            .action-buttons {
                flex-direction: column;
                align-items: stretch;
            }
            .btn-group {
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-actions">
            <div>
                <h1>Invite Dashboard</h1>
                <p>Manage and monitor all invite codes</p>
            </div>
            <a href="/" class="btn btn-secondary" style="float: right;">← Back to Dashboard</a>
        </div>
    </div>

    <div class="dashboard-stats">
        <div class="stat-card">
            <div class="stat-label">Total Invites</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Active</div>
            <div class="stat-value">{{ $stats['active'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Redeemed</div>
            <div class="stat-value">{{ $stats['redeemed'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Expired</div>
            <div class="stat-value">{{ $stats['expired'] }}</div>
        </div>
    </div>

    <div class="chart-section">
        <div class="chart-title"> Invites Generated (Last 7 Days)</div>
        <div class="chart-container">
            <div class="chart-bars" id="chartBars"></div>
        </div>
    </div>

    <div class="action-buttons">
        <div class="btn-group">
            <button class="btn btn-danger" id="bulkDeleteBtn" style="display: none;">
                 Delete Selected
            </button>
            <button class="btn btn-success" id="selectAllBtn">
                 Select All
            </button>
            <button class="btn btn-secondary" id="clearSelectionBtn">
                 Clear Selection
            </button>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary" id="exportBtn">
                 Export to CSV
            </button>
        </div>
    </div>

    <div class="search-section">
        <div class="search-box">
            <input 
                type="text" 
                id="searchInput" 
                placeholder=" Search by code, email, or date..." 
                autocomplete="off"
            >
        </div>
        <div id="selectedInfo" class="selected-info" style="margin-top: 10px;"></div>
    </div>

    <div class="table-container">
        <div id="invitesTable">
            @include('partials.invites-table', ['invites' => $invites])
        </div>
    </div>
</div>

<!-- Modal -->
<div id="inviteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Invite Details</h3>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body" id="modalBody">
            Loading...
        </div>
    </div>
</div>

<script>
    let selectedIds = new Set();
    let searchTimeout;

    // Render chart
    const chartData = @json($stats['last7Days']);
    const maxCount = Math.max(...chartData.map(d => d.count), 1);
    
    const chartBars = document.getElementById('chartBars');
    chartData.forEach(item => {
        const height = (item.count / maxCount) * 150;
        const barDiv = document.createElement('div');
        barDiv.className = 'chart-bar-item';
        barDiv.innerHTML = `
            <div class="chart-bar" style="height: ${height}px;"></div>
            <div class="chart-value">${item.count}</div>
            <div class="chart-label">${item.date}</div>
        `;
        chartBars.appendChild(barDiv);
    });

    function updateSelectedInfo() {
        const infoDiv = document.getElementById('selectedInfo');
        const bulkBtn = document.getElementById('bulkDeleteBtn');
        if (selectedIds.size > 0) {
            infoDiv.innerHTML = ` ${selectedIds.size} invite(s) selected`;
            bulkBtn.style.display = 'inline-flex';
        } else {
            infoDiv.innerHTML = '';
            bulkBtn.style.display = 'none';
        }
        
        // Update checkboxes
        document.querySelectorAll('.invite-checkbox').forEach(cb => {
            cb.checked = selectedIds.has(parseInt(cb.value));
        });
    }

    // Select All
    document.getElementById('selectAllBtn').addEventListener('click', () => {
        document.querySelectorAll('.invite-checkbox').forEach(cb => {
            selectedIds.add(parseInt(cb.value));
        });
        updateSelectedInfo();
    });

    // Clear Selection
    document.getElementById('clearSelectionBtn').addEventListener('click', () => {
        selectedIds.clear();
        updateSelectedInfo();
    });

    // Bulk Delete
    document.getElementById('bulkDeleteBtn').addEventListener('click', () => {
        if (selectedIds.size === 0) return;
        
        if (confirm(`Are you sure you want to delete ${selectedIds.size} invite(s)?`)) {
            fetch('/invites/bulk-delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ids: Array.from(selectedIds) })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    selectedIds.clear();
                    location.reload();
                } else {
                    alert('Error deleting invites');
                }
            });
        }
    });

    // Export
    document.getElementById('exportBtn').addEventListener('click', () => {
        const search = document.getElementById('searchInput').value;
        window.location.href = '/invites/export?search=' + encodeURIComponent(search);
    });

    // View Details
    function viewDetails(id) {
        fetch('/invites/' + id + '/details')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modalBody = document.getElementById('modalBody');
                    modalBody.innerHTML = `
                        <div class="detail-row">
                            <div class="detail-label">ID:</div>
                            <div class="detail-value">${data.invite.id}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Code:</div>
                            <div class="detail-value"><strong>${data.invite.code}</strong></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Email:</div>
                            <div class="detail-value">${data.invite.email}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Max Uses:</div>
                            <div class="detail-value">${data.invite.max_uses}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Used:</div>
                            <div class="detail-value">${data.invite.used}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Expires At:</div>
                            <div class="detail-value">${data.invite.expires_at}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Created At:</div>
                            <div class="detail-value">${data.invite.created_at}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Updated At:</div>
                            <div class="detail-value">${data.invite.updated_at}</div>
                        </div>
                    `;
                    document.getElementById('inviteModal').style.display = 'block';
                }
            });
    }

    // Delete single
    function deleteInvite(id, code) {
        if (confirm(`Are you sure you want to delete invite: ${code}?`)) {
            fetch('/invites/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error deleting invite');
                }
            });
        }
    }

    // Toggle checkbox selection
    function toggleSelect(id) {
        if (selectedIds.has(id)) {
            selectedIds.delete(id);
        } else {
            selectedIds.add(id);
        }
        updateSelectedInfo();
    }

    // Modal close
    document.querySelector('.close').addEventListener('click', () => {
        document.getElementById('inviteModal').style.display = 'none';
    });
    
    window.addEventListener('click', (e) => {
        if (e.target == document.getElementById('inviteModal')) {
            document.getElementById('inviteModal').style.display = 'none';
        }
    });

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const search = this.value;
            const tableContainer = document.getElementById('invitesTable');
            
            tableContainer.innerHTML = '<div class="loading" style="text-align:center;padding:40px;">Searching...</div>';
            
            fetch('/search-invites?search=' + encodeURIComponent(search))
                .then(response => response.text())
                .then(html => {
                    tableContainer.innerHTML = html;
                    selectedIds.clear();
                    updateSelectedInfo();
                })
                .catch(error => {
                    tableContainer.innerHTML = '<div class="empty-state">Error loading results</div>';
                });
        }, 300);
    });
</script>

</body>
</html>