import { Controller } from '@hotwired/stimulus';
import { Dropzone } from 'dropzone';

import '../styles/dropzone_controller.css';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = [
        'uploadFormContainer',
        'uploadSelectButton',
        'uploadPreviewContainer',
        'uploadPreviewFileCountLabel',
        'uploadPreviewList'
    ];

    static values = {
        previewListItemTemplate: String
    };

    initialize() {
        this._handleAddedFile = this.handleAddedFile.bind(this);
        this._handleQueueComplete = this.handleQueueComplete.bind(this);
    }

    connect() {
        this.instance = new Dropzone(this.uploadFormContainerTarget, {
            chunking: true,
            chunkSize: 16000000, // 16 MB

            maxFilesize: 32000, // 32 GB

            clickable: this.uploadSelectButtonTarget,

            previewsContainer: this.uploadPreviewListTarget,
            previewTemplate: this.previewListItemTemplateValue
        });

        this.instance.on('addedfile', this._handleAddedFile);
        this.instance.on('queuecomplete', this._handleQueueComplete);
    }

    handleAddedFile() {
        this.updateFileCountLabel();

        this.instance.element.addEventListener('transitionend', () => {
            this.instance.element.classList.add('hidden');

            this.uploadPreviewContainerTarget.classList.remove('hidden');
            setTimeout(() => {
                this.uploadPreviewContainerTarget.classList.remove('opacity-0');
            });
        }, { once: true });

        this.instance.element.classList.add('opacity-0');
    }

    handleQueueComplete() {
        const response = this.getLastAcceptedFileResponse();

        if (!response || !Object.hasOwn(response, 'url')) {
            return;
        }

        window.location.href = response.url;
    }

    updateFileCountLabel() {
        const count = Array.isArray(this.instance.files) ? this.instance.files.length : 0;

        this.uploadPreviewFileCountLabelTarget.innerText = `Files: ${count}`;
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
