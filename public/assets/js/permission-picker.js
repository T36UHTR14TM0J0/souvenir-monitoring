/**
 * Permission Picker — Reusable
 * Untuk halaman Create Role & Edit Role
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('roleForm');
        if (!form) return;

        const groupCheckboxes = document.querySelectorAll('.check-all');
        const itemCheckboxes  = document.querySelectorAll('.perm-checkbox-item');
        const selectedCountEl = document.getElementById('selectedCount');

        if (itemCheckboxes.length === 0) return;

        /* ============================================
           UPDATE COUNTER & GROUP STATE
           ============================================ */
        function updateCounter() {
            const checked = Array.from(itemCheckboxes).filter(cb => cb.checked).length;

            if (selectedCountEl) {
                selectedCountEl.textContent = checked;
            }

            // Update per group
            document.querySelectorAll('.perm-group').forEach(groupEl => {
                const groupItems   = groupEl.querySelectorAll('.perm-checkbox-item');
                const groupChecked = Array.from(groupItems).filter(cb => cb.checked).length;
                const countValue   = groupEl.querySelector('.group-count-value');
                const groupCb      = groupEl.querySelector('.check-all');

                if (countValue) countValue.textContent = groupChecked;

                if (groupCb) {
                    groupCb.checked       = groupChecked > 0 && groupChecked === groupItems.length;
                    groupCb.indeterminate = groupChecked > 0 && groupChecked < groupItems.length;
                }

                groupEl.classList.toggle('is-active', groupChecked > 0);
            });
        }

        /* ============================================
           UPDATE ITEM VISUAL STATE
           ============================================ */
        function updateItemState(checkbox) {
            const label = checkbox.closest('.perm-item');
            if (!label) return;
            label.classList.toggle('is-checked', checkbox.checked);
        }

        /* ============================================
           GROUP CHECKBOX (Check All per Group)
           ============================================ */
        groupCheckboxes.forEach(groupCb => {
            groupCb.addEventListener('change', function () {
                const group      = this.dataset.group;
                const groupItems = document.querySelectorAll('.perm-' + group);

                groupItems.forEach(cb => {
                    cb.checked = this.checked;
                    updateItemState(cb);
                });

                updateCounter();
            });
        });

        /* ============================================
           INDIVIDUAL CHECKBOX
           ============================================ */
        itemCheckboxes.forEach(checkbox => {
            updateItemState(checkbox); // Initial state

            checkbox.addEventListener('change', function () {
                updateItemState(this);
                updateCounter();
            });
        });

        // Initial counter
        updateCounter();

        /* ============================================
           SELECT ALL / CLEAR ALL (global)
           ============================================ */
        window.selectAllPerms = function () {
            itemCheckboxes.forEach(cb => {
                cb.checked = true;
                updateItemState(cb);
            });
            updateCounter();
        };

        window.clearAllPerms = function () {
            itemCheckboxes.forEach(cb => {
                cb.checked = false;
                updateItemState(cb);
            });
            updateCounter();
        };
    });
})();