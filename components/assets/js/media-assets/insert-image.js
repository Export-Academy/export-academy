class InsertImageModal {
  static modal;
  static get insertImageButton() {
    return $(".insert-image")
  }

  static get imageModal() {
    return $("#insert-image-modal");
  }

  static initialize() {
    InsertImageModal.imageModal.modal()
    this.insertImageButton.on('click', this.handleInsertImage);
  }

  static handleInsertImage(e) {
    InsertImageModal.imageModal.modal('show');
  }
}


