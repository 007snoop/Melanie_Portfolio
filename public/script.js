// variables
const showFormBtn = document.getElementById("show-add-box");
const addBoxForm = document.getElementById("add-box-form");
const cnclAddBtn = document.getElementById("cancel-add-box");
const isAdmin = document.body.dataset.page === "admin";
const container = document.querySelector(".bento-container");
let dragged = null;

// admin page

// makes sure DOM is loaded for DB updates
document.addEventListener("DOMContentLoaded", () => {
	document.querySelectorAll(".box-form").forEach((form) => {
		form.addEventListener("submit", () => {
			form.querySelectorAll("[contenteditable]").forEach((el) => {
				const field = el.dataset.field;
				const hidden = form.querySelector(`input[name="${field}"]`);
				if (hidden) {
					hidden.value = el.innerHTML.trim();
				}
			});
		});
	});
});

// drag logic for box
if (container && document.body.dataset.page === "admin") {
	container.addEventListener("dragstart", (e) => {
		const box = e.target.closest(".bento-item");
		if (!box) return;

		dragged = box;
		box.classList.add("dragging");
        showGridOverlay();
	});

	container.addEventListener("dragend", () => {
		if (!dragged) return;

		dragged.classList.remove("dragging");
		dragged = null;

		container
			.querySelectorAll(".drop-target")
			.forEach((el) => el.classList.remove("drop-target"));
	});

	container.addEventListener("dragover", (e) => {
		e.preventDefault();

		const cells = document.querySelectorAll('.grid-cell');
        let closest = null;
        let minDist = Infinity;

        cells.forEach(cell => {
            const rect = cell.getBoundingClientRect();
            const cx = rect.left + rect.width / 2;
            const cy = rect.top + rect.height / 2;

            const dist = Math.hypot(cx - e.clientX, cy - e.clientY);

            if (dist < minDist) {
                minDist = dist;
                closest = cell;
            }

            cell.classList.remove('active');
        });

        closest?.classList.add('active');
	});

	container.addEventListener("dragleave", (e) => {
		const target = e.target.closest(".bento-item");

		if (!target) return;

		target.classList.remove("drop-target");
	});

	container.addEventListener("drop", (e) => {
		e.preventDefault();

		const active = document.querySelector('.grid-cell.active');
        if (!active || !dragged) {
            return;
        }
        
        const col = active.dataset.col;
        const row = active.dataset.row;

        dragged.style.gridColumnStart = col;
        dragged.style.gridRowStart = row;

        hideGridOverlay();
	});
}

// helper for displaying size
document.addEventListener("change", (e) => {
	if (!e.target.classList.contains("size-picker")) return;

	const box = e.target.closest(".bento-item");
	const size = e.target.value;

	box.classList.remove("size-1x1", "size-2x1", "size-1x2", "size-2x2");
	box.classList.add(`size-${size}`);
});

document.querySelectorAll('.bento-item').forEach(box => {
    const picker = box.querySelector('.size-picker-overlay');
    if (!picker) return;

    picker.addEventListener('click', e => {
        const btn = e.target.closest('.size-btn');
        if (!btn) return;

        const hidden = box.querySelector('input[name="size"]');
        hidden.value = btn.dataset.size;

        picker.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const [w,h] = btn.dataset.size.split('x');

        box.style.setProperty('--w',w);
        box.style.setProperty('--h',h);
    });
});

document.addEventListener('click', (e) => {
    const toggle = e.target.closest('.size-toggle');
    const btn = e.target.closest('.size-btn');

    if (toggle) {
        const item = toggle.closest('.bento-item');
        item.querySelector('.size-menu').toggleAttribute('hidden');
        return;
    }

    if (btn) {
        const item = btn.closest('.bento-item');
        const box = item.querySelector('.bento-box');
        const size = btn.dataset.size;

        const [w, h] = size.split('x');

        item.style.setProperty('--w', w);
        item.style.setProperty('--h', h);

        box.querySelector('input[name="size"]').value = size;
        item.querySelector('.size-menu').hidden = true;
    }

    document
        .querySelectorAll('.size-menu:not([hidden])')
        .forEach(menu => menu.hidden = true);
});

showFormBtn.addEventListener("click", () => {
	if (addBoxForm.style.display === "none") {
		addBoxForm.style.display = "block";
		showFormBtn.style.display = "none";
	}
});

cnclAddBtn.addEventListener("click", () => {
	addBoxForm.style.display = "none";
	showFormBtn.style.display = "block";
});

document.querySelectorAll("[contenteditable]").forEach((el) => {
	autoResizeEditable(el);

	el.addEventListener("input", () => {
		autoResizeEditable(el);
	});

	el.addEventListener("focus", () => {
		el.closest(".bento-item")?.classList.add("editing");
	});

	el.addEventListener("blur", () => {
		el.closest(".bento-item")?.classList.remove("editing");
	});
});

/* Functions */
function saveOrder() {
	const order = [...document.querySelectorAll(".bento-item")].map(
		(el, index) => ({
			id: el.dataset.id,
			position: index,
		}),
	);

	fetch("/api/saveOrder.php", {
		method: "POST",
		headers: { "Content-type": "application/json" },
		body: JSON.stringify(order),
	});
}

function autoResizeEditable(el) {
	el.style.height = "auto";
	el.style.height = el.scrollHeight + "px";
}
function updateBox(b) {
	const payload = {
		action: "update",
		id: box.dataset.id,
		title: box.querySelector('[data-field="title"]')?.innerText || "",
		content: box.querySelector('[data-field="content"]')?.innerText || "",
		position: [...container.children].indexOf(box),
		on_off: !box.classList.contains("disabled"),
		size: box.dataset.size,
	};

	fetch("admin.php", {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
		},
		body: JSON.stringify(payload),
	});
}

/* Box Slot system overlay */
function showGridOverlay() {
    const overlay = document.querySelector('.grid-overlay');
    overlay.innerHTML = '';
    overlay.hidden = false;

    const cols = 4;
    const rows = 10;

    for (let r = 1; r <= rows; r++) {
        for (let c = 1; c <= cols; c++) {
            const element = document.createElement('div');

            element.className = 'grid-cell';
            element.dataset.col = c;
            element.dataset.row = r;
            overlay.appendChild(element);
            
        }
    }
}

function hideGridOverlay() {
    const overlay = document.querySelector('.grid-overlay');
    overlay.hidden = true;
}