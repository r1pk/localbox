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
        this._handleQueueComplete = this.handleQueueComplete.bind(this);
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
        this.instance.on('queuecomplete', this._handleQueueComplete);
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

    handleQueueComplete() {
        const response = this.getLastAcceptedFileResponse();

        if (!response || !Object.hasOwn(response, 'url')) {
            return;
        }

        window.location.href = response.url;
    }

    getLastAcceptedFileResponse() {
        const files = this.instance.getAcceptedFiles();

        if (files.length === 0) {
            return null;
        }

        const file = files[files.length - 1];
        const response = file.xhr.responseText;

        if (!response) {
            return null;
        }

        return JSON.parse(response);
    }

    disconnect() {
        if (this.instance) {
            this.instance.destroy();
        }
    }
}
