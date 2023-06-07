/**
 * @typedef UploaderConfiguration
 * @property {string} id 
 * @property {string|undefined} path
 * @property {boolean} reload
 * @property {string} selectedType
 */

class Uploader {
  static registry = {}

  id;
  path;
  reload = false;
  context;


  /**
   * 
   * @param {UploaderConfiguration} config 
   */
  static init(config) {
    const { id, path, reload, selectedType } = config;
    const uploader = new Uploader();

    uploader.id = id;
    uploader.path = path ?? "";
    uploader.reload = reload;

    uploader.initialize();

    this.registry[id] = uploader;

    $(selectedType).on("dropdown.change", function (e, value) {
      uploader.fileInput.prop("accept", value);
      $(`${selectedType}-desc`).html(value.length === 0 ? "all" : `(${value})`);
    });
    return uploader;
  }

  get triggerButton() {
    return $(`#uploader-trigger-button-${this.id}`);
  }

  get uploaderModal() {
    return $(`#uploader-modal-${this.id}`);
  }

  get dropArea() {
    return $(`#drag-drop-area-${this.id}`);
  }

  get fileInput() {
    return $(`#file-input-${this.id}`);
  }

  get loading() {
    return $(`#uploader-loading-${this.id}`);
  }


  get urlInput() {
    return $(`#media-url-input-${this.id}`);
  }


  get urlUploadButton() {
    return $(`#media-url-button-${this.id}`);
  }

  initialize() {
    this.uploaderModal.modal();

    const uploader = this;


    this.triggerButton.on('click', function (e) {
      uploader.handleUploader(e, uploader);
    });


    this.urlUploadButton.on('click', async function (e) {
      uploader.handleFileURL(uploader);
    });
  }

  async handleFileURL(uploader) {
    const url = uploader.urlInput.val();

    if (url.length === 0) return;

    uploader.uploadFile({ url });
    return;
  }

  handleUploader(e, uploader) {
    const target = $(e.currentTarget);

    let container = target.data("container");
    let input = target.data("input");


    uploader.context = { container, input };

    uploader.uploaderModal.modal('show');

    uploader.dropArea.on('dropenter', function (e) {
      e.preventDefault();
    });

    uploader.dropArea.on('dragover', function (e) {
      e.preventDefault();
    });


    uploader.fileInput.on('change', function (e) {
      uploader.handleFileUpload(e, uploader);
    });

    uploader.dropArea.on('drop', function (e) {
      uploader.handleDropImage(e, uploader);
    });

  }


  handleFileUpload(e, uploader) {
    const files = e.currentTarget.files;
    uploader.uploadFile({ file: files[0] });
  }

  handleDropImage(e, uploader) {
    e.preventDefault();
    const file = e.originalEvent.dataTransfer.files[0];
    uploader.uploadFile({ file });
  }


  async uploadFile({ file, url }) {
    this.loading.removeClass("d-none");

    const data = new FormData();
    let res;

    if (file) {
      data.append('media', file, Date.now());
      data.append('path', this.path);
      data.append('name', Date.now());


      res = await AdminController.fetch("media", "upload", null, data);
    }


    else if (url) {
      data.append("url", url);
      data.append("name", Date.now());
      data.append("path", this.path);

      res = await AdminController.fetch("media", "upload_url", null, data);
    }


    if (res) {
      if (this.reload) {
        window.location.reload();
      } else {

      }

      this.uploaderModal.modal("hide");
    }

    this.loading.addClass("d-none");
  }
}