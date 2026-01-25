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
                <form class="box-form">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="${data.id}">
                    <input type="hidden" name="title">
                    <input type="hidden" name="content">
                    <input type="hidden" name="size" value="1x1">
                    
                    <div class="title-content" contenteditable="true" data-field="title">New Box</div>
                    <div class="box-content" contenteditable="true" data-field="content">Content</div>
                    
                    <label>
                        Enabled
                        <input type="checkbox" name="on_off" checked>
                    </label>
                    
                    <button type="submit">Save</button>
                </form>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="${data.id}">
                    <button type="submit" onclick="return confirm('Delete this box?')">Delete</button>
                </form>
            </div>
        `;
			window.grid.makeWidget(item, { w: 1, h: 1 });

            const form = item.querySelector('.box-form');

            form.addEventListener('submit', (e) => {
                e.preventDefault();
                form.querySelectorAll('[contenteditable]').forEach((el) => {
                    const field = el.dataset.field;
                    const hidden = form.querySelector(`input[name="${field}"]`);

                    if (hidden) {
                        hidden.value = el.innerText.trim();
                    }
                });
                updateBox(item);
            });
		});
}

function removeBox(itemEl) {
	window.grid.removeWidget(itemEl);
}
