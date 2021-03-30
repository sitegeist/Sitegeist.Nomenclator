document.addEventListener('DOMContentLoaded', function() {
	const entryTargets = Array.from(document.getElementsByClassName('nomenclator_enrty'));
	entryTargets.forEach((entryTarget) => {
		entryTarget.addEventListener('click', handleEntryClick, false);
	});
});



const handleEntryClick = (event) => {
	event.preventDefault();
	event.stopPropagation();
	removeModal();

	let entryModal = document.createElement('div');

	entryModal.id = 'entry-modal';

	let currentTarget = event.currentTarget;

	let currentHref = event.currentTarget.getAttribute('href');

	getDesciption(event.currentTarget.dataset.identifier).then((data) => {

		entryModal.innerHTML = `${data.shortDescription} <a class='modal-link' href=${currentHref} target='_blank'>&#10143;</a>`;

		document.body.appendChild(entryModal);

		var modalDimension = {
			width : entryModal.clientWidth,
			height : entryModal.clientHeight
		}

		let position = getPosition(currentTarget , modalDimension);

		entryModal.style.left = `${position.x}px`;
		entryModal.style.top = `${position.y}px`;
	});

}

document.body.addEventListener('click', () => {
	removeModal();
}, true);

window.addEventListener("resize", () => {
	removeModal();
});

document.addEventListener('keydown', function(e) {
	if (e.key === 'Escape') {
		removeModal();
	}
});

async function getDesciption(nodeIdentifier) {

	const response = await fetch('./glossary/entry/'+ nodeIdentifier , {
		method: 'get',

		headers: {
			'Content-Type': 'application/json'

		},
	});
	return response.json();
}

function removeModal() {

	let oldModal =  document.getElementById('entry-modal');

	if (oldModal) {
		oldModal.parentNode.removeChild(oldModal);
	}
}

function getPosition(elem, modalDimension) {
	let offsetLeft = 0;
	let offsetTop = 0;
	let offsetRight= 0;
	const modalGap = 15;
	let targetWidth = elem.clientWidth;
	let targetHeight = elem.clientHeight;

	do {
		if ( !isNaN( elem.offsetLeft ) &&  !isNaN( elem.offsetTop ))
		{
			offsetLeft += elem.offsetLeft;
			offsetTop += elem.offsetTop;
		}
	} while( elem = elem.offsetParent );

	offsetRight = window.innerWidth - offsetLeft - targetWidth;

	let leftFree =  (( offsetLeft + targetWidth / 2 - modalDimension.width / 2  ) > 0 ) ? true : false ;

	let rightFree =  (( offsetRight + targetWidth / 2 - modalDimension.width / 2 ) > 0 ) ? true : false ;

	let topFree = (( offsetTop - modalGap  ) > modalDimension.height ) ? true : false ;

	if (topFree) {
		if(leftFree) {
			if (rightFree){
				return {
					x : offsetLeft + targetWidth / 2 - (modalDimension.width / 2),
					y : offsetTop - modalDimension.height - modalGap
				}
			} else {
				return {
					x : offsetLeft - modalDimension.width,
					y : offsetTop - modalDimension.height - modalGap
				}
			}

		} else {
			if (rightFree){
				return {
					x : offsetLeft,
					y : offsetTop - modalDimension.height - modalGap
				}
			} else {
				return {}
			}
		}

	} else {
		if(leftFree) {
			if (rightFree){
				return {
					x : offsetLeft + targetWidth / 2 - (modalDimension.width / 2),
					y : offsetTop +  modalGap +targetHeight
				}
			} else {
				return {
					x :  offsetLeft - modalDimension.width,
					y : offsetTop + modalGap + targetHeight
				}
			}

		} else {
			if (rightFree){
				return {
					x : offsetLeft,
					y : offsetTop + modalGap + targetHeight
				}
			} else {
				return {}
			}
		}
	}
}



