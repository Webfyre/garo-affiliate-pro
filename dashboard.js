// ============================
// Garo Affiliate Pro – Dashboard JS
// ============================

document.addEventListener("DOMContentLoaded", function () {
  const dashboard = document.querySelector(".garo-affiliate-dashboard");
  if (!dashboard) return;

  const tabs = dashboard.querySelectorAll("[data-tab-target]");
  const tabContents = dashboard.querySelectorAll(".garo-tab-content");

  // Tab switching logic
  tabs.forEach(tab => {
    tab.addEventListener("click", () => {
      tabs.forEach(t => t.classList.remove("active"));
      tabContents.forEach(c => c.classList.remove("active"));

      tab.classList.add("active");
      const targetId = tab.dataset.tabTarget;
      const target = dashboard.querySelector(`#${targetId}`);
      if (target) target.classList.add("active");
    });
  });

  // Copy referral link
  const copyButtons = dashboard.querySelectorAll(".garo-copy-ref");
  copyButtons.forEach(btn => {
    btn.addEventListener("click", function () {
      const input = this.previousElementSibling;
      if (input && input.select) {
        input.select();
        document.execCommand("copy");
        this.innerText = "Copied!";
        setTimeout(() => {
          this.innerText = "Copy Link";
        }, 2000);
      }
    });
  });

  // Load stats via AJAX
  if (dashboard.classList.contains("ajax-enabled")) {
    fetchGaroAffiliateStats();
  }

  function fetchGaroAffiliateStats() {
    fetch(garo_affiliate_pro.ajax_url + '?action=garo_get_affiliate_dashboard', {
      credentials: 'same-origin'
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          updateStat("clicks", data.clicks);
          updateStat("sales", data.sales);
          updateStat("earnings", data.earnings);
        }
      })
      .catch(err => {
        console.error("Affiliate stats failed to load", err);
      });
  }

  function updateStat(type, value) {
    const el = dashboard.querySelector(`.garo-stat-${type}`);
    if (el) el.innerText = value;
  }
});
