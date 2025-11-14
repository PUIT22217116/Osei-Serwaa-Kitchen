<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/header.php';

$db = new Database();
$total_visits = $db->getTotalSiteVisits();
$daily_visits = $db->getSiteVisitsByDate(30); // Last 30 days
?>

<style>
.analytics-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.analytics-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.analytics-header h1 {
    font-size: 2rem;
    margin: 0;
    color: #333;
}

.total-visits-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.total-visits-card h2 {
    font-size: 1rem;
    margin: 0 0 1rem 0;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.total-visits-card .number {
    font-size: 3.5rem;
    font-weight: bold;
    margin: 0;
}

.charts-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.chart-card {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.chart-card h3 {
    margin-top: 0;
    color: #333;
    border-bottom: 2px solid #667eea;
    padding-bottom: 1rem;
}

canvas {
    max-height: 300px;
}

.daily-visits-table {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    overflow-x: auto;
}

.daily-visits-table h3 {
    margin-top: 0;
    color: #333;
    border-bottom: 2px solid #667eea;
    padding-bottom: 1rem;
}

.visits-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95rem;
}

.visits-table thead {
    background: #f8f9fa;
}

.visits-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: #333;
    border-bottom: 2px solid #dee2e6;
}

.visits-table td {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #dee2e6;
}

.visits-table tbody tr:hover {
    background: #f8f9fa;
}

.visits-table .date {
    font-weight: 500;
    color: #667eea;
}

.visits-table .count {
    font-weight: 600;
    color: #764ba2;
    font-size: 1.1rem;
}

.empty-state {
    text-align: center;
    padding: 2rem;
    color: #999;
}

@media (max-width: 768px) {
    .charts-grid {
        grid-template-columns: 1fr;
    }
    
    .total-visits-card .number {
        font-size: 2.5rem;
    }
    
    .analytics-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
}
</style>

<div class="analytics-container">
    <div class="analytics-header">
        <h1>📊 Site Analytics</h1>
    </div>

    <!-- Total Visits Card -->
    <div class="total-visits-card">
        <h2>Total Site Views</h2>
        <p class="number"><?php echo number_format($total_visits); ?></p>
    </div>

    <!-- Charts Grid -->
    <div class="charts-grid">
        <!-- Line Chart: Visits Over Time -->
        <div class="chart-card">
            <h3>Views Trend (Last 30 Days)</h3>
            <canvas id="visitsLineChart"></canvas>
        </div>

        <!-- Bar Chart: Top Days by Visits -->
        <div class="chart-card">
            <h3>Top Performing Days</h3>
            <canvas id="visitsBarChart"></canvas>
        </div>
    </div>

    <!-- Daily Visits Table -->
    <div class="daily-visits-table">
        <h3>Daily Breakdown</h3>
        <?php if (!empty($daily_visits)): ?>
            <table class="visits-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Views</th>
                        <th>Day of Week</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($daily_visits as $day): ?>
                        <tr>
                            <td class="date"><?php echo date('M d, Y', strtotime($day['visit_date'])); ?></td>
                            <td class="count"><?php echo number_format($day['visit_count']); ?></td>
                            <td><?php echo date('l', strtotime($day['visit_date'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <p>No visit data available yet. Visits will start tracking once users access the site.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
    // Data from PHP
    const dailyVisits = <?php echo json_encode($daily_visits); ?>;
    const totalVisits = <?php echo $total_visits; ?>;

    if (dailyVisits.length > 0) {
        // Prepare data for charts
        const dates = dailyVisits.map(d => {
            const date = new Date(d.visit_date);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        });
        
        const counts = dailyVisits.map(d => parseInt(d.visit_count));

        // Line Chart
        const lineCtx = document.getElementById('visitsLineChart').getContext('2d');
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Daily Views',
                    data: counts,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Bar Chart (Top 10 Days)
        const top10 = dailyVisits.slice(0, 10);
        const topDates = top10.map(d => {
            const date = new Date(d.visit_date);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        });
        const topCounts = top10.map(d => parseInt(d.visit_count));

        const barCtx = document.getElementById('visitsBarChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: topDates,
                datasets: [{
                    label: 'Views',
                    data: topCounts,
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(118, 75, 162, 0.8)',
                        'rgba(237, 100, 166, 0.8)',
                        'rgba(255, 154, 158, 0.8)',
                        'rgba(250, 208, 196, 0.8)',
                        'rgba(255, 214, 165, 0.8)',
                        'rgba(254, 243, 199, 0.8)',
                        'rgba(202, 240, 248, 0.8)',
                        'rgba(155, 229, 155, 0.8)',
                        'rgba(171, 205, 239, 0.8)'
                    ],
                    borderColor: [
                        '#667eea',
                        '#764ba2',
                        '#ed64a6',
                        '#ff9a9e',
                        '#fad0c4',
                        '#ffd6a5',
                        '#fef3c7',
                        '#caf0f8',
                        '#9be59b',
                        '#abcdef'
                    ],
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
