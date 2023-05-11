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


  static get urlButton() {
    return $("#image-url-button");
  }

  static get urlInput() {
    return $("#image-url-input");
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

    InsertImageModal.imageFileInput.on("change", InsertImageModal.handleImageUpload);

    InsertImageModal.dropArea.on('drop', InsertImageModal.handleDropImage);

    InsertImageModal.urlButton.on("click", InsertImageModal.handleImageURL);
  }

  static handleImageUpload(e) {
    const image = e.currentTarget.files[0];
    InsertImageModal.uploadImage({ image });
  }


  static handleDropImage(e) {
    e.preventDefault();
    const image = e.originalEvent.dataTransfer.files[0];

    if (["image/jpeg", "image/png", "image/jpg"].includes(image.type))
      InsertImageModal.uploadImage({ image });
  }


  static isImgUrl(url) {
    const img = new Image();
    img.src = url;
    return new Promise(function (resolve) {
      img.onload = () => resolve(true);
      img.onerror = () => resolve(false);
    })
  }


  static async handleImageURL() {
    const url = InsertImageModal.urlInput.val();
    const isImage = await InsertImageModal.isImgUrl(url);
    if (isImage)
      InsertImageModal.uploadImage({ url });
    return;
    // Handle empty string
  }


  static getImageData(image) {
    const data = new FormData();
    data.append('image', image, `img_${Date.now()}`);
    return data;
  }


  static getURLData(url) {
    const data = new FormData();
    data.append('image', {
      url, name: `img_${Date.now()}`
    });

    return data;
  }

  static async uploadImage({ image, url }) {
    InsertImageModal.loading.removeClass("d-none");


    let res;
    if (image) {
      let data = InsertImageModal.getImageData(image);
      res = await AdminController.fetch("assessment", "image_upload", null, data);
    } else if (url) {
      res = await AdminController.fetch("assessment", "image_upload", {
        image: {
          url, name: `img_${Date.now()}`
        }
      });
    }
    console.log(res);
    if (!res) {
      InsertImageModal.loading.addClass("d-none");
      // Handle Error Saving Image
      return;
    }

    let { container, hiddenInput } = InsertImageModal.context;
    container = $(container);
    container.append(res);
    feather.replace();
    InsertImageModal.imageModal.modal('hide');
  }
}


