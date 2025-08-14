(function () {
  let dailyChart = null;
  let billChart = null;

  const css = (name, fallback) => {
    const val = getComputedStyle(document.documentElement)
      .getPropertyValue(name)
      .trim();
    return val || fallback;
  };

  const colors = {
    primary: css("--primary", "rgb(99, 102, 241)"),
    success: css("--success", "rgb(16, 185, 129)"),
    warning: css("--warning", "rgb(245, 158, 11)"),
    danger: css("--danger", "rgb(239, 68, 68)"),
    info: css("--info", "rgb(6, 182, 212)"),
    secondary: css("--secondary", "rgb(107, 114, 128)"),
    gray600: css("--gray-600", "#6b7280"),
  };

  async function loadDashboardStats() {
    try {
      const response = await apiFetch("api/stats.php");
      if (!response.success) return;

      const data = response.data || {};

      // KPIs
      const $ = id => document.getElementById(id);
      if ($("activeUsers"))
        $("activeUsers").textContent = data.active_users || 0;
      if ($("todayRevenue"))
        $("todayRevenue").textContent = formatCurrency(
          data.today_revenue_amount || 0
        );
      if ($("newUsersToday"))
        $("newUsersToday").textContent = data.new_users_today || 0;
      if ($("totalBalance"))
        $("totalBalance").textContent = formatCurrency(
          data.total_users_balance || 0
        );

      // Charts
      const series = data.series || {};
      if (series.daily_transactions)
        renderDailyChart(series.daily_transactions);
      if (series.bill_distribution) renderBillChart(series.bill_distribution);
    } catch (err) {
      console.error("Failed to load dashboard stats:", err);
    }
  }

  function renderDailyChart(data) {
    const container = document.getElementById("dailyChart");
    if (!container) return;

    container.innerHTML = '<canvas id="dailyChartCanvas"></canvas>';
    const ctx = document.getElementById("dailyChartCanvas").getContext("2d");

    if (dailyChart) dailyChart.destroy();

    dailyChart = new Chart(ctx, {
      type: "line",
      data: {
        labels: data.map(d => d.date),
        datasets: [
          {
            label: "Daily Revenue",
            data: data.map(d => Number(d.amount || 0)),
            borderColor: colors.primary,
            backgroundColor: hexToRgba(colors.primary, 0.12),
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: colors.primary,
            pointBorderColor: "#fff",
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7,
            pointHoverBackgroundColor: colors.primary,
            pointHoverBorderColor: "#fff",
            pointHoverBorderWidth: 3,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: "rgba(0,0,0,0.8)",
            titleColor: "#fff",
            bodyColor: "#fff",
            borderColor: colors.primary,
            borderWidth: 1,
            cornerRadius: 8,
            displayColors: false,
            callbacks: {
              label: ctx => "Revenue: " + formatCurrency(ctx.parsed.y),
            },
          },
        },
        scales: {
          x: {
            grid: { display: false },
            border: { display: false },
            ticks: { color: colors.gray600, font: { size: 12 } },
          },
          y: {
            grid: { color: "rgba(0,0,0,0.06)", drawBorder: false },
            border: { display: false },
            ticks: {
              color: colors.gray600,
              font: { size: 12 },
              callback: value => formatCurrency(value),
            },
          },
        },
        interaction: { intersect: false, mode: "index" },
        elements: { point: { hoverRadius: 7 } },
      },
    });
  }

  function renderBillChart(data) {
    const container = document.getElementById("billChart");
    if (!container) return;

    container.innerHTML = '<canvas id="billChartCanvas"></canvas>';
    const ctx = document.getElementById("billChartCanvas").getContext("2d");
    if (billChart) billChart.destroy();

    const palette = [
      colors.primary,
      colors.success,
      colors.warning,
      colors.info,
      colors.danger,
      colors.secondary,
    ];

    const values = data.map(d => Number(d.value || 0));

    billChart = new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: data.map(d => d.label),
        datasets: [
          {
            data: values,
            backgroundColor: palette.slice(0, data.length),
            borderColor: "#fff",
            borderWidth: 3,
            hoverBorderWidth: 4,
            hoverOffset: 8,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: "60%",
        plugins: {
          legend: {
            position: "bottom",
            labels: {
              padding: 14,
              usePointStyle: true,
              pointStyle: "circle",
              font: { size: 12 },
              color: colors.gray600,
            },
          },
          tooltip: {
            backgroundColor: "rgba(0,0,0,0.8)",
            titleColor: "#fff",
            bodyColor: "#fff",
            borderColor: "rgba(255,255,255,0.1)",
            borderWidth: 1,
            cornerRadius: 8,
            displayColors: true,
            callbacks: {
              label: ctx => {
                const total = ctx.dataset.data.reduce((a, b) => a + b, 0) || 1;
                const val = ctx.parsed;
                const pct = ((val / total) * 100).toFixed(1);
                return `${ctx.label}: ${formatCurrency(val)} (${pct}%)`;
              },
            },
          },
        },
        animation: {
          animateRotate: true,
          animateScale: true,
          duration: 900,
          easing: "easeOutQuart",
        },
        interaction: { intersect: false },
      },
    });
  }

  function hexToRgba(color, alpha) {
    // Handle already-rgba colors
    if (color.startsWith("rgb")) {
      return color.replace("rgb", "rgba").replace(")", `, ${alpha})`);
    }
    // Handle hex #rrggbb
    const c = color.replace("#", "");
    if (c.length === 3) {
      const r = parseInt(c[0] + c[0], 16);
      const g = parseInt(c[1] + c[1], 16);
      const b = parseInt(c[2] + c[2], 16);
      return `rgba(${r}, ${g}, ${b}, ${alpha})`;
    }
    if (c.length === 6) {
      const r = parseInt(c.slice(0, 2), 16);
      const g = parseInt(c.slice(2, 4), 16);
      const b = parseInt(c.slice(4, 6), 16);
      return `rgba(${r}, ${g}, ${b}, ${alpha})`;
    }
    return color;
  }

  document.addEventListener("DOMContentLoaded", () => {
    loadDashboardStats();
  });
})();
