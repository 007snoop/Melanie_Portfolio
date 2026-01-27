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

    document.addEventListener('click', (e) => {
        if (!window.IS_ADMIN) {
            return;
        }

        const btn = e.target.closest('.box-remove');
        if (!btn) {
            return;
        }
        const item = btn.closest('.grid-stack-item');
        if (!item) {
            return;
        }
        const id = item.dataset.id;
        if (!id) {
            return;
        }

       

        fetch("/api/deleteBox.php", {
            method: 'POST',
            headers: { "Content-type": "application/json" },
            body: JSON.stringify({ id })
        }).then((res) => {
            if (!res.ok) {
                throw new Error('Delete failed');
            }
            window.grid.removeWidget(item);
        }).catch(err => {
            console.error(err);
            alert('failed to delete box');
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
	const payload = {
		action: "update",
		id: b.dataset.id,
		title: b.querySelector('[data-field="title"]')?.innerText || "",
		content: b.querySelector('[data-field="content"]')?.innerText || "",
		on_off: !b.classList.contains("disabled"),
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
			if (!data || !data.html) {
                console.error('invalid addBox response', data);
                return;
            }
            const gridEl = document.querySelector('.grid-stack');

            // returned html 
            const wrapper = document.createElement('div');
            wrapper.innerHTML = data.html.trim();

            const item = wrapper.firstElementChild;
            if (!item) {
                console.error('no grid item found in html');
                return;
            }

            // insert into grid
            gridEl.appendChild(item);

            //add to gridstack
            window.grid.makeWidget(item);

            // node 
            const node = item.gridstackNode;
            if (!node) {
                console.error('gridstack node not attached');
                return;
            }

            // form submission 
            const form = item.querySelector('.box-form');
            if (form) {
                form.addEventListener('submit', (e) => {
                    e.preventDefault;

                    form.querySelectorAll("[contenteditable]").forEach((el) => {
                        const field = el.dataset.field;
                        const hidden = form.querySelector(`input[name="${field}"]`);
                        if (hidden) {
                            hidden.value = el.innerText.trim();
                        }
                    });
                    updateBox(item);
                });
            }
        });
}

function removeBox(itemEl) {
	window.grid.removeWidget(itemEl);
}
