// View: Notifications Center Drawer

import { state } from '../models/State.js';

export function renderNotifications() {
  if (!state.currentUser) return;

  const userNotifs = state.notifications.filter(n => n.userId === state.currentUser.id || state.currentUser.role === 'admin_systeme');
  const unreadCount = userNotifs.filter(n => !n.isRead).length;

  const badgeEl = document.getElementById('notifBadgeCount');
  if (badgeEl) {
    badgeEl.textContent = unreadCount;
    badgeEl.style.display = unreadCount > 0 ? 'flex' : 'none';
  }

  const listEl = document.getElementById('notifList');
  if (listEl) {
    if (userNotifs.length === 0) {
      listEl.innerHTML = '<div style="padding: 1rem; text-align: center; color: var(--text-muted); font-size: 0.82rem;">Aucune notification.</div>';
    } else {
      listEl.innerHTML = userNotifs.map(n => `
        <div class="notif-item ${n.isRead ? '' : 'unread'}" data-id="${n.id}">
          <div class="notif-title">${n.title}</div>
          <div class="notif-msg">${n.message}</div>
          <div class="notif-time">${n.time}</div>
        </div>
      `).join('');
    }
  }
}
