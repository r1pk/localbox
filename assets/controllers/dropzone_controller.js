import { Controller } from '@hotwired/stimulus';
import { Dropzone } from 'dropzone';

import '../styles/dropzone_controller.css';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static values = {
        url: String
    };

    initialize() {
        this._handleAddedFile = this.handleAddedFile.bind(this);
    }

    connect() {
        const clickable = document.querySelector('[data-dropzone-clickable]');

        const container = document.querySelector('[data-dropzone-preview-container]');
        const template = document.querySelector('[data-dropzone-preview-template]');

        this.instance = new Dropzone(this.element, {
            url: this.urlValue ?? '/',

            chunking: true,
            chunkSize: 16000000, // 16 MB

            maxFilesize: 32000, // 32 GB

            clickable: clickable,

            previewsContainer: container,
            previewTemplate: template?.innerHTML
        });
        this.instance.on('addedfile', this._handleAddedFile);
    }

    handleAddedFile() {
        this.instance.element.addEventListener('transitionend', async () => {
            this.instance.element.classList.add('hidden');

            this.instance.previewsContainer.classList.remove('hidden');
            setTimeout(() => {
                this.instance.previewsContainer.classList.remove('opacity-0', 'scale-0');
            });
        }, { once: true });

        this.instance.element.classList.add('opacity-0', 'scale-0');
    }

    disconnect() {
        if (this.instance) {
            this.instance.destroy();
        }
    }
}
