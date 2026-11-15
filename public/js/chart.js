// Drag a department onto another to move it, and everything in it, there.
(() => {
    const token = document.querySelector('meta[name="csrf-token"]').content;
    const message = document.querySelector('[data-chart-message]');
    let dragged = null;

    document.querySelectorAll('[data-department-id]').forEach((department) => {
        const header = department.querySelector('h2');

        department.addEventListener('dragstart', (event) => {
            dragged = department;
            department.classList.add('is-dragging');
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', department.dataset.departmentId);
        });
        department.addEventListener('dragend', () => {
            department.classList.remove('is-dragging');
            dragged = null;
        });

        header.addEventListener('dragover', (event) => {
            if (dragged && dragged !== department) {
                event.preventDefault();
                header.classList.add('is-drop-target');
            }
        });
        header.addEventListener('dragleave', () => header.classList.remove('is-drop-target'));
        header.addEventListener('drop', async (event) => {
            event.preventDefault();
            header.classList.remove('is-drop-target');
            if (!dragged) {
                return;
            }

            const response = await fetch(`/departments/${dragged.dataset.departmentId}/move`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
                body: JSON.stringify({ parent: Number(department.dataset.departmentId) }),
            });
            const body = await response.json();

            if (response.ok) {
                window.location.reload();
            } else {
                message.textContent = body.message;
                message.hidden = false;
            }
        });
    });
})();
