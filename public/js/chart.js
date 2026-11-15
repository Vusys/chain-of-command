// The chart's two interactions. Both are delegated from the document, so they
// keep working after the slider swaps the tree out.
(() => {
    const token = document.querySelector('meta[name="csrf-token"]').content;
    const message = document.querySelector('[data-chart-message]');
    const body = document.querySelector('[data-chart-body]');
    const departmentOf = (event) => event.target.closest?.('[data-department-id]');
    const headerOf = (event) => event.target.closest?.('[data-department-id] > h2');
    let dragged = null;

    // Drag a department onto another to move it, and everything in it, there.
    document.addEventListener('dragstart', (event) => {
        dragged = departmentOf(event);
        if (!dragged) {
            return;
        }
        dragged.classList.add('is-dragging');
        event.dataTransfer.effectAllowed = 'move';
        event.dataTransfer.setData('text/plain', dragged.dataset.departmentId);
    });
    document.addEventListener('dragend', () => {
        dragged?.classList.remove('is-dragging');
        dragged = null;
    });
    document.addEventListener('dragover', (event) => {
        const header = headerOf(event);
        if (dragged && header && header.parentElement !== dragged) {
            event.preventDefault();
            header.classList.add('is-drop-target');
        }
    });
    document.addEventListener('dragleave', (event) => headerOf(event)?.classList.remove('is-drop-target'));
    document.addEventListener('drop', async (event) => {
        const header = headerOf(event);
        if (!dragged || !header) {
            return;
        }
        event.preventDefault();
        header.classList.remove('is-drop-target');

        const response = await fetch(`/departments/${dragged.dataset.departmentId}/move`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ parent: Number(header.parentElement.dataset.departmentId) }),
        });
        const result = await response.json();

        if (response.ok) {
            window.location.reload();
        } else {
            message.textContent = result.message;
            message.hidden = false;
        }
    });

    // Scrub through time: the tree fades to each month as the slider moves.
    const slider = document.querySelector('[data-as-of]');
    const label = document.querySelector('[data-as-of-label]');
    const months = slider.dataset.months.split(',');
    let pending = null;
    let latest = 0;

    slider.addEventListener('input', () => {
        const month = months[Number(slider.value)];
        label.textContent = new Date(month).toLocaleDateString('en-GB', { month: 'short', year: 'numeric' });
        clearTimeout(pending);
        pending = setTimeout(() => show(month), 120);
    });

    async function show(month) {
        const request = ++latest;
        const response = await fetch(`/chart?on=${month}&partial=1`, { headers: { 'Accept': 'text/html' } });
        const html = await response.text();
        if (request !== latest) {
            return; // a later scrub has already asked for another month
        }

        body.classList.add('is-changing');
        await new Promise((done) => setTimeout(done, 150));
        body.innerHTML = html;
        body.classList.remove('is-changing');
        history.replaceState(null, '', `/chart?on=${month}`);
    }
})();
