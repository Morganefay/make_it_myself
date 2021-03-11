import { buildUrl } from '../utilities.js';

export class FormContact {

    constructor(form) {
        this.formElement = form;
        this.formElement.addEventListener('submit', this.onSubmitForm.bind(this));
    }

    onSubmitForm(event) {
        event.preventDefault();

        const formData = new FormData(this.formElement);

        console.log(this);

        const options = {
            method: 'POST',
            body: formData
        };

        const url = buildUrl('/ajax/contact');

        fetch(url, options)
            .then(response => response.text())
            .then(this.onAjaxContact.bind(this));
    }

    onAjaxContact(content) {
        this.formElement.reset();
        const pElement = document.createElement('p');
        pElement.classList.add('success-message');
        pElement.textContent = content;
        this.formElement.prepend(pElement);


    }
}