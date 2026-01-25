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
		const addBtn = document.getElementById("show-add-box");
		addBtn.addEventListener("click", () => {
			addBox();
		});
	}
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

function addBox() {
	fetch("/api/addBox.php", {
		method: "POST",
		headers: { "Content-Type": "application/json" },
		body: JSON.stringify({
			title: "New Box",
			content: "Content",
		}),
	})
		.then((res) => res.json())
		.then((data) => {
			const item = document.createElement("div");
			item.classList.add("grid-stack-item");
			item.dataset.id = data.id;

			item.innerHTML = `
        <div class="grid-stack-item-content">
            <div class="title-content" contenteditable="true">New Box</div>
            <div class="box-content" contenteditable="true">Content</div>
        </div>
        `;
			window.grid.makeWidget(item, { w: 1, h: 1 });
		});
}

function removeBox(itemEl) {
	window.grid.removeWidget(itemEl);
}
