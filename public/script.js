// variables 
const showFormBtn = document.getElementById("show-add-box");
const addBoxForm = document.getElementById("add-box-form");
const cnclAddBtn = document.getElementById("cancel-add-box");
let dragged = null;

// admin page
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



document.querySelectorAll(".bento-box").forEach((box) => {
	box.addEventListener("dragstart", (event) => {
		dragged = box;
		box.classList.add("dragging");
	});

	box.addEventListener("dragend", () => {
		dragged.classList.remove("dragging");
		dragged = null;
	});

	box.addEventListener("dragover", (event) => event.preventDefault());

	box.addEventListener("drop", (event) => {
		event.preventDefault();
		if (dragged && dragged !== box) {
			box.parentNode.insertBefore(dragged, box);
			saveOrder();
		}
	});
});

document.addEventListener("change", (e) => {
	if (!e.target.classList.contains("size-picker")) return;

    const box = e.target.closest('.bento-box');
    const size = e.target.value;

    box.classList.remove('size-1x1', 'size-2x1', 'size-1x2', 'size-2x2');
    box.classList.add(`size-${size}`);
});

function saveOrder() {
	const order = [...document.querySelectorAll(".bento-box")].map(
		(el, index) => ({
			id: el.dataset.id,
			position: index,
		}),
	);

	fetch("saveOrder.php", {
		method: "POST",
		headers: { "Content-type": "application/json" },
		body: JSON.stringify(order),
	});
}

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

function autoResizeEditable(el) {
    el.style.height = 'auto';
    el.style.height = el.scrollHeight + 'px';
}

document.querySelectorAll('[contenteditable]').forEach(el => {
    autoResizeEditable(el);

    el.addEventListener('input', () => {
        autoResizeEditable(el);
    });

    el.addEventListener('focus', () => {
        el.closest('.bento-box')?.classList.add('editing');
    });

    el.addEventListener('blur', () => {
        el.closest('.bento-box')?.classList.remove('editing');
    });
});