MultipleChoice = class {

  static get removeOptionButton() {
    return $("button.remove-option");
  }

  static get addOptionButton() {
    return $("#add-multiple-choice-option");
  }

  static get optionsContainer() {
    return $("#multiple-choice-container");
  }

  static initialize() {
    MultipleChoice.addOptionButton.on("click", MultipleChoice.handleAddOption);
    MultipleChoice.onUpdate();
  }

  static onUpdate() {
    MultipleChoice.removeOptionButton.on("click", MultipleChoice.handleRemoveOption);
    if (MultipleChoice.removeOptionButton.length === 1) {
      MultipleChoice.removeOptionButton.addClass("disabled");
    } else {
      MultipleChoice.removeOptionButton.removeClass("disabled");
    }
    feather.replace();
  }

  static handleRemoveOption(e) {
    const target = $(e.currentTarget);
    target.parent().remove();
    MultipleChoice.onUpdate();
  }

  static async handleAddOption() {
    const count = MultipleChoice.removeOptionButton.length + 1;
    const content = await AdminController.fetch("component", "render", {
      name: "multiple-choice-option",
      handler: "common\\models\\assessment\\MultipleChoice",
      params: {
        option: count
      }
    });
    MultipleChoice.optionsContainer.append(content);
    MultipleChoice.onUpdate();
  }
}

MultipleChoice.initialize();


