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

	grid.on("change", (event, items) => {
		const data = items.map((i) => ({
			id: i.el.dataset.id,
			x: i.x,
			y: i.y,
			w: i.w,
			h: i.h,
		}));

		fetch("/api/saveOrder.php", {
			method: "POST",
			headers: { "Content-Type": "application/json" },
			body: JSON.stringify({ order: data }),
		});
	});

	document.querySelectorAll("[contenteditable]").forEach((el) => {
		const resize = () => {
			el.style.height = "auto";
			el.style.height = el.scrollHeight + "px";
		};
		el.addEventListener("input", resize);
		resize();
	});

	const addBtn = document.getElementById("show-add-box");
	addBtn.addEventListener("click", () => {
		const id = Date.now();
		const newBox = {
			id,
			title: "New Box",
			content: "Content",
		};
		const item = document.createElement("div");

		item.classList.add("grid-stack-item");
		item.dataset.id = newBox.id;

		item.innerHTML = `
            <div class="grid-stack-item-content">
                <form method="post" class="box-form">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="${newBox.id}">
                    <input type="hidden" name="title">
                    <input type="hidden" name="content">
                    <div class="title-content" contenteditable="true" data-field="title">
                        ${newBox.title}
                    </div>
                    <div class="box-content" contenteditable="true" data-field="content">
                        ${newBox.content}
                    </div>
                    <button type="submit">Save</button>
                </form>
            </div>
        `;

		grid.makeWidget(item, { width: 1, height: 1, x: 0, y: 0 });
	});
});

/* Functions */
function saveOrder() {
	const order = [...document.querySelectorAll(".bento-item")].map((el) => ({
		id: el.dataset.id,
		size: el.dataset.size || "1x1",
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

function addBox(boxData) {
	const item = document.createElement("div");
	item.classList.add("grid-stack-item");
	item.dataset.id = boxData.id;

	item.innerHTML = `
        <div class="grid-stack-item-content">
            <form method="post" class="box-form">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="${boxData.id}">
                <input type="hidden" name="title">
                <input type="hidden" name="content">
                <div class="title-content" contenteditable="true" data-field="title">
                    ${boxData.title}
                </div>
                <div class="box-content" contenteditable="true" data-field="content">
                    ${boxData.content}
                </div>
                <button type="submit">Save</button>
            </form>
        </div>
    `;

	window.grid.addWidget(item, {
		width: 1,
		height: 1,
		x: 0,
		y: 0,
	});
}

function removeBox(itemEl) {
	window.grid.removeWidget(itemEl);
}
