// variables
const container = document.querySelector(".grid-stack");

// admin page

// makes sure DOM is loaded for DB updates
document.addEventListener("DOMContentLoaded", () => {
	const grid = GridStack.init({
		column: 4,
		cellHeight: 120,
		animate: true,
		float: false,
		disableOneColumnMode: true,
		disableDrag: !window.IS_ADMIN,
		disableResize: !window.IS_ADMIN,
	});
	window.grid = grid;

	document.addEventListener("submit", (e) => {
        const form = e.target;
		if (!form.classList.contains("box-form")) return;
		e.preventDefault();

        const item = form.closest('.grid-stack-item');
        if (!item) return;

        form.querySelectorAll('[contenteditable][data-field]').forEach(el => {
            const field = el.dataset.field;
            const hidden = form.querySelector(`input[name="${field}"]`);
            if (hidden) {
                hidden.value = el.innerText.trim();
            }
        });

        updateBox(item);
	});

	grid.on("change", saveOrder);

	document.querySelectorAll("[contenteditable]").forEach((el) => {
		const resize = () => {
			el.style.height = "auto";
			el.style.height = el.scrollHeight + "px";
		};
		el.addEventListener("input", resize);
		resize();
	});

	if (window.IS_ADMIN === true) {
		const addTextBtn = document.getElementById("add-text-box");
		const addLinkbtn = document.getElementById("add-link-box");

		if (addTextBtn) {
			addTextBtn.addEventListener("click", () => {
				addBox({
					type: "text",
					title: "New Text Box",
					content: "Text Content",
				});
			});
		}

		if (addLinkbtn) {
			addLinkbtn.addEventListener("click", () => {
				addBox({
					type: "link",
					title: "New Link Box",
					url: "https://example.com",
				});
			});
		}
	}

	document.addEventListener("click", (e) => {
		if (!window.IS_ADMIN) {
			return;
		}

		const btn = e.target.closest(".box-remove");
		if (!btn) {
			return;
		}
		const item = btn.closest(".grid-stack-item");
		if (!item) {
			return;
		}
		const id = item.dataset.id;
		if (!id) {
			return;
		}

		fetch("/api/deleteBox.php", {
			method: "POST",
			headers: { "Content-type": "application/json" },
			body: JSON.stringify({ id }),
		})
			.then((res) => {
				if (!res.ok) {
					throw new Error("Delete failed");
				}
				window.grid.removeWidget(item);
			})
			.catch((err) => {
				console.error(err);
				alert("failed to delete box");
			});
	});
});

/* Functions */
function saveOrder(event, items) {
	const order = items.map((i) => ({
		id: i.el.dataset.id,
		x: i.x,
		y: i.y,
		w: i.w,
		h: i.h,
	}));

	fetch("/api/saveOrder.php", {
		method: "POST",
		headers: { "Content-type": "application/json" },
		body: JSON.stringify({ order }),
	});
}

function autoResizeEditable(el) {
	el.style.height = "auto";
	el.style.height = el.scrollHeight + "px";
}
function updateBox(b) {
    const form = b.querySelector('.box-form');
    const type = form.querySelector('input[name="type"]')?.value;

	const payload = {
		action: "update",
		id: b.dataset.id,
        type: type,
		on_off: !b.classList.contains("disabled"),
	};

    if (type === 'text') {
        payload.title = b.querySelector('[data-field="title"]')?.innerText || 'text-box';
        payload.content = b.querySelector('[data-field="content"]')?.innerText || '';
    } else if (type === 'link') {
        payload.title = b.querySelector('[data-field="title"]')?.innerText || '';
        payload.url = b.querySelector('[data-field="url"]')?.innerText || '';
    } else {
        exit;
    }

	fetch("admin.php", {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
		},
		body: JSON.stringify(payload),
	});
}

function addBox(payload) {
	fetch("/api/addBox.php", {
		method: "POST",
		headers: { "Content-Type": "application/json" },
		body: JSON.stringify(payload),
	})
		.then((res) => res.json())
		.then((data) => {
			if (!data || !data.html) {
				console.error("invalid addBox response", data);
				return;
			}
			const gridEl = document.querySelector(".grid-stack");

			// returned html
			const wrapper = document.createElement("div");
			wrapper.innerHTML = data.html.trim();

			const item = wrapper.firstElementChild;
			if (!item) {
				console.error("no grid item found in html");
				return;
			}

			// insert into grid
			gridEl.appendChild(item);

			//add to gridstack
			window.grid.makeWidget(item);

			// node
			const node = item.gridstackNode;
			if (!node) {
				console.error("gridstack node not attached");
				return;
			}

		});
}

function removeBox(itemEl) {
	window.grid.removeWidget(itemEl);
}
