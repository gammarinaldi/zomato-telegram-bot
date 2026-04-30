<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Monitoring Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #f82a47;
            --bg: #0f172a;
            --card: rgba(30, 41, 59, 0.7);
            --text: #f1f5f9;
            --accent: #38bdf8;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
            padding: 2rem;
            background-image: radial-gradient(circle at top right, #1e293b 0%, #0f172a 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 600;
            background: linear-gradient(to right, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .card {
            background: var(--card);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            color: var(--accent);
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.05em;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 0.9rem;
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        .badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success { background: rgba(34, 197, 94, 0.2); color: #4ade80; }
        .badge-error { background: rgba(239, 68, 68, 0.2); color: #f87171; }
        .badge-info { background: rgba(59, 130, 246, 0.2); color: #60a5fa; }

        pre {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            background: rgba(0, 0, 0, 0.2);
            padding: 0.5rem;
            border-radius: 0.25rem;
            cursor: help;
        }

        pre:hover {
            white-space: pre-wrap;
            max-width: 600px;
        }

        .pagination {
            margin-top: 2rem;
            display: flex;
            gap: 0.5rem;
        }

        .pagination a {
            color: var(--text);
            text-decoration: none;
            padding: 0.5rem 1rem;
            background: var(--card);
            border-radius: 0.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .pagination a:hover {
            background: var(--primary);
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Zomato Bot <span style="font-weight: 300; font-size: 1.5rem; opacity: 0.7;">| Monitoring</span></h1>
            <div class="stats">
                <span class="badge badge-info">Live Logs</span>
            </div>
        </header>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>User</th>
                        <th>Method</th>
                        <th>URL</th>
                        <th>IP</th>
                        <th>Status</th>
                        <th>Payload</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('H:i:s') }}</td>
                        <td>{{ $log->user_id ?? 'Guest' }}</td>
                        <td><span class="badge badge-info">{{ $log->method }}</span></td>
                        <td>{{ Str::limit($log->url, 30) }}</td>
                        <td>{{ $log->ip_address }}</td>
                        <td>
                            <span class="badge {{ $log->response_status < 400 ? 'badge-success' : 'badge-error' }}">
                                {{ $log->response_status }}
                            </span>
                        </td>
                        <td><pre>{{ json_encode($log->body) }}</pre></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $logs->links() }}
        </div>
    </div>
</body>
</html>
