var lot_picture = document.getElementById('photo2');
if (lot_picture) {
	var item = lot_picture;
	while (!item.classList.contains('form__item')) {
		item = item.parentElement;
	}
	var img = item.querySelector('.preview__img img');
	var preview_remove = item.querySelector('.preview__remove');

	lot_picture.addEventListener('change', function(evt) {
		if (this.files && this.files[0]) {
			var reader = new FileReader();

			reader.onload = function (evt) {
				img.setAttribute('src', evt.target.result);
			};

			reader.readAsDataURL(this.files[0]);

			if (!item.classList.contains('form__item--uploaded')) {
				item.classList.add('form__item--uploaded');
			}
		}
	});

	preview_remove.addEventListener('click', function(evt){
		lot_picture.value = '';
		img.removeAttribute('src');
		item.classList.remove('form__item--uploaded');
	});
}
