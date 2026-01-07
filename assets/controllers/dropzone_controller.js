import { Controller } from '@hotwired/stimulus';
import { Dropzone } from 'dropzone';

import '../styles/dropzone_controller.css';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = [
        'uploadForm',
        'clickableButton',
        'previewContainer'
    ];

    static values = {
        previewTemplate: String
    };

    initialize() {
        this._handleAddedFile = this.handleAddedFile.bind(this);
    }

    connect() {
        this.instance = new Dropzone(this.uploadFormTarget, {
            chunking: true,
            chunkSize: 16000000, // 16 MB

            maxFilesize: 32000, // 32 GB

            clickable: this.clickableButtonTarget,

            previewsContainer: this.previewContainerTarget,
            previewTemplate: this.previewTemplateValue
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
