class InsertImageModal {
  static modal;
  static context;


  static get insertImageButton() {
    return $(".insert-image")
  }

  static get imageModal() {
    return $("#insert-image-modal");
  }


  static get dropArea() {
    return $("#drag-drop-image");
  }

  static get imageFileInput() {
    return $("#image-file-input");
  }

  static get loading() {
    return $("#insert-image-loading");
  }

  static initialize() {
    InsertImageModal.imageModal.modal()
    this.insertImageButton.on('click', this.handleInsertImage);
  }

  static handleInsertImage(e) {
    const target = $(e.currentTarget);

    let container = target.data("container");
    let hiddenInput = target.data("hidden");

    InsertImageModal.context = { container, hiddenInput };


    InsertImageModal.imageModal.modal('show');

    InsertImageModal.dropArea.on('dragenter', function (e) {
      e.preventDefault();
    });

    InsertImageModal.dropArea.on('dragover', function (e) {
      e.preventDefault();
    });

    InsertImageModal.imageFileInput.on("change", InsertImageModal.handleImageUpload)


    InsertImageModal.dropArea.on('drop', InsertImageModal.handleDropImage);
  }

  static handleImageUpload(e) {
    const image = e.currentTarget.files;
    InsertImageModal.uploadImage(image[0]);
  }


  static handleDropImage(e) {
    e.preventDefault();
    const image = e.originalEvent.dataTransfer.files[0];

    if (["image/jpeg", "image/png", "image/jpg"].includes(image.type))
      InsertImageModal.uploadImage(image);
  }

  static async uploadImage(image) {
    InsertImageModal.loading.removeClass("d-none");
    const imageData = new FormData();
    imageData.append('image', image, `img_${Date.now()}`);
    const res = await AdminController.fetch("assessment", "image_upload", null, imageData);

    console.log(res);

    InsertImageModal.imageModal.modal('hide');
  }
}


